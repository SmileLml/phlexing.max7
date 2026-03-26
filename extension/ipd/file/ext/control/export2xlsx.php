<?php
helper::importControl('file');
class myfile extends file
{
    /**
     * File count
     *
     * @var int
     * @access public
     */
    public $fileCount       = 1;

    /**
     * Data record in sharedStrings.
     *
     * @var int
     * @access public
     */
    public $record          = 0;

    /**
     * Style setting
     *
     * @var array
     * @access public
     */
    public $styleSetting    = array();

    /**
     * rels about link
     *
     * @var string
     * @access public
     */
    public $rels            = '';

    /**
     * sheet1 sheetData
     *
     * @var string
     * @access public
     */
    public $sheet1SheetData = '';

    /**
     * sheet1 params like cols mergeCells ...
     *
     * @var array
     * @access public
     */
    public $sheet1Params = array();

    /**
     * field cols.
     *
     * @var array
     * @access public
     */
    public $fieldCols = array();

    /**
     * every counts in need count.
     *
     * @var array
     * @access public
     */
    public $counts = array();

    /**
     * editor images.
     *
     * @var array
     * @access public
     */
    public $editorImgList = array();

    /**
     * images.
     *
     * @var array
     * @access public
     */
    public $imgList = array();

    /**
     * init for excel data.
     *
     * @access public
     * @return void
     */
    public function init()
    {
        $this->app->loadClass('pclzip', true);
        $this->zfile  = $this->app->loadClass('zfile');
        $this->fields = $this->post->fields;
        $this->rows   = $this->post->rows;

        /* Move files field to end. */
        if(isset($this->fields['files']))
        {
            $filesName = $this->fields['files'];
            unset($this->fields['files']);
            $this->fields['files'] = $filesName;
        }

        $rowIDList = array();
        if(!empty($this->rows) and isset(reset($this->rows)->id))
        {
            foreach($this->rows as $row)
            {
                $rowIDList[] = $row->id;
                if(isset($row->files))
                {
                    $files = str_replace('<br />', '<br/>', $row->files);
                    $files = explode('<br/>', $files);
                    $this->fileCount = max($this->fileCount, count($files) - 1);
                }
            }
        }

        /* Get all editor images. */
        $this->editorImgList = $this->getEditorImages($rowIDList);

        /* Init excel file. */
        $this->exportPath = $this->app->getCacheRoot() . $this->app->user->account . uniqid() . '/';
        if(is_dir($this->exportPath))$this->zfile->removeDir($this->exportPath);
        $this->zfile->mkdir($this->exportPath);
        $this->zfile->copyDir($this->app->getCoreLibRoot() . 'phpexcel/xlsx', $this->exportPath);

        $this->sharedStrings = file_get_contents($this->exportPath . 'xl/sharedStrings.xml');

        $this->fieldsKey = array_keys($this->fields);

        $this->sheet1Params['dataValidations'] = '';
        $this->sheet1Params['cols']            = array();
        $this->sheet1Params['mergeCells']      = '';
        $this->sheet1Params['hyperlinks']      = '';

        $this->counts['dataValidations'] = 0;
        $this->counts['mergeCells']      = 0;
        $this->counts['hyperlinks']      = 0;
    }

    /**
     * Get editor images .
     *
     * @param  array $rowIDList
     * @access public
     * @return array
     */
    public function getEditorImages($rowIDList = array())
    {
        $this->loadModel('file');

        $kind  = isset($_POST['kind']) ? $_POST['kind'] : '';
        $files = $this->dao->select('*')->from(TABLE_FILE)
            ->where('extra')->eq('editor')
            ->andWhere('deleted')->eq('0')
            ->andWhere('objectType')->eq($this->post->kind)
            ->beginIF(!empty($kind))->andWhere('objectType')->in($kind)->fi()
            ->beginIF(!empty($rowIDList))->andWhere('objectID')->in($rowIDList)->fi()
            ->fetchAll('id', false);

        foreach($files as $file) $this->file->setFileWebAndRealPaths($file);

        return $files;
    }

