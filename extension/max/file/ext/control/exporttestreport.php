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
     * Export to testreport
     *
     * @access public
     * @return void
     */
    public function exportTestReport()
    {
        $users  = $this->loadModel('user')->getPairs('noletter');
        $sysURL = common::getSysURL();

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

        $headerName = sprintf($this->lang->report->testreport, $this->post->fileName);
        $this->setDocProps($headerName);

        if($headerName)
        {
            $this->addTitle($headerName, 11, 'center');
            $this->wordContent .= '<w:p><w:pPr><w:jc w:val="center"/>' . $this->wrPr . '</w:pPr>';
            foreach($this->lang->report->versionName as $version => $name)
            {
                if(strpos($this->config->version, $version) !== false) $this->lang->report->exportNotice = sprintf($this->lang->report->exportNotice, $name);
            }

            $this->addText('(' . date(DT_DATETIME1) . ' ' . $this->app->user->realname . ' ' . $this->lang->report->exportNotice . ')', array('color' => 'CCCCCC'), true);
            $this->wordContent .= '</w:p>';
        }

        $this->addTitle($this->lang->testreport->legendBasic, 2);
        $this->wordContent .= '<w:tbl>';
        $this->wordContent .= '<w:tblPr><w:tblStyle w:val="a3"/><w:tblW w:w="0" w:type="auto"/><w:tblLayout w:type="fixed"/><w:tblLook w:val="04A0"/></w:tblPr><w:tblGrid><w:gridCol w:w="1300"/><w:gridCol w:w="9421"/></w:tblGrid>';
        $this->wordContent .= '<w:tr w:rsidR="0000463B" w:rsidTr="0000463B">';
        $this->addTh($this->lang->testreport->startEnd);
        $this->addTd($this->post->report->begin . ' ~ ' . $this->post->report->end);
        $this->wordContent .= '</w:tr>';
        $this->wordContent .= '<w:tr w:rsidR="0000463B" w:rsidTr="0000463B">';
        $this->addTh($this->lang->testreport->owner);
        $this->addTd(zget($users, $this->post->report->owner));
        $this->wordContent .= '</w:tr>';
        $this->wordContent .= '<w:tr w:rsidR="0000463B" w:rsidTr="0000463B">';
        $this->addTh($this->lang->testreport->members);
        $members = '';
        foreach(explode(',', $this->post->report->members) as $member) $members .= zget($users, $member) . ' ';
        $this->addTd($members);
        $this->wordContent .= '</w:tr>';

        if($this->post->project->goal)
        {
            $this->wordContent .= '<w:tr w:rsidR="0000463B" w:rsidTr="0000463B">';
            $this->addTh($this->lang->testreport->goal);
            $this->addTd($this->post->project->goal);
            $this->wordContent .= '</w:tr>';
        }

        $this->wordContent .= '<w:tr w:rsidR="0000463B" w:rsidTr="0000463B">';
        $this->addTh($this->lang->testreport->profile);
        $this->addTd($this->post->projectProfile);
        $this->wordContent .= '</w:tr>';
        $this->wordContent .= '</w:tbl>';

        $this->addTitle($this->lang->testreport->legendStoryAndBug, 2);
        $this->wordContent .= '<w:tbl>';
        $this->wordContent .= '<w:tblPr><w:tblStyle w:val="a3"/><w:tblW w:w="0" w:type="auto"/><w:tblLayout w:type="fixed"/><w:tblLook w:val="04A0"/></w:tblPr>';
        $this->wordContent .= '<w:tblGrid><w:gridCol w:w="678"/><w:gridCol w:w="473"/><w:gridCol w:w="4800"/><w:gridCol w:w="1100"/><w:gridCol w:w="1100"/><w:gridCol w:w="810"/><w:gridCol w:w="810"/><w:gridCol w:w="950"/></w:tblGrid>';
        $this->wordContent .= '<w:tr w:rsidR="0000463B" w:rsidTr="0000463B">';
        $this->addTh($this->lang->idAB);
        $this->addTh($this->lang->priAB);
        $this->addTh($this->lang->story->title);
        $this->addTh($this->lang->openedByAB);
        $this->addTh($this->lang->assignedToAB);
        $this->addTh($this->lang->story->estimateAB);
        $this->addTh($this->lang->statusAB);
        $this->addTh($this->lang->story->stageAB);
        $this->wordContent .= '</w:tr>';
        foreach($this->post->stories as $story)
        {
            $this->wordContent .= '<w:tr w:rsidR="0000463B" w:rsidTr="0000463B">';
            $this->addTd($story->id);
            $this->addTd(zget($this->lang->story->priList, $story->pri));
            $this->addTd(html::a($sysURL . $this->createLink('story', 'view', "storyID=$story->id"), $story->title));
            $this->addTd(zget($users, $story->openedBy));
            $this->addTd(zget($users, $story->assignedTo));
            $this->addTd($story->estimate);
            $this->addTd(zget($this->lang->story->statusList, $story->status));
            $this->addTd(zget($this->lang->story->stageList, $story->stage));
            $this->wordContent .= '</w:tr>';
        }
        $this->wordContent .= '</w:tbl>';

        $this->wordContent .= '<w:tbl>';
        $this->wordContent .= '<w:tblPr><w:tblStyle w:val="a3"/><w:tblW w:w="0" w:type="auto"/><w:tblLayout w:type="fixed"/><w:tblLook w:val="04A0"/></w:tblPr>';
        $this->wordContent .= '<w:tblGrid><w:gridCol w:w="678"/><w:gridCol w:w="473"/><w:gridCol w:w="4800"/><w:gridCol w:w="1100"/><w:gridCol w:w="1100"/><w:gridCol w:w="1620"/><w:gridCol w:w="950"/></w:tblGrid>';
        $this->wordContent .= '<w:tr w:rsidR="0000463B" w:rsidTr="0000463B">';
        $this->addTh($this->lang->idAB);
        $this->addTh($this->lang->priAB);
        $this->addTh($this->lang->bug->title);
        $this->addTh($this->lang->openedByAB);
        $this->addTh($this->lang->bug->resolvedBy);
        $this->addTh($this->lang->bug->resolvedDate);
        $this->addTh($this->lang->statusAB);
        $this->wordContent .= '</w:tr>';
        foreach($this->post->bugs as $bug)
        {
            $this->wordContent .= '<w:tr w:rsidR="0000463B" w:rsidTr="0000463B">';
            $this->addTd($bug->id);
            $this->addTd(zget($this->lang->bug->priList, $bug->pri));
            $this->addTd(html::a($sysURL . $this->createLink('bug', 'view', "bugID=$bug->id"), $bug->title));
            $this->addTd(zget($users, $bug->openedBy));
            $this->addTd(zget($users, $bug->resolvedBy));
            $this->addTd($bug->resolvedDate);
            $this->addTd(zget($this->lang->bug->statusList, $bug->status));
            $this->wordContent .= '</w:tr>';
        }
        $this->wordContent .= '</w:tbl>';

        $this->addTitle($this->lang->testreport->legendBuild, 2);
        $this->wordContent .= '<w:tbl>';
        $this->wordContent .= '<w:tblPr><w:tblStyle w:val="a3"/><w:tblW w:w="0" w:type="auto"/><w:tblLayout w:type="fixed"/><w:tblLook w:val="04A0"/></w:tblPr>';
        $this->wordContent .= '<w:tblGrid><w:gridCol w:w="678"/><w:gridCol w:w="7183"/><w:gridCol w:w="1200"/><w:gridCol w:w="1660"/></w:tblGrid>';
        $this->wordContent .= '<w:tr w:rsidR="0000463B" w:rsidTr="0000463B">';
        $this->addTh($this->lang->build->id);
        $this->addTh($this->lang->build->name);
        $this->addTh($this->lang->build->builder);
        $this->addTh($this->lang->build->date);
        $this->wordContent .= '</w:tr>';
        foreach($this->post->builds as $build)
        {
            $this->wordContent .= '<w:tr w:rsidR="0000463B" w:rsidTr="0000463B">';
            $this->addTd($build->id);
            $this->addTd(html::a($sysURL . $this->createLink('build', 'view', "id=$build->id"), $build->name));
            $this->addTd(zget($users, $build->builder));
            $this->addTd($build->date);
            $this->wordContent .= '</w:tr>';
        }
        $this->wordContent .= '</w:tbl>';

        $this->addTitle($this->lang->testreport->legendCase, 2);
        $this->wordContent .= '<w:tbl>';
        $this->wordContent .= '<w:tblPr><w:tblStyle w:val="a3"/><w:tblW w:w="0" w:type="auto"/><w:tblLayout w:type="fixed"/><w:tblLook w:val="04A0"/></w:tblPr>';
        $this->wordContent .= '<w:tblGrid><w:gridCol w:w="678"/><w:gridCol w:w="473"/><w:gridCol w:w="3020"/><w:gridCol w:w="1100"/><w:gridCol w:w="1100"/><w:gridCol w:w="1100"/><w:gridCol w:w="1500"/><w:gridCol w:w="800"/><w:gridCol w:w="950"/></w:tblGrid>';
        $this->wordContent .= '<w:tr w:rsidR="0000463B" w:rsidTr="0000463B">';
        $this->addTh($this->lang->idAB);
        $this->addTh($this->lang->priAB);
        $this->addTh($this->lang->testcase->title);
        $this->addTh($this->lang->testcase->type);
        $this->addTh($this->lang->testtask->assignedTo);
        $this->addTh($this->lang->testtask->lastRunAccount);
        $this->addTh($this->lang->testtask->lastRunTime);
        $this->addTh($this->lang->testtask->lastRunResult);
        $this->addTh($this->lang->statusAB);
        $this->wordContent .= '</w:tr>';
        foreach($this->post->cases as $taskID => $caseList)
        {
            foreach($caseList as $case)
            {
                $this->wordContent .= '<w:tr w:rsidR="0000463B" w:rsidTr="0000463B">';
                $this->addTd($case->id);
                $this->addTd(zget($this->lang->testcase->priList, $case->pri));
                $this->addTd(html::a($sysURL . $this->createLink('testcase', 'view', "id=$case->id"), $case->title));
                $this->addTd(zget($this->lang->testcase->typeList, $case->type));
                $this->addTd(zget($users, $case->assignedTo));
                $this->addTd(zget($users, $case->lastRunner));
                $this->addTd($case->lastRunDate);
                $this->addTd(zget($this->lang->testcase->resultList, $case->lastRunResult));
                $this->addTd(zget($this->lang->testcase->statusList, $case->status));
                $this->wordContent .= '</w:tr>';
            }
        }
        $this->wordContent .= '</w:tbl>';

        $this->addTitle($this->lang->testreport->legendLegacyBugs, 2);
        $this->wordContent .= '<w:tbl>';
        $this->wordContent .= '<w:tblPr><w:tblStyle w:val="a3"/><w:tblW w:w="0" w:type="auto"/><w:tblLayout w:type="fixed"/><w:tblLook w:val="04A0"/></w:tblPr>';
        $this->wordContent .= '<w:tblGrid><w:gridCol w:w="678"/><w:gridCol w:w="1000"/><w:gridCol w:w="800"/><w:gridCol w:w="2200"/><w:gridCol w:w="1100"/><w:gridCol w:w="1100"/><w:gridCol w:w="1300"/><w:gridCol w:w="1650"/><w:gridCol w:w="950"/></w:tblGrid>';
        $this->wordContent .= '<w:tr w:rsidR="0000463B" w:rsidTr="0000463B">';
        $this->addTh($this->lang->idAB);
        $this->addTh($this->lang->bug->severity);
        $this->addTh($this->lang->priAB);
        $this->addTh($this->lang->bug->title);
        $this->addTh($this->lang->openedByAB);
        $this->addTh($this->lang->bug->resolvedBy);
        $this->addTh($this->lang->bug->resolution);
        $this->addTh($this->lang->bug->resolvedDate);
        $this->addTh($this->lang->statusAB);
        $this->wordContent .= '</w:tr>';
        foreach($this->post->legacyBugs as $bug)
        {
            $this->wordContent .= '<w:tr w:rsidR="0000463B" w:rsidTr="0000463B">';
            $this->addTd($bug->id);
            $this->addTd(zget($this->lang->bug->severityList, $bug->severity));
            $this->addTd(zget($this->lang->bug->priList, $bug->pri));
            $this->addTd(html::a($sysURL . $this->createLink('bug', 'view', "id=$bug->id"), $bug->title));
            $this->addTd(zget($users, $bug->openedBy));
            $this->addTd(zget($users, $bug->resolvedBy));
            $this->addTd(zget($this->lang->bug->resolutionList, $bug->resolution));
            $this->addTd(!helper::isZeroDate($bug->resolvedDate) ? substr($bug->resolvedDate, 2) : '');
            $this->addTd(zget($this->lang->bug->statusList, $bug->status));
            $this->wordContent .= '</w:tr>';
        }
        $this->wordContent .= '</w:tbl>';

        $this->addTitle($this->lang->testreport->legendReport, 2);
        if($this->post->charts)
        {
            foreach($this->post->charts as $chart => $chartOption)
            {
                $data      = $this->post->datas[$chart];
                $title     = $this->lang->testtask->report->charts[$chart];
                $chartName = 'chart-' . $chart;
                $this->addTitle($title, 3);
                if(isset($_POST[$chartName]))
                {
                    $this->addImage($this->post->$chartName);
                    $this->addTable($data, $chart);
                    $this->addTextBreak(2);
                }
            }
        }

        foreach($this->post->bugInfo as $infoKey => $infoValue)
        {
            $title     = $this->lang->testreport->$infoKey;
            $chartName = 'chart-' . $infoKey;
            $this->addTitle($title, 3);
            if(isset($_POST[$chartName]))
            {
                $this->addImage($this->post->$chartName);
                $this->addTable($infoValue, $infoKey);
                $this->addTextBreak(2);
            }
        }

        $this->addTitle($this->lang->testreport->legendComment, 2);
        $this->pauseHtmlTag($this->post->report->report);

        $this->addTitle($this->lang->files, 2);
        if(empty($this->post->report->files)) $this->wordContent .= '<w:p><w:pPr><w:spacing w:line="220" w:lineRule="atLeast"/></w:pPr></w:p>';
        $this->formatDocxFiles($this->post->report);

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
    public function addTitle($text, $grade = 1, $align = 'left')
    {
        $this->wordContent .= "<w:p><w:pPr><w:pStyle w:val='$grade'/>";
        if($align != 'left') $this->wordContent .= "<w:jc w:val='$align'/>";
        $this->wordContent .= $this->wrPr . '</w:pPr>';
        $this->addText($text, array(), true);
        $this->wordContent .= '</w:p>';
    }

    public function addTable($tableData, $item, $style = 'block')
    {
        $itemLang    = $this->lang->report->item;
        $valueLang   = $this->lang->report->value;
        $leftWidth   = 2000;
        $centerWidth = 870;
        $rightWidth  = 1000;
        $tblBorders  = '<w:tblBorders><w:top w:space="0" w:sz="4" w:color="auto" w:val="single"/><w:left w:space="0" w:sz="4" w:color="auto" w:val="single"/><w:bottom w:space="0" w:sz="4" w:color="auto" w:val="single"/><w:right w:space="0" w:sz="4" w:color="auto" w:val="single"/><w:insideH w:space="0" w:sz="4" w:color="auto" w:val="single"/><w:insideV w:space="0" w:sz="4" w:color="auto" w:val="single"/></w:tblBorders>';
        $tblLayout   = '<w:tblLayout w:type="fixed"/>';
        $tblCellMar  = '<w:tblCellMar><w:left w:w="108" w:type="dxa"/><w:right w:w="108" w:type="dxa"/></w:tblCellMar>';
        $floatStyle  = '<w:tblpPr w:tblpY="100" w:tblpX="8000" w:horzAnchor="page" w:vertAnchor="text" w:rightFromText="180" w:leftFromText="180"/><w:tblOverlap w:val="never"/>';
        $document    = '<w:tbl>';
        if($item == 'bugStageGroups' or $item == 'bugHandleGroups')
        {
            $width     = $leftWidth * 3 + $rightWidth;
            $document .= '<w:tblPr>
                <w:tblStyle w:val="12"/>' . ($style == 'float' ? $floatStyle : '') . '
                <w:tblW w:w="' . $width . '" w:type="dxa"/><w:jc w:val="center"/>' . $tblBorders . $tblLayout . $tblCellMar . '</w:tblPr>
                <w:tblGrid><w:gridCol w:w="' . $rightWidth . '"/><w:gridCol w:w="' . $leftWidth . '"/><w:gridCol w:w="' . $leftWidth . '"/><w:gridCol w:w="' . $leftWidth . '"/></w:tblGrid>';
            $document .= '<w:tr><w:tblPrEx>' . $tblBorders . $tblLayout . $tblCellMar . '</w:tblPrEx>
                <w:trPr><w:trHeight w:val="287" w:hRule="atLeast"/></w:trPr>
                <w:tc>
                <w:tcPr><w:tcW w:w="' . $rightWidth . '" w:type="dxa"/></w:tcPr>
                <w:p>' . $this->wpPr . '<w:r>' . $this->wrPr . '<w:t><![CDATA[' . htmlspecialchars_decode($item == 'bugStageGroups' ? $this->lang->bug->pri : $this->lang->testreport->date, ENT_QUOTES) . ']]></w:t></w:r></w:p>
                </w:tc>
                <w:tc>
                <w:tcPr><w:tcW w:w="' . $leftWidth . '" w:type="dxa"/></w:tcPr>
                <w:p>' . $this->wpPr . '<w:r>' . $this->wrPr . '<w:t><![CDATA[' . htmlspecialchars_decode($this->lang->testreport->bugStageList['generated'], ENT_QUOTES) . ']]></w:t></w:r></w:p>
                </w:tc>
                <w:tc>
                <w:tcPr><w:tcW w:w="' . $leftWidth . '" w:type="dxa"/></w:tcPr>
                <w:p>' . $this->wpPr . '<w:r>' . $this->wrPr . '<w:t><![CDATA[' . htmlspecialchars_decode($this->lang->testreport->bugStageList['legacy'], ENT_QUOTES) . ']]></w:t></w:r></w:p>
                </w:tc>
                <w:tc>
                <w:tcPr><w:tcW w:w="' . $leftWidth . '" w:type="dxa"/></w:tcPr>
                <w:p>' . $this->wpPr . '<w:r>' . $this->wrPr . '<w:t><![CDATA[' . htmlspecialchars_decode($this->lang->testreport->bugStageList['resolved'], ENT_QUOTES) . ']]></w:t></w:r></w:p>
                </w:tc>
                </w:tr>';
        }
        else
        {
            $width     = $leftWidth + $centerWidth + $rightWidth;
            $document .= '<w:tblPr>
                <w:tblStyle w:val="9"/>' . ($style == 'float' ? $floatStyle : '') . '
                <w:tblW w:w="' . $width . '" w:type="dxa"/><w:jc w:val="center"/>' . $tblBorders . $tblLayout . $tblCellMar . '</w:tblPr>
                <w:tblGrid><w:gridCol w:w="' . $leftWidth . '"/><w:gridCol w:w="' . $centerWidth . '"/><w:gridCol w:w="' . $rightWidth . '"/></w:tblGrid>';
            $document .= '<w:tr><w:tblPrEx>' . $tblBorders . $tblLayout . $tblCellMar . '</w:tblPrEx>
                <w:trPr><w:trHeight w:val="287" w:hRule="atLeast"/></w:trPr>
                <w:tc>
                <w:tcPr><w:tcW w:w="' . $leftWidth . '" w:type="dxa"/></w:tcPr>
                <w:p>' . $this->wpPr . '<w:r>' . $this->wrPr . '<w:t><![CDATA[' . htmlspecialchars_decode($itemLang, ENT_QUOTES) . ']]></w:t></w:r></w:p>
                </w:tc>
                <w:tc>
                <w:tcPr><w:tcW w:w="' . $centerWidth . '" w:type="dxa"/></w:tcPr>
                <w:p>' . $this->wpPr . '<w:r>' . $this->wrPr . '<w:t><![CDATA[' . htmlspecialchars_decode($valueLang, ENT_QUOTES) . ']]></w:t></w:r></w:p>
                </w:tc>
                <w:tc>
                <w:tcPr><w:tcW w:w="' . $rightWidth . '" w:type="dxa"/></w:tcPr>
                <w:p>' . $this->wpPr . '<w:r>' . $this->wrPr . '<w:t><![CDATA[' . htmlspecialchars_decode($this->lang->report->percent, ENT_QUOTES) . ']]></w:t></w:r></w:p>
                </w:tc>
                </w:tr>';
        }
        foreach($tableData as $data)
        {
            if($item == 'bugStageGroups' or $item == 'bugHandleGroups')
            {
                $document .= '<w:tr><w:tblPrEx>' . $tblBorders . $tblLayout . $tblCellMar . '</w:tblPrEx>
                    <w:trPr><w:trHeight w:val="287" w:hRule="atLeast"/></w:trPr>
                    <w:tc>
                    <w:tcPr><w:tcW w:w="' . $rightWidth . '" w:type="dxa"/></w:tcPr>
                    <w:p>' . $this->wpPr . '<w:r>' . $this->wrPr . '<w:t><![CDATA[' . htmlspecialchars_decode($data->name, ENT_QUOTES) . ']]></w:t></w:r></w:p>
                    </w:tc>
                    <w:tc>
                    <w:tcPr><w:tcW w:w="' . $leftWidth . '" w:type="dxa"/></w:tcPr>
                    <w:p>' . $this->wpPr . '<w:r>' . $this->wrPr . '<w:t><![CDATA[' . htmlspecialchars_decode($data->generated, ENT_QUOTES) . ']]></w:t></w:r></w:p>
                    </w:tc>
                    <w:tc>
                    <w:tcPr><w:tcW w:w="' . $leftWidth . '" w:type="dxa"/></w:tcPr>
                    <w:p>' . $this->wpPr . '<w:r>' . $this->wrPr . '<w:t><![CDATA[' . htmlspecialchars_decode($data->legacy, ENT_QUOTES) . ']]></w:t></w:r></w:p>
                    </w:tc>
                    <w:tc>
                    <w:tcPr><w:tcW w:w="' . $leftWidth . '" w:type="dxa"/></w:tcPr>
                    <w:p>' . $this->wpPr . '<w:r>' . $this->wrPr . '<w:t><![CDATA[' . htmlspecialchars_decode($data->resolved, ENT_QUOTES) . ']]></w:t></w:r></w:p>
                    </w:tc>
                    </w:tr>';
            }
            else
            {
                $document .= '<w:tr><w:tblPrEx>' . $tblBorders . $tblLayout . $tblCellMar . '</w:tblPrEx>
                    <w:trPr><w:trHeight w:val="287" w:hRule="atLeast"/></w:trPr>
                    <w:tc>
                    <w:tcPr><w:tcW w:w="' . $leftWidth . '" w:type="dxa"/></w:tcPr>
                    <w:p>' . $this->wpPr . '<w:r>' . $this->wrPr . '<w:t><![CDATA[' . htmlspecialchars_decode($data->name, ENT_QUOTES) . ']]></w:t></w:r></w:p>
                    </w:tc>
                    <w:tc>
                    <w:tcPr><w:tcW w:w="' . $centerWidth . '" w:type="dxa"/></w:tcPr>
                    <w:p>' . $this->wpPr . '<w:r>' . $this->wrPr . '<w:t><![CDATA[' . htmlspecialchars_decode($data->value, ENT_QUOTES) . ']]></w:t></w:r></w:p>
                    </w:tc>
                    <w:tc>
                    <w:tcPr><w:tcW w:w="' . $rightWidth . '" w:type="dxa"/></w:tcPr>
                    <w:p>' . $this->wpPr . '<w:r>' . $this->wrPr . '<w:t><![CDATA[' . ($data->percent * 100) . '%' . ']]></w:t></w:r></w:p>
                    </w:tc>
                    </w:tr>';
            }
        }
        $document .= '</w:tbl>';

        $this->wordContent .= $document;
    }

    public function addTh($fieldName, $width = '1101')
    {
        $this->wordContent .= '<w:tc><w:tcPr></w:tcPr>';
        $this->wordContent .= '<w:p w:rsidR="0000463B" w:rsidRDefault="0000463B" w:rsidP="0000463B"><w:pPr><w:spacing w:line="220" w:lineRule="atLeast"/><w:jc w:val="right"/></w:pPr>';
        $this->wordContent .= '<w:r><w:rPr><w:rFonts w:hint="eastAsia"/><w:b/><w:bCs/></w:rPr>';
        $this->wordContent .= "<w:t><![CDATA[{$fieldName}]]></w:t>";
        $this->wordContent .= '</w:r></w:p></w:tc>';
    }

    public function addTd($content, $width = '7421')
    {
        $this->wordContent .= '<w:tc><w:tcPr><w:vAlign w:val="center"/></w:tcPr>';
        $this->pauseHtmlTag($content);
        $this->wordContent .= '</w:tc>';
    }

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
                if(empty($content) and $content != 0) continue;
                $this->addText($content, $out[2][$i], true);
            }
            return false;
        }
        $text = trim(strip_tags($text));
        if(empty($text) and $text != 0) return false;
        $text     = htmlspecialchars_decode($text, ENT_QUOTES);
        $document = '';
        if(!$inline) $document .= '<w:p>' . $this->wpPr;
        $document .= '<w:r>';
        $document .= $this->transformStyle($styles);
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
    public function addImage($imageData, $extension = 'png')
    {
        $dir = $this->exportPath . 'word/media';
        if(!is_dir($dir)) mkdir($dir, 0777, true);

        $imageData = base64_decode(substr($imageData, strpos($imageData, 'base64,') + 7));
        if(empty($imageData)) return false;

        if(strpos($imageData, 'realpath;') === 0) $imageData = file_get_contents(substr($imageData, strlen('realpath;')));

        $this->imgExtensions[$extension] = true;
        $this->relsID[] = $extension;

        end($this->relsID);
        $relsID = key($this->relsID);

        $path = $dir . '/image' . $relsID . '.' . $extension;
        file_put_contents($path, $imageData);

        list($width, $height) = getimagesize($path);
        if($width > $this->config->word->maxImageWidth)
        {
            $height = ceil(($this->config->word->maxImageWidth / $width) * $height);
            $width  = $this->config->word->maxImageWidth;
        }

        $document  = '';
        $document .= '<w:p><w:pPr><w:jc w:val="center"/></w:pPr>';
        $document .= '<w:r><w:rPr><w:rFonts w:hint="eastAsia" /><w:kern w:val="2"/><w:sz w:val="21"/><w:lang w:val="en-US" w:eastAsia="zh-CN"/></w:rPr>';
        $document .= '<w:pict><v:shape id="pict' . $relsID . '" o:spid="_x0000_s1026" type="#_x0000_t75" style="height:' . $height . 'px;width:' . ($width - $width * 0.13) . 'px">';
        $document .= '<v:imagedata cropleft="8396f" o:title="' . $relsID . '" r:id="rId' . $relsID . '"/>';
        $document .= '<o:lock v:ext="edit" position="f" selection="f" grouping="f" rotation="f" cropping="f" text="f" aspectratio="t"/><w10:wrap type="none"/><w10:anchorlock/></v:shape></w:pict></w:r>';
        $document .= '</w:p>';

        $this->wordContent .= $document;
    }

    public function addLink($text, $href, $styles = array(), $inline = false)
    {
        $document = '';
        $text     = htmlspecialchars_decode($text, ENT_QUOTES);
        $href     = preg_replace('/&(quot|#34);/i', '&', $href);
        if(!$inline) $document .= '<w:p>' . $this->wpPr;
        $document .= '<w:r>' . $this->wrPr . '<w:fldChar w:fldCharType="begin"/></w:r>';
        $document .= '<w:r>' . $this->wrPr . '<w:instrText xml:space="preserve">HYPERLINK "<![CDATA[' . $href . ']]>"</w:instrText></w:r>';
        $document .= '<w:r>' . $this->wrPr . '<w:fldChar w:fldCharType="separate"/></w:r>';
        $document .= '<w:r>';
        $document .= $this->transformStyle($styles);
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
    public function addTextBreak($num = 1)
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
    public function transformStyle($styles = array())
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
    public function setDocProps($header)
    {
        $title      = $header ? $header : $this->post->kind;
        $coreFile   = file_get_contents($this->exportPath . 'docProps/core.xml');
        $createDate = date('Y-m-d') . 'T' . date('H:i:s') . 'Z';
        $account    = $this->app->user->account;
        $coreFile   = sprintf($coreFile, $createDate, $account, $account, $createDate, $title);
        file_put_contents($this->exportPath . 'docProps/core.xml', $coreFile);
    }

    public function pauseLineHtmlTag($text)
    {
        $out = array();
        preg_match_all('/(<br ?(\/?)>)/U', $text, $out);
        $splitByBR = preg_split("/<br ?(\/?)>/U", $text);
        if($out[0])
        {
            foreach($splitByBR as $i => $content)
            {
                if($content) $this->pauseLineHtmlTag($content);

                if(!isset($out[1][$i])) continue;
                $this->wordContent .= '</w:p><w:p>' . $this->wpPr;
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
                if($content) $this->pauseLineHtmlTag($content);

                if(!isset($out[2][$i])) continue;
                $imgExtension = str_replace('data:image/', '', substr($out[2][$i], 0, strpos($out[2][$i], ';')));
                $isFixContent = preg_match('/<\/w:p>$/', $this->wordContent) == false;
                if($isFixContent) $this->wordContent = substr($this->wordContent, 0, strrpos($this->wordContent, '<w:p>'));
                $this->addImage($out[2][$i], $imgExtension);
                if($isFixContent) $this->wordContent = substr($this->wordContent, 0, strlen($this->wordContent) - 6);
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
                if($content) $this->pauseLineHtmlTag($content);

                if(!isset($out[3][$i])) continue;
                $content = trim($out[3][$i]);
                if(empty($content) and $content != 0) continue;
                $this->addLink($out[3][$i], $out[2][$i], array('color'=>'0000FF'), true);
            }
            return false;
        }

        $this->addText($text, array(), true);
    }

    public function formatDocxFiles($content)
    {
        if(empty($content->files)) return;
        foreach($content->files as $file)
        {
            $linkHref  = $file->realPath;
            $extension = $file->extension;
            $linkName  = $file->title;
            if(in_array($extension, $this->config->word->imageExtension))
            {
                if(!file_exists($linkHref)) continue;
                $this->addImage("data:image/{$extension};base64," . base64_encode('realpath;' . $linkHref));
                $this->addTextBreak();
            }
            else
            {
                $this->addLink($linkName . ".$extension", common::getSysURL() . $this->createLink('file', 'download', "fileID={$file->id}"), array('color'=>'0000FF'));
            }
        }
    }

    public function checkFileExist($matches)
    {
        $realFilePath = $this->config->word->filePath . $matches[2];
        preg_match_all('/^{(\d+)\.\w+}$/', $matches[2], $out);
        $imageData = '';
        if($out[0])
        {
            $file = $this->file->getById($out[1][0]);
            $realFilePath = $file->realPath;
            if(file_exists($realFilePath)) $imageData = "data:image/{$file->extension};base64," . base64_encode('realpath;' . $realFilePath);
        }
        return file_exists($realFilePath) ? '<img src="' . $imageData . '" alt="" />' : '';
    }

    public function pauseHtmlTag($content)
    {
        if(empty($content))
        {
            $this->wordContent .= '<w:p><w:pPr><w:spacing w:line="220" w:lineRule="atLeast"/></w:pPr></w:p>';
            return false;
        }

        /* Change the tag of u em and stong to span. */
        $search       = array('<u>', '</u>', '<em>', '</em>', '<strong>', '</strong>', '<b>', '</b>', '&nbsp;');
        $replace      = array('<span style="text-decoration:underline">', '</span>', '<span style="font-style:italic">', '</span>', '<span style="font-weight:bold;">', '</span>', '<span style="font-weight:bold;">', '</span>', ' ');
        $isImage = strpos($content, '<img') !== false;
        $fieldContent = preg_replace_callback('/<img .*src=([\"|\'])(.+)\\1 .*(\/?)>/U', array(&$this, 'checkFileExist'), $content);
        $fieldContent = str_replace($search, $replace, $fieldContent);
        $fieldContent = str_replace("\n", '', $fieldContent);
        $fieldContent = str_replace(array("</li>", '</tr>', '<br />', '</p>'), "\n", $fieldContent);
        $fieldContent = preg_replace(array('/<h\d>/', '/<\/h\d>/'), array('', "\n"), $fieldContent);
        $fieldContent = strip_tags($fieldContent, '<a><img><span>');
        $fieldContent = explode("\n", $fieldContent);

        foreach($fieldContent as $text)
        {
            $this->wordContent .= '<w:p>' . $this->wpPr;
            $this->pauseLineHtmlTag($text);
            $this->wordContent .= '</w:p>';
        }
    }
}
