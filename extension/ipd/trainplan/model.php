<?php
/**
 * The model file of trainplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu <hufangzhou@easycorp.ltd>
 * @package     trainplan
 * @version     $Id: model.php 5079 2020-09-04 09:08:34Z lyc $
 * @link        http://www.zentao.net
 */
?>
<?php
class trainplanModel extends model
{
    /**
     * Create a trainplan.
     *
     * @param  int  $projectID
     * @access public
     * @return int|bool
     */
    public function create($projectID = 0)
    {
        $trainplan = fixer::input('post')
            ->setDefault('status', 'wait')
            ->setDefault('begin, end', null)
            ->add('project', $projectID)
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->join('trainee', ',')
            ->remove('uid')
            ->get();

        $trainplan->trainee = isset($trainplan->trainee) ? ',' . $trainplan->trainee : ',';

        if(!empty($trainplan->end)) $this->config->trainplan->create->requiredFields .= ',begin';

        $this->dao->insert(TABLE_TRAINPLAN)
            ->data($trainplan)->autoCheck()
            ->batchCheck($this->config->trainplan->create->requiredFields, 'notempty')
            ->checkIF(!empty($trainplan->begin), 'end', 'ge', $trainplan->begin)
            ->exec();

        if(!dao::isError()) return $this->dao->lastInsertID();
        return false;
    }

    /**
     * Batch create trainplan.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function batchCreate($projectID = 0)
    {
        $now  = helper::now();
        $data = fixer::input('post')->get();

        $trainplanList = array();

        $requiredFields = ',' . $this->config->trainplan->create->requiredFields . ',';
        $requiredFields = explode(',', trim($requiredFields, ','));

        $this->loadModel('action');
        foreach($data->name as $i => $name)
        {
            if(!$name) continue;

            $trianee = isset($data->trainee[$i]) ? implode(',', $data->trainee[$i]) : '';

            $trainplan = new stdclass();
            $trainplan->name        = $name;
            $trainplan->begin       = !empty($data->begin[$i]) ? $data->begin[$i] : null;
            $trainplan->end         = !empty($data->end[$i]) ? $data->end[$i] : null;
            $trainplan->place       = $data->place[$i];
            $trainplan->lecturer    = $data->lecturer[$i];
            $trainplan->type        = $data->type[$i];
            $trainplan->trainee     = !empty($trianee) ? ',' . $trianee : ',';
            $trainplan->project     = $projectID;
            $trainplan->status      = 'wait';
            $trainplan->createdBy   = $this->app->user->account;
            $trainplan->createdDate = $now;

            if(!empty($trainplan->end)) $requiredFields[] = 'begin';

            if(!helper::isZeroDate($trainplan->begin) and $trainplan->end < $trainplan->begin)
            {
                dao::$errors['message'][] = $this->lang->trainplan->endSmall;
                return false;
            }

            foreach($requiredFields as $field)
            {
                $field = trim($field);
                if(empty($field)) continue;

                if(!isset($trainplan->$field)) continue;
                if(!empty($trainplan->$field)) continue;

                dao::$errors['message'][] = sprintf($this->lang->error->notempty, $this->lang->trainplan->$field);
                return false;
            }

            /* Remove the begin from requiredFields. */
            array_pop($requiredFields);

            $this->dao->insert(TABLE_TRAINPLAN)
                ->data($trainplan)
                ->autoCheck()
                ->exec();

            $trainplanID = $this->dao->lastInsertID();
            $trainplanList[$trainplanID] = $trainplanID;