    /**
     * Export data to Excel. This is main function.
     *
     * @access public
     * @return void
     */
    public function export2Xlsx()
    {
        $this->init();
        $this->setDocProps();
        $this->excelKey  = array();
        $this->maxWidths = array();
        for($i = 0; $i < count($this->fieldsKey); $i++)
        {
            $field = $this->fieldsKey[$i];
            $this->excelKey[$field] = $this->setExcelFiled($i);
            if(strpos($field, 'Date') !== false or in_array($field, $this->config->excel->dateField)) $this->styleSetting['date'][$this->excelKey[$field]] = 1;
        }

        $this->sheet1Params['colspan'] = count($this->excelKey) + $this->fileCount - 1;

        /* Add help lang in start.*/
        if(isset($this->lang->excel->help->{$this->post->kind}))
        {
            $this->setTips('A', 1, $this->post->kind);
        }

        /* Show header data. */
        $this->sheet1SheetData .= '<row r="2" spans="1:%colspan%">';
        foreach($this->fields as $key => $field) $this->sheet1SheetData .= $this->setCellValue($this->excelKey[$key], '2', $field);
        $this->sheet1SheetData .= '</row>';

        /* Write system data in excel.*/
        $this->writeSysData();

        $i = 2;
        $stepWidthList = array();
        $this->app->loadLang('report');

        $images = array();
        if($this->post->kind == 'bug')
        {
            $imgIdList = array();
            foreach($this->rows as $row)
            {
                $steps = zget($row, 'steps', '');
                if(empty($steps)) continue;

                $preg = '/ src="{([0-9]+)(\.(\w+))?}" /';
                preg_match_all($preg, $steps, $match);
                if(empty($match[1])) continue;

                $imgIdList = array_merge($imgIdList, $match[1]);
            }
            $imagIdList = array_unique($imgIdList);
            if($imgIdList) $images = $this->dao->select('*')->from(TABLE_FILE)->where('id')->in($imgIdList)->fetchAll('id', false);
        }

        foreach($this->rows as $num => $row)
        {
            $i ++;
            $this->sheet1SheetData .= '<row r="' . $i . '" spans="1:%colspan%" ht="168" x14ac:dyDescent="0.3">';
            $col = 0;
            foreach($this->excelKey as $key => $letter)
            {
                if(isset($this->lang->report->build) && $this->post->kind == $this->lang->report->build) $this->styleSetting['center'][$letter] = 1;

                $value = isset($row->$key) ? $row->$key : '';
                if($key == 'steps' and $this->post->kind == 'bug') $value = $this->setImgData($i, $col, $value, $images);
                $col++;

                if(in_array($key, $this->config->excel->autoWidths))
                {
                    if(!isset($this->maxWidths[$key])) $this->maxWidths[$key] = 0;
                    if($this->maxWidths[$key] < strlen($value)) $this->maxWidths[$key] = strlen($value);

                    if($key == 'stepDesc' or $key == 'stepExpect')
                    {
                        $stepCount = substr_count($value, '. ');
                        if(!$stepCount) $stepCount = 1;
                        $stepMaxWidth = ceil(strlen($value) / $stepCount);
                        $stepWidthList[]       = $stepMaxWidth ? $stepMaxWidth : $this->config->excel->width->long;
                        $this->maxWidths[$key] = max($stepWidthList);
                    }
                }
                /* Merge Cells.*/
                if(isset($this->post->rowspan[$num]) and is_string($this->post->rowspan[$num]['rows']) and strpos(',' . $this->post->rowspan[$num]['rows'] . ',', ",$key,") !== false)
                {
                    $this->mergeCells($letter . $i, $letter . ($i + $this->post->rowspan[$num]['num'] - 1));
                }
                if(isset($this->post->rowspan[$num]['rows'][$key]))
                {
                    $this->mergeCells($letter . $i, $letter . ($i + $this->post->rowspan[$num]['rows'][$key] - 1));
                }
                if(isset($this->post->colspan[$num]) and strpos(',' . $this->post->colspan[$num]['cols'] . ',', ",$key,") !== false)
                {
                    $this->mergeCells($letter . $i , chr(ord($letter) + $this->post->colspan[$num]['num'] - 1) . $i);
                }

                /* Wipe off html tags.*/
                if(isset($this->config->excel->editor[$this->post->kind]) and in_array($key, $this->config->excel->editor[$this->post->kind])) $value = $this->file->excludeHtml($value);
                if(is_numeric($value)) $value = $value . "\t";
                if($key == 'files')
                {
                    $this->formatFiles($letter, $i, $value);
                }
                else
                {
                    if(($key == 'execution' or $key == 'product') and isset($value[1])) $value = $value[1] == ':' ? $substr($value, 2) : $value;
                    $this->sheet1SheetData .= $this->setCellValue($letter, $i, $value);
                }

                /* Build excel list.*/
                if(!empty($_POST['listStyle']) and in_array($key, $this->post->listStyle)) $this->buildList($key, $i);
            }
            $this->sheet1SheetData .= '</row>';
        }

        $initField = isset($this->config->excel->{$this->post->kind}->initField) ? $this->config->excel->{$this->post->kind}->initField : array();
        if(!empty($_POST['extraNum']))
        {
            $i ++;
            $extraNum = $i + $this->post->extraNum;
            for($i; $i < $extraNum; $i++)
            {
                $this->sheet1SheetData .= '<row r="' . $i . '" spans="1:%colspan%">';
                foreach($this->fieldsKey as $field)
                {
                    if(isset($this->excelKey[$field]))
                    {
                        if(($field == 'execution' or $field == 'product' or $field == 'project') and isset($_POST[$field]))
                        {
                            $this->sheet1SheetData .= $this->setCellValue($this->excelKey[$field], $i, $_POST[$field]);
                        }
                        elseif(strpos($field, 'Date') !== false or in_array($field, $this->config->excel->dateField))
                        {
                            $this->sheet1SheetData .= $this->setCellValue($this->excelKey[$field], $i, '');
                        }
                        elseif(isset($initField[$field]))
                        {
                            $this->sheet1SheetData .= $this->setCellValue($this->excelKey[$field], $i, $this->file->excludeHtml($initField[$field], 'keepNL'));
                        }
                        else
                        {
                            $this->sheet1SheetData .= $this->setCellValue($this->excelKey[$field], $i, '');
                        }
                    }

                    /* Build excel list.*/
                    if(!empty($_POST['listStyle']) and in_array($field, $this->post->listStyle)) $this->buildList($field, $i);
                }
                $this->sheet1SheetData .= '</row>';
            }
        }

        $endColumn = $this->setExcelFiled(count($this->excelKey) + $this->fileCount - 2);
        if($this->fileCount > 1) $this->mergeCells(end($this->excelKey) . 2, $endColumn . 2);
        $this->setStyle($i);

        if(!empty($this->sheet1Params['cols'])) $this->sheet1Params['cols'] = '<cols>' . join($this->sheet1Params['cols']) . '</cols>';
        if(!empty($this->sheet1Params['mergeCells'])) $this->sheet1Params['mergeCells'] = '<mergeCells count="' . $this->counts['mergeCells'] . '">' . $this->sheet1Params['mergeCells'] . '</mergeCells>';
        if(!empty($this->sheet1Params['dataValidations'])) $this->sheet1Params['dataValidations'] = '<dataValidations count="' . $this->counts['dataValidations'] . '">' . $this->sheet1Params['dataValidations'] . '</dataValidations>';
        if(!empty($this->sheet1Params['hyperlinks'])) $this->sheet1Params['hyperlinks'] = '<hyperlinks>' . $this->sheet1Params['hyperlinks'] . '</hyperlinks>';

        /* Save sheet1*/
        $sheet1 = file_get_contents($this->exportPath . 'xl/worksheets/sheet1.xml');

        if(!in_array($this->post->kind, $this->config->excel->noAutoFilter))
        {
            $sheet1 = str_replace(array('%area%', '%xSplit%', '%cols%', '%sheetData%', '%mergeCells%', '%autoFilter%', '%dataValidations%', '%hyperlinks%', '%colspan%'),
                array($this->sheet1Params['area'], $this->sheet1Params['xSplit'], $this->sheet1Params['cols'], $this->sheet1SheetData, $this->sheet1Params['mergeCells'],
                empty($this->rows) ? '' : '<autoFilter ref="A2:' . $endColumn . '2"/>',
                $this->sheet1Params['dataValidations'], $this->sheet1Params['hyperlinks'], $this->sheet1Params['colspan']), $sheet1);
        }
        else
        {
            $sheet1 = str_replace(array('%area%', '%xSplit%', '%cols%', '%sheetData%', '%mergeCells%', '%autoFilter%', '%dataValidations%', '%hyperlinks%', '%colspan%'),
                array($this->sheet1Params['area'], $this->sheet1Params['xSplit'], $this->sheet1Params['cols'], $this->sheet1SheetData, $this->sheet1Params['mergeCells'],
                empty($this->rows) ? '' : $endColumn,
                $this->sheet1Params['dataValidations'], $this->sheet1Params['hyperlinks'], $this->sheet1Params['colspan']), $sheet1);
        }

        file_put_contents($this->exportPath . 'xl/worksheets/sheet1.xml', $sheet1);
        $workbookFile = file_get_contents($this->exportPath . 'xl/workbook.xml');
        $workbookFile = str_replace('%autoFilter%', empty($this->rows) ? '' : '!$A$1:$' . $endColumn . '$1', $workbookFile);
        $workbookFile = str_replace('%cascadeNames%', $this->cascadeNames, $workbookFile);
        file_put_contents($this->exportPath . 'xl/workbook.xml', $workbookFile);

        /* Save sharedStrings file. */
        $this->sharedStrings .= '</sst>';
        $this->sharedStrings  = str_replace('%count%', $this->record, $this->sharedStrings);
        $this->sharedStrings  = preg_replace('/[\x00-\x09\x0B-\x1F\x7F-\x9F]/u', '', $this->sharedStrings);
        file_put_contents($this->exportPath . 'xl/sharedStrings.xml', $this->sharedStrings);

        /* Save link message. */
        if($this->rels)
        {
            $this->rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' . $this->rels . '</Relationships>';
            if(!is_dir($this->exportPath . 'xl/worksheets/_rels/')) mkdir($this->exportPath . 'xl/worksheets/_rels/');
            file_put_contents($this->exportPath . 'xl/worksheets/_rels/sheet1.xml.rels', $this->rels);
        }

        /* urlencode the filename for ie. */
        $fileName = uniqid() . '.xlsx';

        /* Process image. */
        if($this->imgList) $this->file->insertImgToXlsx($this->imgList, $this->exportPath);

        /* Zip to xlsx. */
        helper::cd($this->exportPath);
        $files = array('[Content_Types].xml', '_rels', 'docProps', 'xl');
        $zip   = new pclzip($fileName);
        $zip->create($files);

        $fileData = file_get_contents($this->exportPath . $fileName);
        $this->zfile->removeDir($this->exportPath);
        $this->sendDownHeader($this->post->fileName . '.xlsx', 'xlsx', $fileData);
    }

