<?php
class api extends control
{
    /**
     * Comment.
     * 
     * @param  int    $id
     * @param  string $type  task / bug / todo / story / project / product
     * @param  string $content
     * @param  string $user
     * @access public
     * @return void
     */
    public function mobileGetCustom($module = 'common', $lang = 'zh_cn')
    {
        if($this->get->lang and $this->get->lang != $this->app->getClientLang())
        {
            $this->app->setClientLang($this->get->lang);
            $this->app->loadLang('api');
        }

        if(!$this->loadModel('user')->isLogon()) die(json_encode(array('status' => 'failed', 'reason' => $this->lang->api->failLogin)));

        $data = array();
        $this->app->loadLang('custom');
        $this->loadModel('custom');
        if(!$module || $module === 'all') $module = array_keys($this->lang->custom->object);
        else if($module === 'common') $module = 'story,task,bug,todo,user';
        if(is_string($module)) $module = explode(',', $module);
        foreach ($module as $moduleName)
        {
            $this->app->loadLang($moduleName);
            $moduleData = array();
            foreach ($this->lang->custom->$moduleName->fields as $field => $fieldText)
            {
                $dbFields = $this->custom->getItems("lang=$lang&module=$moduleName&section=$field");
                if(empty($dbFields)) $dbFields = $this->custom->getItems("lang=all&module=$moduleName&section=$field");
                $fields = $this->lang->$moduleName->$field;
                foreach ($dbFields as $fieldObj) $fields[$fieldObj->key] = $fieldObj->value;
                $moduleData[$field] = $fields;
            }
            $data[$moduleName] = $moduleData;
        }

        die($this->api->compression($data));
    }
}
