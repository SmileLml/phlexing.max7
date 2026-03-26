<?php
class myDoc extends doc
{
    /**
     * 选择文档模板。
     * Select template.
     *
     * @access public
     * @param  string $scopeID
     * @param  string $searchName
     * @return void
     */
    public function selectTemplate($scopeID = 'all', $searchName = '')
    {
        if(!empty($_POST['templateID']))
        {
            return $this->send(array('result' => 'success', 'closeModal' => true, 'docApp' => array('executeCommand', 'openCreateDocModal', array($_POST['templateID']))));
        }

        $templateList = $this->doc->getDocTemplateList((int)$scopeID, 'released', 'id_desc', null, $searchName);
        $templateList = $this->doc->filterDeletedDocs($templateList);
        $templateList = $this->doc->filterPrivDocs($templateList, 'template');
        foreach($templateList as $id => $template)
        {
            if(!empty($template->parent))
            {
                unset($templateList[$id]);
                unset($templateList[$template->parent]);
            }
        }

        $scopeList = $this->doc->getTemplateScopes();
        foreach($scopeList as $scope) $this->lang->doc->featureBar['selecttemplate'][$scope->id] = $scope->name;

        $this->view->templateList = $templateList;
        $this->view->scopeID      = $scopeID;
        $this->view->searchName   = $searchName;
        $this->display();
    }
}
