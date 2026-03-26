<?php
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
class excelFile extends fileModel
{
    /**
     * ExcludeHtml.
     *
     * @param  int    $content
     * @param  string $extra
     * @access public
     * @return string
     */
    public function excludeHtml($content, $extra = '')
    {
        if($extra != 'keepNL') $content = str_replace("\n", '', $content);
        $content = str_replace(array('<i>', '&nbsp;', '<br />', '<br/>', '</p>', '</li>'), array('', ' ', "\n", "\n", "\n", "\n"), $content);
        $content = preg_replace('/<[^ia\/]+(.*)>/U', '', $content);
        $content = preg_replace('/<\/[^a]{1}.*>/U', '', $content);
        $content = preg_replace('/<i .*>/U', '', $content);
        if($extra != 'noImg') $content = preg_replace('/<img src="data\/"(.*)\/>/U', "<img src=\"" . common::getSysURL() . "data/\"\$1/>", $content);
        return $content;
    }

    /**
     * Set area style.
     *
     * @param  int    $excelSheet
     * @param  int    $style
     * @param  int    $area
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
     * Get rows from excel.
     *
     * @param  int    $file
     * @access public
     * @return void
     */
    public function getRowsFromExcel($file)
    {
        /* Only parse files in zentao directory. */
        if(strpos($file, $this->app->getBasePath()) !== 0) return array();
        $extension = $this->session->fileImportExtension ? $this->session->fileImportExtension : 'xlsx';

        if($extension == 'xlsx' and version_compare(substr(PHP_VERSION, 0, 3), '7.2') >= 0)
        {
            try
            {
                $this->app->loadClass('spout', true);

                $reader = ReaderFactory::create(Type::XLSX);

                $reader->open($file);
                $iterator = $reader->getSheetIterator();
                $iterator->rewind();

                $sheet   = $iterator->current();
                $rowIter = $sheet->getRowIterator();

                $rows = array();
                foreach($rowIter as $rowIndex => $row)
                {
                    $rowIndex --;
                    if($rowIndex == 0) continue; // 忽略第一行的tips
                    $cols = array();
                    foreach($row as $col)
                    {
                        if($col instanceof DateTime)
                        {
                            $col = $col->format('Y-m-d H:i:s');
                            if(strpos($col, '00:00:00') === 11) $col = substr($col, 0, 10);
                        }
                        $cols[] = $col;
                    }
                    $rows[$rowIndex] = $cols;
                }
            }
            catch(\Box\Spout\Common\Exception\IOException $IOException)
            {
                return $this->lang->excel->error->canNotRead;
            }
        }
        else
        {
            $phpExcel  = $this->app->loadClass('phpexcel');
            $phpReader = new PHPExcel_Reader_Excel2007();
            if(!$phpReader->canRead($file)) $phpReader = new PHPExcel_Reader_Excel5();

            $phpExcel = $phpReader->load($file);
            $sheet    = $phpExcel->getSheet(0);
            $rows     = array();
            $rowIndex = 0;
            foreach($sheet->getRowIterator() as $row)
            {
                $cellIterator = $row->getCellIterator();

                $cols = array();
                foreach($cellIterator as $cell)
                {
                    if(is_null($cell)) continue;
                    $value = $cell->getValue();

                    if($cell->getDataType() == PHPExcel_Cell_DataType::TYPE_NUMERIC)
                    {
                        $cellstyleformat = $cell->getStyle($cell->getCoordinate())->getNumberFormat();

                        $formatcode = $cellstyleformat->getFormatCode();

                        if(preg_match('/^(\[\$[A-Z]*-[0-9A-F]*\])*[hmsdy]/i', $formatcode))
                        {
                            $value = gmdate("Y-m-d H:i:s", PHPExcel_Shared_Date::ExcelToPHP($value));
                            if(strpos($value, '00:00:00') === 11) $value = substr($value, 0, 10);
                        }
                        else
                        {
                            $value = PHPExcel_Style_NumberFormat::toFormattedString($value, $formatcode);
                        }
                    }

                    /* Fix the missing import information of php7.2 version. */
                    if($value instanceof PHPExcel_RichText)
                    {
                        $value = $value->__toString();
                    }

                    $cols[] = $value;
                }

                if($rowIndex > 0) // 忽略第一行的tips
                {
                    $rows[$rowIndex] = $cols;
                }

                $rowIndex ++;
            }
        }

        return $rows;
    }

