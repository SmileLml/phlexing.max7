<?php
class myDesign extends design
{
    /**
     * Custom settings design type.
     *
     * @param  string lang2Set
     * @access public
     * @return void
     */
    public function setType($lang2Set = '')
    {
        $this->loadModel('custom');
        $lang = $this->app->getClientLang();
        if($_POST)
        {
            $data = fixer::input('post')->get();
            $this->custom->deleteItems("lang={$data->lang}&module=design&section=typeList");
            $this->custom->deleteItems("lang={$data->lang}&module=baseline&section=objectList");

            /* Create corresponding document templates. */
            $this->loadModel('baseline');
            foreach(array_filter($this->lang->baseline->objectList) as $baselineKey => $baseline)
            {
                if(empty($baselineKey) or empty($baseline)) continue;
                $this->custom->setItem("{$data->lang}.baseline.objectList.{$baselineKey}", $baseline);
            }

            foreach($data->keys as $index => $key)
            {
                $value = $data->values[$index];
                if(!$value or !$key) continue;
                $this->custom->setItem("{$data->lang}.design.typeList.{$key}", $value);

                /* Create corresponding document templates. */
                $this->custom->setItem("{$data->lang}.baseline.objectList.{$key}", $value . $this->lang->doc->common);
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('design', 'settype', 'lang2set=' . ($data->lang == 'all' ? $data->lang : '')) . "#app={$this->app->tab}"));
        }

        $this->loadModel('stage')->setMenu('waterfall');
        $this->view->title       = $this->lang->design->common . $this->lang->hyphen . $this->lang->design->type;
        $this->view->currentLang = $lang;
        $this->view->lang2Set    = !empty($lang2Set) ? $lang2Set : $lang;
        $this->view->section     = 'typeList';
        $this->display();
    }
}
