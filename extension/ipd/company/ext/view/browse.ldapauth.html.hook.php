<?php $this->session->set('ldapBackLink', $this->app->getURI(true));?>
<?php $ldapHtml = (common::hasPriv('user', 'importLDAP') and $config->vision != 'or') ? html::a($this->createLink('user', 'importLDAP'), $lang->user->importLDAP, '', "class='btn btn-info btn-wide'") : '';?>
<script language='Javascript'>
$(function()
{
    $('#sidebar .cell').append("<div style='margin-top:5px;' class='text-center'>" + <?php echo json_encode($ldapHtml)?> + '</div>');
})
</script>
