<?php
/**
 * The excel library of zdoo, can be used to export excel file.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com> 
 * @author      yaozeyuan <yaozeyuan@cnezsoft.com>
 * @package     Excel
 * @version     $Id$
 * @link        http://www.zdoo.com
 *
 * excel - a library to export excel file, depends on phpexcel.
 *
 * Here are some tips of excelData(named $data) structure maybe uesful. you can use these to create xls/xlsx file . The API is interchangeable.
 *
 * Base property:
 * data->fileName set the fileName
 * data->kind set the kind of excel module
 * data->fields is array like ($field => $fieldTitle). The order of data->fields is also the column's order
 * data->row is array like ($rowNumber => array($field => $value)).This is the data of Excel file. System will fill $value as data into every cell according to $rowNumber and $field
 *
 * Merge cell
 * if there is set data->dataRowspan[$row][$field] or data->dataColspan[$row][$field], the cells will be merge
 *      data->dataRowspan[$row][$field] => merge excelKey[$field]:$rowNumber to excelKey[$field]:($rowNumber + data->dataRowspan[$row][$field]) into one cell
 *      data->dataColspan[$row][$field] => merge excelKey[$field]:$rowNumber to transIntoExcelKey(int(excelKey[$field]) + dataColspan[$row][$field]):$rowNumber into one cell
 *
 * html content
 *      if you set config->excel->editor[$this->rawExcelData->kind] like 'excelKey1,excelKey2...', and excelKey in that, then $value of all column's cell will be process to remove html tag
 *
 * write sysData and use droplist style on cell
 * sysData is an array like (excelKey => valueList), system use this sheet to store extraData.
 * if you want to have droplist style on some column in sheet1, you can set data->($exceKey . 'List'), data->listStyle and  data->sysData sothat the data will be writen into the sysData sheet and you can see the droplist style is enable.
 * the data->listStyle and data->sysData is an  array of series value like ['dropListStyleColumnName' . 'List'] , like ('nameList', 'categoryList', ...) the dropListStyleColumnName used to indicate witch column need that style and data->[dropListStyleColumnName . 'List'] use to transfer data for system get real data to build datalist in sysdata sheet.
 *
 * FreezePane
 * if you set config->excel->freeze->{$this->data->kind}->targetColumnName, this column will be freezed
 *
 * Set width
 * You can set $data->customWidth like array($field => width, $field2 => width, $field3 => width,...) to set width by you like
 * or modify
 *       config->excel->titleFields
 *       config->excel->centerFields
 *       config->excel->dateFields
 * to have default style
 *
 * color
 * if you set data->nocolor, the excel file won't have color
 *
 * File title
 * The lang->excel->title->{data->kind} is the title of data->kind excel file
 *
 * SysData title
 * The lang->excel->title->sysValue is the name of sysData sheet , this is only can use for xls file.
 */
class excel extends model
{
    /**
     * __construct
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->phpExcel        = $this->app->loadClass('phpexcel');
        $this->file            = $this->loadModel('file');
        $this->sysDataColIndex = 0;
        $this->hasSysData      = false;
    }

    /**
     * Init for excel data.
     *
     * @param  int    $data
     * @access public
     * @return void
     */
    public function init($data)
    {
        $this->rawExcelData   = $data;
        $this->fields         = $data->fields;
        $this->rows           = $data->rows;
        $this->headerRowCount = isset($data->headerRowCount) ? $data->headerRowCount : 1;
        $this->fieldsKey      = $this->headerRowCount == 1 ? array_keys($this->fields) : array_keys(reset($this->fields));
    }

