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
        $executionID   = $this->post->executionID;
        $kanbanSetting = $this->execution->getKanbanSetting();
        $tasks         = $this->execution->getKanbanTasks($executionID, "id");
        $bugs          = $this->loadModel('bug')->getExecutionBugs($executionID);

        $this->taskCols    = $this->execution->getKanbanColumns($kanbanSetting);
        $this->statusList  = $this->execution->getKanbanStatusList($kanbanSetting);
        $this->colorList   = $this->execution->getKanbanColorList($kanbanSetting);
        $this->stories     = $this->loadModel('story')->getExecutionStories($executionID, 0, 0, $this->post->orderBy);
        $this->type        = $this->post->type;
        $this->kanbanGroup = $this->execution->getKanbanGroupData($this->stories, $tasks, $bugs, $this->type);
        $this->execution   = $this->execution->getById($executionID);

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
    public function export2Kanban()
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

        $hasTask = false;
        foreach($this->kanbanGroup as $group)
        {
            if(count(get_object_vars($group)) > 0)
            {
                $hasTask = true;
                break;
            }
        }
        if($hasTask) $this->createWord();

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
        $documentXML = str_replace(array('%wordContent%', '%sectpr%'), array($this->wordContent, $this->config->word->sectPrAbeam), $documentXML);
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
        $realnames  = $this->loadModel('user')->getPairs('noletter');
        $tableWidth = '13867';

        $this->wordContent .= '<w:tbl>';
        $this->wordContent .= '<w:tblPr><w:tblW w:w="13867" w:type="dxa"/><w:tblInd w:w="135" w:type="dxa"/><w:tblBorders><w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:insideH w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:insideV w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tblBorders><w:tblLayout w:type="fixed"/><w:tblCellMar><w:left w:w="10" w:type="dxa"/><w:right w:w="10" w:type="dxa"/></w:tblCellMar><w:tblLook w:val="04A0" w:firstRow="1" w:lastRow="0" w:firstColumn="1" w:lastColumn="0" w:noHBand="0" w:noVBand="1"/></w:tblPr><w:tblGrid>';

        $hasGroupCol = (($this->type == 'story' and count($this->stories) > 0) or $this->type != 'story');
        $allCols     = count($this->taskCols) + ($hasGroupCol ? 1 : 0);
        $colWidth    = ceil($tableWidth / $allCols);
        for($i = 0; $i < $allCols; $i++) $this->wordContent .= '<w:gridCol w:w="' . (300 + ceil(11766 / $allCols)) . '"/>';
        $this->wordContent .= '</w:tblGrid>';
        $this->wordContent .= '<w:tr>';
        if($hasGroupCol)
        {
            if($this->type == 'story') $this->addTd($this->lang->story->common);
            if($this->type == 'assignedTo') $this->addTd($this->lang->execution->groups['assignedTo']);
            if($this->type == 'finishedBy') $this->addTd($this->lang->execution->groups['finishedBy']);
        }
        foreach($this->taskCols as $col) $this->addTd($this->statusList[$col]);
        $this->wordContent .= '</w:tr>';

        foreach($this->kanbanGroup as $groupKey => $group)
        {
            if(count(get_object_vars($group)) == 0) continue;
            $this->wordContent .= '<w:tr>';
            if($hasGroupCol)
            {
                $content = '';
                if($groupKey != 'nokey')
                {
                    if($this->type == 'story')
                    {
                        $story   = $group;
                        $content = $story->title . "<br /> #{$story->id} P:" . zget($this->lang->story->priList, $story->pri) . " [" . zget($this->lang->story->stageList, $story->stage) . "]  {$story->estimate}h";
                    }
                    else
                    {
                        $content = zget($realnames, $groupKey);
                    }
                }
                $this->wordContent .= '<w:tc><w:tcPr><w:tcW w:w="' . $colWidth . '"  w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="auto"/><w:tcMar><w:top w:w="57" w:type="dxa"/><w:left w:w="10" w:type="dxa"/><w:bottom w:w="0" w:type="dxa"/><w:right w:w="10" w:type="dxa"/></w:tcMar></w:tcPr>';
                if(empty($content))
                {
                    $this->wordContent .= '<w:p><w:pPr><w:spacing w:after="0"/></w:pPr></w:p>';
                }
                else
                {
                    foreach(explode('<br />', $content) as $line)
                    {
                        $line = trim($line);
                        if(empty($line)) continue;

                        $this->wordContent .= "<w:p><w:pPr><w:spacing w:line='180' w:beforeLines='20' w:afterLines='20'/><w:ind w:leftChars='50' w:left='100' w:rightChars='50' w:right='100'/></w:pPr><w:r><w:rPr><w:sz w:val='18'/><w:szCs w:val='18'/><w:rFonts w:hint='eastAsia'/></w:rPr><w:t><![CDATA[{$line}]]></w:t></w:r></w:p>";
                    }
                }
                $this->wordContent .= '</w:tc>';
            }
            foreach($this->taskCols as $col)
            {
                $this->wordContent .= '<w:tc><w:tcPr><w:tcW w:w="1981" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="auto"/><w:tcMar><w:top w:w="57" w:type="dxa"/><w:left w:w="10" w:type="dxa"/><w:bottom w:w="0" w:type="dxa"/><w:right w:w="10" w:type="dxa"/></w:tcMar><w:vAlign w:val="top"/></w:tcPr>';

                $typeList = array('tasks', 'bugs');
                if(!empty($group->tasks[$col]) or !empty($group->bugs[$col]))
                {
                    foreach($typeList as $type)
                    {
                        $dataList = $group->$type;
                        foreach($dataList[$col] as $data)
                        {
                            $color = zget($this->colorList, $data->status, '#FFFFFF');

                            $this->wordContent .= '<w:p><w:pPr><w:spacing w:before-lines="20" w:after-lines="10"/><w:jc w:val="center"/></w:pPr>';
                            $this->wordContent .= '<w:r><w:pict><v:rect id="_x0000_s2051" style="width:75pt;mso-position-horizontal-relative:char;mso-position-vertical-relative:line" fillcolor=' . "\"$color\"" . '><v:textbox style="mso-fit-shape-to-text:t"><w:txbxContent>';

                            if($type == 'tasks')
                            {
                                $childrenAB = $data->parent > 0 ? "[{$this->lang->task->childrenAB}]" : '';
                                $this->wordContent .= '<w:p><w:pPr><w:spacing w:after="0"/><w:rPr><w:rFonts w:fareast="微软雅黑" w:hint="default"/><w:lang w:val="EN-US" w:fareast="ZH-CN"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="18"/><w:sz-cs w:val="18"/></w:rPr><w:t>'. " <![CDATA[{$childrenAB}" . htmlspecialchars_decode($data->name) . "]]>"  .'</w:t></w:r></w:p>';
                            }
                            else
                            {
                                $this->wordContent .= '<w:p><w:pPr><w:spacing w:after="0"/><w:rPr><w:rFonts w:fareast="微软雅黑" w:hint="default"/><w:lang w:val="EN-US" w:fareast="ZH-CN"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="18"/><w:sz-cs w:val="18"/></w:rPr><w:t>'. " <![CDATA[BUG: " . htmlspecialchars_decode($data->title) . "]]>"  .'</w:t></w:r></w:p>';
                            }

                            $assignedToRealName = zget($realnames, $data->assignedTo);
                            if(empty($data->assignedTo)) $assignedToRealName = $this->lang->task->noAssigned;

                            if($type == 'tasks')
                            {
                                $delayed = isset($data->delay) ? $this->lang->task->delayed : '';
                                $this->wordContent .= '<w:p><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="18"/><w:sz-cs w:val="18"/></w:rPr><w:t>' . "<![CDATA[{$assignedToRealName} {$delayed}  {$data->left}h]]>" . '</w:t></w:r></w:p></w:txbxContent>';
                            }
                            else
                            {
                                $this->wordContent .= '<w:p><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="18"/><w:sz-cs w:val="18"/></w:rPr><w:t>' . "<![CDATA[{$assignedToRealName}]]>" . '</w:t></w:r></w:p></w:txbxContent>';
                            }

                            $this->wordContent .= '</v:textbox><w10:wrap type="none"/><w10:anchorlock/></v:rect></w:pict></w:r>';
                            $this->wordContent .= '</w:p>';
                        }
                    }
                }
                else
                {
                    $this->wordContent .= '<w:p><w:pPr><w:spacing w:beforeLines="50" w:afterLines="50"/></w:pPr></w:p>';
                }
                $this->wordContent .= '</w:tc>';
            }
            $this->wordContent .= '</w:tr>';
        }
        $this->wordContent .= '</w:tbl>';
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
        $this->wordContent .= '<w:trPr><w:trHeight w:val="827"/></w:trPr>';
        $this->wordContent .= '<w:tc><w:tcPr><w:tcW w:w="1981" w:type="dxa"/><w:tcBorders><w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tcBorders><w:tcMar><w:top w:w="57" w:type="dxa"/><w:left w:w="10" w:type="dxa"/><w:bottom w:w="0" w:type="dxa"/><w:right w:w="10" w:type="dxa"/></w:tcMar></w:tcPr>';
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
        if(!$inline) $document .= '<w:p><w:pPr><w:spacing w:after="0"/></w:pPr></w:p><w:p><w:pPr><w:spacing w:after="0"/><w:jc w:val="center"/><w:rPr><w:rFonts w:hint="default"/><w:lang w:val="EN-US"/></w:rPr></w:pPr>';
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
        if(!$inline) $document .= '<w:p><w:pPr><w:spacing w:after="0"/></w:pPr>';
        $document .= '<w:r><w:rPr><w:rFonts w:hint="eastAsia"/><w:lang w:val="en-US" w:eastAsia="zh-CN"/></w:rPr><w:fldChar w:fldCharType="begin"/></w:r>';
        $document .= '<w:r><w:rPr><w:rFonts w:hint="eastAsia"/><w:lang w:val="en-US" w:eastAsia="zh-CN"/></w:rPr><w:instrText xml:space="preserve">HYPERLINK "<![CDATA[' . $href . ']]>"</w:instrText></w:r>';
        $document .= '<w:r><w:rPr><w:rFonts w:hint="eastAsia"/><w:lang w:val="en-US" w:eastAsia="zh-CN"/></w:rPr><w:fldChar w:fldCharType="separate"/></w:r>';
        $document .= '<w:r><w:rPr><w:rFonts w:hint="eastAsia"/><w:color w:val="0000FF"/></w:rPr>';
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
