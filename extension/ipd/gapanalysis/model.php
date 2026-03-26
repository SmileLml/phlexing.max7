<?php
/**
 * The model file of gapanalysis module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu <hufangzhou@easycorp.ltd>
 * @package     gapanalysis
 * @version     $Id: model.php 5079 2021-05-28 14:02:34Z hfz $
 * @link        https://www.zentao.net
 */
?>
<?php
class gapanalysisModel extends model
{
    /**
     * Create a gapanalysis.
     *
     * @param  int  $projectID
     * @access public
     * @return int|bool
     */
    public function create($projectID = 0)
    {
        $gapanalysis = fixer::input('post')
            ->add('project', $projectID)
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->stripTags($this->config->gapanalysis->editor->create['id'], $this->config->allowedTags)
            ->remove('uid')
            ->get();

        $gapanalysis = $this->loadModel('file')->processImgURL($gapanalysis, $this->config->gapanalysis->editor->create['id'], $this->post->uid);
        $this->dao->insert(TABLE_GAPANALYSIS)->data($gapanalysis)->autoCheck()->batchCheck($this->config->gapanalysis->create->requiredFields, 'notempty')->exec();

        if(!dao::isError()) return $this->dao->lastInsertID();
        return false;
    }

    /**
     * Batch create gapanalysis.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function batchCreate($projectID = 0)
    {
        $now  = helper::now();
        $data = fixer::input('post')->get();

        $gapanalysisList = array();

        $this->loadModel('action');
        foreach($data->account as $i => $account)
        {
            if(!$account) continue;

            $gapanalysis = new stdclass();
            $gapanalysis->account     = $account;
            $gapanalysis->role        = $data->role[$i];
            $gapanalysis->analysis    = $data->analysis[$i];
            $gapanalysis->needTrain   = $data->needTrain[$i];
            $gapanalysis->project     = $projectID;
            $gapanalysis->createdBy   = $this->app->user->account;
            $gapanalysis->createdDate = $now;

            $this->dao->insert(TABLE_GAPANALYSIS)->data($gapanalysis)->autoCheck()->exec();

            $gapanalysisID = $this->dao->lastInsertID();
            $gapanalysisList[$gapanalysisID] = $gapanalysisID;
            $this->action->create('gapanalysis', $gapanalysisID, 'Opened');
        }

        return $gapanalysisList;
    }

    /**
     * Update a gapanalysis.
     *
     * @param  int    $gapanalysisID
     * @access public
     * @return array|bool
     */
    public function update($gapanalysisID)
    {
        $oldGapanalysis = $this->getById($gapanalysisID);

        $gapanalysis = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->stripTags($this->config->gapanalysis->editor->edit['id'], $this->config->allowedTags)
            ->remove('uid')
            ->get();

        $gapanalysis = $this->loadModel('file')->processImgURL($gapanalysis, $this->config->gapanalysis->editor->edit['id'], $this->post->uid);
        $this->dao->update(TABLE_GAPANALYSIS)->data($gapanalysis)->autoCheck()->where('id')->eq((int)$gapanalysisID)->exec();

        if(!dao::isError()) return common::createChanges($oldGapanalysis, $gapanalysis);
        return false;
    }

    /**
     * Batch update gapanalysises.
     *
     * @access public
     * @return array
     */
    public function batchUpdate()
    {
        $allChanges        = array();
        $now               = helper::now();
        $data              = fixer::input('post')->get();
        $gapanalysisIdList = $this->post->gapanalysisIdList;
        $oldGapanalysises  = $gapanalysisIdList ? $this->getByList($gapanalysisIdList) : array();
        foreach($gapanalysisIdList as $gapanalysisID)
        {
            $oldGapanalysis = $oldGapanalysises[$gapanalysisID];

            $gapanalysis = new stdclass();
            $gapanalysis->role       = $data->role[$gapanalysisID];
            $gapanalysis->needTrain  = $data->needTrain[$gapanalysisID];
            $gapanalysis->editedBy   = $this->app->user->account;
            $gapanalysis->editedDate = $now;

            $this->dao->update(TABLE_GAPANALYSIS)->data($gapanalysis)
                ->autoCheck()
                ->batchCheck($this->config->gapanalysis->create->requiredFields, 'notempty')
                ->where('id')->eq((int)$gapanalysisID)
                ->exec();

            if(!dao::isError())
            {
                $allChanges[$gapanalysisID] = common::createChanges($oldGapanalysis, $gapanalysis);
            }
            else
            {
                die(js::error('gapanalysis#' . $gapanalysisID . dao::getError(true)));
            }
        }
        return $allChanges;
    }

