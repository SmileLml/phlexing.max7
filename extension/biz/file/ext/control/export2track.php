<?php
helper::importControl('file');
class myfile extends file
{
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
    public $sharedStrings   = '';
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
     * fields and name pairs.
     *
     * @var array
     * @access public
     */
    public $fields    = array();
    public $fieldsKey = array();
    public $excelKey  = array();

    /**
     * excel rows.
     *
     * @var array
     * @access public
     */
    public $rows = array();

    /**
     * colspan settings.
     *
     * @var array
     * @access public
     */
    public $colspan = array();

    /**
     * rowspan settings.
     *
     * @var array
     * @access public
     */
    public $rowspan = array();

    /**
     * zfile class.
     *
     * @var object
     * @access public
     */
    public $zfile;
    public $exportPath;

    /**
     * init for excel data.
     *
     * @access public
     * @return void
     */
    public function init()
    {
        $this->fields    = $this->post->fields;
        $this->rows      = $this->post->rows;
        $this->colspan   = $this->post->colspan;
        $this->rowspan   = $this->post->rowspan;
        $this->fieldsKey = array_keys($this->fields[1]);

        /* Init excel file. */
        $this->app->loadClass('pclzip', true);
        $this->zfile      = $this->app->loadClass('zfile');
        $this->exportPath = $this->app->getCacheRoot() . $this->app->user->account . uniqid() . '/';
        if(is_dir($this->exportPath))$this->zfile->removeDir($this->exportPath);
        $this->zfile->mkdir($this->exportPath);
        $this->zfile->copyDir($this->app->getCoreLibRoot() . 'phpexcel/xlsx', $this->exportPath);

        $this->sharedStrings = file_get_contents($this->exportPath . 'xl/sharedStrings.xml');
        $this->sheet1Params['dataValidations'] = '';
        $this->sheet1Params['cols']            = array();
        $this->sheet1Params['mergeCells']      = '';
        $this->sheet1Params['hyperlinks']      = '';

        $this->counts['dataValidations'] = 0;
        $this->counts['mergeCells']      = 0;
        $this->counts['hyperlinks']      = 0;
    }

