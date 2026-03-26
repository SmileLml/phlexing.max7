<?php
/**
 * The execution browse mobile view file of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2016 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fei Chen <chenfei@cnezsoft.com>
 * @package     my
 * @version     $Id: index.html.php 3830 2016-05-18 09:34:17Z liugang $
 * @link        http://www.zentao.net
 */
?>

<?php include "../../common/view/m.header.html.php";?>

<section id='page' class='section list-with-pager'>
  <div class='box'>
    <table class='table bordered'>
      <thead>
        <tr>
          <th><?php echo $lang->execution->name;?></th>
          <th class='text-center w-70px'><?php echo $lang->statusAB;?></th>
          <th class='text-center w-100px'><?php echo $lang->execution->end;?></th>
        </tr>
      </thead>
      <?php foreach($executions as $execution):?>
      <tr class='text-center' data-url='<?php echo $this->createLink('execution', 'task', "executionID={$execution->id}");?>' data-id='<?php echo $execution->id;?>'>
        <td class='text-left'><?php echo $execution->name;?> </td>
        <?php if(isset($execution->delay)):?>
        <td class='execution-delayed'><?php echo $lang->execution->delayed;?></td>
        <?php else:?>
        <td class='execution-<?php echo $execution->status;?>'><?php echo zget($lang->execution->statusList, $execution->status);?></td>
        <?php endif;?>
        <td><?php echo $execution->end;?></td>
      </tr>
      <?php endforeach;?>
    </table>
  </div>
</section>

<?php include "../../common/view/m.footer.html.php"; ?>