    /**
     * Set excel style
     *
     * @param  int    $excelSheet
     * @access public
     * @return void
     */
    public function setStyle($i)
    {
        $endColumn = $this->setExcelFiled(count($this->excelKey) + $this->fileCount - 2);
        $this->sheet1Params['area'] = "A1:$endColumn$i";
        $i         = isset($this->lang->excel->help->{$this->post->kind}) ? $i - 1 : $i;
        $letters   = array_values($this->excelKey);

        /* Freeze column.*/
        $this->sheet1Params['xSplit']      = '';
        if(isset($this->config->excel->freeze->{$this->post->kind}))
        {
            $xSplit = '<pane xSplit="%xSplit%" ySplit="1" topLeftCell="%topLeftCell%" activePane="bottomRight" state="frozenSplit"/><selection pane="topRight"/><selection pane="bottomLeft"/>';
            $column = $this->excelKey[$this->config->excel->freeze->{$this->post->kind}];
            $column ++;
            $this->sheet1Params['xSplit'] = str_replace(array("%xSplit%", '%topLeftCell%'), array(array_search($column, $letters), $column . '2'), $xSplit);
        }

        /* Set column width */
        foreach($this->excelKey as $key => $letter)
        {
            $shortWidth = $this->config->excel->width->short;
            $titleWidth = $this->config->excel->width->middle;
            $contWidth  = $this->config->excel->width->long;
            $postion    = array_search($letter, $letters) + 1;

            if(strpos($key, 'Date') !== false) $this->setWidth($postion, 12);
            if($key == 'files') $this->setWidth($postion, 12);
            if(in_array($key, $this->config->excel->dateField)) $this->setWidth($postion, 12);
            if(in_array($key, $this->config->excel->shortFields)) $this->setWidth($postion, $shortWidth);
            if(in_array($key, $this->config->excel->titleFields)) $this->setWidth($postion, $titleWidth);
            if(isset($this->config->excel->editor[$this->post->kind]) and in_array($key, $this->config->excel->editor[$this->post->kind])) $this->setWidth($postion, $contWidth);
            if(in_array($key, $this->config->excel->autoWidths) and isset($this->maxWidths[$key]) and ($this->maxWidths[$key] * 0.7 + 0.71) > $shortWidth)
            {
                $this->setWidth($postion, $this->maxWidths[$key] * 0.7 + 0.71);
            }
            if(isset($_POST['width'][$key])) $this->setWidth($postion, $_POST['width'][$key]);
        }
    }

