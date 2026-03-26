<?php
class myScreen extends control
{
    /**
     * Ajax get options.
     *
     * @access public
     * @return void
     */
    public function ajaxGetOptions()
    {
        $fieldStr = $this->post->field;
        if(empty($fieldStr))
        {
            echo('[]');
            return;
        }

        list($table, $field) = explode('.', $fieldStr);

        if(!isset($this->config->screen->fieldConfig->$table) or !isset($this->config->screen->fieldConfig->$table->options[$field]))
        {
            echo('[]');
            return;
        }

        $typeConfig = $this->config->screen->fieldConfig->$table->options[$field];
        $options = array();
        if($typeConfig['type'] == 'lang')
        {
            $options = $typeConfig['options'];
        }
        else
        {
            $options = $this->screen->getSysOptions($typeConfig['options']);
        }

        $formatOptions = array();
        foreach($options as $value => $label)
        {
            $formatOptions[] = array('label' => $label, 'value' => $value);
        }

        echo(json_encode($formatOptions));
    }
}
