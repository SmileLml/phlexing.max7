<?php
class excelFeedback extends feedbackModel
{
    /**
     * Set export list value
     *
     * @access public
     * @return void
     */
    public function setListValue()
    {
        /* Group by module for cascade. */
        $moduleList        = array();
        $modulesProductMap = $this->loadModel('feedback')->getModuleList('feedback');
        foreach($modulesProductMap as $productID => $modules)
        {
            if(empty($modules)) continue;
            foreach($modules as $moduleID => $moduleName)
            {
                if($this->post->fileType == 'xlsx')
                {
                    $moduleList[$productID][$moduleID] = $moduleName . "(#$moduleID)";
                }
                else
                {
                    $moduleList[$moduleID] = $moduleName . "(#$moduleID)";
                }
            }
        }

        $this->post->set('moduleList',  $moduleList);
    }
}
