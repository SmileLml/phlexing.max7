<?php
/**
 * The edit view file of pivot of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     pivot
 * @version     $Id: browse.html.php 5096 2013-07-11 07:02:43Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<style>
#main{min-width: 500px !important; padding: unset;}
#main>.container {padding: unset;}
</style>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $title;?></h2>
    </div>
  </div>
    <form class='form-ajax' method='post'>
      <table class="table table-form">
        <tr>
          <th><?php echo $lang->pivot->group;?></th>
          <td>
            <?php echo html::select('group[]', $groups, $pivot->group, "class='chosen form-control' data-max_drop_width='200' multiple");?>
          </td>
        </tr>
        <tr>
          <th class='namePadding'><?php echo $lang->pivot->name;?></th>
          <td class='required requiredPadding'>
            <ul class='nav nav-tabs'>
              <?php $clientLang = $this->app->getClientLang();?>
              <?php foreach($config->langs as $langKey => $currentLang):?>
              <?php $active = $langKey == $clientLang ? 'active' : ''?>
              <li class='<?php echo $active;?>'><?php echo html::a('#name'. str_replace('-', '_', $langKey), $currentLang, '', "data-toggle='tab'");?></li>
              <?php endforeach;?>
            </ul>
            <div class='tab-content'>
              <?php foreach($config->langs as $langKey => $currentLang):?>
              <?php $active = $langKey == $clientLang ? 'active' : ''?>
              <div class='tab-pane <?php echo $active?>' id='name<?php echo str_replace('-', '_', $langKey);?>'>
                <?php echo html::input("name[$langKey]", zget($pivot->names, $langKey, ''), "id='name{$langKey}' class='form-control'");?>
              </div>
              <?php endforeach;?>
            </div>
            <div id='name'></div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->pivot->desc;?></th>
          <td>
            <ul class='nav nav-tabs'>
              <?php $clientLang = $this->app->getClientLang();?>
              <?php foreach($config->langs as $langKey => $currentLang):?>
              <?php $active = $langKey == $clientLang ? 'active' : ''?>
              <li class='<?php echo $active;?>'><?php echo html::a('#desc'. str_replace('-', '_', $langKey), $currentLang, '', "data-toggle='tab'");?></li>
              <?php endforeach;?>
            </ul>
            <div class='tab-content'>
              <?php foreach($config->langs as $langKey => $currentLang):?>
              <?php $active = $langKey == $clientLang ? 'active' : ''?>
              <div class='tab-pane <?php echo $active?>' id='desc<?php echo str_replace('-', '_', $langKey);?>'>
                <?php echo html::textarea("desc[$langKey]", zget($pivot->descs, $langKey, ''), "id='desc{$langKey}' rows='7' class='form-control'");?>
              </div>
              <?php endforeach;?>
            </div>
            <div id='desc'></div>
          </td>
        </tr>
        <tr>
          <td colspan='2' class='form-actions text-center'>
            <?php echo html::submitButton();?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
