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
        $this->app->loadClass('pclzip', true);
        $this->zfile     = $this->app->loadClass('zfile');
        $this->taskTree  = $this->execution->getTree($this->post->executionID);
        $this->execution   = $this->execution->getById($this->post->executionID);
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
    public function export2Tree()
    {
        $this->docxInit();
        $this->setDocxDocProps($this->execution->name);

        $this->addTitle($this->execution->name, 11, 'center');
        $this->wordContent .= '<w:p><w:pPr><w:jc w:val="center"/><w:rPr><w:rFonts w:hint="eastAsia"/><w:lang w:val="en-US" w:eastAsia="zh-CN"/></w:rPr></w:pPr>';
        $this->addText('(' .$this->lang->word->headNotice . (common::checkNotCN() ? ' ' . $this->lang->word->visitZentao : ''), array('color' => '3F3F3F'), true);
        if(!common::checkNotCN()) $this->addLink($this->lang->word->visitZentao, $this->host, array('color'=>'0000FF'), true);
        $this->addText(')', array('color' => '3F3F3F'), true);
        $this->wordContent .= '</w:p>';

        $order = 0;
        $this->createWord($this->taskTree, $order);

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
    public function createWord($taskTree, $order = 1)
    {
        foreach($taskTree as $task)
        {
            $prefix= '';
            if($task->type == 'module') $prefix = "[{$this->lang->task->moduleAB}] ";
            if($task->type == 'product') $prefix = "[{$this->lang->productCommon}] ";
            if($task->type == 'story') $prefix = "[{$this->lang->task->storyAB}] ";
            if($task->type == 'branch')
            {
                $this->app->loadLang('branch');
                $prefix = "[{$this->lang->branch->common}] ";
            }
            if($task->type == 'task')
            {
                $prefix = "[{$this->lang->task->common}] ";
                if($task->parent > 0 && !$task->isParent) $prefix = "[{$this->lang->task->children}] ";
                if($task->isParent) $prefix = "[{$this->lang->task->parent}] ";
            }

            $this->wordContent .= '<w:p w:rsidR="0000463B" w:rsidRDefault="0000463B" w:rsidP="0000463B"><w:pPr><w:numPr><w:ilvl w:val="' . $order . '"/><w:numId w:val="4"/></w:numPr><w:rPr><w:rFonts w:hint="eastAsia"/></w:rPr></w:pPr>';
            if($task->type == 'task' or $task->type == 'story')
            {
                $link = $this->host . $this->createLink($task->type, 'view', "id={$task->id}");
                $this->wordContent .= $this->addLink($prefix . $task->title, $link, array(), true);
            }
            else
            {
                $this->wordContent .= $this->addText($prefix . $task->name, array(), true);
            }
            $this->wordContent .= '</w:p>';
            if(!empty($task->children)) $this->createWord($task->children, $order + 1);
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
        $this->pauseHtmlTag($text);
        $this->wordContent .= '</w:p>';
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
    public function addText($text, $styles = array(), $inline = false)
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
                if($content)$this->addText($content, array(), true);

                if(!isset($out[3][$i])) continue;
                $content = trim($out[3][$i]);
                if(empty($content)) continue;
                $this->addText($content, $out[2][$i], true);
            }
            return false;
        }
        $text = trim(strip_tags($text));
        $text = preg_replace('/[\x00-\x09\x0B-\x1F\x7F-\x9F]/u', '', $text);
        $text = htmlspecialchars_decode($text, ENT_QUOTES);
        if(empty($text)) return false;
        $document = '';
        if(!$inline) $document .= '<w:p><w:pPr><w:spacing w:after="0"/><w:rPr><w:rFonts w:hint="eastAsia"/><w:lang w:val="en-US" w:eastAsia="zh-CN"/></w:rPr></w:pPr>';
        $document .= '<w:r>';
        $document .= $this->transformStyle($styles);
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
        $text     = htmlspecialchars_decode($text, ENT_QUOTES);
        $href     = preg_replace('/&(quot|#34);/i', '&', $href);
        $href     = preg_replace('/[\x00-\x09\x0B-\x1F\x7F-\x9F]/u', '', $href);
        if(!$inline) $document .= '<w:p><w:pPr><w:rPr><w:rFonts w:hint="eastAsia"/><w:lang w:val="en-US" w:eastAsia="zh-CN"/></w:rPr></w:pPr>';
        $document .= '<w:r><w:rPr><w:rFonts w:hint="eastAsia"/><w:lang w:val="en-US" w:eastAsia="zh-CN"/></w:rPr><w:fldChar w:fldCharType="begin"/></w:r>';
        $document .= '<w:r><w:rPr><w:rFonts w:hint="eastAsia"/><w:lang w:val="en-US" w:eastAsia="zh-CN"/></w:rPr><w:instrText xml:space="preserve">HYPERLINK "<![CDATA[' . $href . ']]>"</w:instrText></w:r>';
        $document .= '<w:r><w:rPr><w:rFonts w:hint="eastAsia"/><w:lang w:val="en-US" w:eastAsia="zh-CN"/></w:rPr><w:fldChar w:fldCharType="separate"/></w:r>';
        $document .= '<w:r>';
        $document .= $this->transformStyle($styles);
        $document .= "<w:t><![CDATA[$text]]></w:t>";
        $document .= '</w:r><w:r><w:rPr><w:rFonts w:hint="eastAsia"/><w:lang w:val="en-US" w:eastAsia="zh-CN"/></w:rPr><w:fldChar w:fldCharType="end"/></w:r>';
        if(!$inline) $document .= '</w:p>';
        $this->wordContent .= $document;
    }

    /**
     * Transform style
     *
     * @param  array  $styles
     * @access public
     * @return void
     */
    public function transformStyle($styles = array())
    {
        $wordStyle  = '<w:rPr>';
        if(isset($styles['font-family'])) $styles['font-family'] = str_replace(array('&', ';', '"', "'"), '', $styles['font-family']);
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
                $color = $value;
                if($color[0] == '#') $color = substr($value, 1);
                $wordStyle .= '<w:color w:val="' . $color .'"/>';
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
     * Pause html tag
     *
     * @param  string    $text
     * @access public
     * @return void
     */
    public function pauseHtmlTag($text)
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
                if($content) $this->pauseHtmlTag($content);

                if(!isset($out[1][$i])) continue;
                $this->wordContent .= '</w:p><w:p><w:pPr><w:rPr><w:rFonts w:hint="eastAsia"/><w:lang w:val="en-US" w:eastAsia="zh-CN"/></w:rPr></w:pPr>';
            }
            return false;
        }

        $out = array();
        preg_match_all("/<img .*src=([\"|\'])(.*)\\1 .*(\/?)>/U", $text, $out);
        $splitByIMG = preg_split("/<img .*src=([\"|\'])(.*)\\1 .*(\/?)>/U", $text);
        if($out[0])
        {
            foreach($splitByIMG as $i => $content)
            {
                if($content) $this->pauseHtmlTag($content);

                if(!isset($out[2][$i])) continue;
                $this->addImage($out[2][$i], true);
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
                $this->addLink($out[3][$i], $out[2][$i], array('color'=>'0000FF'), true);
            }
            return false;
        }

        $this->addText($text, array(), true);
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
