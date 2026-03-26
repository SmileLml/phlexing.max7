<?php
/**
 * The mail file of makeup module of RanZhi.
 *
 * @copyright   Copyright 2009-2018 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     makeup
 * @version     $Id$
 * @link        http://www.ranzhi.org
 */
?>
<?php
$app->loadConfig('mail');

$domain     = zget($this->config->mail, 'domain', common::getSysURL());
$mailTitle  = "{$lang->makeup->common}#{$makeup->id} ";
$mailTitle .= html::a($domain . helper::createLink('my', 'review', "type=makeup", 'html') . "#app=oa", zget($users, $makeup->createdBy));
$mailTitle .= " {$makeup->begin}~{$makeup->end}";
?>
<?php include $app->getModuleRoot() . 'common/view/mail.header.html.php';?>
<tr>
  <td>
    <table cellpadding='0' cellspacing='0' width='600' style='border: none; border-collapse: collapse;'>
      <tr>
        <td style='padding: 10px; background-color: #F8FAFE; border: none; font-size: 14px; font-weight: 500; border-bottom: 1px solid #e5e5e5;'>
          <?php echo $mailTitle;?>
        </td>
      </tr>
    </table>
  </td>
</tr>
<tr>
  <td style='padding: 10px; border: none;'>
    <fieldset style='border: 1px solid #e5e5e5'>
      <legend style='color: #114f8e'><?php echo $lang->makeup->common;?></legend>
      <div style='padding:5px;'>
        <p><?php echo $lang->makeup->createdBy . ':' . zget($users, $makeup->createdBy)?></p>
        <p><?php echo $lang->makeup->status . ':' . zget($lang->makeup->statusList, $makeup->status)?></p>
        <p><?php echo "{$lang->makeup->date}: {$makeup->begin} {$makeup->start}~{$makeup->end} {$makeup->finish}"?></p>
        <p><?php echo $lang->makeup->desc?></p>
        <p><?php echo $makeup->desc?></p>
      </div>
    </fieldset>
  </td>
</tr>
<?php include $app->getModuleRoot() . 'common/view/mail.footer.html.php';?>
