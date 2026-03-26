<?php
/**
 * The control file of file module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      ChenTao<chentao@easycorp.ltd>
 * @package     export
 * @link        https://www.zentao.net
 */
helper::importControl('file');
class myfile extends file
{
    public $wordContent   = '';
    public $imgExtensions = array();
    public $relsID        = array();
    public $wrPr          = '<w:rPr><w:rFonts w:hint="eastAsia"/><w:lang w:val="en-US" w:eastAsia="zh-CN"/></w:rPr>';
    public $wpPr          = '';

    private $columnWidth = 2000;
    private $tblBorders  = '<w:tblBorders><w:top w:space="0" w:sz="4" w:color="auto" w:val="single"/><w:left w:space="0" w:sz="4" w:color="auto" w:val="single"/><w:bottom w:space="0" w:sz="4" w:color="auto" w:val="single"/><w:right w:space="0" w:sz="4" w:color="auto" w:val="single"/><w:insideH w:space="0" w:sz="4" w:color="auto" w:val="single"/><w:insideV w:space="0" w:sz="4" w:color="auto" w:val="single"/></w:tblBorders>';
    private $tblLayout   = '<w:tblLayout w:type="fixed"/>';
    private $tblCellMar  = '<w:tblCellMar><w:left w:w="108" w:type="dxa"/><w:right w:w="108" w:type="dxa"/></w:tblCellMar>';
    private $floatStyle  = '<w:tblpPr w:tblpY="100" w:tblpX="8000" w:horzAnchor="page" w:vertAnchor="text" w:rightFromText="180" w:leftFromText="180"/><w:tblOverlap w:val="never"/>';

    public function __construct()
    {
        parent::__construct();
        $this->loadModel('report');
    }

    /**
     * Export to review report.
     *
     * @access public
     * @return void
     */
    public function exportReviewReport()
    {
        $this->init();
        $fileName = trim($this->post->fileName);
        if(empty($fileName)) $fileName = date('Ymd');
        $review     = $this->post->review;
        $headerName = isset($review->title) ? $review->title : $this->lang->review->report->common;
        $this->setChartDocProps($headerName);

        /* Add report title. */
        $this->addReportTitle($headerName);

        /* Draw tables. */
        $this->addReviewExplainTable();
        $this->addChartTextBreak();

        $this->columnWidth = 1000;
        $this->addOtherTable();

        /* Remove default images. */
        $defaultImage   = '';
        $contentTypeXML = file_get_contents($this->exportPath . '[Content_Types].xml');
        $contentTypeXML = str_replace('%defaultimage%', $defaultImage, $contentTypeXML);
        file_put_contents($this->exportPath . '[Content_Types].xml', $contentTypeXML);

        /* Remove images rels. */
        $imageRels    = '';
        $documentRels = file_get_contents($this->exportPath . 'word/_rels/document.xml.rels');
        $documentRels = str_replace('%image%', $imageRels, $documentRels);
        file_put_contents($this->exportPath . 'word/_rels/document.xml.rels', $documentRels);

        /* Generate document XML. */
        $documentXML = file_get_contents($this->exportPath . 'word/document.xml');
        $documentXML = str_replace('%wordContent%', $this->wordContent, $documentXML);
        file_put_contents($this->exportPath . 'word/document.xml', $documentXML);

        /* Zip to docx. */
        $tmpFileName = uniqid() . '.docx';
        helper::cd($this->exportPath);
        $files = array('[Content_Types].xml', '_rels', 'docProps', 'word', 'customXml');
        $zip   = new pclzip($tmpFileName);
        $zip->create($files);

        file_put_contents($this->app->getCacheRoot() . '/json2.txt', json_encode($this->post));
        $fileData = file_get_contents($this->exportPath . $tmpFileName);
        $this->zfile->removeDir($this->exportPath);
        $this->sendDownHeader($fileName . '.docx', 'docx', $fileData);

        return;
    }

    /**
     * Init for word.
     *
     * @access private
     * @return void
     */
    private function init()
    {
        $this->app->loadClass('pclzip', true);

        $this->wpPr      = "<w:pPr>{$this->wrPr}</w:pPr>";
        $this->zfile     = $this->app->loadClass('zfile');
        $this->relsID[6] = '';

        /* Init excel file. */
        $this->exportPath = $this->app->getCacheRoot() . $this->app->user->account . $this->post->kind . uniqid() . '/';
        if(is_dir($this->exportPath))$this->zfile->removeDir($this->exportPath);
        $this->zfile->mkdir($this->exportPath);
        $this->zfile->copyDir($this->app->getCoreLibRoot() . 'word', $this->exportPath);

        $this->kind = $this->post->kind;
        $this->host = common::getSysURL();
    }

