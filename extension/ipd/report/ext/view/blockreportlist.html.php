<div class='panel'>
  <div class='panel-heading'>
    <div class='panel-title'><?php echo $lang->report->list;?></div>
  </div>
  <div class='panel-body'>
    <div class='list-group'>
      <?php
      if(isset($lang->reportList->$submenu->lists))
      {
          ksort($lang->reportList->$submenu->lists);
          foreach($lang->reportList->$submenu->lists as $list)
          {
              $list .= '|';
              list($label, $module, $method, $requestParams) = explode('|', $list);
              $requestParams = $requestParams ? $requestParams : "projectID=$projectID";
              $class  = $label == $title ? 'selected' : '';
              if(common::hasPriv($module, $method)) echo html::a($this->createLink($module, $method, $requestParams), '<i class="icon icon-file-text"></i> ' . $label, '', "class='$class' title='$label' data-app='{$app->tab}'");
          }
      }
      ?>
    </div>
  </div>
</div>
