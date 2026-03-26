<?php
class myCustom extends custom
{
    /**
     * Init company estimate unit.
     *
     * @access public
     * @return void
     */
    public function estimate()
    {
        /* Load config and set menu. */
        $this->app->loadLang('project');
        $this->app->loadConfig('project');
        $this->lang->navGroup->custom = 'admin';
        $this->lang->custom->menu = $this->lang->admin->menu;
        $unit = zget($this->config->custom, 'hourPoint', '1');

        if(strtolower($this->server->request_method) == "post")
        {
            /* Load module and get the data from the post. */
            $this->loadModel('setting');
            $data = fixer::input('post')->get();

            $this->setting->setItem('system.custom.hourPoint', $data->hourPoint);
            $this->setting->setItem('system.custom.scaleFactor', $data->scaleFactor);
            $this->setting->setItem('system.custom.cost', $data->cost);
            $this->setting->setItem('system.custom.efficiency', !empty($data->efficiency) ? $data->efficiency : 1);
            $this->setting->setItem('system.custom.days', $data->days);
            $this->setting->setItem('system.project.defaultWorkhours', $data->defaultWorkhours);

            /* Update story estimate field. */
            if($unit != $_POST['hourPoint'])
            {
                $stories = $this->dao->select('id,estimate')->from(TABLE_STORY)->fetchAll();
                foreach($stories as $story)
                {
                    $this->dao->update(TABLE_STORY)->set('estimate')->eq($story->estimate * $this->post->scaleFactor)->where('id')->eq($story->id)->exec();
                }
            }

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('custom', 'estimate')));
        }

        $this->view->title       = $this->lang->custom->common . $this->lang->hyphen . $this->lang->custom->estimateConfig;
        $this->view->position[]  = $this->lang->custom->common;
        $this->view->position[]  = $this->lang->custom->estimateConfig;

        $this->view->unit        = $unit;
        $this->view->cost        = zget($this->config->custom, 'cost', '');
        $this->view->efficiency  = zget($this->config->custom, 'efficiency', '');
        $this->view->scaleFactor = zget($this->config->custom, 'scaleFactor', '');
        $this->view->hours       = zget($this->config->project, 'defaultWorkhours', '');
        $this->view->days        = zget($this->config->custom, 'days', '');
        $this->display();
    }
}