    /**
     * Set excel filed name.
     *
     * @param  int    $count
     * @access public
     * @return void
     */
    public function setExcelFiled($count)
    {
        $letter = 'A';
        for($i = 1; $i <= $count; $i++)$letter++;
        return $letter;
    }

    /**
     * Set img data.
     *
     * @param  int    $row
     * @param  int    $col
     * @param  string $content
     * @param  array  $images
     * @access public
     * @return void
     */
    public function setImgData($row = 0, $col = 0, $content = '', $images = array())
    {
        $row          = $row - 1;
        $imageWidth   = 2000000;
        $imageHight   = 1000000;
        $fontHight    = 200000;
        $maxCellHight = 3900000;

        /* xml 文件结构.*/
        $xdr = array();
        $xdr['colWidth']   = 41;
        $xdr['fromCol']    = $col;
        $xdr['fromColOff'] = 200000;
        $xdr['fromRow']    = $row;
        $xdr['fromRowOff'] = 1;
        $xdr['toCol']      = $col;
        $xdr['toColOff']   = $xdr['fromColOff'] + $imageWidth;
        $xdr['toRow']      = $row;
        $xdr['toRowOff']   = $xdr['fromRowOff'] + $imageHight;

        $list = array();
        $imgGroup = explode('</p>' , $content);
        if(empty($imgGroup)) return;

        $rowOff = 0;
        $fieldValue = array();
        $allowedExtensions = array('png', 'jpg', 'gif');
        foreach($imgGroup as $key => $value)
        {
            $fileValue[$key] = $value;

            /* 匹配img标签根据src获取图片id。*/
            $preg = '/ src="{([0-9]+)(\.(\w+))?}" /';
            preg_match($preg, $value, $match);
            if(!isset($match[1])) continue;

            $imageID = $match[1];
            if(!isset($images[$imageID])) continue; // 如果图片不存在直接跳过。

            $imgFile = $images[$imageID];
            $this->loadModel('file')->setFileWebAndRealPaths($imgFile);
            $realPath = $this->loadModel('file')->saveAsTempFile($imgFile);
            if(!is_file($realPath))
            {
                $fileValue[$key] = preg_replace('/(<img[^>]*>)/i', "", $fileValue[$key]);
                continue;
            }

            /* 过滤html标签.*/
            $value = preg_replace('/(<img[^>]*>)/i', "", $value);
            $value = htmlspecialchars(strip_tags($value));
            /* 获取本单元格内本段文字长度. */
            $length = mb_strlen($value, 'utf-8');

            /* 计算本段文字所占行高. */
            $rowHight = ceil($length / 40) * $fontHight;
            $rowOff += $rowHight;

            if(!empty($match[1]))
            {
                $extension = $match[3];
                if(!in_array($extension, $allowedExtensions)) $extension = 'png';

                $xdr['fromRowOff']  = $rowOff - $rowHight;
                $xdr['toRowOff']    = $xdr['fromRowOff'] + $imageHight;
                $xdr['ext']         = $match[1] . '.' . $extension;
                $xdr['name']        = $match[1];
                $xdr['extension']   = $extension;
                $list[]             = $xdr;
                $rowOff            += ($imageHight + 100000);
            }

            /* 如果图片累计高度超出行高 图片叠加显示.*/
            if($rowOff > $maxCellHight)
            {
                $rowOff = $maxCellHight - $imageHight;
                $fileValue[$key] = preg_replace('/(<img[^>]*>)/i', "", $fileValue[$key]);
            }
            else
            {
                $fileValue[$key] = preg_replace('/(<p><img[^>]*>)/i', "<p></p><p></p><p></p><p></p><p></p><p></p>", $fileValue[$key]);
                $fileValue[$key] = preg_replace('/(<img[^>]*>)/i', "<p></p><p></p><p></p><p></p><p></p><p></p>", $fileValue[$key]);
            }
        }

        $this->imgList[$row] = $list;
        $value = implode('</p>', $fileValue);
        return $value;
    }

