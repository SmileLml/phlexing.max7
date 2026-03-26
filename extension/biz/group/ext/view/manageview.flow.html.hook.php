<?php
$primaryFlows = $this->dao->select('module,name')->from(TABLE_WORKFLOW)->where('navigator')->eq('primary')->andWhere('buildin')->eq('0')->fetchAll();
foreach($primaryFlows as $primaryFlow):
if(!common::hasPriv($primaryFlow->module, 'browse')):
?>
<?php $className = (isset($group->acl['views'][$primaryFlow->module]) or empty($group->acl['views'])) ? "checked" : '';?>
<?php
$html = <<<EOD
<div class='group-item'>
  <div class='checkbox-primary'>
    <input type='checkbox' id='{$primaryFlow->module}' name='actions[views][{$primaryFlow->module}]' value='{$primaryFlow->module}' $className>
    <label class='priv' for='{$primaryFlow->module}'>{$primaryFlow->name} </label>
  </div>
</div>
EOD;
?>
<script>
    $('.group-item:last').before(<?php echo json_encode($html)?>);
</script>
<?php endif;?>
<?php endforeach;?>
