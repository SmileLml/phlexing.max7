<?php
class flow extends control
{
    /**
     * Get more fields by ajax.
     *
     * @param  string $module
     * @param  string $field
     * @param  string $keys
     * @access public
     * @return void
     */
    public function ajaxGetMore($module, $field, $keys = '')
    {
        $field = $this->loadModel('workflowfield')->getByField($module, $field);
        if(!$field)
        {
            $options = array('info' => $this->lang->noResultsMatch);
            die(json_encode($options));
        }

        $keys    = urldecode(helper::safe64Decode($keys));
        $search  = $this->get->search;
        $limit   = $this->get->limit;
        $options = $this->workflowfield->getFieldOptions($field, false, $keys, $search, $limit);

        die(json_encode($options));
    }
}