    /**
     * Format files field.
     *
     * @param  int    $excelSheet
     * @param  int    $i
     * @param  int    $content
     * @access public
     * @return void
     */
    public function formatFiles($letter, $i, $content)
    {
        $fieldCount = count($this->excelKey);
        if(empty($content))
        {
            for($j = 0; $j < $this->fileCount; $j++)
            {
                $fieldName = $this->setExcelFiled($fieldCount + $j - 1);
                $this->sheet1SheetData .= $this->setCellValue($fieldName, $i, $content);
            }
            return;
        }

        $content = str_replace('<br />', '<br/>', $content);
        $content = explode('<br/>', $content);
        for($j = 0; $j < $this->fileCount; $j++)
        {
            $fieldName = $this->setExcelFiled($fieldCount + $j - 1);
            if(!empty($content[$j]))
            {
                $linkHtml = $content[$j];
                preg_match("/<a href='([^']+)'[^>]*>(.+)<\/a>/", $linkHtml, $out);
                $linkHref  = $out[1];
                $linkName  = $out[2];
                $this->sheet1SheetData .= $this->setCellValue($fieldName, $i, $linkName);
                $this->counts['hyperlinks']++;
                $this->rels .= '<Relationship Id="rId' . $this->counts['hyperlinks'] . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/hyperlink" Target="' . htmlspecialchars($linkHref) . '" TargetMode="External"/>';
                $this->sheet1Params['hyperlinks'] .= '<hyperlink ref="' . $fieldName . $i . '" r:id="rId' . $this->counts['hyperlinks'] . '" />';
            }
            else
            {
                $this->sheet1SheetData .= $this->setCellValue($fieldName, $i, '');
            }
        }
    }

