<?php
/**
 * The mail file of customer module of RanZhi.
 *
 * @copyright   Copyright 2009-2018 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chu Jilu <chujilu@cnezsoft.com>
 * @package     customer
 * @version     $Id: sendmail.html.php 867 2010-06-17 09:32:58Z yuren_@126.com $
 * @link        http://www.ranzhi.org
 */
?>
<?php
$app->loadConfig('mail');

$domain     = zget($this->config->mail, 'domain', common::getSysURL());
$mailTitle  = "{$lang->lieu->common}#{$lieu->id} ";
$mailTitle .= html::a($domain . helper::createLink('my', 'review', "type=lieu", 'html') . "#app=oa", zget($users, $lieu->createdBy));
$mailTitle .= formatTime($lieu->begin, DT_DATE1) . '~' . formatTime($lieu->end, DT_DATE1);
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
      <legend style='color: #114f8e'><?php echo $lang->lieu->common;?></legend>
      <div style='padding:5px;'>
        <p><?php echo $lang->lieu->createdBy . ':' . zget($users, $lieu->createdBy)?></p>
        <p><?php echo $lang->lieu->status . ':' . zget($lang->lieu->statusList, $lieu->status)?></p>
        <p><?php echo $lang->lieu->date . ':' . formatTime($lieu->begin . ' ' . $lieu->start, DT_DATETIME2) . '~' . formatTime($lieu->end . ' ' . $lieu->finish, DT_DATETIME2);?></p>
        <p><?php echo $lang->lieu->desc;?></p>
        <p><?php echo $lieu->desc;?></p>
      </div>
    </fieldset>
  </td>
</tr>
<?php include $app->getModuleRoot() . 'common/view/mail.footer.html.php';?>
