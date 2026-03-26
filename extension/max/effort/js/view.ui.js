/**
 * 提示并删除日志。
 * Delete effort with tips.
 *
 * @param  int    effortID
 * @access public
 * @return void
 */
window.confirmDelete = function(effortID)
{
    zui.Modal.confirm(confirmDelete).then((res) =>
    {
        if(res) $.ajaxSubmit({url: $.createLink('effort', 'delete', 'effortID=' + effortID + '&confirm=yes&from=view')});
    });
}