            if(!dao::isError()) $this->action->create('trainplan', $trainplanID, 'Opened');
        }

        return $trainplanList;
    }

    /**
     * Update a trainplan.
     *
     * @param  int    $trainplanID
     * @access public
     * @return array|bool
     */
    public function update($trainplanID)
    {
        $oldTrainplan = $this->getById($trainplanID);

        $trainplan = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->join('trainee', ',')
            ->remove('uid')
            ->get();

        $trainplan->trainee = ',' . $trainplan->trainee;

        if(!empty($trainplan->end)) $this->config->trainplan->create->requiredFields .= ',begin';

        $this->dao->update(TABLE_TRAINPLAN)
            ->data($trainplan)->autoCheck()
            ->batchCheck($this->config->trainplan->create->requiredFields, 'notempty')
            ->checkIF(!empty($trainplan->begin), 'end', 'ge', $trainplan->begin)
            ->where('id')->eq((int)$trainplanID)
            ->exec();

        if(!dao::isError()) return common::createChanges($oldTrainplan, $trainplan);
        return false;
    }

    /**
     * Batch update trainplans.
     *
     * @access public
     * @return array
     */
    public function batchUpdate()
    {
        $allChanges      = array();
        $now             = helper::now();
        $data            = fixer::input('post')->get();
        $trainplanIDList = $this->post->trainplanIDList;
        $oldTrainplans   = $trainplanIDList ? $this->getByList($trainplanIDList) : array();

        $requiredFields = ',' . $this->config->trainplan->create->requiredFields . ',';
        $requiredFields = explode(',', trim($requiredFields, ','));

        foreach($trainplanIDList as $trainplanID)
        {
            $oldTrainplan = $oldTrainplans[$trainplanID];

            $trianee = isset($data->trainee[$trainplanID]) ? implode(',', $data->trainee[$trainplanID]) : '';

            $trainplan = new stdclass();
            $trainplan->name       = $data->name[$trainplanID];
            $trainplan->begin      = $data->begin[$trainplanID];
            $trainplan->end        = $data->end[$trainplanID];
            $trainplan->place      = $data->place[$trainplanID];
            $trainplan->lecturer   = $data->lecturer[$trainplanID];
            $trainplan->type       = $data->type[$trainplanID];
            $trainplan->trainee    = !empty($trianee) ? ',' . $trianee : ',';
            $trainplan->editedBy   = $this->app->user->account;
            $trainplan->editedDate = $now;

            if(!empty($trainplan->end)) $requiredFields[] = 'begin';

            if(!empty($trainplan->begin) and $trainplan->end < $trainplan->begin)
            {
                dao::$errors['message'][] = $this->lang->trainplan->endSmall;
                return false;
            }

            foreach($requiredFields as $field)
            {
                $field = trim($field);
                if(empty($field)) continue;

                if(!isset($trainplan->$field)) continue;
                if(!empty($trainplan->$field)) continue;

                dao::$errors['message'][] = sprintf($this->lang->error->notempty, $this->lang->trainplan->$field);
                return false;
            }

            /* Remove the begin from requiredFields. */
            array_pop($requiredFields);

            $this->dao->update(TABLE_TRAINPLAN)->data($trainplan)
                ->autoCheck()
                ->where('id')->eq((int)$trainplanID)
                ->exec();

            if(!dao::isError())
            {
                $allChanges[$trainplanID] = common::createChanges($oldTrainplan, $trainplan);
            }
            else
            {
                die(js::error('trainplan#' . $trainplanID . dao::getError(true)));
            }
        }
        return $allChanges;
    }

    /**
     * Finish a trainplan.
     *
     * @param  int    $trainplanID
     * @access public
     * @return array
     */
    public function finish($trainplanID)
    {
        $oldTrainplan = $this->getById($trainplanID);

        $trainplan = fixer::input('post')->setDefault('status', 'done')->remove('comment, trainplanIDList')->get();

        $this->dao->update(TABLE_TRAINPLAN)->data($trainplan)->autoCheck()->where('id')->eq($trainplanID)->exec();

        if(!dao::isError()) return common::createChanges($oldTrainplan, $trainplan);
    }

    /**
     * Commit the summary of trainplan.
     *
     * @param  int    $trainplanID
     * @access public
     * @return array
     */
    public function summary($trainplanID)
    {
        $oldTrainplan = $this->getById($trainplanID);

        $trainplan = fixer::input('post')->stripTags($this->config->trainplan->editor->summary['id'], $this->config->allowedTags)->remove('uid')->get();
        $trainplan = $this->loadModel('file')->processImgURL($trainplan, $this->config->trainplan->editor->summary['id'], $this->post->uid);

        $this->dao->update(TABLE_TRAINPLAN)->data($trainplan)->autoCheck()->where('id')->eq($trainplanID)->exec();

        if(!dao::isError()) return common::createChanges($oldTrainplan, $trainplan);
    }

    /**
     * Get trainplan info by id.
     *
     * @param  int    $trainplanID
     * @access public
     * @return object|bool
     */
    public function getById($trainplanID)
    {
        $trainplan = $this->dao->select('*')->from(TABLE_TRAINPLAN)->where('id')->eq((int)$trainplanID)->fetch();
        if(!$trainplan) return false;

        $trainplan = $this->loadModel('file')->replaceImgURL($trainplan, 'summary');
        return $trainplan;
    }

    /**
     * Get trainplans list.
     *
     * @param  int    $projectID
     * @param  string $browseType
     * @param  string $param
     * @param  string $orderBy
     * @param  int    $pager
     * @access public
     * @return array
     */
    public function getList($projectID, $browseType = '', $param = '', $orderBy = 'id_desc', $pager = null)
    {
        if($browseType == 'bysearch') return $this->getBySearch($projectID, $param, $orderBy, $pager);

        return $this->dao->select('*')->from(TABLE_TRAINPLAN)
            ->where('deleted')->eq(0)
            ->beginIF($browseType != 'all')->andWhere('status')->eq($browseType)->fi()
            ->andWhere('project')->eq($projectID)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);
    }

    /**
     * Get trainplan list.
     *
     * @param  array   $trainplanIDList
     * @access public
     * @return array
     */
    public function getByList($trainplanIDList = 0)
    {
        return $this->dao->select('*')->from(TABLE_TRAINPLAN)
            ->where('deleted')->eq(0)
            ->beginIF($trainplanIDList)->andWhere('id')->in($trainplanIDList)->fi()
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
        $this->config->trainplan->search['queryID']   = $queryID;
        $this->config->trainplan->search['actionURL'] = $actionURL;

        $trainees = $this->dao->select('t1.account, t2.realname')->from(TABLE_GAPANALYSIS)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account=t2.account')
            ->where('t1.project')->eq($this->session->project)
            ->andWhere('t1.needTrain')->eq('yes')
            ->andWhere('t1.deleted')->eq(0)
            ->fetchPairs();

        $this->config->trainplan->search['params']['trainee']['values'] = arrayUnion(array(''), $trainees);

        $this->loadModel('search')->setSearchParams($this->config->trainplan->search);
    }

    /**
     * Get trainplan by search.
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
                $this->session->set('trainplanQuery', $query->sql);
                $this->session->set('trainplanForm', $query->form);
            }
            else
            {
                $this->session->set('trainplanQuery', ' 1 = 1');
            }
        }
        else
        {
            if($this->session->trainplanQuery == false) $this->session->set('trainplanQuery', ' 1 = 1');
        }

        $trainplanQuery = $this->session->trainplanQuery;

        return $this->dao->select('*')->from(TABLE_TRAINPLAN)
            ->where($trainplanQuery)
            ->andWhere('deleted')->eq('0')
            ->andWhere('project')->eq($projectID)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);
    }

    /**
     * Get the members who can join in training.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getTrainMembers($projectID = 0)
    {
        $members    = array();
        $users      = $this->loadModel('user')->getPairs('noclosed');
        $memberList = $this->dao->select('account')->from(TABLE_GAPANALYSIS)->where('needTrain')->eq('yes')->andWhere('project')->eq($projectID)->andWhere('deleted')->eq(0)->fetchPairs();;

        if(!empty($memberList))
        {
            foreach($memberList as $member) $members[$member] = zget($users, $member);
        }

        return $members;
    }

    /**
     * Judge an action is clickable or not.
     *
     * @param  object  $trainplan
     * @param  string  $action
     * @access public
     * @return bool
     */
    public static function isClickable($trainplan, $action)
    {
        $action = strtolower($action);

        if($action == 'edit' or $action == 'finish')  return $trainplan->status == 'wait';
        if($action == 'summary') return $trainplan->status == 'done';

        return true;
    }
}