    /**
     * Export data to Excel. This is main function.
     *
     * @param  object $data
     * @param  string $fileType xls | xlsx
     * @param  string $savePath, if $savePath != '', then the file will save in $savePath
     * @access public
     * @return void
     */
    public function export($excelData, $fileType = 'xls', $savePath = '', $schemaSheets = '', $schemaSheetIndex = null)
    {
        $sheetIndex = 0;
        foreach($excelData->dataList as $data) $this->phpExcel->createSheet(); // Create sheets.

        foreach($excelData->dataList as $data)
        {
            $this->init($data);

            $this->excelKey = array();
            foreach($this->fieldsKey as $colIndex => $field) $this->excelKey[$field] = $this->setExcelField($colIndex);

            /* Set file base property */
            $excelProps = $this->phpExcel->getProperties();
            $excelProps->setCreator('ZDOO');
            $excelProps->setLastModifiedBy('ZDOO');
            $excelProps->setTitle('Office XLS Document');
            $excelProps->setSubject('Office XLS Document');
            $excelProps->setDescription('Document generated by PHPExcel.');
            $excelProps->setKeywords('office excel PHPExcel');
            $excelProps->setCategory('Result file');

            $excelSheet = $this->phpExcel->getSheet($sheetIndex);
            $sheetTitle = isset($this->rawExcelData->title) ? $this->rawExcelData->title : $this->rawExcelData->kind;
            if($sheetTitle) $excelSheet->setTitle($sheetTitle);

            $sheetRow = 1;
            if($this->headerRowCount == 1)
            {
                foreach($this->fields as $field => $fieldName)
                {
                    $cell = $this->excelKey[$field] . $sheetRow;
                    $excelSheet->setCellValueExplicit($cell, $fieldName, PHPExcel_Cell_DataType::TYPE_STRING);
                }
                $sheetRow++;
            }
            else
            {
                foreach($this->fields as $fieldIndex => $fields)
                {
                    foreach($fields as $field => $fieldName)
                    {
                        if(isset($this->excelKey[$field]))
                        {
                            $cell = $this->excelKey[$field] . $sheetRow;
                            /* Merge Cells.*/
                            if(isset($this->rawExcelData->headerRowspan[$fieldIndex][$field]) && is_int($this->rawExcelData->headerRowspan[$fieldIndex][$field]))
                            {
                                $endCell = $this->excelKey[$field] . ($sheetRow + $this->rawExcelData->headerRowspan[$fieldIndex][$field] - 1);
                                $excelSheet->mergeCells($cell . ":" . $endCell);
                            }
                            if(isset($this->rawExcelData->headerColspan[$fieldIndex][$field]) && is_int($this->rawExcelData->headerColspan[$fieldIndex][$field]))
                            {
                                $column  = $this->setExcelField($this->rawExcelData->headerColspan[$fieldIndex][$field] - 1, $this->excelKey[$field]);
                                $endCell = $column . $sheetRow;
                                $excelSheet->mergeCells($cell . ":" . $endCell);
                            }

                            $excelSheet->setCellValueExplicit($cell, $fieldName, PHPExcel_Cell_DataType::TYPE_STRING);
                        }
                    }
                    $sheetRow++;
                }
            }

            /* Write system data in excel.*/
            $this->writeSysData();

            $skipNumberCell = array(); // When merging cells, data cannot be merged. When assigning values to cells, it is necessary to skip merging data other than the first row
            foreach($this->rows as $rowIndex => $row)
            {
                foreach($row as $field => $value)
                {
                    if(isset($this->excelKey[$field]))
                    {
                        $cell = $this->excelKey[$field] . $sheetRow;
                        /* Merge Cells.*/
                        if (isset($this->rawExcelData->dataRowspan[$rowIndex][$field]) && is_int($this->rawExcelData->dataRowspan[$rowIndex][$field]))
                        {
                            $endCell = $this->excelKey[$field] . ($sheetRow + $this->rawExcelData->dataRowspan[$rowIndex][$field] - 1);
                            /* Merge underlying data.  */
                            for($i = $sheetRow + 1; $i <= $sheetRow + $this->rawExcelData->dataRowspan[$rowIndex][$field] - 1; $i++)
                            {
                                $skipNumberCell[] = $this->excelKey[$field] . $i;
                            }

                            $excelSheet->mergeCells($cell . ":" . $endCell);
                        }

                        if(isset($this->rawExcelData->dataColspan[$rowIndex][$field]) && is_int($this->rawExcelData->dataColspan[$rowIndex][$field]))
                        {
                            $column  = $this->setExcelField($this->rawExcelData->dataColspan[$rowIndex][$field] - 1, $this->excelKey[$field]);
                            $endCell = $column . $sheetRow;
                            $excelSheet->mergeCells($cell . ":" . $endCell);
                        }

                        /* Wipe off html tags.*/
                        if(isset($this->config->excel->editor[$this->rawExcelData->kind]) and in_array($field, $this->config->excel->editor[$this->rawExcelData->kind])) $value = $this->file->excludeHtml($value);
                        if(!in_array($cell, $skipNumberCell))
                        {
                            if(isset($this->rawExcelData->percentageFields[$rowIndex][$field]))
                            {
                                $excelSheet->setCellValueExplicit($cell, $value, PHPExcel_Cell_DataType::TYPE_STRING);
                            }
                            elseif(isset($this->rawExcelData->numberFields) && in_array($field, $this->rawExcelData->numberFields))
                            {
                                $excelSheet->setCellValueExplicit($cell, $value, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                            }
                            else
                            {
                                $excelSheet->setCellValueExplicit($cell, $value, PHPExcel_Cell_DataType::TYPE_STRING);
                            }
                        }

                        /* Add comments to cell, Excel5 don't work, must be Excel2007. */
                        if(isset($this->rawExcelData->comments[$rowIndex][$field])) $excelSheet->getComment($cell)->getText()->createTextRun($this->rawExcelData->comments[$rowIndex][$field]);

                        /* Calculate date. */
                        if((strpos($field, 'Date') !== false or in_array($field, $this->config->excel->dateFields)) && helper::isZeroDate($value)) $excelSheet->setCellValue($cell, $value);
                    }

                    /* Build excel list.*/
                    if(isset($this->rawExcelData->listStyle) and in_array($field, $this->rawExcelData->listStyle)) $this->buildList($excelSheet, $field, $sheetRow);
                }
                $sheetRow++;
            }

            $this->setStyle($excelSheet, $sheetRow - 1);
            $this->setAlignment($excelSheet);

            if(isset($this->rawExcelData->help))
            {
                $excelSheet->mergeCells("A" . $sheetRow . ":" . end($this->excelKey) . $sheetRow);
                $excelSheet->setCellValue("A" . $sheetRow, $this->rawExcelData->help);
            }

            $sheetIndex++;
        }

        /* If hasn't sys data remove the last sheet. */
        if(!$this->hasSysData) $this->phpExcel->removeSheetByIndex($this->phpExcel->getSheetCount() - 1);
        $this->phpExcel->setActiveSheetIndex(0);

        if(!empty($schemaSheets))
        {
            foreach($schemaSheets as $schemaSheet)
            {
                $schemaSheetRows = $schemaSheet->getHighestRow();
                if($schemaSheetRows > 1) $this->phpExcel->addSheet($schemaSheet, $schemaSheetIndex);
            }
        }

        /* urlencode the filename for ie. */
        $fileName = $excelData->fileName;
        if(strpos($this->server->http_user_agent, 'MSIE') !== false || strpos($this->server->http_user_agent, 'Trident') !== false) $fileName = urlencode($fileName);

        $writer      = $fileType == 'xls' ? 'Excel5' : 'Excel2007';
        $excelWriter = PHPExcel_IOFactory::createWriter($this->phpExcel, $writer);
        $excelWriter->setPreCalculateFormulas(false);
        if($savePath == '')
        {
            /* Clean the ob content to make sure no space or utf-8 bom output. */
            $obLevel = ob_get_level();
            for($i = 0; $i < $obLevel; $i++) ob_end_clean();

            setcookie('downloading', 1);
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename=\"{$fileName}.{$fileType}\"");
            header('Cache-Control: max-age=0');

            $excelWriter->save('php://output');
        }
        else
        {
            $excelWriter->save($savePath);
        }
        exit;
    }

