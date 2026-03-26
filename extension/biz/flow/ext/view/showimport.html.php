<?php
/**
 * The show import view file of flow module of ZDOO.
 *
 * @copyright   Copyright 2009-2018 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     flow
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::import($jsRoot . 'md5.js');?>
<?php js::set('moduleName', $flow->module);?>
<?php js::set('buildin', $flow->buildin);?>
<div class='panel'>
  <div class='panel-heading'>
    <strong><?php echo $lang->flow->showImport;?></strong>
  </div>
  <div class='panel-body'>
    <form id='ajaxForm' method='post'>
      <div class="table-responsive" style="overflow: auto;">
        <?php $insert = true;?>
        <?php if($mode == 'template') include '../../view/templateimport.html.php';?>
        <?php
        if($mode == 'auto')
        {
            include './autoimport.html.php';
            foreach($dataList as $key => $data)
            {
                if(!empty($data['id']))
                {
                    $insert = false;
                    break;
                }
            }
        }
        ?>
        <div class='form-actions text-center'>
          <?php
          $submitBtnHtml = "<button type='button' data-toggle='modal' data-target='#importNoticeModal' class='btn btn-primary btn-wide'>{$lang->save}</button>";
          if($insert) $submitBtnHtml = html::submitButton($lang->import);
          echo $submitBtnHtml;
          echo html::backButton();
          ?>
        </div>
      </div>
      <?php if(!$insert) include $app->getModuleRoot() . 'common/view/noticeimport.html.php';?>
    </form>
  </div>
</div>
<script>
$.fn.extend(
{
    datatable:function(e,s){return false;}
});
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
