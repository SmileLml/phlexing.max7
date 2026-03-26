<?php
/**
 * The model file of researchplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     researchplan
 * @version     $Id: model.php 5079 2021-06-08 16:08:34Z
 * @link        https://www.zentao.net
 */
?>
<?php
class researchplanModel extends model
{
    /**
     * Get research plan list.
     *
     * @param  int     $projectID
     * @param  string  $browseType
     * @param  int     $queryID
     * @param  string  $orderBy
     * @param  object  $pager
     * @access public
     * @return array
     */
    public function getList($projectID, $browseType, $queryID, $orderBy, $pager = null)
    {
        $researchplanQuery = '';
        if($browseType == 'bysearch')
        {
            $query = $queryID ? $this->loadModel('search')->getQuery($queryID) : '';
            if($query)
            {
                $this->session->set('researchplanQuery', $query->sql);
                $this->session->set('researchplanForm',  $query->form);
            }

            if($this->session->researchplanQuery == false) $this->session->set('researchplanQuery', ' 1 = 1');

            $researchplanQuery = $this->session->researchplanQuery;
        }

        return $this->dao->select('*')->from(TABLE_RESEARCHPLAN)
            ->where('deleted')->eq(0)
            ->andWhere('project')->eq($projectID)
            ->beginIF($browseType == 'bysearch')->andWhere($researchplanQuery)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get research plan pairs.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getPairs($projectID = 0)
    {
        return $this->dao->select('id,name')->from(TABLE_RESEARCHPLAN)->where('project')->eq($projectID)->andWhere('deleted')->eq(0)->fetchPairs('id');
    }

    /**
     * Get a research plan.
     *
     * @param  int    $planID
     * @access public
     * @return object
     */
    public function getByID($planID)
    {
        return $this->dao->findByID($planID)->from(TABLE_RESEARCHPLAN)->fetch();
    }

    /**
     * Get report list.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getReportPairs($projectID)
    {
        return $this->dao->select('relatedPlan,id')->from(TABLE_RESEARCHREPORT)->where('project')->eq($projectID)->fetchPairs('relatedPlan');
    }

    /**
     * Create a research plan.
     *
     * @param  int    $projectID
     * @access public
     * @return int|bool
     */
    public function create($projectID = 0)
    {
        $researchPlan = fixer::input('post')
            ->add('project', $projectID)
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->join('stakeholder', ',')
            ->join('team', ',')
            ->remove('uid')
            ->stripTags($this->config->researchplan->editor->create['id'], $this->config->allowedTags)
            ->get();

        if(empty($researchPlan->begin)) $researchPlan->begin = null;
        if(empty($researchPlan->end)) $researchPlan->end = null;
        if(!empty($researchPlan->end)) $this->config->researchplan->create->requiredFields .= ',begin';

        $this->dao->insert(TABLE_RESEARCHPLAN)->data($researchPlan)
            ->batchCheck($this->config->researchplan->create->requiredFields, 'notempty')
            ->checkIF(!empty($researchPlan->begin), 'end', 'gt', $researchPlan->begin)
            ->autoCheck()
            ->exec();

        if(!dao::isError()) return $this->dao->lastInsertID();
        return false;
    }

    /**
     * Update research plan.
     *
     * @access int    $planID
     * @access public
     * @return array|bool
     */
    public function update($planID)
    {
        $oldPlan = $this->getByID($planID);
        $newPlan = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->join('stakeholder', ',')
            ->join('team', ',')
            ->remove('uid')
            ->stripTags($this->config->researchplan->editor->edit['id'], $this->config->allowedTags)
            ->get();

        if(empty($newPlan->begin)) $newPlan->begin = null;
        if(empty($newPlan->end)) $newPlan->end = null;
        if(!empty($newPlan->end)) $this->config->researchplan->edit->requiredFields .= ',begin';

        $this->dao->update(TABLE_RESEARCHPLAN)->data($newPlan)
            ->where('id')->eq($planID)
            ->batchCheck($this->config->researchplan->edit->requiredFields, 'notempty')
            ->checkIF(!empty($newPlan->begin), 'end', 'gt', $newPlan->begin)
            ->autoCheck()
            ->exec();

        if(!dao::isError()) return common::createChanges($oldPlan, $newPlan);
        return false;
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
        $this->config->researchplan->search['actionURL'] = $actionURL;
        $this->config->researchplan->search['queryID']   = $queryID;

        $this->config->researchplan->search['params']['stakeholder']['values'] = $this->loadModel('user')->getPairs('noclosed|noletter|all');

        $this->loadModel('search')->setSearchParams($this->config->researchplan->search);
    }
}
