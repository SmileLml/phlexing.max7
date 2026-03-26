<?php
/**
 * The batch create view of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Zeng
 * @package     user
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php if(empty($maxImport) and $allCount > $this->config->file->maxImport):?>
<div id="mainContent" class="main-content">
  <div class="main-header">
    <h2><?php echo $lang->user->batchImport;?></h2>
  </div>
  <p><?php echo sprintf($lang->file->importSummary, $allCount, html::input('maxImport', $config->file->maxImport, "style='width:50px'"), ceil($allCount / $config->file->maxImport));?></p>
  <p><?php echo html::commonButton($lang->import, "id='import'", 'btn btn-primary');?></p>
</div>
<script>
$(function()
{
    $('#maxImport').keyup(function()
    {
        if(parseInt($('#maxImport').val())) $('#times').html(Math.ceil(parseInt($('#allCount').html()) / parseInt($('#maxImport').val())));
    });
    $('#import').click(function(){location.href = createLink('user', 'showImport', "pageID=1&maxImport=" + $('#maxImport').val())})
});
</script>
<?php else:?>
<?php js::import($jsRoot . 'md5.js');?>
<?php js::set('roleGroup', $roleGroup);?>
<div id="mainContent" class="main-content">
  <div class="main-header">
    <h2><?php echo $lang->user->batchImport;?></h2>
    <?php if($userAddWarning):?>
    <div class="text-danger" style="float:left;line-height:35px;"><?php echo $userAddWarning;?></div>
    <?php endif;?>
  </div>
  <?php
  $visibleFields  = array();
  $requiredFields = array();
  foreach(explode(',', $showFields) as $field)
  {
      if(strpos(",{$config->user->availableBatchCreateFields},", ",{$field},") === false) continue;
      if($field) $visibleFields[$field] = '';
  }

  foreach(explode(',', $config->user->create->requiredFields) as $field)
  {
      if($field)
      {
          $requiredFields[$field] = '';
          if(strpos(",{$config->user->availableBatchCreateFields},", ",{$field},") !== false) $visibleFields[$field] = '';
      }
  }
  $minWidth = (count($visibleFields) > 3) ? 'w-150px' : '';
  $showVisionList = count($visionList) > 1;
  ?>
  <form method='post' class='load-indicator main-form' enctype='multipart/form-data' target='hiddenwin' id="batchCreateForm">
    <div class="table-responsive">
      <table class="table table-form">
        <thead>
          <tr class='text-center'>
            <th class='c-id'><?php echo $lang->idAB;?></th>
            <th class='c-dept<?php echo zget($visibleFields, 'dept', ' hidden') . zget($requiredFields, 'dept', '', ' required');?>'>              <?php echo $lang->user->dept;?></th>
            <th class='accountThWidth required'><?php echo $lang->user->account;?></th>
            <th class='c-realname required'><?php echo $lang->user->realname;?></th>
            <?php if($showVisionList):?>
              <th class='c-visions required'><?php echo $lang->user->visions;?></th>
            <?php endif;?>
            <th class='c-role<?php echo zget($requiredFields, 'role', '', ' required')?>'><?php echo $lang->user->role;?></th>
            <th class='c-type'><?php echo $lang->user->type;?></th>
            <th class='c-group'><?php echo $lang->user->group;?></th>
            <th class='<?php echo zget($visibleFields, 'email', "$minWidth hidden", $minWidth) . zget($requiredFields, 'email', '', ' required')?>'><?php echo $lang->user->email;?></th>
            <th class='genderThWidth<?php echo zget($visibleFields, 'gender', ' hidden')?>'><?php echo $lang->user->gender;?></th>
            <th class="<?php echo $minWidth;?> required"><?php echo $lang->user->password;?></th>
            <th class='c-join'><?php echo $lang->user->join;?></th>
            <th class='c-contact'><?php echo $lang->user->qq;?></th>
            <th class='c-contact'><?php echo $lang->user->weixin;?></th>
            <th class='c-contact'><?php echo $lang->user->mobile;?></th>
            <th class='c-contact'><?php echo $lang->user->phone;?></th>
            <th class='c-contact'><?php echo $lang->user->address;?></th>
          </tr>
        </thead>
        <tbody>
        <?php $insert = true;?>
        <?php $depts = $depts + array('ditto' => $lang->user->ditto)?>
        <?php $lang->user->roleList = $lang->user->roleList + array('ditto' => $lang->user->ditto)?>
        <?php $groupList  = $groupList + array('ditto' => $lang->user->ditto);?>
        <?php $visionList = $visionList + array('ditto' => $lang->user->ditto);?>
        <?php $i = 1;?>
        <?php foreach($userData as $key => $user):?>
        <?php
        if(empty($user->dept))     $user->dept     = 0;
        if(empty($user->account))  $user->account  = '';
        if(empty($user->realname)) $user->realname = '';
        if(empty($user->role))     $user->role     = '';
        if(empty($user->email))    $user->email    = '';
        if(empty($user->join))     $user->join     = '';
        if(empty($user->qq))       $user->qq       = '';
        if(empty($user->weixin))   $user->weixin   = '';
        if(empty($user->mobile))   $user->mobile   = '';
        if(empty($user->phone))    $user->phone    = '';
        if(empty($user->address))  $user->address  = '';
        ?>
        <tr class='text-center'>
          <td><?php echo $i;?></td>
          <td class='text-left' style='overflow:visible'><?php echo html::select("dept[$i]", $depts,($i > 1 and !$user->dept) ? 'ditto' : $user->dept, "class='form-control chosen'");?></td>
          <td><?php echo html::input("account[$i]", $user->account, "class='form-control account_$i' onchange='changeEmail($i)'");?></td>
          <td><?php echo html::input("realname[$i]", $user->realname, "class='form-control'");?></td>
          <?php if($showVisionList):?>
            <td class='text-left' style='overflow:visible'>
              <?php echo html::select("visions[$i][]", $visionList, $i > 1 ? 'ditto' : (isset($visionList[$this->config->vision]) ? $this->config->vision : key($visionList)), "class='form-control chosen' multiple");?>
            </td>
          <?php else:?>
            <?php echo html::hidden("visions[$i][]", $this->config->vision);?>
          <?php endif;?>
          <td><?php echo html::select("role[$i]", $lang->user->roleList, ($i > 1 and !$user->role) ? 'ditto' : $user->role, "class='form-control' onchange='changeGroup(this.value, $i)'");?></td>
          <td><?php echo html::select("type[$i]", array('inside' => $lang->user->inside, 'outside' => $lang->user->outside), $user->type, "class='form-control'");?></td>
          <td class='text-left' style='overflow:visible'><?php echo html::select("group[$i][]", $groupList, $i > 1 ? 'ditto' : '', "class='form-control chosen' multiple");?></td>
          <td><?php echo html::input("email[$i]", $user->email, "class='form-control email_$i' onchange='setDefaultEmail($i)'");?></td>
          <td><?php echo html::radio("gender[$i]", (array)$lang->user->genderList, !empty($user->gender) ? $user->gender : 'm');?></td>
          <td align='left'>
            <div class='input-group'>
            <?php
            echo html::input("password[$i]", '', "class='form-control' onkeyup='toggleCheck(this, $i)' oninput=\"this.value = this.value.replace(/[^\\x00-\\xff]/g, '');\"");
            echo "<span class='input-group-addon passwordStrength'></span>";
            if($i != 1) echo "<span class='input-group-addon passwordBox'><input type='checkbox' name='ditto[$i]' id='ditto$i' " . ($i > 1 ? "checked" : '') . " /> {$lang->user->ditto}</span>";
            ?>
            </div>
          </td>
          <td><?php echo html::input("join[$i]", $user->join, "class='form-control form-date'");?></td>
          <td><?php echo html::input("qq[$i]", $user->qq, "class='form-control'");?></td>
          <td><?php echo html::input("weixin[$i]", $user->weixin, "class='form-control'");?></td>
          <td><?php echo html::input("mobile[$i]", $user->mobile, "class='form-control'");?></td>
          <td><?php echo html::input("phone[$i]", $user->phone, "class='form-control'");?></td>
          <td><?php echo html::input("address[$i]", $user->address, "class='form-control'");?></td>
        </tr>
        <?php $i ++;?>
        <?php endforeach;?>
        <tr>
          <th colspan='2'><?php echo $lang->user->verifyPassword?></th>
          <td colspan='3'>
            <div class="required required-wrapper"></div>
            <input type='password' style="display:none"> <!-- for disable autocomplete all browser -->
            <?php echo html::password('verifyPassword', '', "class='form-control disabled-ie-placeholder' placeholder='{$lang->user->placeholder->verify}'");?>
          </td>
        </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="<?php echo count($visibleFields)?>" class="text-center form-actions">
              <?php
              $submitText  = $isEndPage ? $this->lang->save : $this->lang->file->saveAndNext;
              $isStartPage = $pagerID == 1 ? true : false;
              if(!$insert and $dataInsert === '')
              {
                echo "<button type='button' data-toggle='modal' data-target='#importNoticeModal' class='btn btn-primary btn-wide'>{$submitText}</button>";
              }
              else
              {
                  echo html::submitButton($submitText);
                  if($dataInsert !== '') echo html::hidden('insert', $dataInsert);
              }
              echo html::hidden('isEndPage', $isEndPage ? 1 : 0);
              echo html::hidden('pagerID', $pagerID);
              ?>
              <?php echo html::linkButton($lang->goback, $this->createLink('company', 'browse'), 'self', '', 'btn btn-back btn-wide');?>
            </td>
          </tr>
        </tfoot>
      </table>
      <?php if(!$insert and $dataInsert === '') include $app->getModuleRoot() . 'common/view/noticeimport.html.php';?>
    </div>
  </form>
</div>
<?php echo html::hidden('verifyRand', $rand);?>
<?php js::set('passwordStrengthList', $lang->user->passwordStrengthList)?>
<?php js::set('batchCreateCount', $config->user->batchCreate)?>
<?php endif;?>

<?php if(!empty($properties['user'])):?>
<?php
$userMaxCount = $properties['user']['value'];
$userCount    = $this->dao->select("COUNT('*') as count")->from(TABLE_USER)
    ->where('deleted')->eq(0)
    ->fetch('count');
js::set('userCount', $userCount);
js::set('userMaxCount', $userMaxCount);
js::set('noticeUserCreate', str_replace('%maxcount%', $userMaxCount, $lang->user->noticeUserCreate));
?>
<script>
$(function()
{
    $('#submit').click(function()
    {
        if(userMaxCount > 0)
        {
            var allUserCount = parseInt(userCount);

            $('[id^="account"]').each(function()
            {
                if($(this).val()) allUserCount += 1;
            });

            if(allUserCount > userMaxCount)
            {
                alert(noticeUserCreate.replace('%usercount%', allUserCount));
                return false;
            }
        }
    })
})
</script>
<?php endif;?>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
