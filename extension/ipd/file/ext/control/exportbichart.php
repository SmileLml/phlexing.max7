<?php
helper::importControl('file');
class myfile extends file
{
    public $wordContent   = '';
    public $imgExtensions = array();
    public $relsID        = array();
    public $wrPr          = '<w:rPr><w:rFonts w:hint="eastAsia"/><w:lang w:val="en-US" w:eastAsia="zh-CN"/></w:rPr>';
    public $wpPr          = '';
    /**
     * Init for word
     *
     * @access public
     * @return void
     */
    public function chartInit()
    {
        $this->app->loadClass('pclzip', true);
        $this->wpPr   = "<w:pPr>{$this->wrPr}</w:pPr>";
        $this->zfile  = $this->app->loadClass('zfile');
        $this->datas  = $this->post->datas;
        $this->images = $this->post->images;
        $this->relsID[6] = '';

        /* Init excel file. */
        $this->exportPath = $this->app->getCacheRoot() . $this->app->user->account . $this->post->kind . uniqid() . '/';
        if(is_dir($this->exportPath))$this->zfile->removeDir($this->exportPath);
        $this->zfile->mkdir($this->exportPath);
        $this->zfile->copyDir($this->app->getCoreLibRoot() . 'word', $this->exportPath);

        // post content: kind(string), items(array), datas(array), items(array), fileName(string).
        $this->kind = $this->post->kind;
        $this->host = common::getSysURL();
    }

    /**
     * Export BI Chart
     *
     * @access public
     * @return void
     */
    public function exportBIChart()
    {
        $this->chartInit();
        $headerName = $this->post->fileName;
        $this->setChartDocProps($headerName);

        if($headerName)
        {
            $this->addChartTitle($headerName, 2, 'center');
            $this->wordContent .= '<w:p><w:pPr><w:jc w:val="center"/>' . $this->wrPr . '</w:pPr></w:p>';
        }

        $order = 1;
        foreach($this->post->items as $item)
        {
            $title = $this->post->datas[$item]['title']['text'];
            $this->addChartTitle($title, 3);
            if(!empty($this->images[$item])) $this->addChartImage($this->images[$item], $item);
            if(!empty($this->datas[$item])) $this->addChartTable($this->datas[$item], $item);
            $this->addChartTextBreak(2);
        }

        $defaultImage   = '';
        $contentTypeXML = file_get_contents($this->exportPath . '[Content_Types].xml');
        foreach($this->imgExtensions as $imgExtension => $val) $defaultImage .= '<Default ContentType="image/' . $imgExtension . '" Extension="' . $imgExtension . '"/>';
        $contentTypeXML = str_replace('%defaultimage%', $defaultImage, $contentTypeXML);
        file_put_contents($this->exportPath . '[Content_Types].xml', $contentTypeXML);

        $imageRels    = '';
        $documentRels = file_get_contents($this->exportPath . 'word/_rels/document.xml.rels');
        foreach($this->relsID as $i => $extension)
        {
            if($extension) $imageRels .= '<Relationship Id="rId' . $i . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/image' . $i . '.' . $extension . '"/>';
        }
        $documentRels = str_replace('%image%', $imageRels, $documentRels);
        file_put_contents($this->exportPath . 'word/_rels/document.xml.rels', $documentRels);

        $documentXML = file_get_contents($this->exportPath . 'word/document.xml');
        $documentXML = str_replace('%wordContent%', $this->wordContent, $documentXML);
        $documentXML = str_replace('<w:pgMar w:top="1440" w:right="1800" w:bottom="1440" w:left="1800" w:header="851" w:footer="992" w:gutter="0"/>',
            '<w:pgMar w:top="720" w:right="720" w:bottom="720" w:left="720" w:header="851" w:footer="992" w:gutter="0"/>', $documentXML);
        file_put_contents($this->exportPath . 'word/document.xml', $documentXML);

        /* Zip to xlsx. */
        $fileName = uniqid() . '.docx';
        helper::cd($this->exportPath);
        $files = array('[Content_Types].xml', '_rels', 'docProps', 'word', 'customXml');
        $zip   = new pclzip($fileName);
        $zip->create($files);

        $fileData = file_get_contents($this->exportPath . $fileName);
        $this->zfile->removeDir($this->exportPath);
        $this->sendDownHeader($this->post->fileName . '.docx', 'docx', $fileData);
        exit;
    }