    /**
     * Add report title.
     *
     * @param  string  $headerName
     * @access private
     * @return void
     */
    private function addReportTitle($headerName = '')
    {
        $this->addChartTitle($headerName, 2, 'center');
        $this->wordContent .= '<w:p><w:pPr><w:jc w:val="center"/>' . $this->wrPr . '</w:pPr>';

        if(trim($this->config->visions, ',') == 'lite')
        {
            $this->lang->report->exportNotice =  sprintf($this->lang->report->exportNotice, $this->lang->liteName);
        }
        else
        {
            foreach($this->lang->report->versionName as $edition => $name)
            {
                if(strpos($this->config->edition, $edition ) !== false) $this->lang->report->exportNotice = sprintf($this->lang->report->exportNotice, $name);
            }
        }
        $this->addChartText('(' . date(DT_DATETIME1) . ' ' . $this->app->user->realname . ' ' . $this->lang->report->exportNotice . ')', array('color' => 'CCCCCC'), true);
        $this->wordContent .= '</w:p>';
    }

    /**
     * Add header table.
     *
     * @param  string $style
     * @access private
     * @return void
     */
    private function addReviewExplainTable($style = 'block')
    {
        $this->columnWidth = 2250;
        $width             = $this->columnWidth * 4;
        $boldWrPr          = '<w:rPr><w:rFonts w:hint="eastAsia"/><w:lang w:val="en-US" w:eastAsia="zh-CN"/><w:b/><w:bCs/></w:rPr>';
        $review            = $this->post->review;
        $reviewerCount     = $this->post->reviewerCount;
        $consumed          = $this->post->consumed;
        $issues            = $this->post->issues;
        $objectScale       = $this->post->objectScale;
        $approvalNode      = $this->post->approvalNode;
        $issueCounts       = count($issues);

        $document  = '<w:tbl>';
        $document .= $this->tableDefine($width, 4, $style);

        /* Table row 1. */
        $document .= '<w:tr>';
        $document .= '<w:tc><w:tcPr><w:tcW w:w="' . $width * 4 . '" w:type="dxa"/><w:gridSpan w:val="4"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2"/></w:tcPr>';
        $document .= '<w:p w:rsidR="0000463B" w:rsidRDefault="0000463B" w:rsidP="0000463B"><w:pPr><w:spacing w:line="220" w:lineRule="atLeast"/><w:jc w:val="center"/></w:pPr>';
        $document .= '<w:r><w:rPr><w:rFonts w:hint="eastAsia"/><w:b/><w:bCs/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr>';
        $document .= "<w:t><![CDATA[{$this->lang->review->explain}]]></w:t>";
        $document .= '</w:r></w:p></w:tc>';
        $document .= '</w:tr>';

        /* Table row 2. */
        $document .= '<w:tr>';
        $document .=    $this->tableTrDefine();
        $document .=    $this->tableCell($width, $this->lang->review->object, false, 'restart', 1, $boldWrPr);
        $document .=    $this->tableCell($width, zget($this->lang->baseline->objectList, $review->category));
        $document .=    $this->tableCell($width, $this->lang->review->reviewerCount, false, 'restart', 1, $boldWrPr);
        $document .=    $this->tableCell($width, $reviewerCount);
        $document .= '</w:tr>';

        /* Table row 3. */
        $document .= '<w:tr>';
        $document .=    $this->tableTrDefine();
        $document .=    $this->tableCell($width, $this->lang->review->reviewedDate, false, 'restart', 1, $boldWrPr);
        $document .=    $this->tableCell($width, isset($approvalNode[0]->reviewedDate) && !helper::isZeroDate($approvalNode[0]->reviewedDate) ? $approvalNode[0]->reviewedDate : '');
        $document .=    $this->tableCell($width, $this->lang->review->reviewedHours, false, 'restart', 1, $boldWrPr);
        $document .=    $this->tableCell($width, floor($consumed));
        $document .= '</w:tr>';

        /* Table row 4. */
        $document .= '<w:tr>';
        $document .=    $this->tableTrDefine();
        $document .=    $this->tableCell($width, $this->lang->review->issueCount, false, 'restart', 1, $boldWrPr);
        $document .=    $this->tableCell($width, $issueCounts);
        $document .=    $this->tableCell($width, $this->lang->review->objectScale, false, 'restart', 1, $boldWrPr);
        $document .=    $this->tableCell($width, round($objectScale, 2));
        $document .= '</w:tr>';

        /* Table row 5. */
        $document .= '<w:tr>';
        $document .=    $this->tableTrDefine();
        $document .=    $this->tableCell($width, $this->lang->review->issueRate, false, 'restart', 1, $boldWrPr);
        $document .=    $this->tableCell($width, $objectScale == 0 ? 0 : round($issueCounts / $objectScale, 2));
        $document .=    $this->tableCell($width, $this->lang->review->issueFoundRate, false, 'restart', 1, $boldWrPr);
        $document .=    $this->tableCell($width, $consumed == 0 ? 0 : round($issueCounts / $consumed, 2));
        $document .= '</w:tr>';

        $document .= '</w:tbl>';

        $this->wordContent .= $document;
    }

