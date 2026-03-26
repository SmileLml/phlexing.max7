<?php
class execution extends control
{
    /**
     * Kanban setting.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function ajaxKanbanSetting($executionID)
    {
        if($_POST)
        {
            $this->loadModel('setting');
            $data = fixer::input('post')
                ->setDefault('laneField', 'status')
                ->get();

            if(common::hasPriv('execution', 'setLaneFields'))
            {
                /* Save lane field. */
                $this->setting->setItem("system.execution.kanbanSetting.laneField", $data->laneField);

                if($data->laneField == 'subStatus')
                {
                    if(!$this->post->subStatus) die(js::alert($this->lang->kanbanSetting->emptyColumns));

                    /* Save sub status to show. */
                    $this->setting->setItem("system.execution.kanbanSetting.subStatus", json_encode($data->subStatus));
                }
            }
            if(common::hasPriv('execution', 'kanbanHideCols') && $data->laneField == 'status')
            {
                $allCols = $data->allCols;
                $this->setting->setItem("system.execution.kanbanSetting.allCols", $allCols);
            }
            if(common::hasPriv('execution', 'kanbanColsColor'))
            {
                /* Save colors of status. */
                if($data->laneField == 'status') $this->setting->setItem("system.execution.kanbanSetting.colorList", json_encode($data->colorList));

                /* Save colors of sub status. */
                if($data->laneField == 'subStatus') $this->setting->setItem("system.execution.kanbanSetting.subStatusColor", json_encode($data->subStatusColor));
            }

            die(js::reload('parent.parent'));
        }

        $this->app->loadLang('task');

        $this->view->setting     = $this->execution->getKanbanSetting();
        $this->view->executionID = $executionID;
        $this->display();
    }
}
