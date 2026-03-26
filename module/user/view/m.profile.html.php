<?php
/**
 * The profile view mobile view file of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2016 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fei Chen <chenfei@cnezsoft.com>
 * @package     my 
 * @version     $Id: index.html.php 3830 2016-05-18 09:34:17Z liugang $
 * @link        http://www.zentao.net
 */
?>

<?php
$bodyClass = 'with-menu-top';
include "../../common/view/m.header.html.php";
include "./m.featurebar.html.php";
?>

<div id='page' class='list-with-actions'>
  <div class='section no-margin'>
    <div class='heading gray'>
      <div class='title'> <?php echo $lang->user->profile;?></div>
    </div>
    <div class='box'>
      <table class='table bordered table-detail'>
        <tr>
          <td class='w-100px'><?php echo $lang->user->dept;?></td>
          <td>
          <?php
          if(empty($deptPath))
          {   
              echo "/";
          }   
          else
          {   
              foreach($deptPath as $key => $dept)
              {   
                  if($dept->name) echo $dept->name;
                  if(isset($deptPath[$key + 1])) echo $lang->arrow;
              }   
          }   
          ?>
          </td>
        </tr>
        <tr>
          <td><?php echo $lang->user->account;?></td>
          <td><?php echo $user->account;?></td>
        </tr>
        <tr>
          <td><?php echo $lang->user->realname;?></td>
          <td><?php echo $user->realname;?></td>
        </tr>
        <tr>
          <td><?php echo $lang->user->role;?></td>
          <td><?php echo $lang->user->roleList[$user->role];?></td>
        </tr>
        <tr>
          <td><?php echo $lang->group->priv;?></td>
          <td><?php foreach($groups as $group) echo $group->name . ' '; ?></td>
        </tr>
        <tr>
          <td><?php echo $lang->user->commiter;?></td>
          <td><?php echo $user->commiter;?></td>
        </tr>
        <tr>
          <td><?php echo $lang->user->email;?></td>
          <td><?php echo $user->email;?></td>
        </tr>
        <tr>
          <td><?php echo $lang->user->join;?></td>
          <td><?php echo $user->join;?></td>
        </tr>
        <tr>
          <td><?php echo $lang->user->visits;?></td>
          <td><?php echo $user->visits;?></td>
        </tr>
        <tr>
          <td><?php echo $lang->user->ip;?></td>
          <td><?php echo $user->ip;?></td>
        </tr>
        <tr>
          <td><?php echo $lang->user->last;?></td>
          <td><?php echo $user->last;?></td>
        </tr>
        <?php if(!empty($config->user->contactField)):?>
        <?php foreach(explode(',', $config->user->contactField) as $field):?>
        <tr>
          <td><?php echo $lang->user->$field;?></td>
          <td>
            <?php
            if($field == 'skype' and $user->$field)
            {
                echo html::a("callto://$user->skype", $user->skype);
            }
            elseif($field == 'qq' and $user->$field)
            {
                echo html::a("tencent://message/?uin=$user->qq", $user->qq);
            }
            else
            {
                echo $user->$field;
            }
            ?>
          </td>
        </tr>
        <?php endforeach;?>
        <?php endif;?>
        <tr>
          <td><?php echo $lang->user->address;?></td>
          <td><?php echo $user->address;?></td>
        </tr>
        <tr>
          <td><?php echo $lang->user->zipcode;?></td>
          <td><?php echo $user->zipcode;?></td>
        </tr>
        <?php if($user->ranzhi):?>
        <tr>
          <td><?php echo $lang->user->ranzhi;?></td>
          <td>
            <?php echo $user->ranzhi . ' ';?>
            <?php if(common::hasPriv('my', 'unbind')) echo html::a($this->createLink('my', 'unbind'), "<i class='icon-unlink'></i>", 'hiddenwin', "class='bin-icon' title='{$lang->user->unbind}'");?>
          </td>
        </tr>
        <?php endif;?>
      </table>
    </div>
  </form>
</div>
<script>
$('#<?php echo $methodName?>' + 'Tab').addClass('active');
</script>
<?php include "../../common/view/m.footer.html.php"; ?>
