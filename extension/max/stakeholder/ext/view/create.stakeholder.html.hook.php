<?php
$useAddWarning = $this->loadModel('user')->getAddUserWarning();
$userLimit     = $this->user->getBizUserLimit();
$leftUsers     = $this->user->getLeftUsers();
?>
<?php js::set('useAddWarning', $useAddWarning);?>
<?php js::set('userLimit', $userLimit);?>
<?php js::set('leftUsers', $leftUsers);?>
<script>
$(function()
{
    if(userLimit && leftUsers <= 0) $("input[name='name']").attr('readonly', true);
    if(useAddWarning)
    {
        $('input[name*=newUser]').change(function()
        {
            if($(this).prop('checked'))
            {
                $("input[name='name']").closest('tr').append("<td class='use-add-warning'><span style='color: red'>" + useAddWarning + "</span></td>");
            }
            else
            {
                $(".use-add-warning").remove();
            }
        })
    }
})
</script>
