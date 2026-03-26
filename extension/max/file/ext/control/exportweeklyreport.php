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
     * Export to weekly report.
     *
     * @access public
     * @return void
     */
    public function exportweeklyreport()
    {
        $this->init();
        $fileName = trim($this->post->fileName);
        if(empty($fileName)) $fileName = date('Ymd');
        $headerName = sprintf($this->lang->weekly->exportWeekly, $fileName);
        $this->setChartDocProps($headerName);

        /* Add report title. */
        $this->addReportTitle($headerName);

        /* Draw tables. */
        $this->addHeaderTable();
        $this->addChartTextBreak();
        $this->addSummaryTable();
        $this->addChartTextBreak();
        $this->addFinishedTable();
        $this->addChartTextBreak();
        $this->addPostponedTable();
        $this->addChartTextBreak();
        $this->addNextWeekTable();
        $this->addChartTextBreak();
        $this->addWorkloadTable();

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
    private function addHeaderTable($style = 'block')
    {
        $this->columnWidth = 2250;
        $width             = $this->columnWidth * 4;
        $data              = $this->post->data;

        $document  = '<w:tbl>';
        $document .= $this->tableDefine($width, 4, $style);

        /* Table row 1. */
        $document .= '<w:tr>';
        $document .=    $this->tableTrDefine();
        $document .=    $this->tableCell($width, $this->lang->weekly->term);
        $document .=    $this->tableCell($width, $data->monday . '~' . $data->lastDay);
        $document .=    $this->tableCell($width, $this->lang->weekly->master);
        $document .=    $this->tableCell($width, $data->master);
        $document .= '</w:tr>';

        /* Table row 2. */
        $document .= '<w:tr>';
        $document .=    $this->tableTrDefine();
        $document .=    $this->tableCell($width, $this->lang->weekly->project);
        $document .=    $this->tableCell($width, $data->project->name);
        $document .=    $this->tableCell($width, $this->lang->weekly->staff);
        $document .=    $this->tableCell($width, $data->staff);
        $document .= '</w:tr>';

        $document .= '</w:tbl>';

        $this->wordContent .= $document;
    }

    /**
     * Add summary table.
     *
     * @param  string  $style
     * @access private
     * @return void
     */
    private function addSummaryTable($style = 'block')
    {
        $this->addChartTitle($this->lang->weekly->summary, 3, 'center');

        $data  = $this->post->data;
        $width = $this->columnWidth * 4;

        $document  = '<w:tbl>';
        $document .= $this->tableDefine($width, 4, $style);

        /* Table row 1. */
        $document .= '<w:tr>';
        $document .=     $this->tableTrDefine();
        $document .=     $this->tableCell($width, $this->lang->weekly->progress);
        $document .=     $this->tableCell($width, '');
        $document .=     $this->tableCell($width, $this->lang->weekly->analysisResult);
        $document .=     $this->tableCell($width, '');
        $document .= '</w:tr>';

        /* Table row 2. */
        $document .= '<w:tr>';
        $document .=     $this->tableTrDefine();
        $document .=     $this->tableCell($width, $this->lang->weekly->pv);
        $document .=     $this->tableCell($width, $data->pv);
        $document .=     $this->tableCell($width, $this->lang->weekly->progress, true);
        $document .=     $this->tableCell($width, $data->progress, true);
        $document .= '</w:tr>';

        /* Table row 3. */
        $document .= '<w:tr>';
        $document .=     $this->tableTrDefine();
        $document .=     $this->tableCell($width, $this->lang->weekly->ev);
        $document .=     $this->tableCell($width, $data->ev);
        $document .=     $this->tableCell($width, '', true, 'continue');
        $document .=     $this->tableCell($width, '', true, 'continue');
        $document .= '</w:tr>';

        /* Table row 4. */
        $document .= '<w:tr>';
        $document .=     $this->tableTrDefine();
        $document .=     $this->tableCell($width, $this->lang->weekly->ac);
        $document .=     $this->tableCell($width, $data->ac);
        $document .=     $this->tableCell($width, '', true, 'continue');
        $document .=     $this->tableCell($width, '', true, 'continue');
        $document .= '</w:tr>';

        /* Table row 5. */
        $document .= '<w:tr>';
        $document .=     $this->tableTrDefine();
        $document .=     $this->tableCell($width, $this->lang->weekly->sv);
        $document .=     $this->tableCell($width, $data->sv ? $data->sv . '%' : '');
        $document .=     $this->tableCell($width, '', true, 'continue');
        $document .=     $this->tableCell($width, '', true, 'continue');
        $document .= '</w:tr>';

        /* Table row 6. */
        $budget      = $this->loadModel('workestimation')->getBudget($data->project->id);
        $projectCost = empty($budget) ? zget($this->config->custom, 'cost', 1) : $budget->unitLaborCost;

        $document .= '<w:tr>';
        $document .=     $this->tableTrDefine();
        $document .=     $this->tableCell($width, $this->lang->weekly->cv);
        $document .=     $this->tableCell($width, $data->cv ? $data->cv . '%' : '');
        $document .=     $this->tableCell($width, $this->lang->weekly->cost);
        $document .=     $this->tableCell($width, empty($projectCost) ? 0 : $data->ac * $projectCost);
        $document .= '</w:tr>';

        $document .= '</w:tbl>';

        $this->wordContent .= $document;
    }

    /**
     * Add finished tasks table.
     *
     * @param  string  $style
     * @access private
     * @return void
     */
    private function addFinishedTable($style = 'block')
    {
        $this->addChartTitle($this->lang->weekly->finished, 3, 'center');

        $this->columnWidth = 1500;
        $width             = $this->columnWidth * 6;

        $document  = '<w:tbl>';
        $document .= $this->tableDefine($width, 6, $style);

        /* Table header. */
        $document .= '<w:tr>';
        $document .=     $this->tableTrDefine();
        $document .=     $this->tableCell($width, $this->lang->idAB);
        $document .=     $this->tableCell($width * 3, $this->lang->task->name);
        $document .=     $this->tableCell($width, $this->lang->task->estStarted);
        $document .=     $this->tableCell($width, $this->lang->task->deadline);
        $document .=     $this->tableCell($width, $this->lang->task->realStarted);
        $document .=     $this->tableCell($width, $this->lang->task->finishedBy);
        $document .= '</w:tr>';

        /* Tasks list. */
        $finished = $this->post->data->finished;
        foreach($finished as $task)
        {
            $realStarted = $task->realStarted;
            if(!helper::isZeroDate($task->realStarted)) $realStarted = substr($task->realStarted, 0, 11);

            $document .= '<w:tr>';
            $document .=     $this->tableTrDefine();
            $document .=     $this->tableCell($width, $task->id);
            $document .=     $this->tableCell($width * 3, $task->name);
            $document .=     $this->tableCell($width, $task->estStarted);
            $document .=     $this->tableCell($width, $task->deadline);
            $document .=     $this->tableCell($width, $realStarted);
            $document .=     $this->tableCell($width, zget($this->post->data->users, $task->finishedBy));
            $document .= '</w:tr>';
        }

        /* Table footer. */
        $document .= '<w:tr>';
        $document .=     $this->tableTrDefine();
        $document .=     $this->tableCell($width, sprintf($this->lang->weekly->totalCount, count($finished)), false, '', 6);
        $document .= '</w:tr>';

        $document .= '</w:tbl>';

        $this->wordContent .= $document;
    }

    /**
     * Add postponed tasks table.
     *
     * @param  string  $style
     * @access private
     * @return void
     */
    private function addPostponedTable($style = 'block')
    {
        $this->addChartTitle($this->lang->weekly->postponed, 3, 'center');

        $this->columnWidth = 1300;
        $width             = $this->columnWidth * 7;

        $document  = '<w:tbl>';
        $document .= $this->tableDefine($width, 7, $style);

        /* Table header. */
        $document .= '<w:tr>';
        $document .=     $this->tableTrDefine();
        $document .=     $this->tableCell($width, $this->lang->idAB);
        $document .=     $this->tableCell($width * 3, $this->lang->task->name);
        $document .=     $this->tableCell($width, $this->lang->task->assignedTo);
        $document .=     $this->tableCell($width, $this->lang->task->estStarted);
        $document .=     $this->tableCell($width, $this->lang->task->deadline);
        $document .=     $this->tableCell($width, $this->lang->task->realStarted);
        $document .=     $this->tableCell($width, $this->lang->task->progress);
        $document .= '</w:tr>';

        /* Tasks list. */
        $postponed = $this->post->data->postponed;
        foreach($postponed as $task)
        {
            $realStarted = $task->realStarted;
            if(!helper::isZeroDate($task->realStarted)) $realStarted = substr($task->realStarted, 0, 11);

            $document .= '<w:tr>';
            $document .=     $this->tableTrDefine();
            $document .=     $this->tableCell($width, $task->id);
            $document .=     $this->tableCell($width * 3, $task->name);
            $document .=     $this->tableCell($width, zget($this->post->data->users, $task->assignedTo));
            $document .=     $this->tableCell($width, $task->estStarted);
            $document .=     $this->tableCell($width, $task->deadline);
            $document .=     $this->tableCell($width, $realStarted);
            $document .=     $this->tableCell($width, $task->progress . '%');
            $document .= '</w:tr>';
        }

        /* Table footer. */
        $document .= '<w:tr>';
        $document .=     $this->tableTrDefine();
        $document .=     $this->tableCell($width, sprintf($this->lang->weekly->totalCount, count($postponed)), false, '', 7);
        $document .= '</w:tr>';

        $document .= '</w:tbl>';

        $this->wordContent .= $document;
    }

    /**
     * Add next week tasks table.
     *
     * @param  string  $style
     * @access private
     * @return void
     */
    private function addNextWeekTable($style = 'block')
    {
        $this->addChartTitle($this->lang->weekly->nextWeek, 3, 'center');

        $this->columnWidth = 1800;
        $width             = $this->columnWidth * 5;

        $document  = '<w:tbl>';
        $document .= $this->tableDefine($width, 5, $style);

        /* Table header. */
        $document .= '<w:tr>';
        $document .=     $this->tableTrDefine();
        $document .=     $this->tableCell($width, $this->lang->idAB);
        $document .=     $this->tableCell($width * 3, $this->lang->task->name);
        $document .=     $this->tableCell($width, $this->lang->task->assignedTo);
        $document .=     $this->tableCell($width, $this->lang->task->estStarted);
        $document .=     $this->tableCell($width, $this->lang->task->deadline);
        $document .= '</w:tr>';

        /* Tasks list. */
        $nextWeek = $this->post->data->nextWeek;
        foreach($nextWeek as $task)
        {
            $document .= '<w:tr>';
            $document .=     $this->tableTrDefine();
            $document .=     $this->tableCell($width, $task->id);
            $document .=     $this->tableCell($width * 3, $task->name);
            $document .=     $this->tableCell($width, zget($this->post->data->users, $task->assignedTo));
            $document .=     $this->tableCell($width, $task->estStarted);
            $document .=     $this->tableCell($width, $task->deadline);
            $document .= '</w:tr>';
        }

        /* Table footer. */
        $document .= '<w:tr>';
        $document .=     $this->tableTrDefine();
        $document .=     $this->tableCell($width, sprintf($this->lang->weekly->totalCount, count($nextWeek)), false, '', 5);
        $document .= '</w:tr>';

        $document .= '</w:tbl>';

        $this->wordContent .= $document;
    }

    /**
     * Add workload table.
     *
     * @param  string  $style
     * @access private
     * @return void
     */
    private function addWorkloadTable($style = 'block')
    {
        $this->addChartTitle($this->lang->weekly->workloadByType, 3, 'center');

        $this->columnWidth = 825;
        $width             = $this->columnWidth * 11;

        $document  = '<w:tbl>';
        $document .= $this->tableDefine($width, 11, $style);

        /* Table header. */
        $document .= '<w:tr>';
        $document .=     $this->tableTrDefine();
        $document .=     $this->tableCell($width, $this->lang->task->type);
        foreach($this->lang->task->typeList as $type => $name)
        {
            if(empty($name)) continue;
            $document .= $this->tableCell($width, $name);
        }
        $document .=     $this->tableCell($width, $this->lang->weekly->total);
        $document .= '</w:tr>';

        /* Workload. */
        $document .= '<w:tr>';
        $document .=    $this->tableTrDefine();
        $document .=    $this->tableCell($width, $this->lang->weekly->workload);
        $total = 0;
        foreach($this->lang->task->typeList as $type => $name)
        {
            if(empty($name)) continue;

            $worktimes = zget($this->post->data->workload, $type, 0);
            $total += $worktimes;
            $document .= $this->tableCell($width, $worktimes);
        }
        $document .=     $this->tableCell($width, $total);
        $document .= '</w:tr>';

        $document .= '</w:tbl>';

        $this->wordContent .= $document;
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
     * @access private
     * @return string
     */
    private function tableCell($width = 2000, $content = '', $vMerge = false, $vMergeVal = 'restart', $gridSpan = 1)
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
                        '<w:r>' .
                            $this->wrPr .
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
