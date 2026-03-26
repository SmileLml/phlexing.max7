<?php
class instanceCne extends cneModel
{
    /**
     * 获取应用字段配置。
     * Get the application field configuration.
     *
     * @param  object       $instance
     * @access public
     * @return array
     */
    public function getCustomFields($instance)
    {
        $apiParams = new stdclass();
        $apiParams->cluster   = '';
        $apiParams->namespace = $instance->spaceData->k8space;
        $apiParams->name      = $instance->k8name;
        $apiParams->channel   = $this->config->CNE->api->channel;

        $apiUrl = '/api/cne/app/settings/custom';
        $result = $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if($result && $result->code != 200) return array();

        $fields = array();
        $isNotCn   = common::checkNotCN();
        foreach($result->data as $field)
        {
            if($isNotCn) $field->label = $field->name;
            if(!isset($field->value)) $field->value = $field->default;
            $fields[] = $field;
        }
        return $fields;
    }
}
