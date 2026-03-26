<?php
/**
 * The model file of marketreport module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hu Fangzhou<hufangzhou@easycorp.ltd>
 * @package     marketreport
 * @link        https://www.zentao.net
 */
class marketreportModel extends Model
{
    /**
     * create a report.
     *
     * @param  int $markekID
     * @access public
     * @return int|false
     */
    public function create($marketID = 0)
    {
        $now        = helper::now();
        $marketName = $this->post->marketName;

        $report = fixer::input('post')
            ->add('openedBy', $this->app->user->account)
            ->add('openedDate', $now)
            ->remove('uid')
            ->remove('labels')
            ->remove('newMarket')
            ->remove('marketName')
            ->setDefault('owner', '')
            ->setDefault('participants', '')
            ->setDefault('market', $marketID)
            ->setDefault('research', 0)
            ->setIF($this->post->status == 'published', 'publishedBy', $this->app->user->account)
            ->setIF($this->post->status == 'published', 'publishedDate', $now)
            ->setIF($this->post->status == 'draft', 'publishedBy', '')
            ->setIF($this->post->status == 'draft', 'publishedDate', null)
            ->setIF($this->post->source == 'outside', 'owner', '')
            ->setIF($this->post->source == 'outside', 'participants', '')
            ->setIF($this->post->source == 'outside', 'research', 0)
            ->join('participants', ',')
            ->stripTags($this->config->marketreport->editor->create['id'], $this->config->allowedTags)
            ->get();

        $report->participants = ',' . $report->participants . ',';

        $this->dao->insert(TABLE_MARKETREPORT)->data($report)
            ->batchCheck($this->config->marketreport->create->requiredFields, 'notempty')->exec();

        if(!dao::isError())
        {
            $reportID = $this->dao->lastInsertID();

            $this->loadModel('file')->updateObjectID($this->post->uid, $reportID, 'marketreport');
            $this->file->saveUpload('marketreport', $reportID);

            if($marketName)
            {
                $marketID = $this->loadModel('market')->createMarketByName($marketName);
                if(!dao::isError())
                {
                    $this->loadModel('action')->create('market', $marketID, 'created');
                    $this->dao->update(TABLE_MARKETREPORT)
                        ->set('market')->eq($marketID)
                        ->where('id')->eq($reportID)
                        ->exec();
                }
            }

            return $reportID;
        }

        return false;
    }

    /**
     * Update a report.
     *
     * @param  int $reportID
     * @access public
     * @return array|false
     */
    public function update($reportID)
    {
        $now       = helper::now();
        $oldReport = $this->getByID($reportID);

        $report = fixer::input('post')
            ->remove('uid')
            ->remove('labels')
            ->setDefault('participants', '')
            ->setDefault('market', 0)
            ->setDefault('research', 0)
            ->setDefault('deleteFiles', array())
            ->setDefault('lastEditedDate', $now)
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setIF($this->post->status == 'published', 'publishedBy', $this->app->user->account)
            ->setIF($this->post->status == 'published', 'publishedDate', $now)
            ->setIF($this->post->status == 'draft', 'publishedBy', '')
            ->setIF($this->post->status == 'draft', 'publishedDate', '')
            ->setIF($this->post->source == 'outside', 'owner', '')
            ->setIF($this->post->source == 'outside', 'participants', '')
            ->setIF($this->post->source == 'outside', 'research', 0)
            ->join('participants', ',')
            ->stripTags($this->config->marketreport->editor->edit['id'], $this->config->allowedTags)
            ->get();
        $report->participants = ',' . $report->participants . ',';

        $this->dao->update(TABLE_MARKETREPORT)->data($report, 'deleteFiles')
            ->batchCheck($this->config->marketreport->edit->requiredFields, 'notempty')
            ->where('id')->eq($reportID)
            ->exec();

        if(!dao::isError())
        {
            $this->file->processFile4Object('marketreport', $oldReport, $report);
            return common::createChanges($oldReport, $report);
        }

        return false;
    }

    /**
     * Publish a report.
     *
     * @param  int    $reportID
     * @access public
     * @return void
     */
    public function publish($reportID)
    {
        $this->dao->update(TABLE_MARKETREPORT)->set('status')->eq('published')->where('id')->eq($reportID)->exec();
        $this->loadModel('action')->create('marketreport', $reportID, 'published');
    }

    /**
     * Get by id.
     *
     * @param  int    $id
     * @access public
     * @return object|false
     */
    public function getByID($id)
    {
        $report = $this->dao->select('*')->from(TABLE_MARKETREPORT)->where('id')->eq($id)->fetch();
        if(!$report) return false;

        $report->files = $this->loadModel('file')->getByObject('marketreport', $id);
        return $report;
    }

