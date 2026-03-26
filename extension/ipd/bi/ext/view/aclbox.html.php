<?php $whiteUsers  = $this->loadModel('user')->getPairs('nodeleted|noclosed');?>
<?php if(!isset($aclList)) $aclList = $lang->bi->aclList;?>
<tr>
  <th><?php echo $lang->bi->acl;?></th>
  <td><?php echo nl2br(html::radio('acl', $aclList, $acl, "onclick='setWhite(this.value);'", 'block'));?></td>
</tr>
<?php $whiteClass = $acl == 'open' ? 'hidden' : '';?>
<tr id="whitelistBox" class='<?php echo $whiteClass;?>'>
  <th><?php echo $lang->whitelist;?></th>
  <td>
    <div class='input-group'>
      <?php echo html::select('whitelist[]', $whiteUsers, $whitelist, 'class="form-control chosen" multiple');?>
      <?php echo $this->fetch('my', 'buildContactLists', "dropdownName=whitelist&attr=&showManage=no");?>
    </div>
  </td>
</tr>
<?php if(isset($whitelistStyle)):?>
<tr>
  <th style='<?php echo $whitelistStyle;?>'></th>
</tr>
<?php endif;?>

<script>
function setWhite(acl)
{
    acl != 'open' ? $('#whitelistBox').removeClass('hidden') : $('#whitelistBox').addClass('hidden');
}

function setMailto(mailto, contactListID)
{
    link = createLink('user', 'ajaxGetOldContactUsers', 'listID=' + contactListID + '&dropdownName=' + mailto);
    $.get(link, function(users)
    {
        $('#' + mailto).replaceWith(users);
        $('#' + mailto + '_chosen').remove();
        $('#' + mailto).chosen();
    });
}

</script>
