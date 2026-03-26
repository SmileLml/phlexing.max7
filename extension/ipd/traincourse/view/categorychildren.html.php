<?php
/**
 * The manage category children view file of traincourse module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     traincourse
 * @version     $Id: categorychildren.html.php 4029 2022-02-10 10:50:41Z $
 * @link        https://www.zentao.net
 */
?>
<form method='post' class='form-horizontal' id='childForm' action="<?php echo inlink('categoryChildren', "type=$type&category=$parent&originalType=$originalType");?>">
  <div class='panel panel-block'>
    <div class='panel-heading'>
    <strong><?php echo $parent ? $lang->traincourse->categoryChildren . ' <i class="icon-double-angle-right"></i> ' : $lang->traincourse->category; ?></strong>
    <?php
    foreach($origins as $origin)
    {
        echo html::a(inlink('browseCategory', "type=trainskill&category=$origin->id"), $origin->name . " <i class='icon-angle-right text-muted'></i> ");
    }
    ?>
    </div>

    <div class='panel-body' id='childList'>
      <?php
      $maxID = 0;
      foreach($children as $child)
      {
          if($maxID < $child->id) $maxID = $child->id;
          $disabled = '';
          echo "<div class='form-group tree'>";
          echo "<div class='col-xs-6 col-md-4 col-md-offset-2'>" . html::input("children[$child->id]", $child->name, "class='form-control' $disabled") . "</div>";
          echo "<div class='col-xs-6 col-md-2'><i class='icon-move sort-handle'></i></div>";
          echo html::hidden("mode[$child->id]", 'update', "$disabled");
          echo "</div>";
      }

      for($i = 0; $i < TRAINCOURSE::NEW_CHILD_COUNT ; $i ++)
      {
          echo "<div class='form-group tree'>";
          echo "<div class='col-xs-6 col-md-4 col-md-offset-2'>" . html::input("children[]", '', "class='form-control' placeholder='{$this->lang->traincourse->category}'") . "</div>";
          echo "<div class='col-xs-6 col-md-2'><i class='icon-move sort-handle'></i></div>";
          echo html::hidden('mode[]', 'new');
          echo "</div>";
      }

      $button = ($type == 'dept') ? html::submitButton() . ' ' . html::backButton() : html::submitButton();
      echo "<div class='form-group'><div class='col-xs-8 col-md-offset-2'>" . $button . "</div></div>";
      echo html::hidden('parent',   $parent);
      ?>
    </div>
  </div>
</form>
<?php js::set('maxID', $maxID);?>
<?php if(isset($pageJS)) js::execute($pageJS);?>
