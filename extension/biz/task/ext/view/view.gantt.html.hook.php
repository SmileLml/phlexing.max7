<?php $startMessage = $this->task->checkDepend($task->id, 'begin');?>
<?php $finishMessage = $this->task->checkDepend($task->id, 'end');?>
<?php if($startMessage or $finishMessage):?>
<style>
.main-actions .btn-toolbar .tooltip+.btn { margin-left: 10px;}
.main-actions .btn-toolbar .tooltip .tooltip-inner{text-align:left;}
 </style>
<script>
$(function()
{
    <?php if($startMessage):?>
    var $startA = $('.main-actions .btn-toolbar .icon-task-start').closest('a');
    $startA.tooltip({title:<?php echo json_encode(str_replace('\n', "", $startMessage));?>, tipClass:'tooltip-warning'});
    $startA.unbind('click');
    $startA.click(function(){ return false;});
    <?php endif;?>
    <?php if($finishMessage):?>
    var $finishA = $('.main-actions .btn-toolbar .icon-task-finish').closest('a');
    $finishA.tooltip({title:<?php echo json_encode(str_replace('\n', "", $finishMessage));?>, tipClass:'tooltip-warning'});
    $finishA.unbind('click');
    $finishA.click(function(){ return false;});
    <?php endif;?>
})
</script>
<?php endif;?>
