<?php if(!empty($properties['user'])):?>
<?php
$userMaxCount = $properties['user']['value'];
$userCount    = $this->dao->select("COUNT('*') as count")->from(TABLE_USER)
    ->where('deleted')->eq(0)
    ->fetch('count');
js::set('userCount', $userCount);
js::set('userMaxCount', $userMaxCount);
js::set('noticeUserCreate', str_replace('%maxcount%', $userMaxCount, $lang->user->noticeUserCreate));
?>
<script>
$(function()
{
    $('#submit').click(function()
    {
        if(userMaxCount > 0)
        {
            var allUserCount = parseInt(userCount);

            $('[id^="account"]').each(function()
            {
                if($(this).val()) allUserCount += 1;
            });

            if(allUserCount > userMaxCount)
            {
                alert(noticeUserCreate.replace('%usercount%', allUserCount));
                return false;
            }
        }
    })
})
</script>
<?php endif;?>
