/**
 * 根据职位更改权限组。
 * Change the permission group according to the role.
 *
 * @param  role   $role
 * @access public
 * @return void
 */
function batchChangeRole(event)
{
    const role  = $(event.target).val();
    const group = role && roleGroup[role] ? roleGroup[role] : '';
    $(event.target).closest('tr').find('[name^="group"]').zui('picker').$.setValue(group);
}

/**
 * 切换界面类型。
 * When the visions is changed.
 *
 * @param  event  event
 * @access public
 * @return void
 */
function batchChangeVision(event)
{
    const $target = $(event.target);
    const visions = $target.val();
    const link = $.createLink('user', 'ajaxGetGroups', 'visions=' + visions);
    $.getJSON(link, function(data)
    {
        let $currentRow = $(event.target).closest('tr');
        let group  = $currentRow.find('[name^="group"]').val();
        let $group = $currentRow.find('[name^="group"]').zui('picker');
        $group.render({items: data});
        $group.$.setValue(group);

        let $row = $currentRow.next('tr');
        while($row.length)
        {
            if($row.find('[data-name="visions"]').attr('data-ditto') != 'on') break;

            group  = $row.find('[name^="group"]').val();
            $group = $row.find('[name^="group"]').zui('picker');
            $group.render({items: data});
            $group.$.setValue(group);
            $row = $row.next('tr');
        }
    });
}