    /**
     * Add title
     *
     * @param  sting    $text
     * @param  int    $grade
     * @param  string $align
     * @access public
     * @return void
     */
    public function addChartTitle($text, $grade = 1, $align = 'left')
    {
        $this->wordContent .= "<w:p><w:pPr><w:pStyle w:val='$grade'/>";
        if($align != 'left') $this->wordContent .= "<w:jc w:val='$align'/>";
        $this->wordContent .= $this->wrPr . '</w:pPr>';
        $this->addChartText($text, array(), true);
        $this->wordContent .= '</w:p>';
    }

    public function addChartTable($tableData, $item, $style = 'block')
    {
        $columnCount = isset($tableData['columns']) ? count($tableData['columns']) : 0;
        if($columnCount == 0) return;
        $allWidth    = 6000;
        $width       = $allWidth / $columnCount;
        $tblBorders  = '<w:tblBorders><w:top w:space="0" w:sz="4" w:color="auto" w:val="single"/><w:left w:space="0" w:sz="4" w:color="auto" w:val="single"/><w:bottom w:space="0" w:sz="4" w:color="auto" w:val="single"/><w:right w:space="0" w:sz="4" w:color="auto" w:val="single"/><w:insideH w:space="0" w:sz="4" w:color="auto" w:val="single"/><w:insideV w:space="0" w:sz="4" w:color="auto" w:val="single"/></w:tblBorders>';
        $tblLayout   = '<w:tblLayout w:type="fixed"/>';
        $tblCellMar  = '<w:tblCellMar><w:left w:w="108" w:type="dxa"/><w:right w:w="108" w:type="dxa"/></w:tblCellMar>';
        $floatStyle  = '<w:tblpPr w:tblpY="100" w:tblpX="8000" w:horzAnchor="page" w:vertAnchor="text" w:rightFromText="180" w:leftFromText="180"/><w:tblOverlap w:val="never"/>';
        $document    = '<w:tbl>';
        $document   .= '<w:tblPr>
            <w:tblStyle w:val="9"/>' . ($style == 'float' ? $floatStyle : '') . '
            <w:tblW w:w="' . $allWidth . '" w:type="dxa"/><w:jc w:val="center"/>' . $tblBorders . $tblLayout . $tblCellMar . '</w:tblPr>
            <w:tblGrid><w:gridCol w:w="' . $width . '"/><w:gridCol w:w="' . $width . '"/><w:gridCol w:w="' . $width . '"/></w:tblGrid>';

        $document .= '<w:tr><w:tblPrEx>' . $tblBorders . $tblLayout . $tblCellMar . '</w:tblPrEx>
            <w:trPr><w:trHeight w:val="287" w:hRule="atLeast"/></w:trPr>';
        foreach($tableData['columns'] as $column)
        {
            $document .= '<w:tc><w:tcPr><w:tcW w:w="' . $width . '" w:type="dxa"/></w:tcPr>
            <w:p>' . $this->wpPr . '<w:r>' . $this->wrPr . '<w:t>' . $column['title'] . '</w:t></w:r></w:p></w:tc>';
        }
        $document .= '</w:tr>';

        foreach($tableData['source'] as $source)
        {
            $document .= '<w:tr><w:tblPrEx>' . $tblBorders . $tblLayout . $tblCellMar . '</w:tblPrEx>
                <w:trPr><w:trHeight w:val="287" w:hRule="atLeast"/></w:trPr>';
            foreach($source as $value)
            {
                $document .= '<w:tc><w:tcPr><w:tcW w:w="' . $width . '" w:type="dxa"/></w:tcPr>';
                $document .= '<w:p>' . $this->wpPr . '<w:r>' . $this->wrPr . '<w:t>' . $value . '</w:t></w:r></w:p>';
                $document .= '</w:tc>';
            }
            $document .= '</w:tr>';
        }
        $document .= '</w:tbl>';

        $this->wordContent .= $document;
    }