    /**
     * Add other table.
     *
     * @param  string $style
     * @access private
     * @return void
     */
    private function addOtherTable($style = 'block')
    {
        $width = $this->columnWidth * 10;

        $document  = '<w:tbl>';
        $document .= $this->tableDefine($width, 10, $style);

        $document .= $this->getIssueTable($style);
        $document .= $this->getApprovalTable($style);
        $document .= $this->getResultTable($style);

        $document .= '</w:tbl>';

        $this->wordContent .= $document;
    }

    /**
     * Add issue table.
     *
     * @param  string $style
     * @access private
     * @return void
     */
    private function getIssueTable($style = 'block')
    {
        $issues = $this->post->issues;
        if(empty($issues)) return '';

        $users = $this->post->users;

        /* Table row 1. */
        $document = '<w:tr>';
        $document .= '<w:tc><w:tcPr><w:tcW w:w="' . $this->columnWidth . '" w:type="dxa"/><w:gridSpan w:val="1"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2"/></w:tcPr>';
        $document .= '<w:p w:rsidR="0000463B" w:rsidRDefault="0000463B" w:rsidP="0000463B"><w:pPr><w:spacing w:line="220" w:lineRule="atLeast"/><w:jc w:val="center"/></w:pPr>';
        $document .= '<w:r><w:rPr><w:rFonts w:hint="eastAsia"/><w:b/><w:bCs/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr>';
        $document .= "<w:t><![CDATA[{$this->lang->idAB}]]></w:t>";
        $document .= '</w:r></w:p></w:tc>';
        $document .= '<w:tc><w:tcPr><w:tcW w:w="' . $this->columnWidth * 2 . '" w:type="dxa"/><w:gridSpan w:val="2"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2"/></w:tcPr>';
        $document .= '<w:p w:rsidR="0000463B" w:rsidRDefault="0000463B" w:rsidP="0000463B"><w:pPr><w:spacing w:line="220" w:lineRule="atLeast"/><w:jc w:val="center"/></w:pPr>';
        $document .= '<w:r><w:rPr><w:rFonts w:hint="eastAsia"/><w:b/><w:bCs/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr>';
        $document .= "<w:t><![CDATA[{$this->lang->review->issues}]]></w:t>";
        $document .= '</w:r></w:p></w:tc>';
        $document .= '<w:tc><w:tcPr><w:tcW w:w="' . $this->columnWidth * 2 . '" w:type="dxa"/><w:gridSpan w:val="2"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2"/></w:tcPr>';
        $document .= '<w:p w:rsidR="0000463B" w:rsidRDefault="0000463B" w:rsidP="0000463B"><w:pPr><w:spacing w:line="220" w:lineRule="atLeast"/><w:jc w:val="center"/></w:pPr>';
        $document .= '<w:r><w:rPr><w:rFonts w:hint="eastAsia"/><w:b/><w:bCs/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr>';
        $document .= "<w:t><![CDATA[{$this->lang->review->reviewer}]]></w:t>";
        $document .= '</w:r></w:p></w:tc>';
        $document .= '<w:tc><w:tcPr><w:tcW w:w="' . $this->columnWidth * 5 . '" w:type="dxa"/><w:gridSpan w:val="5"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2"/></w:tcPr>';
        $document .= '<w:p w:rsidR="0000463B" w:rsidRDefault="0000463B" w:rsidP="0000463B"><w:pPr><w:spacing w:line="220" w:lineRule="atLeast"/><w:jc w:val="center"/></w:pPr>';
        $document .= '<w:r><w:rPr><w:rFonts w:hint="eastAsia"/><w:b/><w:bCs/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr>';
        $document .= "<w:t><![CDATA[{$this->lang->comment}]]></w:t>";
        $document .= '</w:r></w:p></w:tc>';
        $document .= '</w:tr>';

        /* Table rows. */
        foreach($issues as $issue)
        {
            $document .= '<w:tr>';
            $document .=    $this->tableTrDefine();
            $document .=    $this->tableCell($this->columnWidth, $issue->id);
            $document .=    $this->tableCell($this->columnWidth * 2, $issue->title, false, '', 2);
            $document .=    $this->tableCell($this->columnWidth * 2, zget($users, $issue->createdBy), false, '', 2);
            $document .=    $this->tableCell($this->columnWidth * 5, strip_tags($issue->opinion), false, '', 5);
            $document .= '</w:tr>';
        }

        return $document;
    }

