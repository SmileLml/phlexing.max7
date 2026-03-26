<?php
/**
 * The model file of reviewcl module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     reviewcl
 * @version     $Id: model.php 5107 2020-09-09 09:46:12Z xieqiyu@easycorp.ltd $
 * @link        http://www.zentao.net
 */
class reviewclModel extends model
{
    /**
     * Create a reviewcl.
     *
     * @param  string $type
     * @access public
     * @return bool|int
     */
    public function create($type = 'waterfall')
    {
        $now = helper::now();
        $reviewcl = fixer::input('post')
            ->setDefault('createdBy', $this->app->user->account)
            ->setDefault('createdDate', $now)
            ->setDefault('type', $type)
            ->get();

        $this->dao->insert(TABLE_REVIEWCL)->data($reviewcl)->autoCheck()->batchCheck($this->config->reviewcl->create->requiredFields, 'notempty')->exec();

        if(!dao::isError()) return $this->dao->lastInsertID();

        return false;
    }

    /**
     * Batch create reviewcls.
     *
     * @param  string $type
     * @access public
     * @return bool
     */
    public function batchCreate($type = 'waterfall')
    {
        $data = fixer::input('post')->get();

        $this->loadModel('action');
        foreach($data->titles as $i => $title)
        {
            if(!trim($title)) continue;

            $reviewcl = new stdclass();
            $reviewcl->object      = $data->objects[$i];
            $reviewcl->category    = $data->categories[$i];
            $reviewcl->title       = $title;
            $reviewcl->type        = $type;
            $reviewcl->createdBy   = $this->app->user->account;
            $reviewcl->createdDate = helper::now();

            $this->dao->insert(TABLE_REVIEWCL)->data($reviewcl)->autoCheck()->batchCheck($this->config->reviewcl->create->requiredFields, 'notempty')->exec();

            $reviewclID = $this->dao->lastInsertID();
            $this->action->create('reviewcl', $reviewclID, 'Opened');
        }

        return true;
    }

    /**
     * Update a reviewcl.
     *
     * @param  int    $reviewclID
     * @access public
     * @return bool|array
     */
    public function update($reviewclID = 0)
    {
        $oldReviewcl = $this->getByID($reviewclID);
        $reviewcl = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->get();

        $this->dao->update(TABLE_REVIEWCL)->data($reviewcl)->autoCheck()->batchCheck($this->config->reviewcl->edit->requiredFields, 'notempty')->where('id')->eq($reviewclID)->exec();

        if(!dao::isError()) return common::createChanges($oldReviewcl, $reviewcl);

        return false;
    }

    /**
     * Get reviewcl list.
     *
     * @param  string  $object PP|QAP|CMP|ITP|URS|SRS|HLDS|DDS|DBDS|ADS|Code|ITTC|STP|STTC|UM
     * @param  string  $orderBy
     * @param  int     $pager
     * @param  string  $type
     * @access public
     * @return array
     */
    public function getList($object = '', $orderBy = 'id_desc', $pager = null, $type = '')
    {
        return $this->dao->select('*')->from(TABLE_REVIEWCL)
            ->where('deleted')->eq(0)
            ->beginIF($object)->andWhere('object')->eq($object)->fi()
            ->beginIF(!empty($type))->andWhere('type')->eq($type)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchGroup('category');
    }

    /**
     * Get reviewcl by list.
     *
     * @param  array $idList
     * @access public
     * @return array
     */
    public function getByList($idList)
    {
        return $this->dao->select('id, title')->from(TABLE_REVIEWCL)
            ->where('deleted')->eq(0)
            ->andWhere('id')->in($idList)
            ->fetchPairs();
    }

    /**
     * Get a reviewcl by id.
     *
     * @param  int    $reviewclID
     * @access public
     * @return object
     */
    public function getByID($reviewclID)
    {
        return $this->dao->select('*')->from(TABLE_REVIEWCL)
            ->Where('id')->eq($reviewclID)
            ->andWhere('deleted')->eq(0)
            ->fetch();
    }
}