    /**
     * Get report list.
     *
     * @param  int    $marketID
     * @param  string $mode
     * @param  string $status
     * @param  string $orderBy
     * @param  int    $involved
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($marketID = 0, $mode = '', $status = 'published', $orderBy = 'id_desc', $involved = 0, $pager = null)
    {
        $marketPairs      = $this->dao->select('id')->from(TABLE_MARKET)->where('deleted')->eq(0)->fetchPairs();
        $researchPairs    = $this->dao->select('id')->from(TABLE_MARKETRESEARCH)->where('deleted')->eq(0)->fetchPairs();
        $marketPairs[0]   = 0;
        $researchPairs[0] = 0;

        return $this->dao->select('*')->from(TABLE_MARKETREPORT)
            ->where('deleted')->eq(0)
            ->andWhere('market')->in(array_keys($marketPairs))
            ->andWhere('research')->in(array_keys($researchPairs))
            ->beginIF(strpos($mode, 'all') === false)->andWhere('market')->eq($marketID)->fi()
            ->beginIF($status != 'all')->andWhere('status')->eq($status)->fi()
            ->beginIF($this->cookie->involvedReport || $involved)
            ->andWhere('owner', true)->eq($this->app->user->account)
            ->orWhere('participants')->like('%,' . $this->app->user->account . ',%')
            ->markRight(1)
            ->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get pairs by research.
     *
     * @param  int    $researchID
     * @access public
     * @return array
     */
    public function getPairsByResearch($researchID)
    {
        return $this->dao->select('id,name,market')->from(TABLE_MARKETREPORT)
            ->where('deleted')->eq(0)
            ->andWhere('research')->eq($researchID)
            ->orderBy('id_desc')
            ->fetchAll('id');
    }

    /**
     * Print cell data.
     *
     * @param object $col
     * @param object $report
     * @param array  $users
     * @param array  $markets
     * @param string $mode
     * @param int    $fromMarket   currentMarketID, Used to distinguish whether it was accessed within a specific market or from the overall report list.
     *
     * @access public
     * @return void
     */
    public function printCell($col, $report, $users, $markets, $researches, $fromMarket = 0)
    {
        $canView  = common::hasPriv('marketreport', 'view');
        $account  = $this->app->user->account;
        $id       = $col->id;
        if($col->show)
        {
            $class = "c-{$id}";
            if($id == 'status') $class .= ' c-status';
            if($id == 'name' || $id == 'market' || $id == 'research') $class .= ' text-left c-name';

            $title = '';
            if($id == 'name') $title = " title='{$report->name}'";
            if($id == 'research') $title = " title='" . zget($researches, $report->research, '') . "'";
            if($id == 'openedBy') $title = " title='" . zget($users, $report->openedBy) . "'";
            if($id == 'lastEditedBy') $title = " title='" . zget($users, $report->lastEditedBy) . "'";

            echo "<td class='" . $class . "'" . $title . ">";
            if($this->config->edition != 'open') $this->loadModel('flow')->printFlowCell('marketreport', $report, $id);
            switch($id)
            {
            case 'id':
                printf('%03d', $report->id);
                break;
            case 'name':
                echo html::a(helper::createLink('marketreport', 'view', "id=$report->id&fromMarket=$fromMarket"), trim($report->name), null, "title='$report->name'");
                break;
            case 'status':
                echo "<span class='status-{$report->status}'>" . $this->processStatus('marketreport', $report) . "</span>";
                break;
            case 'owner':
                echo zget($users, $report->owner);
                break;
            case 'market':
                echo zget($markets, $report->market, '');
                break;
            case 'research':
                echo zget($researches, $report->research, '');
                break;
            case 'source':
                echo zget($this->lang->marketreport->sourceList, $report->source, '');
                break;
            case 'openedBy':
                echo zget($users, $report->openedBy);
                break;
            case 'openedDate':
                echo substr($report->openedDate, 5, 11);
                break;
            case 'lastEditedBy':
                echo zget($users, $report->lastEditedBy);
                break;
            case 'lastEditedDate':
                echo helper::isZeroDate($report->lastEditedDate) ? '' : substr($report->lastEditedDate, 5, 11);
                break;
            case 'actions':
                echo $this->buildOperateMenu($report, 'browse', $fromMarket);
                break;
            }
            echo '</td>';
        }
    }

    /**
     * Build operate menu.
     *
     * @param  object $report
     * @param  string $type
     * @param  int    $fromMarket   currentMarketID, Used to distinguish whether it was accessed within a specific market or from the overall report list.
     * @access public
     * @return string
     */
    public function buildOperateMenu($report, $type = 'view', $fromMarket = 0)
    {
        $menu = '';
        if($report->deleted) return $menu;

        $publishClass  = $report->status == 'published' ? ($type == 'view' ? 'hidden' : 'disabled') : '';
        $menu         .= $this->buildMenu('marketreport', 'publish', "reportID=$report->id", $report, 'browse', 'publish', 'hiddenwin', $publishClass);

        $menu .= $this->buildMenu('marketreport', 'edit',   "reportID=$report->id&fromMarket=$fromMarket", $report, 'browse');
        $menu .= $this->buildMenu('marketreport', 'delete', "reportID=$report->id", $report, 'browse', 'trash', 'hiddenwin');

        return $menu;
    }

    /**
     * Count reports by researchID.
     *
     * @param  int    $researchID
     * @access public
     * @return int
     */
    public function countReports($researchID)
    {
        return $this->dao->select('COUNT(1) AS reports')->from(TABLE_MARKETREPORT)
            ->where('research')->eq($researchID)
            ->andWhere('deleted')->eq(0)
            ->fetch('reports');
    }
}
