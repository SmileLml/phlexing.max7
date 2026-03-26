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
    public $boldWrPr      = '<w:rPr><w:rFonts w:hint="eastAsia"/><w:lang w:val="en-US" w:eastAsia="zh-CN"/><w:b/><w:bCs/></w:rPr>';

    private $columnWidth = 800;
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
     * Export to cm report.
     *
     * @access public
     * @return void
     */
    public function exportCMReport()
    {
        $this->init();
        $fileName = trim($this->post->fileName);
        if(empty($fileName)) $fileName = date('Ymd');

        $headerName = sprintf($this->lang->cm->exportCM, $fileName);
        $this->setChartDocProps($headerName);

        /* Add report title. */
        $this->addReportTitle($headerName);

        /* Draw tables. */
        $this->addReportTable();
        $this->addDescTable();

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

    /**
     * Add text.
     *
     * @param  int    $text
     * @param  array  $styles
     * @param  bool   $inline
     * @access public
     * @return void
     */
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

    /**
     * Generate table cell.
     *
     * @param  int     $width
     * @param  string  $content
     * @param  bool    $vMerge
     * @param  string  $vMergeVal
     * @param  int     $gridSpan
     * @access private
     * @return string
     */
    private function tableCell($width = 2000, $content = '', $vMerge = false, $vMergeVal = 'restart', $gridSpan = 1, $isStrong = false)
    {
        $tcPr = '<w:vMerge w:val="' . $vMergeVal . '"/>
            <w:gridSpan w:val="%d"/>';

        if(!$vMerge)
        {
            $tcPr = '<w:tcW w:w="' . $width . '" w:type="dxa"/>
                <w:gridSpan w:val="%d"/>';
        }
        $tcPr = sprintf($tcPr, $gridSpan);

        $tc = '<w:tc>
                    <w:tcPr>' .
                        $tcPr .
                    '</w:tcPr>
                    <w:p>' .
                        $this->wpPr .
                        '<w:r>' .
                            ($isStrong ? $this->boldWrPr  : $this->wrPr) .
                            '<w:t>' . $content . '</w:t>
                        </w:r>
                    </w:p>
                </w:tc>';

        return $tc;
    }

    /**
     * Add baseline report.
     *
     * @access private
     * @return void
     */
    private function addReportTable()
    {
        $this->app->loadLang('project');
        $this->addChartTitle($this->lang->cm->baselineReport, 3, 'center');

        $report = $this->post->report;
        $users  = $this->post->users;
        $width  = $this->columnWidth;

        $document  = '<w:tbl>';
        $document .= $this->tableDefine($width * 13, 13);

        /* Table row 1. */
        $document .= '<w:tr>';
        $document .=     $this->tableTrDefine();
        $document .=     $this->tableCell($width, $this->lang->cm->projectID,                                       false, '', 1, true);
        $document .=     $this->tableCell($width, $report['project']->id,                                           false, '', 3);
        $document .=     $this->tableCell($width, $this->lang->cm->release,                                         false, '', 1, true);
        $document .=     $this->tableCell($width, zget($this->lang->project->modelList, $report['project']->model), false, '', 4);
        $document .=     $this->tableCell($width, $this->lang->cm->approver,                                        false, '', 1, true);
        $document .=     $this->tableCell($width, '',                                                               false, '', 3);
        $document .= '</w:tr>';

        /* Table row 2. */
        $document .= '<w:tr>';
        $document .=     $this->tableTrDefine();
        $document .=     $this->tableCell($width, $this->lang->cm->projectName,         false, '', 1, true);
        $document .=     $this->tableCell($width, $report['project']->name,             false, '', 8);
        $document .=     $this->tableCell($width, $this->lang->cm->compiling,           false, '', 1, true);
        $document .=     $this->tableCell($width, zget($users, $report['project']->PM), false, '', 3, true);
        $document .= '</w:tr>';

        $document .= '</w:tbl>';

        $this->wordContent .= $document;
    }

    /**
     * Add description table.
     *
     * @access private
     * @return void
     */
    private function addDescTable()
    {
        $this->app->loadLang('baseline');
        $this->app->loadLang('reviewissue');

        $this->addChartTitle($this->lang->cm->changeDesc, 3, 'center');

        $report = $this->post->report;
        $users  = $this->post->users;
        $width  = $this->columnWidth;

        $document  = '<w:tbl>';
        $document .= $this->tableDefine($width * 13, 13);

        /* Build audit list title. */
        $document .= '<w:tr>';
        $document .=     $this->tableTrDefine();
        $document .=     $this->tableCell($width, $this->lang->cm->baselineItem,   false, '', 2, true);
        $document .=     $this->tableCell($width, $this->lang->cm->configItemName, false, '', 2, true);
        $document .=     $this->tableCell($width, $this->lang->cm->configIdentify, false, '', 3, true);
        $document .=     $this->tableCell($width, $this->lang->cm->currentVersion, false, '', 3, true);
        $document .=     $this->tableCell($width, $this->lang->cm->releaseDate,    false, '', 2, true);
        $document .=     $this->tableCell($width, $this->lang->cm->publisher,      false, '', 1, true);
        $document .= '</w:tr>';

        /* Build audit list data. */
        $auditCount = count($report['audit']);
        foreach($report['audit'] as $type => $item)
        {
            $rowspan   = count($item);
            $itemIndex = 1;
            foreach($item as $audit)
            {
                $document .= '<w:tr>';
                $document .=     $this->tableTrDefine();
                if($rowspan) $document .= $this->tableCell($width, $this->lang->cm->$type, true, $itemIndex == 1 ? 'restart' : 'continue', 2);

                $document .=     $this->tableCell($width, zget($this->lang->baseline->objectList, $audit->category),           false, '', 2);
                $document .=     $this->tableCell($width, $audit->title,                                                       false, '', 3);
                $document .=     $this->tableCell($width, $audit->version,                                                     false, '', 3);
                $document .=     $this->tableCell($width, !helper::isZeroDate($audit->createdDate) ? $audit->createdDate : '', false, '', 2);
                $document .=     $this->tableCell($width, zget($users, $audit->createdBy),                                     false, '', 1);
                $document .= '</w:tr>';

                $itemIndex ++;
            }
        }

        /* Build issue list title. */
        $document .= '<w:tr>';
        $document .=     $this->tableTrDefine();
        $document .=     $this->tableCell($width, $this->lang->cm->configAdmin,    false, '', 8, true);
        $document .=     $this->tableCell($width, $this->lang->cm->solutionMan,    false, '', 4, true);
        $document .=     $this->tableCell($width, $this->lang->cm->confManagement, false, '', 1, true);
        $document .= '</w:tr>';

        $document .= '<w:tr>';
        $document .=     $this->tableTrDefine();
        $document .=     $this->tableCell($width, $this->lang->cm->issueID,        false, '', 1, true);
        $document .=     $this->tableCell($width, $this->lang->cm->issueDesc,      false, '', 2, true);
        $document .=     $this->tableCell($width, $this->lang->cm->baselineAudit,  false, '', 2, true);
        $document .=     $this->tableCell($width, $this->lang->cm->discoveryDate,  false, '', 1, true);
        $document .=     $this->tableCell($width, $this->lang->cm->personLiable,   false, '', 1, true);
        $document .=     $this->tableCell($width, $this->lang->cm->proposedScheme, false, '', 1, true);
        $document .=     $this->tableCell($width, $this->lang->cm->proposedDate,   false, '', 1, true);
        $document .=     $this->tableCell($width, $this->lang->cm->solutionResult, false, '', 1, true);
        $document .=     $this->tableCell($width, $this->lang->cm->resolvingDate,  false, '', 1, true);
        $document .=     $this->tableCell($width, $this->lang->cm->resolvingBy,    false, '', 1, true);
        $document .=     $this->tableCell($width, $this->lang->cm->currentState,   false, '', 1, true);
        $document .= '</w:tr>';

        /* Build audit list data. */
        foreach($report['issue'] as $issue)
        {
            $document .= '<w:tr>';
            $document .=     $this->tableTrDefine();

            $document .=     $this->tableCell($width, $issue->id,                                                                false, '', 1);
            $document .=     $this->tableCell($width, $issue->title,                                                             false, '', 2);
            $document .=     $this->tableCell($width, $issue->reviewTitle,                                                       false, '', 2);
            $document .=     $this->tableCell($width, !helper::isZeroDate($issue->createdDate) ? $issue->createdDate : '',       false, '', 1);
            $document .=     $this->tableCell($width, zget($users, $issue->createdBy),                                           false, '', 1);
            $document .=     $this->tableCell($width, $issue->opinion,                                                           false, '', 1);
            $document .=     $this->tableCell($width, !helper::isZeroDate($issue->opinionDate) ? $issue->opinionDate : '',       false, '', 1);
            $document .=     $this->tableCell($width, zget($this->lang->reviewissue->resolutionList, $issue->resolution),        false, '', 1);
            $document .=     $this->tableCell($width, !helper::isZeroDate($issue->resolutionDate) ? $issue->resolutionDate : '', false, '', 1);
            $document .=     $this->tableCell($width, zget($users, $issue->resolutionBy),                                        false, '', 1);
            $document .=     $this->tableCell($width, zget($this->lang->reviewissue->statusList, $issue->status),                false, '', 1);
            $document .= '</w:tr>';
        }

        $document .= '</w:tbl>';

        $this->wordContent .= $document;
    }
}
