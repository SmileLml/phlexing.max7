<?php
/**
 * The mobile view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2016 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fei Chen <chenfei@cnezsoft.com>
 * @package     project
 * @version     $Id: index.html.php 3830 2016-05-18 09:34:17Z liugang $
 * @link        http://www.zentao.net
 */

include "../../common/view/m.header.html.php";
?>

<div id='page' class='list-with-actions'>
  <div class='heading gray'>
    <div class='title'> <?php echo $lang->project->view;?></div>
    <nav class='nav'><a class='btn primary' href='javascript:history.go(-1);'><?php echo $lang->goback;?></a></nav>
  </div>
  <div class='section no-margin'>
    <div class='outline'>
      <nav class="nav" data-display="" data-selector="a" data-show-single="true" data-active-class="active" data-animate="false">
        <a class="active" data-target="#legendDesc"><?php echo $lang->project->desc?></a>
        <a data-target="#legendBasicInfo"><?php echo $lang->execution->basicInfo?></a>
      </nav>
      <div>
        <div class="display in" id="legendDesc">
          <div class='heading gray'>
            <div class='title'><strong><?php echo $lang->project->desc?></strong></div>
          </div>
          <div class='article'>
            <?php echo $project->desc;?>
            <div>
              <label class='strong'><?php echo $lang->execution->lblStats?></label>
              <?php printf($lang->execution->stats, (int)$workhour->totalHours, $workhour->totalEstimate, $workhour->totalConsumed, $workhour->totalLeft)?>
            </div>
          </div>
          <?php include '../../common/view/m.action.html.php';?>
        </div>
        <div class="display hidden" id="legendBasicInfo">
          <table class='table bordered table-detail'>
            <tr>
              <td class='w-100px'><?php echo $lang->project->name;?></td>
              <td><?php echo $project->name;?></td>
            </tr>
            <tr>
              <td><?php echo $lang->project->code;?></td>
              <td><?php echo $project->code;?></td>
            </tr>
            <tr>
              <td><?php echo $lang->execution->beginAndEnd;?></td>
              <td><?php echo $project->begin . ' ~ ' . $project->end;?></td>
            </tr>
            <tr>
              <td><?php echo $lang->project->budget;?></td>
              <td><?php echo $project->budget;?></td>
            </tr>
            <tr>
              <td><?php echo $lang->project->status;?></td>
              <td class='<?php echo $project->status;?>'><?php $lang->show($lang->project->statusList, $project->status);?></td>
            </tr>
            <tr>
              <td><?php echo $lang->execution->PM;?></td>
              <td><?php echo zget($users, $project->PM, $project->PM);?></td>
            </tr>
            <tr>
              <td><?php echo $lang->execution->PO;?></td>
              <td><?php echo zget($users, $project->PO, $project->PO);?></td>
            </tr>
            <tr>
              <td><?php echo $lang->execution->QD;?></td>
              <td><?php echo zget($users, $project->QD, $project->QD);?></td>
            </tr>
            <tr>
              <td><?php echo $lang->execution->RD;?></td>
              <td><?php echo zget($users, $project->RD, $project->RD);?></td>
            </tr>
            <tr>
              <td><?php echo $lang->execution->products;?></td>
              <td>
                <?php
                foreach($products as $productID => $product)
                {
                    if($product->type !== 'normal')
                    {
                        echo $product->name . '/' . $branchGroups[$productID][$product->branch] . "|";
                    }
                    else
                    {
                        echo $product->name . "|";
                    }
                }
                ?>
              </td>
            </tr>
            <tr>
              <td><?php echo $lang->project->acl;?></td>
              <td><?php echo $lang->project->aclList[$project->acl];?></td>
            </tr>
            <?php if($project->acl == 'custom'):?>
            <tr>
              <td><?php echo $lang->project->whitelist;?></td>
              <td>
                <?php
                $whitelist = explode(',', $project->whitelist);
                foreach($whitelist as $groupID) if(isset($groups[$groupID])) echo $groups[$groupID] . '&nbsp;';
                ?>
              </td>
            </tr>
            <?php endif;?>
          </table>
        </div>
      </div>
    </div>
  </form>
</div>
<?php include "../../common/view/m.footer.html.php"; ?>
