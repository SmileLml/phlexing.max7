<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<style>
.story{border: 1px solid #dcdcdc;}
.story div{padding: 16px;}
.story .title{background-color: #e5e6e8;}
.story .content{background-color: #f4f5f7;}
.confirmButton{margin-top: 20px;}
.body-modal #mainContent {padding-top: 20px;}
#mainContent .main-header{padding: 20px 40px; border: 0px;display: contents;}
#mainContent .outline{color: #838a9d;}
.body-modal .main-header>h2{white-space: normal;}
</style>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <i class="icon icon-exclamation text-warning"></i>
        <span class='outline'><?php echo sprintf($lang->story->confirmRetractTip, $lang->$objectType->common);?></span>
      </h2>
    </div>
    <div class='main-content'>
      <?php foreach($stories as $story):?>
      <div class='story'>
        <div class='title'>
          <span class='label label-id'><?php echo $story->id;?></span>
          <?php echo $story->title;?>
        </div>
        <div class='content'>
           <p>[<?php echo $lang->story->legendSpec;?>]</p>
           <span><?php echo $story->spec;?></span>
           <p>[<?php echo $lang->story->legendVerify;?>]</p>
           <span><?php echo $story->verify;?></span>
        </div>
      </div>
      <?php endforeach;?>
      <form method='post' target='hiddenwin' class='text-center confirmButton'>
        <?php echo html::hidden('confirm', 1);?>
        <?php echo html::submitButton($lang->confirm);?>
      </form>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
