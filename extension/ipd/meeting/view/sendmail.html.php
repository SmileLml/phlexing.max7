<?php
/**
 * The mail file of meeting module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuchun Li <liyuchun@easycorp.ltd>
 * @package     meeting
 * @version     $Id: sendmail.html.php 4129 2021-06-11 13:53:00 $
 * @link        https://www.zentao.net
 */
?>
<?php $mailTitle = 'MEETING #' . $object->id . ' ' . $object->name;?>
<?php include $this->app->getModuleRoot() . 'common/view/mail.header.html.php';?>
<tr>
  <td>
    <table cellpadding='0' cellspacing='0' width='600' style='border: none; border-collapse: collapse;'>
      <tr>
        <td style='padding: 10px; background-color: #F8FAFE; border: none; font-size: 14px; font-weight: 500; border-bottom: 1px solid #e5e5e5;'>
          <?php echo html::a($domain . helper::createLink('meeting', 'view', "meetingID=$object->id", 'html'), $mailTitle, '', "text-decoration: underline;'");?>
        </td>
      </tr>
    </table>
  </td>
</tr>
<tr>
  <td style='padding: 10px; border: none;'>
    <fieldset style='border: 1px solid #e5e5e5'>
      <legend style='color: #114f8e'><?php echo $this->lang->meeting->date;?></legend>
      <div style='padding:5px;'><?php echo $object->date . ' ' . $object->begin . ' ~ ' . $object->end;?></div>
    </fieldset>
  </td>
</tr>
<tr>
  <td style='padding: 10px; border: none;'>
    <fieldset style='border: 1px solid #e5e5e5'>
      <legend style='color: #114f8e'><?php echo $this->lang->meeting->mode;?></legend>
      <div style='padding:5px;'><?php echo zget($this->lang->meeting->modeList, $object->mode);?></div>
    </fieldset>
  </td>
</tr>
<?php if($object->room):?>
<tr>
  <td style='padding: 10px; border: none;'>
    <fieldset style='border: 1px solid #e5e5e5'>
      <legend style='color: #114f8e'><?php echo $this->lang->meeting->room;?></legend>
      <div style='padding:5px;'><?php echo zget($rooms, $object->room);?></div>
    </fieldset>
  </td>
</tr>
<?php endif;?>
<tr>
  <td style='padding: 10px; border: none;'>
    <fieldset style='border: 1px solid #e5e5e5'>
      <legend style='color: #114f8e'><?php echo $this->lang->meeting->host;?></legend>
      <div style='padding:5px;'><?php echo zget($users, $object->host);?></div>
    </fieldset>
  </td>
</tr>
<tr>
  <td style='padding: 10px; border: none;'>
    <fieldset style='border: 1px solid #e5e5e5'>
      <legend style='color: #114f8e'><?php echo $this->lang->meeting->participant;?></legend>
      <div style='padding:5px;'><?php echo $object->participantName;?></div>
    </fieldset>
  </td>
</tr>
<?php if($object->minutedBy):?>
<tr>
  <td style='padding: 10px; border: none;'>
    <fieldset style='border: 1px solid #e5e5e5'>
      <legend style='color: #114f8e'><?php echo $this->lang->meeting->minutedBy;?></legend>
      <div style='padding:5px;'><?php echo zget($users, $object->minutedBy);?></div>
    </fieldset>
  </td>
</tr>
<tr>
  <td style='padding: 10px; border: none;'>
    <fieldset style='border: 1px solid #e5e5e5'>
      <legend style='color: #114f8e'><?php echo $this->lang->meeting->minutes;?></legend>
      <div style='padding:5px;'><?php echo $object->minutes;?></div>
    </fieldset>
  </td>
</tr>
<?php endif;?>
<?php include $this->app->getModuleRoot() . 'common/view/mail.footer.html.php';?>