    /**
     * Add approval table.
     *
     * @param  string $style
     * @access private
     * @return void
     */
    private function getApprovalTable($style = 'block')
    {
        $approvalNode = $this->post->approvalNode;
        if(empty($approvalNode)) return '';

        $users           = $this->post->users;
        $accountConsumed = $this->post->accountConsumed;

        /* Table row 1. */
        $document = '<w:tr>';
        $document .= '<w:tc><w:tcPr><w:tcW w:w="' . $this->columnWidth . '" w:type="dxa"/><w:gridSpan w:val="1"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2"/></w:tcPr>';
        $document .= '<w:p w:rsidR="0000463B" w:rsidRDefault="0000463B" w:rsidP="0000463B"><w:pPr><w:spacing w:line="220" w:lineRule="atLeast"/><w:jc w:val="center"/></w:pPr>';
        $document .= '<w:r><w:rPr><w:rFonts w:hint="eastAsia"/><w:b/><w:bCs/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr>';
        $document .= "<w:t><![CDATA[{$this->lang->review->reviewedDate}]]></w:t>";
        $document .= '</w:r></w:p></w:tc>';
        $document .= '<w:tc><w:tcPr><w:tcW w:w="' . $this->columnWidth . '" w:type="dxa"/><w:gridSpan w:val="1"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2"/></w:tcPr>';
        $document .= '<w:p w:rsidR="0000463B" w:rsidRDefault="0000463B" w:rsidP="0000463B"><w:pPr><w:spacing w:line="220" w:lineRule="atLeast"/><w:jc w:val="center"/></w:pPr>';
        $document .= '<w:r><w:rPr><w:rFonts w:hint="eastAsia"/><w:b/><w:bCs/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr>';
        $document .= "<w:t><![CDATA[{$this->lang->review->reviewer}]]></w:t>";
        $document .= '</w:r></w:p></w:tc>';
        $document .= '<w:tc><w:tcPr><w:tcW w:w="' . $this->columnWidth * 2 . '" w:type="dxa"/><w:gridSpan w:val="2"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2"/></w:tcPr>';
        $document .= '<w:p w:rsidR="0000463B" w:rsidRDefault="0000463B" w:rsidP="0000463B"><w:pPr><w:spacing w:line="220" w:lineRule="atLeast"/><w:jc w:val="center"/></w:pPr>';
        $document .= '<w:r><w:rPr><w:rFonts w:hint="eastAsia"/><w:b/><w:bCs/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr>';
        $document .= "<w:t><![CDATA[{$this->lang->review->reviewResult}]]></w:t>";
        $document .= '</w:r></w:p></w:tc>';
        $document .= '<w:tc><w:tcPr><w:tcW w:w="' . $this->columnWidth . '" w:type="dxa"/><w:gridSpan w:val="1"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2"/></w:tcPr>';
        $document .= '<w:p w:rsidR="0000463B" w:rsidRDefault="0000463B" w:rsidP="0000463B"><w:pPr><w:spacing w:line="220" w:lineRule="atLeast"/><w:jc w:val="center"/></w:pPr>';
        $document .= '<w:r><w:rPr><w:rFonts w:hint="eastAsia"/><w:b/><w:bCs/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr>';
        $document .= "<w:t><![CDATA[{$this->lang->review->consumed}]]></w:t>";
        $document .= '</w:r></w:p></w:tc>';
        $document .= '<w:tc><w:tcPr><w:tcW w:w="' . $this->columnWidth * 5 . '" w:type="dxa"/><w:gridSpan w:val="5"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2"/></w:tcPr>';
        $document .= '<w:p w:rsidR="0000463B" w:rsidRDefault="0000463B" w:rsidP="0000463B"><w:pPr><w:spacing w:line="220" w:lineRule="atLeast"/><w:jc w:val="center"/></w:pPr>';
        $document .= '<w:r><w:rPr><w:rFonts w:hint="eastAsia"/><w:b/><w:bCs/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr>';
        $document .= "<w:t><![CDATA[{$this->lang->review->finalOpinion}]]></w:t>";
        $document .= '</w:r></w:p></w:tc>';
        $document .= '</w:tr>';

        /* Table rows. */
        foreach($approvalNode as $reviewItem)
        {
            $document .= '<w:tr>';
            $document .=    $this->tableTrDefine();
            $document .=    $this->tableCell($this->columnWidth, !helper::isZeroDate($reviewItem->reviewedDate) ? $reviewItem->reviewedDate : '');
            $document .=    $this->tableCell($this->columnWidth, zget($users, $reviewItem->reviewedBy));
            $document .=    $this->tableCell($this->columnWidth * 2, zget($this->lang->review->resultList, $reviewItem->result), false, '', 2);
            $document .=    $this->tableCell($this->columnWidth, !empty($accountConsumed[$reviewItem->reviewedBy]) && $reviewItem->result != 'ignore' ? array_shift($accountConsumed[$reviewItem->reviewedBy]) : 0);
            $document .=    $this->tableCell($this->columnWidth * 5, strip_tags($reviewItem->opinion), false, '', 5);
            $document .= '</w:tr>';
        }

        return $document;
    }