    public function addChartText($text, $styles = array(), $inline = false)
    {
        $out = array();
        preg_match_all("/<span .*style=([\"|\'])(.*)\\1>(.*)<\/span>/U", $text, $out);
        $noTags = preg_split("/<span .*style=([\"|\'])(.*)\\1>(.*)<\/span>/U", $text);
        if($out[2])
        {
            foreach($out[2] as $i => $styles)
            {
                $styles = explode(';', $styles);
                unset($out[2][$i]);
                foreach($styles as $style)
                {
                    if(empty($style)) continue;
                    if(strpos($style, ':') === false) continue;
                    list($key, $value) = explode(':', $style);

                    $out[2][$i][$key] = $value;
                }
            }

            foreach($noTags as $i => $content)
            {
                if($content)$this->addChartText($content, array(), true);

                if(!isset($out[3][$i])) continue;
                $content = trim($out[3][$i]);
                if(empty($content)) continue;
                $this->addChartText($content, $out[2][$i], true);
            }
            return false;
        }
        $text = trim(strip_tags($text));
        if(empty($text)) return false;
        $document = '';
        if(!$inline) $document .= '<w:p>' . $this->wpPr;
        $document .= '<w:r>';
        $document .= $this->transformChartStyle($styles);
        $document .= "<w:t><![CDATA[$text]]></w:t>";
        $document .= '</w:r>';
        if(!$inline) $document .= '</w:p>';
        $this->wordContent .= $document;
    }

    /**
     * Add image
     *
     * @param  int    $path
     * @param  int    $inline
     * @access public
     * @return void
     */
    public function addChartImage($imageData, $item)
    {
        $extension = 'png';
        $this->imgExtensions[$extension] = true;
        $this->relsID[] = $extension;

        $mediaPath = $this->exportPath . 'word/media/';
        if(!file_exists($mediaPath)) mkdir($mediaPath, 0777, true);

        end($this->relsID);
        $relsID = key($this->relsID);
        $path   = $this->exportPath . 'word/media/image' . $relsID . '.png';
        file_put_contents($path, base64_decode(substr($imageData, 22)));
        list($width, $height, $type) = getimagesize($path);
        $showWidth = min($width, 560);
        if($showWidth != $width)
        {
            $showHeight = floor($height * $showWidth / $width);
        } else $showHeight = $height;
        /* Remove 20mm width in word. */
        $cutImage = true;
        if(isset($this->lang->{$this->kind}->report->$item->type) and $this->lang->{$this->kind}->report->$item->type == 'bar') $cutImage = false;

        $document  = '';
        $document .= '<w:p><w:pPr><w:jc w:val="center"/></w:pPr>';
        $document .= '<w:r><w:rPr><w:rFonts w:hint="eastAsia" /><w:kern w:val="2"/><w:sz w:val="21"/><w:lang w:val="en-US" w:eastAsia="zh-CN"/></w:rPr>';
        $document .= '<w:pict><v:shape id="pict' . $relsID . '" o:spid="_x0000_s1026" type="#_x0000_t75" style="height:' . $showHeight . 'px;width:' . $showWidth . 'px;rotation:0f;" o:ole="f" fillcolor="#FFFFFF" filled="f" o:preferrelative="t" stroked="f" coordorigin="0,0" coordsize="21600,21600"><v:fill on="f" color2="#FFFFFF" focus="0%"/>';
        $document .= '<v:imagedata gain="65536f" blacklevel="0f" gamma="0" o:title="5" r:id="rId' . $relsID . '"/>';
        $document .= '<o:lock v:ext="edit" position="f" selection="f" grouping="f" rotation="f" cropping="f" text="f" aspectratio="t"/><w10:wrap type="none"/><w10:anchorlock/></v:shape></w:pict></w:r>';
        $document .= '</w:p>';

        $this->wordContent .= $document;
    }