    /**
     * Write system data to sheet2
     *
     * @access public
     * @return void
     */
    public function writeSysData()
    {
        $count   = 0;
        $maxList = '';
        $this->config->excel->sysDataField = array_unique($this->config->excel->sysDataField);
        foreach($this->config->excel->sysDataField as $sysDataField)
        {
            $sysDataList = $sysDataField . 'List';
            $$sysDataList = (isset($_POST[$sysDataList]) and is_array($_POST[$sysDataList])) ? array_values($_POST[$sysDataList]) : '';

            if(!isset($_POST['cascade'][$sysDataField]))
            {
                if($$sysDataList and count($$sysDataList) > $count)
                {
                    $count   = count($$sysDataList);
                    $maxList = $sysDataList;
                }
            }
            else
            {
                $cascadeField = $_POST['cascade'][$sysDataField];
                foreach($_POST[$sysDataList] as $cascadeFieldID => $sysDatas)
                {
                    if(!is_array($sysDatas)) continue;
                    $sysCascadeKey  = $sysDataField . $cascadeField . $cascadeFieldID;
                    $$sysCascadeKey = array_values($sysDatas);
                    if(count($sysDatas) > $count)
                    {
                        $count   = count($sysDatas);
                        $maxList = $sysCascadeKey;
                    }
                }
            }
        }

        $sheetData = '';
        if($maxList)
        {
            foreach($$maxList as $key => $value)
            {
                $col = 'A';
                $row = $key + 1;
                $sheetData .= '<row r="' . $row . '" spans="1:5">';
                foreach($this->config->excel->sysDataField as $sysDataField)
                {
                    $sysDataList = $sysDataField . 'List';
                    if(!isset($_POST['cascade'][$sysDataField]))
                    {
                        if(isset($this->fieldCols[$sysDataList])) $col = $this->fieldCols[$sysDataList];
                        $sysDatas   = isset($$sysDataList) ? $$sysDataList : '';
                        $sheetData .= $this->setCellValue($col, $row, isset($sysDatas[$key]) ? $sysDatas[$key] : '', false);
                        $this->fieldCols[$sysDataList] = $col;

                        $col ++;
                    }
                    else
                    {
                        $cascadeField = $_POST['cascade'][$sysDataField];
                        foreach($_POST[$sysDataList] as $cascadeFieldID => $sysDatas)
                        {
                            if(!is_array($sysDatas)) continue;

                            $sysCascadeKey = $sysDataField . $cascadeField . $cascadeFieldID;
                            if(isset($this->fieldCols[$sysCascadeKey])) $col = $this->fieldCols[$sysCascadeKey];

                            $sysDatas   = $$sysCascadeKey;
                            $sheetData .= $this->setCellValue($col, $row, isset($sysDatas[$key]) ? $sysDatas[$key] : '', false);
                            $this->fieldCols[$sysCascadeKey] = $col;
                            $col ++;
                        }

                        $cascadeList  = $cascadeField . 'List';
                        $cascadeDatas = isset($$cascadeList) ? $$cascadeList : '';
                        if(isset($cascadeDatas[$key]))
                        {
                            $cascadeValue   = $cascadeDatas[$key];
                            $cascadeFieldID = substr($cascadeValue, strrpos($cascadeValue, '#') + 1);
                            $cascadeFieldID = substr($cascadeFieldID, 0, strrpos($cascadeFieldID, ')'));
                            $sheetData .= $this->setCellValue($col, $row, $cascadeValue, false);
                            $this->fieldCols[$cascadeField . $sysDataField] = $col;
                            $col ++;
                            $sheetData .= $this->setCellValue($col, $row, "_{$sysDataField}_{$cascadeFieldID}_", false);
                            $this->fieldCols[$cascadeField . $sysDataField . 'ID'] = $col;
                            $col ++;
                        }
                    }
                }

                $sheetData .= '</row>';
            }
        }

        $sheet2 = file_get_contents($this->exportPath . 'xl/worksheets/sheet2.xml');
        $sheet2 = sprintf($sheet2, "A1" . ($count ? ":{$col}" . $count : ''), $sheetData);
        file_put_contents($this->exportPath . 'xl/worksheets/sheet2.xml', $sheet2);

        $this->cascadeNames = '';

        foreach($this->config->excel->sysDataField as $sysDataField)
        {
            if(isset($_POST['cascade'][$sysDataField]))
            {
                $sysDataList  = $sysDataField . 'List';
                $cascadeField = $_POST['cascade'][$sysDataField];

                $cascadeList = $cascadeField . 'List';
                if(isset($_POST[$cascadeList]))
                {
                    foreach($_POST[$sysDataList] as $cascadeFieldID => $sysDatas)
                    {
                        if(!is_array($sysDatas)) continue;
                        $sysCascadeKey = $sysDataField . $cascadeField . $cascadeFieldID;
                        $lastField     = $this->fieldCols[$sysCascadeKey];

                        $cascadeName = "_{$sysDataField}_{$cascadeFieldID}_";
                        if(empty($cascadeName)) continue;

                        $this->cascadeNames .= '<definedName name="' . $cascadeName . '">' . $this->lang->excel->title->sysValue . "!\${$lastField}\$1:\${$lastField}\$" . count($sysDatas) . '</definedName>';
                    }
                }
                if(isset($lastField)) $this->cascadeArea[$sysDataField] = "{$this->lang->excel->title->sysValue}!\$" . ++$lastField . "\$1:\$" . ++$lastField . "\$" . count($$cascadeList);
            }
        }
    }

