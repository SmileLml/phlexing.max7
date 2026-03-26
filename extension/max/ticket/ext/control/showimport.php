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
helper::importControl('ticket');
class myticket extends ticket
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
            $this->ticket->createFromImport();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $locate = true;
            if($this->post->isEndPage) $locate = inlink('browse');
            return $this->send(array('result' => 'success', 'load' => $locate, 'closeModal' => true));
        }
        if(!isset($this->config->ticket->dtable)) $this->config->ticket->dtable = new stdclass();
        if(!isset($this->config->ticket->dtable->fieldList)) $this->config->ticket->dtable->fieldList = array();
        $this->config->ticket->dtable->fieldList['module']['dataSource'] = array('module' => 'tree', 'method' => 'getOptionMenu', 'params' => ['rootID' => 0, 'type' => 'ticket', 'startModule' => 0, 'branch' => 'all']);

        $this->config->ticket->dtable->fieldList['openedBuild']['control']    = 'multiple';
        $this->config->ticket->dtable->fieldList['openedBuild']['dataSource'] = array('module' => 'build', 'method' =>'getBuildPairs', 'params' => array('productIdList' => array()));

        $this->config->ticket->dtable->fieldList['assignedTo']['control']    = 'picker';
        $this->config->ticket->dtable->fieldList['assignedTo']['dataSource'] = array('module' => 'user', 'method' => 'getPairs', 'params' => 'noclosed|nodeleted|noletter');

        $ticketData = $this->transfer->readExcel('ticket', $pagerID, $insert);

        if(!$ticketData) return $this->send(array('result' => 'success', 'load' => array('alert' => $this->lang->ticket->importReload, 'locate' => $this->session->ticketList ? $this->session->ticketList : $this->createlink('ticket', 'browse'))));

        $ticketDatas = current($ticketData->datas);
        if(!empty($ticketDatas->id)) return $this->send(array('result' => 'success', 'load' => array('alert' => $this->lang->ticket->importReload, 'locate' => $this->session->ticketList ? $this->session->ticketList : $this->createlink('ticket', 'browse'))));

        $index = 1;
        foreach($ticketData->datas as $data)
        {
            $data->id   = $index ++;
            $data->pri  = empty($data->pri)  ? 3      : $data->pri;
            $data->type = empty($data->type) ? 'code' : $data->type;
        }
        unset($ticketData->fields['product']['items'][0]);

        $modulesProductMap = $this->loadModel('feedback')->getModuleList('ticket');
        foreach($modulesProductMap as $productID => $modules)
        {
            $items = array();
            foreach($modules as $moduleID => $moduleName) $items[] = array('text' => $moduleName, 'value' => $moduleID);
            $modulesProductMap[$productID] = $items;
        }

        list($buildsProductMap) = $this->ticket->getOpenedBuilds(false);
        foreach($buildsProductMap as $productID => $builds)
        {
            $items = array();
            foreach($builds as $buildID => $buildName) $items[] = array('text' => $buildName, 'value' => $buildID);
            $buildsProductMap[$productID] = $items;
        }

        $this->view->title             = $this->lang->ticket->common . $this->lang->hyphen . $this->lang->ticket->showImport;
        $this->view->datas             = $ticketData;
        $this->view->backLink          = inlink('browse');
        $this->view->modulesProductMap = $modulesProductMap;
        $this->view->buildsProductMap  = $buildsProductMap;
        $this->display();
    }
}