    /**
     * Set excel filed name.
     *
     * @param  int    $colIndex
     * @param  string $letter
     * @access public
     * @return string
     */
    public function setExcelField($colIndex, $letter = 'A')
    {
        for($i = 1; $i <= $colIndex; $i++) $letter++;
        return $letter;
    }

    /**
     * Write SysData sheet in xls.
     *
     * @access public
     * @return void
     */
    public function writeSysData()
    {
        if(!isset($this->rawExcelData->sysDataList)) return true;

        $this->hasSysData = true;

        $sheetIndex = $this->phpExcel->getSheetCount() - 1;
        $this->phpExcel->getSheet($sheetIndex)->setTitle($this->lang->excel->title->sysValue);

        foreach($this->rawExcelData->sysDataList as $field)
        {
            $column  = $this->setExcelField($this->sysDataColIndex);
            $dataKey = $field . 'List';
            if(!isset($this->rawExcelData->$dataKey)) continue;

            $row = 1;
            foreach($this->rawExcelData->$dataKey as $value)
            {
                $this->phpExcel->getSheet($sheetIndex)->setCellValueExplicit("$column$row", $value, PHPExcel_Cell_DataType::TYPE_STRING);
                $row++;
            }
            $this->sysDataColIndex++;
        }
    }

    /**
     * Build dropmenu list.
     * For a tip , if you want to modify that function , search "phpExcel DataValidation namedRange" in stackoverflow.com maybe helpful.
     *
     * @param  int    $excelSheet
     * @param  int    $field
     * @param  int    $row
     * @access public
     * @return void
     */
    public function buildList($excelSheet, $field, $row)
    {
        $listName = $field . 'List';
        $colIndex = array_search($field, $this->rawExcelData->sysDataList);
        $column   = $this->setExcelField($colIndex);
        if(isset($this->rawExcelData->$listName))
        {
            $itemCount = count($this->rawExcelData->$listName);
            if($itemCount == 0) $itemCount = 1;
            $range = "{$this->lang->excel->title->sysValue}!\${$column}\$1:\${$column}\$" . $itemCount;
        }
        else
        {
            $range = is_array($this->rawExcelData->$listName) ? '' : '"' . $this->rawExcelData->$listName . '"';
        }
        $objValidation = $excelSheet->getCell($this->excelKey[$field] . $row)->getDataValidation();
        $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
            ->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
            ->setAllowBlank(false)
            ->setShowErrorMessage(false)
            ->setShowDropDown(true)
            ->setErrorTitle($this->lang->excel->error->title)
            ->setError($this->lang->excel->error->info)
            ->setFormula1($range);
    }

