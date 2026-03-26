<?php
/**
 * The model file of researchreport module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     researchreport
 * @version     $Id: model.php 5079 2021-06-08 16:08:34Z
 * @link        https://www.zentao.net
 */
?>
<?php
class researchreportModel extends model
{
    /**
     * Get research report list.
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
        $researchreportQuery = '';
        if($browseType == 'bysearch')
        {
            $query = $queryID ? $this->loadModel('search')->getQuery((int)$queryID) : '';
            if($query)
            {
                $this->session->set('researchreportQuery', $query->sql);
                $this->session->set('researchreportForm',  $query->form);
            }

            if($this->session->researchreportQuery == false) $this->session->set('researchreportQuery', ' 1 = 1');

            $researchreportQuery = $this->session->researchreportQuery;
        }

        return $this->dao->select('*')->from(TABLE_RESEARCHREPORT)
            ->where('deleted')->eq(0)
            ->andWhere('project')->eq($projectID)
            ->beginIF($browseType == 'bysearch')->andWhere($researchreportQuery)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get research report pairs.
     *
     * @param  int   $projectID
     * @access public
     * @return array
     */
    public function getPairs($projectID = 0)
    {
        return $this->dao->select('id,title')->from(TABLE_RESEARCHREPORT)
            ->where('deleted')->eq(0)
            ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
            ->fetchPairs('id', 'title');
    }

    /**
     * Get a research report.
     *
     * @param  int    $reportID
     * @access public
     * @return object
     */
    public function getByID($reportID)
    {
        return $this->dao->findByID($reportID)->from(TABLE_RESEARCHREPORT)->fetch();
    }

    /**
     * Create a research report.
     *
     * @param  int    $projectID
     * @access public
     * @return int|bool
     */
    public function create($projectID = 0)
    {
        $researchReport = fixer::input('post')
            ->add('project', $projectID)
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->join('stakeholder', ',')
            ->join('team', ',')
            ->remove('uid')
            ->stripTags($this->config->researchreport->editor->create['id'], $this->config->allowedTags)
            ->get();

        if(empty($researchReport->begin)) $researchReport->begin = null;
        if(empty($researchReport->end)) $researchReport->end = null;
        if(!empty($researchReport->end)) $this->config->researchreport->create->requiredFields .= ',begin';

        $this->dao->insert(TABLE_RESEARCHREPORT)->data($researchReport)
            ->batchCheck($this->config->researchreport->create->requiredFields, 'notempty')
            ->checkIF(!empty($researchReport->begin), 'end', 'gt', $researchReport->begin)
            ->autoCheck()
            ->exec();

        if(!dao::isError()) return $this->dao->lastInsertID();
        return false;
    }

    /**
     * Update research report.
     *
     * @access int    $reportID
     * @access public
     * @return array|bool
     */
    public function update($reportID)
    {
        $oldReport = $this->getByID($reportID);
        $newReport = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->join('stakeholder', ',')
            ->join('team', ',')
            ->remove('uid')
            ->stripTags($this->config->researchreport->editor->edit['id'], $this->config->allowedTags)
            ->get();

        if(empty($newReport->begin)) $newReport->begin = null;
        if(empty($newReport->end)) $newReport->end = null;
        if(!empty($newReport->end)) $this->config->researchreport->edit->requiredFields .= ',begin';

        $this->dao->update(TABLE_RESEARCHREPORT)->data($newReport)
            ->where('id')->eq($reportID)
            ->batchCheck($this->config->researchreport->edit->requiredFields, 'notempty')
            ->checkIF(!empty($newReport->begin), 'end', 'gt', $newReport->begin)
            ->autoCheck()
            ->exec();

        if(!dao::isError()) return common::createChanges($oldReport, $newReport);
        return false;
    }

    /**
     * Build search form.
     *
     * @param  int    $projectID
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildSearchForm($projectID, $queryID, $actionURL)
    {
        $this->config->researchreport->search['actionURL'] = $actionURL;
        $this->config->researchreport->search['queryID']   = $queryID;

        $this->config->researchreport->search['params']['relatedPlan']['values'] = arrayUnion(array(''), $this->loadModel('researchplan')->getPairs($projectID));

        $this->loadModel('search')->setSearchParams($this->config->researchreport->search);
    }

    /**
     * Get related requirement.
     *
     * @param  int    $reportID
     * @access public
     * @return array
     */
    public function getRelatedUR($reportID = 0)
    {
        return $this->dao->select('id,title')->from(TABLE_STORY)
            ->where('source')->eq('researchreport')
            ->andWhere('type')->eq('requirement')
            ->andWhere('sourceNote')->eq($reportID)
            ->andWhere('deleted')->eq(0)
            ->fetchPairs('id', 'title');
    }
}
