<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php
    $webRoot = $this->app->getWebRoot();
    $jsRoot  = $webRoot . "js/";
    js::import($jsRoot . 'uploader/min.js');
    css::import($jsRoot . 'uploader/min.css');
    $uid = uniqid();
    $urlParams = "module=traincourse&uid=$uid";
?>
<style>
.ke-container {height:150px;}
</style>
<?php $requiredFields = $config->traincourse->edit->requiredFields;?>
<div class="center-block" id="mainContent">
  <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='ajaxForm' action='<?php echo inlink('editCourse', "courseID=$course->id");?>'>
    <table class='table table-form'>
      <tbody>
        <tr>
          <th><?php echo $lang->traincourse->category;?></th>
          <td><?php echo html::select('category', $categoryPairs, $course->category, "class='form-control chosen' autocomplete='off'" . (strpos($requiredFields, 'category') !== false ? ' required' : ''));?></td>
          <td> </td>
        </tr>
        <tr>
          <th><?php echo $lang->traincourse->name;?></th>
          <td><?php echo html::input('name', $course->name, "class='form-control' autocomplete='off' required");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->traincourse->teacher;?></th>
          <td><?php echo html::input('teacher', $course->teacher, "class='form-control' autocomplete='off'" . (strpos($requiredFields, 'teacher') !== false ? ' required' : ''));?></td>
        </tr>
        <tr>
          <th><?php echo $lang->traincourse->courseCover;?></th>
          <td>
            <div id='imageUploader' class="uploader" data-ride="uploader" data-url="<?php echo $this->createLink('file', 'ajaxUploadLargeFile', $urlParams)?>">
                <div class="uploader-message text-center">
                  <div class="content"></div>
                  <button type="button" class="close">x</button>
                </div>
                <?php if(!empty($file)):?>
                <div class="uploader-files file-list file-list-grid">
                  <img class='demoImage' src='<?php echo $file->webPath;?>' controls='controls' width='100%'/>
                </div>
                <?php else:?>
                <div class="uploader-files file-list file-list-grid" data-drag-placeholder="<?php echo $this->lang->file->drag?>"></div>
                <?php endif;?>
                <div class="uploader-actions">
                  <button type="button" class="btn btn-link uploader-btn-browse"><i class="icon icon-plus"></i> <?php echo $lang->traincourse->uploadCover?></button>
                  <button type='button' class='btn btn-link uploader-btn-start'><i class='icon icon-arrow-up'></i><?php echo $this->lang->file->beginUpload;?></button>
                </div>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->traincourse->desc;?></th>
          <td colspan='2'><?php echo html::textarea('desc', $course->desc, "class='form-control kindeditor'" . (strpos($requiredFields, 'desc') !== false ? ' required' : ''));?></td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan='3' class='text-center form-actions'> <?php echo html::submitButton();?> </td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.modal.html.php';?>