    public function addChartLink($text, $href, $styles = array(), $inline = false)
    {
        $document = '';
        $href     = preg_replace('/&(quot|#34);/i', '&', $href);
        if(!$inline) $document .= '<w:p>' . $this->wpPr;
        $document .= '<w:r>' . $this->wrPr . '<w:fldChar w:fldCharType="begin"/></w:r>';
        $document .= '<w:r>' . $this->wrPr . '<w:instrText xml:space="preserve">HYPERLINK "<![CDATA[' . $href . ']]>"</w:instrText></w:r>';
        $document .= '<w:r>' . $this->wrPr . '<w:fldChar w:fldCharType="separate"/></w:r>';
        $document .= '<w:r>';
        $document .= $this->transformChartStyle($styles);
        $document .= "<w:t><![CDATA[$text]]></w:t>";
        $document .= '</w:r><w:r>' . $this->wrPr . '<w:fldChar w:fldCharType="end"/></w:r>';
        if(!$inline) $document .= '</w:p>';
        $this->wordContent .= $document;
    }

    /**
     * Add text break
     *
     * @param  int    $num
     * @access public
     * @return void
     */
    public function addChartTextBreak($num = 1)
    {
        $document = '';
        for($i = 0; $i < $num; $i++) $document .= '<w:p>' . $this->wpPr . '</w:p>';
        $this->wordContent .= $document;
    }

    /**
     * Transform style
     *
     * @param  array  $styles
     * @access public
     * @return void
     */
    public function transformChartStyle($styles = array())
    {

        $wordStyle  = '<w:rPr>';
        if(isset($styles['font-family'])) $styles['font-family'] = str_replace(array('&', ';'), '', $styles['font-family']);
        $wordStyle .= isset($styles['font-family']) ? '<w:rFonts w:hint="eastAsia" w:ascii="' . $styles['font-family'] . '" w:hAnsi="' . $styles['font-family'] . '" w:eastAsia="' . $styles['font-family'] . '" w:cs="' . $styles['font-family'] . '"/>' : '<w:rFonts w:hint="eastAsia"/>';
        foreach($styles as $key => $value)
        {
            switch($key)
            {
            case 'font-style':
                $wordStyle .= '<w:i/><w:iCs/>';
                break;
            case 'font-weight':
                $wordStyle .= '<w:b/><w:bCs/>';
                break;
            case 'text-decoration':
                $wordStyle .= '<w:u w:val="single" w:color="auto"/>';
                break;
            case 'color':
                $wordStyle .= '<w:color w:val="' . substr($value, 1) .'"/>';
                break;
            case 'font-size':
                preg_match('/\d+(\.\d+)?/', $value, $out);
                $value = $out[0] * 2;
                $wordStyle .= '<w:sz w:val="' . $value . '"/><w:szCs w:val="' . $value . '"/>';
                break;
            }
        }
        $wordStyle .= '<w:lang w:val="en-US" w:eastAsia="zh-CN"/></w:rPr>';
        return $wordStyle;
    }

    /**
     * Set doc props
     *
     * @access public
     * @return void
     */
    public function setChartDocProps($header)
    {
        $title      = $header ? $header : $this->post->kind;
        $coreFile   = file_get_contents($this->exportPath . 'docProps/core.xml');
        $createDate = date('Y-m-d') . 'T' . date('H:i:s') . 'Z';
        $account    = $this->app->user->account;
        $coreFile   = sprintf($coreFile, $createDate, $account, $account, $createDate, $title);
        file_put_contents($this->exportPath . 'docProps/core.xml', $coreFile);
    }
}