    /**
     * Build drop list.
     *
     * @param  string    $field
     * @param  int       $row
     * @access public
     * @return void
     */
    public function buildList($field, $row)
    {
        $listName = $field . 'List';
        $range    = is_array($this->post->$listName) ? '' : '"' . $this->post->$listName . '"';

        $sysDataField = $field;
        if(in_array($sysDataField, $this->config->excel->sysDataField))
        {
            if(!isset($_POST['cascade'][$sysDataField]))
            {
                $sysDataList = $sysDataField . 'List';
                $col         = zget($this->fieldCols, $sysDataList, '');
                if(empty($col)) return false;

                $range = !empty($_POST[$sysDataList]) ? "{$this->lang->excel->title->sysValue}!\${$col}\$1:\${$col}\$" . (!is_array($_POST[$sysDataList]) ? 1 : count($_POST[$sysDataList])) : $range;
            }
            elseif(!isset($this->cascadeArea))
            {
                return false;
            }
            else
            {
                $cascadeKey = 'A';
                $cascadeField = $_POST['cascade'][$sysDataField];
                foreach($this->fieldsKey as $fieldKey)
                {
                    if($fieldKey == $cascadeField) break;
                    $cascadeKey ++;
                }
                $range = "INDIRECT(VLOOKUP({$cascadeKey}{$row},{$this->cascadeArea[$sysDataField]},2,0))";
            }
        }

        $this->sheet1Params['dataValidations'] .= '<dataValidation type="list" showErrorMessage="1" errorTitle="' . $this->lang->excel->error->title . '" error="' . $this->lang->excel->error->info . '" sqref="' . $this->excelKey[$field] . $row . '">';
        $this->sheet1Params['dataValidations'] .= '<formula1>' . $range . '</formula1></dataValidation>';
        $this->counts['dataValidations']++;
    }

    /**
     * Merge cells
     *
     * @param  string    $start   like A1
     * @param  string    $end     like B1
     * @access public
     * @return void
     */
    public function mergeCells($start, $end)
    {
        $this->sheet1Params['mergeCells'] .= '<mergeCell ref="' . $start . ':' . $end . '"/>';
        $this->counts['mergeCells']++;
    }

    /**
     * Set column width
     *
     * @param  int    $column
     * @param  int    $width
     * @access public
     * @return void
     */
    public function setWidth($column, $width)
    {
        $this->sheet1Params['cols'][$column] = '<col min="' . $column . '" max="' . $column . '" width="' . $width . '" customWidth="1"/>';
    }

    /**
     * Set cell value
     *
     * @param  string    $key
     * @param  int       $i
     * @param  int       $value
     * @param  bool      $style
     * @access public
     * @return string
     */
    public function setCellValue($key, $i, $value, $style = true)
    {
        /* Set style. The id number in styles.xml. */
        $s = '';
        if($style)
        {
            $s = $i % 2 == 0 ? '2' : '5';
            $s = $i == 2 ? 1 : $s;
            if($s != 1)
            {
                if(isset($this->styleSetting['center'][$key])) $s = $s == 2 ? 3 : 6;
                if(isset($this->styleSetting['date'][$key])) $s = $s <= 3 ? 4 : 7;
            }
            $s = 's="' . $s . '"';
        }

        $cellValue = '';
        if(is_numeric($value))
        {
            $cellValue .= '<c r="' . $key . $i . '" ' . $s . '><v>' . $value . '</v></c>';
        }
        elseif(!empty($value))
        {
            $cellValue .= '<c r="' . $key . $i . '" ' . $s . ' t="s"><v>' . $this->record . '</v></c>';
            $this->appendSharedStrings($value);
        }
        else
        {
            $cellValue .= '<c r="' . $key . $i . '" ' . $s . '/>';
        }

        return $cellValue;
    }