    /**
     * Set excel style.
     *
     * @param  object $excelSheet
     * @param  int    $endRow
     * @access public
     * @return void
     */
    public function setStyle($excelSheet, $endRow)
    {
        $startRow  = $this->headerRowCount + 1;
        $endColumn = $this->setExcelField(count($this->excelKey) - 1);

        if(isset($this->rawExcelData->help) and isset($this->rawExcelData->extraNum)) $endRow--;

        /* Freeze column.*/
        if(isset($this->config->excel->freeze->{$this->rawExcelData->kind}))
        {
            $column = $this->excelKey[$this->config->excel->freeze->{$this->rawExcelData->kind}];
            $column++;
            $excelSheet->FreezePane($column . $startRow);
        }

        /* Set row height. */
        $excelSheet->getRowDimension(1)->setRowHeight(20);

        /* Set header style for this table.*/
        $headerStyle = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('rgb' => '808080')
                )
            ),
            'font' => array(
                'bold'  => true,
                'color' => array('rgb' => 'ffffff')
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'fill' => array(
                 'type'       => PHPExcel_Style_Fill::FILL_SOLID,
                 'startcolor' => array('rgb' => '343399')
            )
        );
        if(isset($this->rawExcelData->nocolor))
        {
            $headerStyle['font']['color']['rgb']      = '000000';
            $headerStyle['fill']['startcolor']['rgb'] = 'ffffff';
        }
        $this->setAreaStyle($excelSheet, $headerStyle, 'A1:' . $endColumn . $this->headerRowCount);

        /* Set content style for this table.*/
        $contentStyle = array(
            'font'    => array(
                'size' => 9
            ),
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap'     => true
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('rgb' => '808080')
                )
            )
        );
        $this->setAreaStyle($excelSheet, $contentStyle, 'A' . $startRow . ':' . $endColumn . $endRow);

        /* Set column width and style. */
        $customWidth = isset($this->rawExcelData->customWidth) ? array_keys($this->rawExcelData->customWidth) : array();
        foreach($this->excelKey as $key => $letter)
        {
            $titleWidth = !empty($this->config->excel->width->title)   ? $this->config->excel->width->title   : '25';
            $contWidth  = !empty($this->config->excel->width->content) ? $this->config->excel->width->content : '50';

            /* Set width of date fields. */
            if(strpos($key, 'Date') !== false) $excelSheet->getColumnDimension($letter)->setWidth(12);

            /* Set width of title fields. */
            if(in_array($key, $this->config->excel->titleFields)) $excelSheet->getColumnDimension($letter)->setWidth($titleWidth);

            /* Set width of editor fields. */
            if(isset($this->config->excel->editor[$this->rawExcelData->kind]) and in_array($key, $this->config->excel->editor[$this->rawExcelData->kind]))
            {
                $excelSheet->getColumnDimension($letter)->setWidth($contWidth);
            }

            /* Set cell format of center fields. */
            if(in_array($key, $this->config->excel->centerFields))
            {
                $centerStyle = array(
                    'font'    => array(
                        'size' => 9
                    ),
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('rgb' => '808080')
                        )
                    )
                );
                $this->setAreaStyle($excelSheet, $centerStyle, $letter . $startRow . ':' . $letter . $endRow);
            }

            /* Set cell format of date fields. */
            if(strpos($key, 'Date') !== false or in_array($key, $this->config->excel->dateFields))
            {
                $dateFormat = array(
                    'font'    => array(
                        'size' => 9
                    ),
                    'alignment' => array(
                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                        'wrap'     => true
                    ),
                    'numberformat' => array(
                        'code' => PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('rgb' => '808080')
                        )
                    )
                );
                $this->setAreaStyle($excelSheet, $dateFormat, $letter . $startRow . ':' . $letter . $endRow);
            }

            /* Set column width by userdefined settings. */
            if(in_array($key, $customWidth)) $excelSheet->getColumnDimension($letter)->setWidth($this->rawExcelData->customWidth[$key]);
        }

        /* Set row color. */
        if(isset($this->rawExcelData->colors))
        {
            foreach($this->rawExcelData->colors as $row => $color)
            {
                $beginColumn = $this->excelKey[$color->begin];
                $endColumn   = $this->excelKey[$color->end];
                $excelSheet->getStyle("$beginColumn$row:$endColumn$row")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $excelSheet->getStyle("$beginColumn$row:$endColumn$row")->getFill()->getStartColor()->setRGB($color->color);
            }
        }
        elseif(!isset($this->rawExcelData->nocolor))
        {
            /* Set interlaced color for this table. */
            for($row = $startRow; $row <= $endRow; $row++)
            {
                $excelSheet->getRowDimension($row)->setRowHeight(20);

                $area  = "A$row:$endColumn$row";
                $color = $row % 2 == 0 ? 'FFB2D7EA' : 'FFdee6fb';
                $excelStyle = $excelSheet->getStyle($area);
                $excelFill  = $excelStyle->getFill();
                $excelFill->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $excelFill->getStartColor()->setARGB($color);
            }
        }
    }

    /**
     * Set area style.
     *
     * @param  object $excelSheet
     * @param  array  $style
     * @param  string $area
     * @access public
     * @return void
     */
    public function setAreaStyle($excelSheet, $style, $area)
    {
        $styleObj = new PHPExcel_Style();
        $styleObj->applyFromArray($style);
        $excelSheet->setSharedStyle($styleObj, $area);
    }

    /**
     * Set alignment.
     *
     * @param  object $excelSheet
     * @access public
     * @return bool
     */
    public function setAlignment($excelSheet)
    {
        if(!isset($this->rawExcelData->percentageFields)) return true;

        foreach($this->rawExcelData->percentageFields as $row => $fields)
        {
            foreach($fields as $key => $field)
            {
                if(isset($this->excelKey[$key]))
                {
                    $cell = $this->excelKey[$key] . ($this->headerRowCount + $row + 1);
                    $excelSheet->getStyle($cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                }
            }
        }
    }
}
