<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php
    $webRoot = $this->app->getWebRoot();
    $jsRoot  = $webRoot . "js/";
    js::import($jsRoot . 'uploader/min.js');
    css::import($jsRoot . 'uploader/min.css');
    js::set('uid', $uid);
    js::set('module', $module);
    $urlParams = "module=$module&uid=$uid";
?>
<?php $requiredFields = $config->traincourse->create->requiredFields;?>
<div class="main-content" id="mainContent">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->traincourse->createCourse;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='ajaxForm'>
      <table class='table table-form'>
        <tbody>
          <tr>
            <th><?php echo $lang->traincourse->category;?></th>
            <td><?php echo html::select('category', $categoryPairs, '', "class='form-control chosen' autocomplete='off'"  . (strpos($requiredFields, 'category') !== false ? ' required' : ''));?></td>
            <td> </td>
          </tr>
          <tr>
            <th><?php echo $lang->traincourse->courseName;?></th>
            <td><?php echo html::input('name', '', "class='form-control' autocomplete='off' required");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->traincourse->teacher;?></th>
            <td><?php echo html::input('teacher', '', "class='form-control' autocomplete='off'"  . (strpos($requiredFields, 'teacher') !== false ? ' required' : ''));?></td>
          </tr>
          <tr>
            <th><?php echo $lang->traincourse->courseCover;?></th>
            <td>
              <div id='imageUploader' class="uploader" data-ride="uploader" data-url="<?php echo $this->createLink('file', 'ajaxUploadLargeFile', $urlParams)?>">
                  <div class="uploader-message text-center">
                    <div class="content"></div>
                    <button type="button" class="close">x</button>
                  </div>
                  <div class="uploader-files file-list file-list-grid" data-drag-placeholder="<?php echo $lang->traincourse->drag?>"></div>
                  <div class="uploader-actions">
                    <button type="button" class="btn btn-link uploader-btn-browse"><i class="icon icon-plus"></i> <?php echo $lang->traincourse->uploadCover?></button>
                    <button type='button' class='btn btn-link uploader-btn-start'><i class='icon icon-arrow-up'></i><?php echo $this->lang->file->beginUpload;?></button>
                  </div>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->traincourse->desc;?></th>
            <td colspan='2'><?php echo html::textarea('desc', '', "rows='6' class='form-control kindeditor' hidefocus='true'");?></td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan='3' class='text-center form-actions'>
              <?php echo html::submitButton();?>
              <?php echo html::backButton();?>
            </td>
          </tr>
        </tfoot>
      </table>
    </form>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