    /**
     * Get gapanalysis info by id.
     *
     * @param  int    $gapanalysisID
     * @access public
     * @return object|bool
     */
    public function getById($gapanalysisID)
    {
        $gapanalysis = $this->dao->select('*')->from(TABLE_GAPANALYSIS)->where('id')->eq((int)$gapanalysisID)->fetch();
        if(!$gapanalysis) return false;

        $gapanalysis = $this->loadModel('file')->replaceImgURL($gapanalysis, 'analysis');
        return $gapanalysis;
    }

    /**
     * Get gapanalysises list.
     *
     * @param  int    $projectID
     * @param  string $browseType
     * @param  string $param
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($projectID, $browseType = '', $param = '', $orderBy = 't2.id_asc', $pager = null)
    {
        if($browseType == 'bysearch') return $this->getBySearch($projectID, $param, $orderBy, $pager);

        return $this->dao->select('t1.*, t1.id as gapanalysisID')->from(TABLE_GAPANALYSIS)->alias('t1')
            ->leftJoin(TABLE_TEAM)->alias('t2')->on('t1.project = t2.root')
            ->where('deleted')->eq(0)
            ->andWhere('project')->eq($projectID)
            ->andWhere('t2.type')->eq('project')
            ->orderBy($orderBy)
            ->page($pager, 't1.account')
            ->fetchAll('gapanalysisID');
    }

    /**
     * Get gapanalysises by id list.
     *
     * @param  array   $gapanalysisIdList
     * @access public
     * @return array
     */
    public function getByList($gapanalysisIdList = array())
    {
        return $this->dao->select('*')->from(TABLE_GAPANALYSIS)
            ->where('deleted')->eq(0)
            ->beginIF($gapanalysisIdList)->andWhere('id')->in($gapanalysisIdList)->fi()
            ->fetchAll('id');
    }

    /**
     * Build search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildSearchForm($queryID, $actionURL)
    {
        $this->config->gapanalysis->search['queryID']   = $queryID;
        $this->config->gapanalysis->search['actionURL'] = $actionURL;

        $memberList = $this->loadModel('project')->getTeamMemberPairs($this->session->project);

        $this->config->gapanalysis->search['params']['account']['values'] = arrayUnion(array(''), $memberList);

        $this->loadModel('search')->setSearchParams($this->config->gapanalysis->search);
    }

    /**
     * Get gapanalysis by search.
     *
     * @param  int    $projectID
     * @param  string $queryID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getBySearch($projectID, $queryID = '', $orderBy = 'id_desc', $pager = null)
    {
        if($queryID && $queryID != 'myQueryID')
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set('gapanalysisQuery', $query->sql);
                $this->session->set('gapanalysisForm', $query->form);
            }
            else
            {
                $this->session->set('gapanalysisQuery', ' 1 = 1');
            }
        }
        else
        {
            if($this->session->gapanalysisQuery == false) $this->session->set('gapanalysisQuery', ' 1 = 1');
        }

        $gapanalysisQuery = $this->session->gapanalysisQuery;

        return $this->dao->select('*')->from(TABLE_GAPANALYSIS)
            ->where($gapanalysisQuery)
            ->andWhere('deleted')->eq('0')
            ->andWhere('project')->eq($projectID)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get analyable members.
     *
     * @param  int   $projectID
     * @access public
     * @return array
     */
    public function getAnalyzableMembers($projectID)
    {
        /* Get team members. */
        $members         = array();
        $users           = $this->loadModel('user')->getPairs('noclosed');
        $memberList      = $this->loadModel('project')->getTeamMemberPairs($projectID);
        $analyzedMembers = $this->dao->select('account')->from(TABLE_GAPANALYSIS)->where('project')->eq($projectID)->fetchAll('account');
        foreach($memberList as $member => $realname)
        {
            if(!isset($analyzedMembers[$member])) $members[$member] = zget($users, $member);
        }

        return $members;
    }

    /**
     * GetPreAndNext
     *
     * @param  array    idList
     * @param  int      listID
     * @param  string   type
     * @param  string   title
     * @access public
     * @return object
     */
    public function getPreAndNext($idList = '', $listID = 0, $type = '', $title = 'title')
    {
        $idList = $this->session->$idList ? $this->session->$idList : array();
        $offset = array_search($listID, $idList);
        $preID  = isset($idList[$offset - 1]) ? $idList[$offset - 1] : '';
        $nextID = isset($idList[$offset + 1]) ? $idList[$offset + 1] : '';

        $pre  = empty($preID) ? '' : $this->loadModel($type)->getById($preID);
        $next = empty($nextID) ? '' : $this->loadModel($type)->getById($nextID);

        if(!empty($pre)) $pre->title  = $pre->$title;
        if(!empty($next)) $next->title = $next->$title;

        $preNext = new stdclass();
        $preNext->pre = $pre;
        $preNext->next = $next;
        return $preNext;
    }
}
