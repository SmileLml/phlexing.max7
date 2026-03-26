<?php
/**
 * The model file of workestimation module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     workestimation
 * @version     $Id
 * @link        http://www.zentao.net
 */
class workestimationModel extends model
{
    /**
     * Get a budget.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getBudget($projectID)
    {
        return $this->dao->select('*')->from(TABLE_WORKESTIMATION)->where('project')->eq($projectID)->fetch();
    }

    /**
     * Get project scale.
     *
     * @param  int    $projectID
     * @access public
     * @return int
     */
    public function getProjectScale($projectID)
    {
        $products      = $this->loadModel('product')->getProductPairsByProject($projectID);
        $productIdList = array_keys($products);

        $scale = $this->dao->select('cast(sum(t1.estimate) as decimal(10,2)) as scale')->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PROJECTSTORY)->alias('t2')->on('t1.id = t2.story')
            ->where('t1.product')->in($productIdList)
            ->andWhere('t2.project')->eq($projectID)
            ->andWhere('t1.type')->eq('story')
            ->andWhere('t1.isParent')->eq('0')
            ->andWhere('t1.deleted')->eq(0)
            ->fetch('scale');
        return $scale;
    }

    /*
     * Save a budget.
     *
     * @param  int    $projectID
     * @access public
     * @return bool
     */
    public function save($projectID)
    {
        $data = fixer::input('post')
            ->setDefault('project', $projectID)
            ->setIF(empty($_POST['dayHour']), 'dayHour', 0)
            ->setIF(!isset($_POST['productivity']), 'productivity', 1)
            ->get();

        $budget = $this->getBudget($projectID);
        if(empty($budget))
        {
            $this->dao->insert(TABLE_WORKESTIMATION)->data($data)
                ->batchCheck($this->config->workestimation->index->requiredFields, 'notempty')
                ->exec();
        }
        else
        {
            $this->dao->update(TABLE_WORKESTIMATION)->data($data)
                ->batchCheck($this->config->workestimation->index->requiredFields, 'notempty')
                ->where('id')->eq($budget->id)
                ->exec();
        }

        return !dao::isError();
    }
}