    /**
     * Insert image to xlsx.
     *
     * @param array   $imageList
     * @param string  $path
     * @access public
     * @return void
     */
    public function insertImgToXlsx($imageList = array(), $path = '')
    {
        $this->setContentTypesXML($imageList, $path);
        $this->setSheet1XMLRels($path);
        $this->setSheet1XML($path);
        $this->setDrawing1XMLRels($imageList, $path);
        $this->setDrawing1XML($imageList, $path);
        $this->moveToMedia($imageList, $path);
    }

    /**
     * Get extensions by image list.
     *
     * @param  array  $imageList
     * @access public
     * @return void
     */
    public function getExtensions($imageList = array())
    {
        $extensions = array();
        foreach($imageList as $group)
        {
            foreach($group as $image)
            {
                $extensions[$image['extension']] = $image['extension'];
            }
        }
        return $extensions;
    }

    /**
     * Set ContentTypes.xml content.
     *
     * @param  string $exportPath
     * @access public
     * @return void
     */
    public function setContentTypesXML($imageList, $exportPath)
    {
        $extensions = $this->getExtensions($imageList);
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                <Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">';
        if(in_array('png', $extensions)) $xml .= '<Default Extension="png" ContentType="image/png" />';
        if(in_array('gif', $extensions)) $xml .= '<Default Extension="gif" ContentType="image/gif" />';
        if(in_array('jpg', $extensions)) $xml .= '<Default Extension="jpg" ContentType="image/jpeg" />';
        $xml .= '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml" />
                    <Default Extension="xml" ContentType="application/xml" />
                    <Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml" />
                    <Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml" />
                    <Override PartName="/xl/worksheets/sheet2.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml" />
                    <Override PartName="/xl/theme/theme1.xml" ContentType="application/vnd.openxmlformats-officedocument.theme+xml" />
                    <Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml" />
                    <Override PartName="/xl/sharedStrings.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sharedStrings+xml" />
                    <Override PartName="/xl/drawings/drawing1.xml" ContentType="application/vnd.openxmlformats-officedocument.drawing+xml" />
                    <Override PartName="/docProps/core.xml" ContentType="application/vnd.openxmlformats-package.core-properties+xml" />
                    <Override PartName="/docProps/app.xml" ContentType="application/vnd.openxmlformats-officedocument.extended-properties+xml" />
                </Types>';
        file_put_contents($exportPath . '[Content_Types].xml', $xml);
    }

    /**
     * Insert drawing xml to sheet1.xml.
     *
     * @param  string $exportPath
     * @access public
     * @return void
     */
    public function setSheet1XML($exportPath = '')
    {
        $filePath = $exportPath . 'xl/worksheets/';
        $fileName = 'sheet1.xml';
        $content  = file_get_contents($filePath . $fileName);
        $content  = str_replace('</worksheet>', '<drawing r:id="rId99999999"/></worksheet>', $content);
        file_put_contents($filePath . $fileName , $content);
    }

    /**
     * Create sheet1.xml.rels.
     *
     * @param  string $exportPath
     * @access public
     * @return void
     */
    public function setSheet1XMLRels($exportPath = '')
    {
        $filePath     = $exportPath . 'xl/worksheets/_rels/';
        if(!is_dir($filePath)) mkdir($filePath, 0777 ,true);

        $fileName     = 'sheet1.xml.rels';
        if(file_exists($filePath . $fileName))
        {
            $content      = file_get_contents($filePath . $fileName);
            $relationship = '<Relationship Id="rId99999999" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/drawing" Target="../drawings/drawing1.xml" />';
            $content      = str_replace('</Relationships>', $relationship . '</Relationships>', $content);
        }
        else
        {
            $content = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId99999999" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/drawing" Target="../drawings/drawing1.xml"/></Relationships>';
        }

        file_put_contents($filePath . $fileName , $content);
    }

