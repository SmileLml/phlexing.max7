<?php
class instanceInstanceZen extends instanceZen
{
    /**
     * 查看商店应用详情。
     * Show instance view.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function storeView($id)
    {
        parent::storeView($id);

        $instance = $this->view->instance;
        if($instance->source == 'system' && strpos($instance->chart, 'zentao') === 0)
        {
            $defaultMetric = new stdclass();
            $defaultMetric->rate  = 0;
            $defaultMetric->usage = 0;
            $defaultMetric->limit = 0;

            $instanceMetric = new stdclass();
            $instanceMetric->cpu    = $defaultMetric;
            $instanceMetric->memory = $defaultMetric;
            $instanceMetric->disk   = $defaultMetric;

            $newInstance = new stdclass();
            if($instance->appVersion != $this->config->version) $instance->appVersion = $newInstance->appVersion = $this->config->version;
            if(getenv('CHART_VERSION') && $instance->version != getenv('CHART_VERSION')) $instance->version = $newInstance->version = getenv('CHART_VERSION');

            if(isset($newInstance->appVersion) || isset($newInstance->version)) $this->instance->updateByID($id, $newInstance);
            if(dao::isError()) return false;

            $this->view->instance       = $instance;
            $this->view->defaultAccount = '';
            $this->view->instanceMetric = $instanceMetric;
            $this->view->dbList         = array();
            $this->view->zentaoApp      = $id;
        }
    }

    /**
     * 生成自定义配置表单的字段。
     * Generate custom fields of form.
     *
     * @param  int    $appID
     * @access public
     * @return bool
     */
    public function buildCustomConfig($appID)
    {
        $customFields = $this->session->{"instanceFields{$appID}"};
        if(empty($customFields))
        {
            $cloudApp = $this->loadModel('store')->getAppInfo($appID);
            if($cloudApp && !empty($cloudApp->custom_settings))
            {
                $this->session->set("instanceFields{$appID}", $cloudApp->custom_settings);
                $customFields = $cloudApp->custom_settings;
            }
        }
        if(empty($customFields)) return false;

        $isNotCN = common::checkNotCN();
        foreach($customFields as $field)
        {
            $this->lang->instance->{$field->name} = $isNotCN ? $field->name : $field->label;

            $fieldType = 'string';
            if($field->type == 'date')     $fieldType = 'date';
            if($field->type == 'datetime') $fieldType = 'datetime';
            if(strpos($field->type, 'date') !== false && empty($field->default)) $field->default = null;

            if($field->type == 'switch' || $field->type == 'switcher')
            {
                $fieldType = 'bool';
                $field->default = $field->default ? true : false;
            }

            $isMulti = $field->style == 'multi' || $field->type == 'checkbox' || $field->type == 'checkList';
            $this->config->instance->form->custom[$field->name] = array(
                'type'      => $isMulti ? 'array' : $fieldType,
                'default'   => zget($field, 'default', ''),
                'separator' => $isMulti ? zget($field, 'separator', ',') : '',
                'required'  => empty($field->depends) ? zget($field, 'required', true) : false
            );
            if($fieldType != 'bool') $this->config->instance->form->custom[$field->name]['filter'] = $isMulti ? 'join' : 'trim';

            if(!empty($field->depends) && !empty($field->required))
            {
                foreach($field->depends as $depend)
                {
                    $dependValue = $this->post->{$depend->key};
                    if($depend->operator == 'eq' && $dependValue != $depend->value) continue 2;
                    if($depend->operator == 'ne' && $dependValue == $depend->value) continue 2;
                    if($depend->operator == 'gt' && $dependValue <= $depend->value) continue 2;
                    if($depend->operator == 'lt' && $dependValue >= $depend->value) continue 2;
                    if($depend->operator == 'in' && strpos(",$dependValue,", ",$depend->value,") === false) continue 2;
                }

                $this->config->instance->form->custom[$field->name]['required'] = true;
            }
        }

        return !dao::isError();
    }

    /**
     * 检查自定义配置字段。
     * Check custom fields.
     *
     * @param  int    $appID
     * @access public
     * @return void
     */
    public function checkCustomFields($appID)
    {
        $customFields = $this->session->{"instanceFields{$appID}"};
        if(empty($customFields) || empty($this->config->instance->form->custom)) return false;

        $formData = form::data($this->config->instance->form->custom)->get();
        foreach($customFields as $field)
        {
            $fieldValue = $formData->{$field->name};
            if(!empty($field->validators) && !empty($fieldValue))
            {
                foreach((array)$field->validators as $validator)
                {
                    if(!validater::checkByRule($fieldValue, $validator))
                    {
                        dao::$errors[$field->name][] = sprintf($this->lang->instance->errors->invalidData, $this->lang->instance->{$field->name});
                    }
                }
            }

            if(!empty($field->depends))
            {
                $setEmpty = false;
                foreach($field->depends as $depend)
                {
                    $dependValue = $this->post->{$depend->key};
                    if($depend->operator == 'eq' && $dependValue != $depend->value) $setEmpty = true;
                    if($depend->operator == 'ne' && $dependValue == $depend->value) $setEmpty = true;
                    if($depend->operator == 'gt' && $dependValue <= $depend->value) $setEmpty = true;
                    if($depend->operator == 'lt' && $dependValue >= $depend->value) $setEmpty = true;
                    if($depend->operator == 'in' && strpos(",$dependValue,", ",$depend->value,") === false) $setEmpty = true;
                }

                if($setEmpty)
                {
                    unset($this->config->instance->form->custom[$field->name]);
                    continue;
                }
            }

            if((empty($fieldValue) && empty($field->default)) || $fieldValue == $field->default)
            {
                unset($this->config->instance->form->custom[$field->name]);
                continue;
            }
        }
        return !dao::isError();
    }
}
