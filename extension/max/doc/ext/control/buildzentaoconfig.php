<?php
class myDoc extends doc
{
    /**
     * @param string $type
     * @param int $oldBlockID
     */
    public function buildZentaoConfig($type, $oldBlockID = 0)
    {
        if($_POST)
        {
            $templateID = (int)$this->post->templateID;
            $doc = $this->dao->select('*')->from(TABLE_DOC)->where('id')->eq($templateID)->fetch();
            $isTemplate = $doc->templateType != '';

            if(!$this->docZen->checkBlockPriv($type)) return $this->send(array('result' => false, 'message' => sprintf($this->lang->docTemplate->noPriv, $this->lang->docTemplate->zentaoList[$type])));

            $docblock = new stdClass();
            $docblock->type     = $type;
            $docblock->doc      = $templateID;
            $docblock->settings = inlink('buildZentaoConfig', "type=$type&oldBlockID={blockID}");

            $blockContent = fixer::input('post')->remove('templateID')->get();
            if(!$isTemplate)
            {
                if($type == 'gantt')
                {
                    $blockContent->ganttOptions = $this->doc->getGanttData($this->post->project);
                    $blockContent->showFields   = $this->config->programplan->ganttCustom->ganttFields;
                    $blockContent->ganttFields  = $this->doc->getGanttFields();
                }
                else
                {
                    $getColsMethod = strpos(',HLDS,DDS,DBDS,ADS,', ",{$type},") !== false ? 'getDesignTableCols' : "get{$type}TableCols";
                    $getDataMethod = strpos(',HLDS,DDS,DBDS,ADS,', ",{$type},") !== false ? 'getDesignTableData' : "get{$type}TableData";
                    if(method_exists($this->doc, $getColsMethod)) $blockContent->cols = call_user_func_array(array($this->doc, $getColsMethod), array());
                    if(method_exists($this->doc, $getDataMethod))
                    {
                        $params = array();
                        foreach($this->config->doc->getTableDataParams[$type] as $paramKey) $params[$paramKey] = $this->post->$paramKey;
                        if(strpos(',HLDS,DDS,DBDS,ADS,', ",{$type},") !== false) $params['type'] = $type;

                        $contentData = call_user_func_array(array($this->doc, $getDataMethod), $params);
                        if(is_array($contentData) && isset($contentData['result']) && $contentData['result'] == 'fail') return $this->send($contentData);
                        $blockContent->data = $contentData;
                    }
                }
                $docblock->extra = 'fromTemplate';
            }

            $docblock->content = json_encode($blockContent);

            $this->dao->insert(TABLE_DOCBLOCK)->data($docblock)->exec();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $newBlockID = $this->dao->lastInsertId();

            $props = json_encode(array('isTemplate' => true));
            return $this->send(array('result' => 'success', 'closeModal' => true, 'callback' => "window.insertZentaoList('{$type}', $newBlockID, $props, $oldBlockID)"));
        }

        $searchTab  = '';
        $isTemplate = true;
        if($oldBlockID)
        {
            $docBlock  = $this->doc->getDocBlock($oldBlockID);
            $content   = json_decode($docBlock->content, true);
            $searchTab = zget($content, 'searchTab', '');

            $doc = $this->dao->select('*')->from(TABLE_DOC)->where('id')->eq($docBlock->doc)->fetch();
            $isTemplate = $doc->templateType != '';
            if(!$isTemplate)
            {
                if(strpos(',HLDS,DDS,DBDS,ADS,projectCase,projectStory,gantt,', ",{$type},") !== false)
                {
                    $this->view->projects = $type != 'gantt' ? $this->loadModel('project')->getPairs(false, 'haspriv') : $this->loadModel('project')->getPairsByModel(array('ipd', 'waterfall', 'waterfallplus'));
                    $this->view->project  = zget($content, 'project', 0);
                }
                if(strpos(',productStory,bug,productCase,HLDS,DDS,DBDS,ADS,projectCase,projectStory,', ",{$type},") !== false)
                {
                    $this->view->products = empty($this->view->project) ? $this->loadModel('product')->getPairs('', 0, '', $type == 'bug' ? 'all' : 0) : $this->loadModel('product')->getProductPairsByProject($this->view->project, 'all', '', true, true);
                    $this->view->product  = zget($content, 'product', 0);
                }
                if(strpos(',task,executionStory,', ",{$type},") !== false)
                {
                    $this->view->executions = $this->loadModel('execution')->getPairs();
                    $this->view->execution  = zget($content, 'execution', 0);
                }
            }
        }

        if($type == 'productCase' || $type == 'projectCase') $this->view->caseStage = !empty($content) ? zget($content, 'caseStage', '') : '';

        $this->view->type       = $type;
        $this->view->oldBlockID = $oldBlockID;
        $this->view->tabs       = zget($this->lang->docTemplate->searchTabList, $type, array());
        $this->view->searchTab  = $searchTab;
        $this->view->isTemplate = $isTemplate;
        $this->display();
    }
}
