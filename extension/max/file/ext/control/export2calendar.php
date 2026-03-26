<?php
helper::importControl('file');
class myfile extends file
{
    public $wordContent   = '';
    public $imgExtensions = array();
    public $relsID        = array();
    /**
     * Init for word
     *
     * @access public
     * @return void
     */
    public function docxInit()
    {
        $this->loadModel('execution');
        $this->taskGroup = $this->execution->getTasks4Calendar($this->post->executionID);
        $this->execution = $this->execution->getById($this->post->executionID);

        $this->app->loadClass('pclzip', true);
        $this->zfile     = $this->app->loadClass('zfile');
        $this->relsID[6] = '';

        /* Init excel file. */
        $this->exportPath = $this->app->getCacheRoot() . $this->app->user->account . uniqid() . '/';
        if(is_dir($this->exportPath))$this->zfile->removeDir($this->exportPath);
        $this->zfile->mkdir($this->exportPath);
        $this->zfile->copyDir($this->app->getCoreLibRoot() . 'word', $this->exportPath);

        $this->host = common::getSysURL();
    }

    /**
     * Export to Word
     *
     * @access public
     * @return void
     */
    public function export2Calendar()
    {
        $this->docxInit();
        $this->setDocxDocProps($this->execution->name);

        $this->addTitle($this->execution->name, 11, 'center');
        $this->wordContent .= '<w:p><w:pPr><w:spacing w:after="0"/><w:jc w:val="center"/><w:rPr><w:rFonts w:hint="eastAsia"/><w:lang w:val="en-US" w:eastAsia="zh-CN"/></w:rPr></w:pPr>';
        $this->addText('(' .$this->lang->word->headNotice . (common::checkNotCN() ? ' ' . $this->lang->word->visitZentao : ''), array('color' => '3F3F3F'), true);
        if(!common::checkNotCN()) $this->addLink($this->lang->word->visitZentao, $this->host, array('color'=>'0000FF'), true);
        $this->addText(')', array('color' => '3F3F3F'), true);
        $this->wordContent .= '</w:p>';

        $order = 0;
        $this->createWord();

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

        $sectPr = $this->post->direction == 'abeam' ? $this->config->word->sectPrAbeam : $this->config->word->sectProVertical;
        $documentXML = str_replace(array('%wordContent%', '%sectpr%'), array($this->wordContent, $sectPr), $documentXML);
        file_put_contents($this->exportPath . 'word/document.xml', $documentXML);

        setcookie('downloading', 1);

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
     * Create Word
     *
     * @param  int|array    $module
     * @param  int    $step
     * @param  int    $order
     * @access public
     * @return void
     */
    public function createWord()
    {
        $users = $this->loadModel('user')->getPairs('noletter');
        foreach($this->taskGroup as $month => $tasks)
        {
            $this->wordContent .= '<w:p><w:pPr><w:pStyle w:val="3"/><w:ind w:leftChars="-709" w:left="-1560"/></w:pPr><w:r><w:t>' . $month . '</w:t></w:r></w:p>';

            $timestamp = strtotime($month) + 3600;
            $monthDays = date('t', $timestamp);
            $endStamp  = $timestamp + $monthDays * 24 * 3600;

            $this->wordContent .= '<w:tbl>';
            $this->wordContent .= '<w:tblPr><w:tblW w:w="0" w:type="auto"/><w:tblInd w:w="-1690" w:type="dxa"/><w:tblBorders><w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:insideH w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:insideV w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tblBorders><w:tblLayout w:type="fixed"/><w:tblLook w:val="04A0"/></w:tblPr><w:tblGrid><w:gridCol w:w="1660"/><w:gridCol w:w="1660"/><w:gridCol w:w="1660"/><w:gridCol w:w="1660"/><w:gridCol w:w="1660"/><w:gridCol w:w="1660"/><w:gridCol w:w="1660"/></w:tblGrid>';
            $this->wordContent .= '<w:tr>';
            for($i = 0; $i <= 6; $i++)
            {
                $week = ($i + 1) % 7;
                $this->addTd($this->lang->datepicker->dayNames[$week]);
            }
            $this->wordContent .= '</w:tr>';

            $offsetDay = 0;
            while($offsetDay < $monthDays)
            {
                $this->wordContent .= '<w:tr>';
                for($i = 0; $i <= 6; $i++)
                {
                    $week         = ($i + 1) % 7;
                    $content      = '';
                    $currentStamp = $timestamp + $offsetDay * 24 * 3600;
                    if($week == date('w', $currentStamp) and $currentStamp < $endStamp)
                    {
                        $content = date('j', $currentStamp);
                        $date    = date('Y-m-d', $currentStamp);
                        if(isset($tasks[$date]))
                        {
                            foreach($tasks[$date] as $task)
                            {
                                if($task['status'] == 'wait') continue;

                                $taskActor = $taskAction = '';
                                if(isset($task['action']) and isset($task['actor']))
                                {
                                    $taskAction = $this->lang->execution->{$task['action']};
                                    $taskActor  = zget($users, $task['actor']);
                                }

                                $content .= "<br /><a href='{$this->host}{$task['url']}'>{$taskActor}{$taskAction}{$task['title']}</a>";
                            }
                        }
                        $offsetDay ++;
                    }
                    $this->addTd($content);
                }
                $this->wordContent .= '</w:tr>';
            }

            $this->wordContent .= '</w:tbl>';
            $this->wordContent .= $this->addText();
        }
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
    public function addTitle($text, $grade = 1, $align = 'left')
    {
        $this->wordContent .= "<w:p><w:pPr><w:pStyle w:val='$grade'/>";
        if($align != 'left') $this->wordContent .= "<w:jc w:val='$align'/>";
        $this->wordContent .= "<w:rPr><w:rFonts w:hint='eastAsia'/><w:lang w:val='en-US' w:eastAsia='zh-CN'/></w:rPr></w:pPr>";
        $this->pauseHtmlTag($text, true);
        $this->wordContent .= '</w:p>';
    }

    public function addTd($content, $width = '7421')
    {
        $this->wordContent .= '<w:tc><w:tcPr><w:vAlign w:val="left"/></w:tcPr>';
        $this->pauseHtmlTag($content);
        $this->wordContent .= '</w:tc>';
    }

    /**
     * Add text
     *
     * @param  string    $text
     * @param  array  $styles
     * @param  bool    $inline
     * @access public
     * @return void
     */
    public function addText($text = '', $styles = array(), $inline = false)
    {
        $text = trim(strip_tags($text));
        $text = preg_replace('/[\x00-\x09\x0B-\x1F\x7F-\x9F]/u', '', $text);
        $document = '';
        if(!$inline) $document .= '<w:p><w:pPr><w:spacing w:after="0"/><w:rPr><w:rFonts w:hint="eastAsia"/><w:lang w:val="en-US" w:eastAsia="zh-CN"/></w:rPr></w:pPr>';
        $document .= '<w:r>';
        $document .= "<w:t><![CDATA[$text]]></w:t>";
        $document .= '</w:r>';
        if(!$inline) $document .= '</w:p>';
        $this->wordContent .= $document;
    }
    /**
     * Add link
     *
     * @param  string    $text
     * @param  string    $href
     * @param  array     $styles
     * @param  bool      $inline
     * @access public
     * @return void
     */
    public function addLink($text, $href, $styles = array(), $inline = false)
    {
        $document = '';
        $text     = preg_replace('/[\x00-\x09\x0B-\x1F\x7F-\x9F]/u', '', $text);
        $href     = preg_replace('/&(quot|#34);/i', '&', $href);
        $href     = preg_replace('/[\x00-\x09\x0B-\x1F\x7F-\x9F]/u', '', $href);
        if(!$inline) $document .= '<w:p><w:pPr><w:spacing w:after="0"/><w:rPr><w:rFonts w:hint="eastAsia"/><w:lang w:val="en-US" w:eastAsia="zh-CN"/></w:rPr></w:pPr>';
        $document .= '<w:r><w:rPr><w:rFonts w:hint="eastAsia"/><w:lang w:val="en-US" w:eastAsia="zh-CN"/></w:rPr><w:fldChar w:fldCharType="begin"/></w:r>';
        $document .= '<w:r><w:rPr><w:rFonts w:hint="eastAsia"/><w:lang w:val="en-US" w:eastAsia="zh-CN"/></w:rPr><w:instrText xml:space="preserve">HYPERLINK "<![CDATA[' . $href . ']]>"</w:instrText></w:r>';
        $document .= '<w:r><w:rPr><w:rFonts w:hint="eastAsia"/><w:lang w:val="en-US" w:eastAsia="zh-CN"/></w:rPr><w:fldChar w:fldCharType="separate"/></w:r>';
        $document .= '<w:r>';
        $document .= "<w:t><![CDATA[$text]]></w:t>";
        $document .= '</w:r><w:r><w:rPr><w:rFonts w:hint="eastAsia"/><w:lang w:val="en-US" w:eastAsia="zh-CN"/></w:rPr><w:fldChar w:fldCharType="end"/></w:r>';
        if(!$inline) $document .= '</w:p>';
        $this->wordContent .= $document;
    }

    /**
     * Pause html tag
     *
     * @param  string    $text
     * @access public
     * @return void
     */
    public function pauseHtmlTag($text, $inline = false)
    {
        /* Fix like aaa\n <ul></ul> then add <br /> to new line. */
        $text = preg_replace('/([^>\s]\s*)(<p|<ul|<ol)/i', "\\1<br />\\2",$text);

        $out = array();
        preg_match_all('/(<br ?(\/?)>|<\/p>|<\/li>)/U', $text, $out);
        $splitByBR = preg_split("/<br ?(\/?)>|<\/p>|<\/li>/U", $text);
        if($out[0])
        {
            foreach($splitByBR as $i => $content)
            {
                if($content) $this->pauseHtmlTag($content, $inline);
            }
            return false;
        }

        $out = array();
        preg_match_all("/<a .*href=([\"|\'])(.*)\\1.*>(.*)<\/a>/U", $text, $out);
        $splitByA = preg_split("/<a .*href=([\"|\'])(.*)\\1.*>(.*)<\/a>/U", $text);
        if($out[0])
        {
            foreach($splitByA as $i => $content)
            {
                if($content) $this->pauseHtmlTag($content);

                if(!isset($out[3][$i])) continue;
                $content = trim($out[3][$i]);
                if(empty($content)) continue;
                $this->addLink($out[3][$i], $out[2][$i], array('color'=>'0000FF'), $inline);
            }
            return false;
        }

        $this->addText($text, array(), $inline);
    }

    /**
     * Set doc props
     *
     * @access public
     * @return void
     */
    public function setDocxDocProps()
    {
        $title      = $this->post->fileName;
        $coreFile   = file_get_contents($this->exportPath . 'docProps/core.xml');
        $createDate = date('Y-m-d') . 'T' . date('H:i:s') . 'Z';
        $account    = $this->app->user->account;
        $coreFile   = sprintf($coreFile, $createDate, $account, $account, $createDate, $title);
        file_put_contents($this->exportPath . 'docProps/core.xml', $coreFile);
    }
}
