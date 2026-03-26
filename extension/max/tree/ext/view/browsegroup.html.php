<?php
/**
 * The browsegroup view file of chart module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     chart
 * @version     $Id: browsegroup.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php $dataType = $type == 'report' ? 'custom' : $type;?>
<?php js::set('dataType', $dataType);?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php echo html::a($gobackLink, $lang->goback, '', 'class="btn btn-secondary"');?>
    <div class="divider"></div>
    <div class="page-title">
      <span class='text' title='<?php echo $lang->chart->manageGroup;?>'><?php echo $lang->chart->manageGroup;?></span>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="side-col col-4">
    <div class='panel'>
      <div class='panel-heading'>
        <div class='panel-title'><?php echo !empty($group) ? $group->name : $lang->chart->allGroup;?></div>
      </div>
      <div class='panel-body'>
        <ul id='modulesTree' data-name='tree-chart'></ul>
      </div>
    </div>
  </div>
  <div class="main-col col-8">
    <div class="panel">
      <div class="panel-heading">
        <div class="panel-title">
          <?php echo $lang->chart->manageGroup;?>
        </div>
      </div>
      <div class="panel-body">
        <form id='childrenForm' method='post' target='hiddenwin' action='<?php echo $this->createLink('tree', 'manageChild', "rootID=$dimensionID&viewType=$type&oldPage=yes");?>'>
          <table class='table table-form table-auto'>
            <tr>
              <td class="text-middle text-right with-padding">
                <?php
                echo "<span>" . html::a($this->createLink('tree', 'browsegroup', "dimensionID=$dimensionID&groupID=0&type=$type"), $lang->chart->allGroup, '', "data-app='bi'") . "<i class='icon icon-angle-right muted'></i></span>";
                if(!empty($groupID))
                {
                    foreach($parentGroups as $parentGroup)
                    {
                        echo "<span>" . html::a($this->createLink('tree', 'browsegroup', "dimensionID=$dimensionID&groupID=$parentGroup->id&type=$type"), $parentGroup->name, '', "data-app='bi'") . " <i class='icon icon-angle-right muted'></i></span>";
                    }
                }
                ?>
              </td>
              <td>
                <div id='sonGroup'>
                  <?php $maxOrder = 0;?>
                  <?php foreach($sonGroups as $sonGroup):?>
                  <?php if($sonGroup->order > $maxOrder) $maxOrder = $sonGroup->order;?>
                  <?php $disabled = $sonGroup->owner == 'system' ? 'disabled' : '';?>
                  <div class="table-row row-module">
                    <div class="table-col col-module"><?php echo html::input("modules[id$sonGroup->id]", $sonGroup->name, 'class="form-control"' . $disabled);?></div>
                    <div class="table-col col-shorts"><?php echo html::input("shorts[id$sonGroup->id]", $sonGroup->short, "class='form-control' placeholder='{$lang->tree->short}' $disabled") . html::hidden("order[id$sonGroup->id]", $sonGroup->order);?></div>
                    <div class="table-col col-actions"> </div>
                  </div>
                  <?php endforeach;?>
                  <?php for($i = 0; $i < 5 ; $i ++):?>
                  <div class="table-row row-module row-module-new">
                    <div class="table-col col-module"><?php echo html::input("modules[]", '', "class='form-control' placeholder='{$lang->chart->groupName}'");?></div>
                    <div class="table-col col-shorts"><?php echo html::input("shorts[]", '', "class='form-control' placeholder='{$lang->tree->short}'");?></div>
                    <div class="table-col col-actions">
                      <button type="button" class="btn btn-link btn-icon btn-add" onclick="addItem(this)"><i class="icon icon-plus"></i></button>
                      <button type="button" class="btn btn-link btn-icon btn-delete" onclick="deleteItem(this)"><i class="icon icon-close"></i></button>
                    </div>
                  </div>
                  <?php endfor;?>

                  <div id="insertItemBox" class="template">
                    <div class="table-row row-module row-module-new">
                      <div class="table-col col-module"><?php echo html::input("modules[]", '', "class='form-control' placeholder='{$lang->chart->groupName}'");?></div>
                      <div class="table-col col-shorts"><?php echo html::input("shorts[]", '', "class='form-control' placeholder='{$lang->tree->short}'");?></div>
                      <div class="table-col col-actions">
                        <button type="button" class="btn btn-link btn-icon btn-add" onclick="addItem(this)"><i class="icon icon-plus"></i></button>
                        <button type="button" class="btn btn-link btn-icon btn-delete" onclick="deleteItem(this)"><i class="icon icon-close"></i></button>
                      </div>
                    </div>
                  </div>

                </div>
              </td>
            </tr>
            <tr>
              <td></td>
              <td colspan="2" class="form-actions">
                <?php
                echo html::submitButton();
                if(!isonlybody()) echo html::a($gobackLink, $lang->goback, '', 'class="btn btn-wide"');
                echo html::hidden('parentModuleID', $groupID);
                echo html::hidden('maxOrder', $maxOrder);
                ?>
              </td>
            </tr>
            </tbody>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
$(function()
{
    var data = $.parseJSON('<?php echo helper::jsonEncode4Parse($groupStructure);?>');
    var orderModule = 0;
    var options =
    {
        initialState: 'preserve',
        data: data,
        sortable:
        {
            lazy: true,
            nested: true,
            canMoveHere: function($ele, $target)
            {
                if($ele && $target && $ele.parent().closest('li').attr('data-id') !== $target.parent().closest('li').attr('data-id')) return false;
            },
            start: function(e)
            {
                orderModule = e.element.data('id');
            }
        },
        itemCreator: function($li, item)
        {
            var link = '<a href="' + createLink('tree', 'browseGroup', 'dimensionID=<?php echo $dimensionID ?>&moduleID={0}&type=<?php echo $type;?>'.format(item.id)) + '" data-app="bi" title="' + item.name + '">' + item.name + '</a>';
            /* Disable link when item.grade != 1. */
            if(item.grade != 1) link = item.name;
            $li.append($('<span class="module-name" data-id="' + item.id + '">' + link + '</span>'));
            if(item.owner == 'system') $li.addClass('system');
            if(item.nodeType || item.type) $li.addClass('tree-item-' + (item.nodeType || item.type));
            $li.toggleClass('active', '<?php echo $groupID ?>' === item.id);
            return true;
        },
        actions:
        {
            sort:
            {
                title: '<?php echo $lang->tree->dragAndSort ?>',
                template: '<a class="sort-handler"><i class="icon-move"></i></a>'
            },
            edit:
            {
                linkTemplate: '<?php echo helper::createLink('tree', 'edit', "moduleID={0}&type=$type"); ?>',
                title: '<?php echo $lang->chart->editGroup;?>',
                template: '<a><i class="icon-edit"></i></a>'
            },
            "delete":
            {
                linkTemplate: '<?php echo helper::createLink('tree', 'delete', "rootID=$dimensionID&moduleID={0}"); ?>',
                title: '<?php echo $lang->chart->deleteGroup;?>',
                template: '<a><i class="icon-trash"></i></a>'
            },
            subModules:
            {
                linkTemplate: '<?php echo helper::createLink('tree', 'browsegroup', "dimensionID=$dimensionID&moduleID={0}&type=$type"); ?>',
                title: '<?php echo $lang->chart->childTitle;?>',
                template: '<a><?php echo '<i class="icon-split"></i>';?></a>',
            }
        },
        action: function(event)
        {
            var action = event.action, $target = $(event.target), item = event.item;
            if(action.type === 'edit')
            {
                new $.zui.ModalTrigger({
                    type: 'iframe',
                    height: '320px',
                    url: action.linkTemplate.format(item.id),
                    keyboard: true
                }).show();
            }
            else if(action.type === 'delete')
            {
                hiddenwin.location.href = action.linkTemplate.format(item.id);
            }
            else if(action.type === 'sort' && event.item == null)
            {
                var orders = {};
                $('#modulesTree').find('li:not(.tree-action-item)').each(function()
                {
                    var $li = $(this);
                    if($li.hasClass('tree-item-branch')) return;

                    var item = $li.data();
                    orders['orders[' + item.id + ']'] = $li.attr('data-order') || item.order;
                });

                $.post(createLink('tree', 'updateOrder', 'rootID=<?php echo $dimensionID;?>&viewType=chart&moduleID=' + orderModule), orders, function(data)
                {
                    $('.main-col').load(location.href + ' .main-col .panel');
                }).error(function()
                {
                    bootbox.alert(lang.timeout);
                });
            }
            else if(action.type === 'subModules')
            {
                window.location.href = action.linkTemplate.format(item.id);
            }
        }
    };

    if(<?php echo (common::hasPriv('tree', 'updateorder')) ? 'false' : 'true' ?>) options.actions["sort"] = false;
    if(<?php echo (common::hasPriv('tree', 'edit')) ? 'false' : 'true' ?>) options.actions["edit"] = false;
    if(<?php echo (common::hasPriv('tree', 'delete')) ? 'false' : 'true' ?>) options.actions["delete"] = false;

    var $tree = $('#modulesTree').tree(options);

    var tree = $tree.data('zui.tree');
    if(<?php echo $groupID ?>)
    {
        var $currentLi = $tree.find('.module-name[data-id=' + <?php echo $groupID ?> + ']').closest('li');
        if($currentLi.length) tree.show($currentLi);
    }

    $tree.on('mouseenter', 'li:not(.tree-action-item)', function(e)
    {
        $('#modulesTree').find('li.hover').removeClass('hover');
        $(this).addClass('hover');
        e.stopPropagation();
    });

    $('#subNavbar > ul > li > a[href*=tree][href*=browse]').not('[href*=<?php echo 'chart';?>]').parent().removeClass('active');

    $("#modulesTree > li.system > .tree-actions > a[data-type='delete']").remove();
    $("#modulesTree > li.system > .tree-actions > a[data-type='edit']").remove();
    $("#modulesTree > li > ul .tree-actions > a[data-type='subModules']").remove();
});
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