    /**
     * Add result table.
     *
     * @param  string $style
     * @access private
     * @return void
     */
    private function getResultTable($style = 'block')
    {
        $boldWrPr       = '<w:rPr><w:rFonts w:hint="eastAsia"/><w:lang w:val="en-US" w:eastAsia="zh-CN"/><w:b/><w:bCs/></w:rPr>';
        $review         = $this->post->review;
        $approval       = $this->post->approval;
        $users          = $this->post->users;
        $reviewer       = $this->post->reviewer;
        $reviewResult   = isset($approval->result) ? $approval->result : ($review->result == 'pass' ? 'pass' : 'fail');
        $reportReviwers = '';
        if(is_array($reviewer))
        {
            foreach($reviewer as $account) $reportReviwers .= zget($users, $account) . ' ';
        }

        /* Table row 1. */
        $document = '<w:tr>';
        $document .= '<w:tc><w:tcPr><w:tcW w:w="' . $this->columnWidth * 8 . '" w:type="dxa"/><w:gridSpan w:val="8"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2"/></w:tcPr>';
        $document .= '<w:p w:rsidR="0000463B" w:rsidRDefault="0000463B" w:rsidP="0000463B"><w:pPr><w:spacing w:line="220" w:lineRule="atLeast"/><w:jc w:val="center"/></w:pPr>';
        $document .= '<w:r><w:rPr><w:rFonts w:hint="eastAsia"/><w:b/><w:bCs/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr>';
        $document .= "<w:t><![CDATA[{$this->lang->review->resultExplain}]]></w:t>";
        $document .= '</w:r></w:p></w:tc>';
        $document .= '<w:tc><w:tcPr><w:tcW w:w="' . $this->columnWidth * 2 . '" w:type="dxa"/><w:gridSpan w:val="2"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2"/></w:tcPr>';
        $document .= '<w:p w:rsidR="0000463B" w:rsidRDefault="0000463B" w:rsidP="0000463B"><w:pPr><w:spacing w:line="220" w:lineRule="atLeast"/><w:jc w:val="center"/></w:pPr>';
        $document .= '<w:r><w:rPr><w:rFonts w:hint="eastAsia"/><w:b/><w:bCs/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr>';
        $document .= "<w:t><![CDATA[{$this->lang->review->conclusion}]]></w:t>";
        $document .= '</w:r></w:p></w:tc>';
        $document .= '</w:tr>';

        /* Table row 2. */
        $document .= '<w:tr>';
        $document .=    $this->tableTrDefine();
        $document .=    $this->tableCell($this->columnWidth * 8, $this->lang->review->resultExplainList['pass'], false, '', 9);
        $document .=    $this->tableCell($this->columnWidth * 2, zget($this->lang->review->resultList, $reviewResult), true, 'restart');
        $document .= '</w:tr>';

        /* Table row 3. */
        $document .= '<w:tr>';
        $document .=    $this->tableTrDefine();
        $document .=    $this->tableCell($this->columnWidth * 8, $this->lang->review->resultExplainList['fail'], false, '', 9);
        $document .=    $this->tableCell($this->columnWidth * 2, zget($this->lang->review->resultList, $reviewResult), true, '');
        $document .= '</w:tr>';

        /* Table row 4. */
        $document .= '<w:tr>';
        $document .=    $this->tableTrDefine();
        $document .=    $this->tableCell($this->columnWidth * 3, $this->lang->review->reportCreatedBy, false, '', 3, $boldWrPr);
        $document .=    $this->tableCell($this->columnWidth * 2, zget($users, $review->createdBy), false, '', 2);
        $document .=    $this->tableCell($this->columnWidth * 3, $this->lang->review->reportApprovedBy, false, '', 3, $boldWrPr);
        $document .=    $this->tableCell($this->columnWidth * 2, $reportReviwers, false, '', 2);
        $document .= '</w:tr>';

        return $document;
    }

