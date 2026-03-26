<?php
helper::importControl('instance');
class myInstance extends instance
{
    /**
       * 查看应用详情。
       * Show instance view.
       *
       * @param  int    $id
       * @param  string $type
       * @param  string $tab
       * @access public
       * @return void
       */
      public function view($id, $type = 'store', $tab = 'baseinfo')
      {
          if($type == 'store')
          {
              $instance = $this->instance->getByID($id);
              if($instance)
              {
                  $customFields = $this->loadModel('cne')->getCustomFields($instance);
                  foreach($customFields as $field) $this->lang->instance->{$field->name} = common::checkNotCN() ? $field->name : $field->label;
              }
          }

          parent::view($id, $type, $tab);
      }
}
