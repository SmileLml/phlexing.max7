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
     * every counts in need count.
     *
     * @var array
     * @access public
     */
    public $counts = array();

    /**
     * init for excel data.
     *
     * @access public
     * @return void
     */
    public function init()
    {
        $this->loadModel('execution');
        $tasks   = $this->post->rows;
        $orderBy = $this->post->orderBy;
        $users   = $this->loadModel('user')->getPairs('noletter');

        $taskLang = $this->lang->task;
        $this->fields["group_{$orderBy}"] = $this->lang->execution->groups[$orderBy];
        $this->fields['id']         = $taskLang->id;
        $this->fields['pri']        = $taskLang->pri;
        $this->fields['name']       = $taskLang->name;
        $this->fields['status']     = $taskLang->status;
        $this->fields['assignedTo'] = $taskLang->assignedTo;
        $this->fields['finishedBy'] = $taskLang->finishedBy;
        $this->fields['estimate']   = $taskLang->estimateAB;
        $this->fields['consumed']   = $taskLang->consumedAB;
        $this->fields['left']       = $taskLang->leftAB;
        $this->fields['progress']   = $taskLang->progress;
        $this->fields['type']       = $taskLang->type;
        $this->fields['deadline']   = $taskLang->deadlineAB;

        $this->orderBy     = $orderBy;
        $this->groupByList = array();
        $this->groupTasks  = array();
        foreach($tasks as $task)
        {
            if($orderBy == 'story')
            {
                $this->groupTasks[$task->story][] = $task;
                $this->groupByList[$task->story]  = $task->story;
            }
            else
            {
                $this->groupTasks[$task->$orderBy][] = $task;
            }
        }

        $this->app->loadClass('pclzip', true);
        $this->zfile = $this->app->loadClass('zfile');

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
     * Export data to Excel. This is main function.
     *
     * @access public
     * @return void
     */
    public function export2Group()
    {
        $this->init();
        $this->setDocProps();
        $this->excelKey  = array();
        $this->maxWidths = array();
        for($i = 0; $i < count($this->fieldsKey); $i++)
        {
            $field = $this->fieldsKey[$i];
            $this->excelKey[$field] = $this->setExcelFiled($i);
            if(strpos($field, 'Date') !== false or in_array($field, $this->config->excel->dateField))$this->styleSetting['date'][$this->excelKey[$field]] = 1;
        }

        /* Show header data. */
        $this->sheet1SheetData = '<row r="1" spans="1:%colspan%">';
        foreach($this->fields as $key => $field) $this->sheet1SheetData .= $this->setCellValue($this->excelKey[$key], '1', $field);
        $this->sheet1SheetData .= '</row>';
        $this->writeSysData();

        $i = 1;
        $excelData = array();
        foreach($this->groupTasks as $groupKey => $tasks)
        {
            $groupName = $groupKey;
            if($this->orderBy == 'story') $groupName = empty($groupName) ? $this->lang->task->noStory : zget($this->groupByList, $groupKey);
            if($this->orderBy == 'assignedTo' and $groupName == '') $groupName = $this->lang->task->noAssigned;

            $groupWait     = 0;
            $groupDone     = 0;
            $groupDoing    = 0;
            $groupClosed   = 0;
            $groupEstimate = 0.0;
            $groupConsumed = 0.0;
            $groupLeft     = 0.0;

            $groupSum = 0;
            foreach($tasks as $taskKey => $task)
            {
                if($this->orderBy == 'story')
                {
                    if($task->parent >= 0)
                    {
                        $groupEstimate += (float)$task->estimate;
                        $groupConsumed += (float)$task->consumed;
                        if($task->status != $this->lang->task->statusList['cancel'] && $task->status != $this->lang->task->statusList['closed']) $groupLeft += (float)$task->left;
                    }
                }
                else
                {
                    if($task->parent >= 0)
                    {
                        $groupEstimate += (float)$task->estimate;
                        $groupConsumed += (float)$task->consumed;

                        if($this->orderBy == 'status' || ($task->status != $this->lang->task->statusList['cancel'] && $task->status != $this->lang->task->statusList['closed'])) $groupLeft += (float)$task->left;
                    }
                }

                if($task->status == $this->lang->task->statusList['wait'])   $groupWait++;
                if($task->status == $this->lang->task->statusList['doing'])  $groupDoing++;
                if($task->status == $this->lang->task->statusList['done'])   $groupDone++;
                if($task->status == $this->lang->task->statusList['closed']) $groupClosed++;
            }
            $groupSum = count($tasks);

            $groupIndex = 0;
            foreach($tasks as $taskKey => $task)
            {
                $i++;
                $columnData = array();
                $this->sheet1SheetData .= '<row r="' . $i . '" spans="1:%colspan%">';
                foreach($this->excelKey as $key => $letter)
                {
                    $value = isset($task->$key) ? $task->$key : '';
                    if($key == "group_{$this->orderBy}")
                    {
                        if($groupIndex > 0) continue;

                        $value  = $groupName;
                        $value .= "\n" . sprintf($this->lang->execution->groupSummaryAB, $groupSum, $groupWait, $groupDoing, $groupEstimate . $this->lang->execution->workHourUnit, $groupConsumed . $this->lang->execution->workHourUnit, $groupLeft . $this->lang->execution->workHourUnit);
                        $value  = strip_tags(str_replace(array('&nbsp;', '<div>', '</div>'), array(' ', '', "\n"), $value));
                    }
                    if(in_array($key, $this->config->excel->autoWidths))
                    {
                        if(!isset($this->maxWidths[$key])) $this->maxWidths[$key] = 0;
                        if($this->maxWidths[$key] < strlen($value)) $this->maxWidths[$key] = strlen($value);
                    }
                    /* Merge Cells.*/
                    if($groupSum and "group_{$this->orderBy}" == $key and $groupIndex == 0) $this->mergeCells($letter . $i, $letter . ($i + $groupSum - 1));

                    /* Wipe off html tags.*/
                    $this->sheet1SheetData .= $this->setCellValue($letter, $i, $value);
                }
                $this->sheet1SheetData .= '</row>';
                $groupIndex ++;
            }
        }

        $this->sheet1Params['colspan'] = count($this->excelKey) + $this->fileCount - 1;
        $endColumn = $this->setExcelFiled(count($this->excelKey) + $this->fileCount - 2);
        if($this->fileCount > 1) $this->mergeCells(end($this->excelKey) . 1, $endColumn . 1);
        $this->setStyle($i);

        if(!empty($this->sheet1Params['cols'])) $this->sheet1Params['cols'] = '<cols>' . join($this->sheet1Params['cols']) . '</cols>';
        if(!empty($this->sheet1Params['mergeCells'])) $this->sheet1Params['mergeCells'] = '<mergeCells count="' . $this->counts['mergeCells'] . '">' . $this->sheet1Params['mergeCells'] . '</mergeCells>';
        if(!empty($this->sheet1Params['dataValidations'])) $this->sheet1Params['dataValidations'] = '<dataValidations count="' . $this->counts['dataValidations'] . '">' . $this->sheet1Params['dataValidations'] . '</dataValidations>';
        if(!empty($this->sheet1Params['hyperlinks'])) $this->sheet1Params['hyperlinks'] = '<hyperlinks>' . $this->sheet1Params['hyperlinks'] . '</hyperlinks>';

        /* Save sheet1*/
        $sheet1 = file_get_contents($this->exportPath . 'xl/worksheets/sheet1.xml');
        $sheet1 = str_replace(array('%area%', '%xSplit%', '%cols%', '%sheetData%', '%mergeCells%', '%autoFilter%', '%dataValidations%', '%hyperlinks%', '%colspan%'),
            array($this->sheet1Params['area'], $this->sheet1Params['xSplit'], $this->sheet1Params['cols'], $this->sheet1SheetData, $this->sheet1Params['mergeCells'],
            empty($this->groupTasks) ? '' : '<autoFilter ref="A1:' . $endColumn . '1"/>',
            $this->sheet1Params['dataValidations'], $this->sheet1Params['hyperlinks'], $this->sheet1Params['colspan']), $sheet1);
        file_put_contents($this->exportPath . 'xl/worksheets/sheet1.xml', $sheet1);

        $workbookFile = file_get_contents($this->exportPath . 'xl/workbook.xml');
        $workbookFile = str_replace('%autoFilter%', empty($this->groupTasks) ? '' : '!$A$1:$' . $endColumn . '$1', $workbookFile);
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
        $this->sheet1Params['area'] = "A1:{$endColumn}1";
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
            $titleWidth = 30;
            $contWidth  = $this->config->excel->width->long;
            $postion    = array_search($letter, $letters) + 1;

            if(strpos($key, 'Date') !== false) $this->setWidth($postion, 12);
            if(in_array($key, $this->config->excel->dateField)) $this->setWidth($postion, $shortWidth);
            if(in_array($key, $this->config->excel->titleFields)) $this->setWidth($postion, $titleWidth);
            if("group_{$this->orderBy}" == $key) $this->setWidth($postion, $titleWidth);
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
     * Write system data to sheet2
     *
     * @access public
     * @return void
     */
    public function writeSysData()
    {
        $sheet2 = file_get_contents($this->exportPath . 'xl/worksheets/sheet2.xml');
        $sheet2 = sprintf($sheet2, "A1",'');
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
        $sheetTitle   = isset($this->lang->excel->title->{$this->post->kind}) ? $this->lang->excel->title->{$this->post->kind} : $this->post->kind;
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
                    $titlesSize += count($_POST[$listKey]);
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
        $workbookFile = str_replace(array('%sheetTitle%', '%sysValue%', '%definedNames%'), array($sheetTitle, $this->lang->excel->title->sysValue, $definedNames), $workbookFile);
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
        $value    = htmlspecialchars_decode($value);
        $value    = htmlspecialchars($value, ENT_QUOTES);
        $this->sharedStrings .= '<si><t' . $preserve . '>' . $value . '</t></si>';
        $this->record++;
    }
}
