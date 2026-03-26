<?php
/**
 * The testtask browse mobile view file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2016 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fei Chen <chenfei@cnezsoft.com>
 * @package     execution
 * @version     $Id
 * @link        http://www.zentao.net
 */

include "../../common/view/m.header.html.php";
?>

<section id='page' class='section list-with-pager'>
  <table class='table bordered'>
    <thead>
      <tr>
        <th><?php echo $lang->testtask->name;?></th>
        <th class='text-center w-140px'><?php echo $lang->testtask->begin;?></th>
        <th class='text-center w-140px'><?php echo $lang->testtask->end;?></th>
      </tr>
    </thead>
    <?php foreach($tasks as $product => $productTasks):?>
    <?php foreach($productTasks as $task):?>
    <tr class= 'text-center' data-id="<?php echo $task->id;?>" data-url="<?php echo $this->createLink('testtask', 'view', 'testtaskID=' . $task->id);?>">
      <td class='text-left'><?php echo $task->name;?></td>
      <td class='w-100px'><?php echo $task->begin;?></td>
      <td class='w-100px'><?php echo $task->end;?></td>
    </tr>
    <?php endforeach;?>
    <?php endforeach;?>
  </table>
</section>

<?php include "../../common/view/m.footer.html.php"; ?>