    /**
     * Create drawing1.xml.
     *
     * @param  array  $imgList
     * @param  int    $path
     * @access public
     * @return void
     */
    public function setDrawing1XML($imgList = array(), $exportPath = '')
    {
        $xml  = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
        $xml .= '<xdr:wsDr xmlns:xdr="http://schemas.openxmlformats.org/drawingml/2006/spreadsheetDrawing" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main">';
        foreach($imgList as $row => $images)
        {
            foreach($images as $key => $image)
            {
                $xml .= '<xdr:twoCellAnchor editAs="oneCell">';
                $xml .= "<xdr:from>
                           <xdr:col>{$image['fromCol']}</xdr:col>
                           <xdr:colOff>{$image['fromColOff']}</xdr:colOff>
                           <xdr:row>{$image['fromRow']}</xdr:row>
                           <xdr:rowOff>{$image['fromRowOff']}</xdr:rowOff>
                         </xdr:from>
                         <xdr:to>
                           <xdr:col>{$image['toCol']}</xdr:col>
                           <xdr:colOff>{$image['toColOff']}</xdr:colOff>
                           <xdr:row>{$image['toRow']}</xdr:row>
                           <xdr:rowOff>{$image['toRowOff']}</xdr:rowOff>
                         </xdr:to>";
                $xml .= '<xdr:pic>
                           <xdr:nvPicPr>
                             <xdr:cNvPr id="' . $image['name'] . '" name="' . $image['name'] . '" />
                             <xdr:cNvPicPr>
                               <a:picLocks noChangeAspect="1"/>
                             </xdr:cNvPicPr>
                           </xdr:nvPicPr>
                           <xdr:blipFill>
                             <a:blip r:embed="rId' . $image['name'] . '"/>
                             <a:stretch>
                               <a:fillRect/>
                             </a:stretch>
                           </xdr:blipFill>
                           <xdr:spPr>
                             <a:xfrm>
                               <a:off x="13458825" y="152401"/>
                               <a:ext cx="1504950" cy="1104154"/>
                             </a:xfrm>
                             <a:prstGeom prst="rect">
                              <a:avLst/>
                             </a:prstGeom>
                           </xdr:spPr>
                         </xdr:pic>';
                $xml .= '<xdr:clientData/>';
                $xml .= '</xdr:twoCellAnchor>';
            }
        }
        $xml .= '</xdr:wsDr>';
        if(!is_dir($exportPath . 'xl/drawings/')) mkdir($exportPath . 'xl/drawings/', 0777, true);
        file_put_contents($exportPath . 'xl/drawings/drawing1.xml', $xml);
    }

    /**
     * Create drawing1.xml.rels.
     *
     * @param  array  $imgList
     * @param  string $exportPath
     * @access public
     * @return void
     */
    public function setDrawing1XMLRels($imgList = array(), $exportPath = '')
    {
        $rels  = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        $rels .= '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' . "\n";

        $imgIDList = array();
        foreach($imgList as $list)
        {
            if(empty($list)) continue;
            foreach($list as $image)
            {
                if(in_array($image['name'], $imgIDList)) continue;
                $imgIDList[] = $image['name'];
                $rels .= '<Relationship Id="rId' . $image['name'] . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="../media/image' . $image['ext'] . '" />' . "\n";
            }
        }
        $rels .= '</Relationships>';

        if(!is_dir($exportPath . 'xl/drawings/_rels/')) mkdir($exportPath . 'xl/drawings/_rels/', 0777, true);
        file_put_contents($exportPath . 'xl/drawings/_rels/drawing1.xml.rels', $rels);
    }

    /**
     * Move file to media and rename filename.
     *
     * @param  array  $imgList
     * @param  string $exportPath
     * @access public
     * @return void
     */
    public function moveToMedia($imgList = array(), $exportPath = '')
    {
        if(!is_dir($exportPath . 'xl/media/')) mkdir($exportPath . 'xl/media/');

        $files = $this->dao->select('*')->from(TABLE_FILE)->orderBy('id')->fetchAll('id', false);
        $zfile = $this->app->loadClass('zfile');

        foreach($imgList as $list)
        {
            if(empty($list)) continue;
            foreach($list as $image)
            {
                if(!isset($files[$image['name']])) continue;

                $imgFile = $files[$image['name']];

                $this->loadModel('file')->setFileWebAndRealPaths($imgFile);

                $realPath = $this->loadModel('file')->saveAsTempFile($imgFile);
                if(file_exists($realPath)) $zfile->copyFile($realPath, $exportPath . 'xl/media/image' . $image['ext']);
            }
        }
    }
}