    /**
     * Export data to Excel. This is main function.
     *
     * @access public
     * @return void
     */
    public function export2Track()
    {
        $this->init();
        $this->setDocProps();
        $this->excelKey  = array();
        $this->maxWidths = array();
        for($i = 0; $i < count($this->fieldsKey); $i++)
        {
            $field = $this->fieldsKey[$i];
            $this->excelKey[$field] = $this->setExcelFiled($i);
        }

        /* Show header data. */
        $i = 0;
        $this->sheet1SheetData = '<row r="1" spans="1:%colspan%">';
        foreach($this->fields[0] as $key => $field)
        {
            $letter = $this->setExcelFiled($i);
            $this->sheet1SheetData .= $this->setCellValue($letter, '1', $field);
            if(isset($this->colspan['head'][$key]))
            {
                $this->mergeCells($letter . '1', $this->setExcelFiled($i + $this->colspan['head'][$key] - 1) . '1');
                $i += $this->colspan['head'][$key] - 1;
            }
            $i++;
        }
        $this->sheet1SheetData .= '</row>';
        $this->sheet1SheetData .= '<row r="2" spans="1:%colspan%">';
        foreach($this->fields[1] as $key => $field) $this->sheet1SheetData .= $this->setCellValue($this->excelKey[$key], '2', $field);
        $this->sheet1SheetData .= '</row>';

        /* Write system data in excel.*/
        $this->writeSysData();

        $i = 2;
        $excelData = array();
        foreach($this->rows as $num => $row)
        {
            $i ++;
            $columnData = array();
            $this->sheet1SheetData .= '<row r="' . $i . '" spans="1:%colspan%" ht="168" x14ac:dyDescent="0.3">';
            $this->app->loadLang('report');
            $col = 0;
            foreach($this->excelKey as $key => $letter)
            {
                $value = zget($row, $key, '');
                $col++;

                if(!isset($this->maxWidths[$key])) $this->maxWidths[$key] = 0;
                if($this->maxWidths[$key] < strlen($value)) $this->maxWidths[$key] = strlen($value);

                /* Merge Cells.*/
                if(isset($this->rowspan['body'][$num][$key])) $this->mergeCells($letter . $i, $letter . ($i + $this->rowspan['body'][$num][$key] - 1));

                $this->sheet1SheetData .= $this->setCellValue($letter, $i, $value);
            }
            $this->sheet1SheetData .= '</row>';
        }

        $this->sheet1Params['colspan'] = count($this->excelKey);
        $endColumn = $this->setExcelFiled(count($this->excelKey));

        $this->setStyle($i);

        if(!empty($this->sheet1Params['cols'])) $this->sheet1Params['cols'] = '<cols>' . join($this->sheet1Params['cols']) . '</cols>';
        if(!empty($this->sheet1Params['mergeCells'])) $this->sheet1Params['mergeCells'] = '<mergeCells count="' . $this->counts['mergeCells'] . '">' . $this->sheet1Params['mergeCells'] . '</mergeCells>';
        if(!empty($this->sheet1Params['dataValidations'])) $this->sheet1Params['dataValidations'] = '<dataValidations count="' . $this->counts['dataValidations'] . '">' . $this->sheet1Params['dataValidations'] . '</dataValidations>';
        if(!empty($this->sheet1Params['hyperlinks'])) $this->sheet1Params['hyperlinks'] = '<hyperlinks>' . $this->sheet1Params['hyperlinks'] . '</hyperlinks>';

        /* Save sheet1*/
        $this->sheet1SheetData = str_replace('%colspan%', count($this->excelKey), $this->sheet1SheetData);
        $sheet1 = str_replace(array('%area%', '%xSplit%', '%cols%', '%sheetData%', '%mergeCells%', '%autoFilter%', '%dataValidations%', '%hyperlinks%', '%colspan%'),
            array($this->sheet1Params['area'], $this->sheet1Params['xSplit'],
            empty($this->sheet1Params['cols']) ? '' : $this->sheet1Params['cols'],
            $this->sheet1SheetData, $this->sheet1Params['mergeCells'],
            empty($this->rows) ? '' : '<autoFilter ref="A2:' . $endColumn . '2"/>',
            $this->sheet1Params['dataValidations'], $this->sheet1Params['hyperlinks'], $this->sheet1Params['colspan']),
            file_get_contents($this->exportPath . 'xl/worksheets/sheet1.xml'));

        file_put_contents($this->exportPath . 'xl/worksheets/sheet1.xml', $sheet1);
        $workbookFile = file_get_contents($this->exportPath . 'xl/workbook.xml');
        $workbookFile = str_replace('%autoFilter%', empty($this->rows) ? '' : '!$A$1:$' . $endColumn . '$1', $workbookFile);
        $workbookFile = str_replace('%cascadeNames%', '', $workbookFile);
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
        $endColumn = $this->setExcelFiled(count($this->excelKey));
        $this->sheet1Params['area'] = "A1:$endColumn$i";

        $i       = isset($this->lang->excel->help->{$this->post->kind}) ? $i - 1 : $i;
        $letters = array_flip(array_values($this->excelKey));

        /* Freeze column.*/
        $this->sheet1Params['xSplit'] = '<pane ySplit="2" topLeftCell="A3" activePane="bottomLeft" state="frozen"/>';

        /* Set column width */
        foreach($this->excelKey as $key => $letter)
        {
            $minWidth   = 10;
            $postion    = zget($letters, $letter) + 1;
            $fieldWidth = !isset($this->maxWidths[$key]) ? $minWidth : $this->maxWidths[$key] * 0.7 + 0.71;
            if(isset($_POST['width'][$key]))  $fieldWidth = $_POST['width'][$key];
            $this->setWidth($postion, max($fieldWidth, $minWidth));
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
     * Write system data to sheet2
     *
     * @access public
     * @return void
     */
    public function writeSysData()
    {
        $sheet2 = file_get_contents($this->exportPath . 'xl/worksheets/sheet2.xml');
        $sheet2 = sprintf($sheet2, "A1:A1", '');
        file_put_contents($this->exportPath . 'xl/worksheets/sheet2.xml', $sheet2);
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
            $s = $i == 1 ? 1 : $s;
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
        $sheetTitle   = $this->lang->file->track;
        $headingSize  = 2;
        $headingPairs = '';
        $titlesSize   = 2;
        $titlesVector = '';

        $appFile = file_get_contents($this->exportPath . 'docProps/app.xml');
        $appFile = sprintf($appFile, $headingSize, $headingPairs, $titlesSize, $sheetTitle, $this->lang->excel->title->sysValue, $titlesVector);
        file_put_contents($this->exportPath . 'docProps/app.xml', $appFile);

        $coreFile   = file_get_contents($this->exportPath . 'docProps/core.xml');
        $createDate = date('Y-m-d') . 'T' . date('H:i:s') . 'Z';
        $coreFile   = sprintf($coreFile, $createDate, $createDate);
        file_put_contents($this->exportPath . 'docProps/core.xml', $coreFile);

        $workbookFile = file_get_contents($this->exportPath . 'xl/workbook.xml');
        $definedNames = '';
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
}
