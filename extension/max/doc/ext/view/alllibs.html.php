<?php if($type != 'book'):?>
  <?php include $app->getModuleRoot() . 'doc/view/alllibs.html.php';?>
<?php else:;?>
  <?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
  <div class="main-row fade" id="mainRow">
    <?php include $app->getModuleRoot() . 'doc/view/side.html.php';?>
    <?php if($this->cookie->browseType == 'bylist'):?>
    <?php include dirname(__FILE__) . '/alllibsbylist.html.php';?>
    <?php else:?>
    <?php include dirname(__FILE__) . '/alllibsbygrid.html.php';?>
    <?php endif;?>
  </div>
  <?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
<?php endif;?>
<?php $spliter = (empty($this->app->user->feedback) && !$this->cookie->feedbackView) ? true : false;?>
<?php if(!$spliter):?>
<script>
$('.side-col .cell .side-footer').remove();
$('.side-col .cell .nav li').each(function(index)
{
    if(index == 0 || index == 1) $(this).remove();
})
</script>
<?php endif;?>