    /**
     * Set doc props
     *
     * @access public
     * @return void
     */
    public function setDocProps()
    {
        $sheetTitle   = !empty($this->lang->excel->title->{$this->post->kind}) ? $this->lang->excel->title->{$this->post->kind} : $this->post->kind;
        $headingSize  = 2;
        $headingPairs = '';
        $titlesSize   = 2;
        $titlesVector = '';
        if(isset($_POST['cascade']))
        {
            $headingSize  = 4;

            foreach($_POST['cascade'] as $key => $value)
            {
                $listKey = $value . 'List';
                if(isset($_POST[$listKey]))
                {
                    $titlesSize += empty($_POST[$listKey]) ? 0 : count($_POST[$listKey]);
                    foreach($_POST[$listKey] as $titleID => $titleName) $titlesVector .= "<vt:lpstr>_{$titleID}_</vt:lpstr>";
                }
            }
            $headingPairs = '<vt:variant><vt:lpstr>命名范围</vt:lpstr></vt:variant><vt:variant><vt:i4>' . ($titlesSize - 2) . '</vt:i4></vt:variant>';
        }

        $appFile = file_get_contents($this->exportPath . 'docProps/app.xml');
        $appFile = sprintf($appFile, $headingSize, $headingPairs, $titlesSize, $sheetTitle, $this->lang->excel->title->sysValue, $titlesVector);
        file_put_contents($this->exportPath . 'docProps/app.xml', $appFile);

        $coreFile   = file_get_contents($this->exportPath . 'docProps/core.xml');
        $createDate = date('Y-m-d') . 'T' . date('H:i:s') . 'Z';
        $coreFile   = sprintf($coreFile, $createDate, $createDate);
        file_put_contents($this->exportPath . 'docProps/core.xml', $coreFile);

        $workbookFile = file_get_contents($this->exportPath . 'xl/workbook.xml');
        $definedNames = '';
        if($this->rows or isset($_POST['cascade']))
        {
        $definedNames = '<definedNames>';
        if(isset($_POST['cascade'])) $definedNames .= '%cascadeNames%';
        if($this->rows) $definedNames .= '<definedName name="_xlnm._FilterDatabase" localSheetId="0" hidden="1">' . $sheetTitle . '%autoFilter%</definedName>';
        $definedNames .= '</definedNames>';
        }
        if(!in_array($this->post->kind, $this->config->excel->isShowSystemTab))
        {
            $workbookFile = str_replace(array('%sheetTitle%', '%sysValue%', '%definedNames%'), array($sheetTitle, $this->lang->excel->title->sysValue, $definedNames), $workbookFile);
        }
        else
        {
            $workbookFile = str_replace(array('%sheetTitle%', '%sysValue%', '%definedNames%'), array($sheetTitle, ' ', $definedNames), $workbookFile);
        }
        file_put_contents($this->exportPath . 'xl/workbook.xml', $workbookFile);
    }

    /**
     * Append shared strings
     *
     * @param  string    $value
     * @access public
     * @return void
     */
    public function appendSharedStrings($value)
    {
        $preserve = strpos($value, "\n") === false ? '' : ' xml:space="preserve"';
        $preserve = strpos($value, "#") === false ? '' : ' xml:space="preserve"';

        if($this->post->kind == 'bug') $preserve = ' xml:space="preserve"';
        $value    = htmlspecialchars_decode($value);
        $value    = htmlspecialchars($value, ENT_QUOTES);
        $this->sharedStrings .= '<si><t' . $preserve . '>' . $value . '</t></si>';
        $this->record++;
    }

    /**
     * Set tips.
     *
     * @param  string    $y
     * @param  int       $x
     * @param  string    $tips
     * @access public
     * @return void
     */
    function setTips($y, $x, $tips)
    {
        $tipsHeight = zget($this->config->excel->tipsHeight, $tips, '');
        if($tipsHeight) $tipsHeight = 'ht="' . $tipsHeight . '"';

        $this->mergeCells($y . $x, $this->setExcelFiled($this->sheet1Params['colspan'] - 1) . $x);
        $this->sheet1SheetData .= '<row r="' . $x . '" spans="1:%colspan%" ' . $tipsHeight . ' customHeight="1">';
        $this->sheet1SheetData .= $this->setCellValue($y, $x, $this->getRequiredFieldsTips($tips));
        $this->sheet1SheetData .= '</row>';
    }

    /**
     * get Tips
     *
     * @param  int    $kind
     * @access public
     * @return void
     */
    function getRequiredFieldsTips($kind)
    {
        if(!in_array($kind, $this->config->excel->requiredFieldModule)) return $this->lang->excel->help->$kind;

        $requiredFields = isset($this->config->$kind->create->requiredFields) ? explode(',', $this->config->$kind->create->requiredFields) :  '';
        if(in_array($kind, array('story', 'requirement', 'epic', 'bug', 'testcase')) && $this->session->product)
        {
            $product = $this->loadModel('product')->fetchByID($this->session->product);
            if($product && $product->type != 'normal') $requiredFields[] = 'branch';
        }
        if(empty($requiredFields)) return $requiredFields;

        $tmp = array();
        foreach($requiredFields as $field) $tmp[] = $this->lang->$kind->$field;

        $comma = common::checkNotCN() ? ',' : '，';
        return sprintf($this->lang->excel->help->$kind, implode($comma, $tmp));
    }
}
