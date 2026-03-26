<?php
/**
 * The model file of excel module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     excel
 * @link        https://www.zentao.net
 */
helper::importControl('feedback');
class myfeedback extends feedback
{
    /**
     * Show import.
     *
     * @param  int    $pagerID
     * @param  int    $maxImport
     * @param  string $insert
     * @access public
     * @return void
     */
    public function showImport($pagerID = 1, $maxImport = 0, $insert = '')
    {
        $this->loadModel('transfer');

        if($_POST)
        {
            $message = $this->feedback->createFromImport();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $locate = inlink('showImport', "pagerID=" . ($this->post->pagerID + 1) . "maxImport=$maxImport&insert=" . zget($_POST, 'insert', ''));
            if($this->post->isEndPage) $locate = inlink('admin');
            return $this->send(array('result' => 'success', 'message' => $message, 'closeModal' => true, 'load' => $locate));
        }

        $feedbackData = $this->transfer->readExcel('feedback', $pagerID, $insert);
        if(empty($feedbackData)) return $this->send(array('result' => 'fail', 'message' => $this->lang->excel->error->noData));

        if(!isset(reset($feedbackData->datas)->id))
        {
            $index = 1;
            foreach($feedbackData->datas as $data) $data->id = $index ++;
        }
        $modulesProductMap = $this->loadModel('feedback')->getModuleList('feedback');
        foreach($modulesProductMap as $productID => $modules)
        {
            $items = array();
            foreach($modules as $moduleID => $moduleName) $items[] = array('text' => $moduleName, 'value' => $moduleID);
            $modulesProductMap[$productID] = $items;
        }

        $this->view->title             = $this->lang->feedback->common . $this->lang->hyphen . $this->lang->feedback->showImport;
        $this->view->datas             = $feedbackData;
        $this->view->modulesProductMap = $modulesProductMap;
        $this->view->backLink          = inlink('admin');

        $this->display();
    }
}
