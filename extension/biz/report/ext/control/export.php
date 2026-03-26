<?php
/**
 * The model file of export module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     export
 * @link        https://www.zentao.net
 */
helper::importControl('report');
class myReport extends report
{
    /**
     * Export.
     *
     * @param  int    $module
     * @param  int    $productID
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function export($module, $productID = 0, $taskID = 0)
    {
        if($_POST)
        {
            $this->loadModel($module);
            if($module == 'testtask' && $productID && $taskID)
            {
                $task    = $this->testtask->getById($taskID);
                $bugInfo = $this->testtask->getBugInfo($taskID, $productID);
            }
            $items = explode(',', trim($this->post->items, ','));
            foreach($items as $item)
            {
                $chartFunc = 'getDataOf' . $item;
                if($module == 'testtask' && $productID && $taskID)
                {
                    $chartData = isset($bugInfo[$item]) ? $bugInfo[$item] : $this->$module->$chartFunc($taskID);
                }
                elseif($module == 'story')
                {
                    $chartData = $this->$module->$chartFunc($module);
                }
                else
                {
                    $chartData = $this->$module->$chartFunc();
                }
                $datas[$item]  = $this->report->computePercent($chartData);
                $images[$item] = isset($_POST["chart-$item"]) ? $this->post->{"chart-$item"} : '';
                unset($_POST["chart-$item"]);

                foreach($datas[$item] as $data)
                {
                    if(!empty($data->title)) $data->name = $data->title;
                }
            }
            $this->post->set('datas',  $datas);
            $this->post->set('items',  $items);
            $this->post->set('images', $images);
            $this->post->set('kind',   $module);
            return $this->fetch('file', 'export2chart', $_POST);
        }

        $this->view->module    = $module;
        $this->view->productID = $productID;
        $this->view->taskID    = $taskID;
        $this->display();
    }
}