    /**
     * Add title.
     *
     * @param  sting  $text
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
     * Add text break.
     *
     * @param  int     $num
     * @access private
     * @return void
     */
    private function addChartTextBreak($num = 1)
    {
        $document = '';
        for($i = 0; $i < $num; $i++) $document .= '<w:p>' . $this->wpPr . '</w:p>';
        $this->wordContent .= $document;
    }

    /**
     * Transform style.
     *
     * @param  array   $styles
     * @access private
     * @return void
     */
    private function transformChartStyle($styles = array())
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
     * Set doc props.
     *
     * @param  string  $header
     * @access private
     * @return void
     */
    private function setChartDocProps($header)
    {
        $title      = $header ? $header : $this->post->kind;
        $coreFile   = file_get_contents($this->exportPath . 'docProps/core.xml');
        $createDate = date('Y-m-d') . 'T' . date('H:i:s') . 'Z';
        $account    = $this->app->user->account;
        $coreFile   = sprintf($coreFile, $createDate, $account, $account, $createDate, $title);

        file_put_contents($this->exportPath . 'docProps/core.xml', $coreFile);
    }

    /**
     * Generate table cell.
     *
     * @param  int     $width
     * @param  string  $content
     * @param  bool    $vMerge
     * @param  string  $vMergeVal
     * @param  int     $gridSpan
     * @param  string  $wrPr
     * @access private
     * @return string
     */
    private function tableCell($width = 2000, $content = '', $vMerge = false, $vMergeVal = 'restart', $gridSpan = 1, $wrPr = '')
    {
        $tcPr = '<w:vMerge w:val="' . $vMergeVal . '"/>';

        if(!$vMerge)
        {
            $tcPr = '<w:tcW w:w="' . $width . '" w:type="dxa"/>
                <w:gridSpan w:val="%d"/>';
            $tcPr = sprintf($tcPr, $gridSpan);
        }

        $tc = '<w:tc>
            <w:tcPr>' .
                        $tcPr .
                    '</w:tcPr>
                    <w:p>' .
                        $this->wpPr .
                        '<w:r w:rsidRPr="00C8127A">' .
                             (!empty($wrPr) ? $wrPr : $this->wrPr) .
                            '<w:t>' . $content . '</w:t>
                        </w:r>
                    </w:p>
                </w:tc>';

        return $tc;
    }

    /**
     * Table define.
     *
     * @param  int    $width
     * @param  int    $columnCount
     * @param  string $style
     * @access private
     * @return string
     */
    private function tableDefine($width, $columnCount, $style = 'block')
    {
        $columns = '';
        for($i = 0; $i < $columnCount; $i++)
        {
            $columns .= '<w:gridCol w:w="' . $this->columnWidth . '"/>';
        }

        $document = '
            <w:tblPr>
                <w:tblStyle w:val="9"/>' . ($style == 'float' ? $this->floatStyle : '') . '
                <w:tblW w:w="' . $width . '" w:type="dxa"/>
                <w:jc w:val="center"/>' . $this->tblBorders . $this->tblLayout . $this->tblCellMar .
            '</w:tblPr>
            <w:tblGrid>' .
                $columns .
            '</w:tblGrid>';

        return $document;
    }

    /**
     * Table TR style define.
     *
     * @access private
     * @return string
     */
    private function tableTrDefine()
    {
        return '<w:tblPrEx>' . $this->tblBorders . $this->tblLayout . $this->tblCellMar . '</w:tblPrEx>';
    }
}
