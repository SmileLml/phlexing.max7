$(function(){
    showAnonymous();
    $(document).on('change', '#anonymous', function(){toggleHide();});
    $(document).on('change', '#type', function(){showAnonymous();});
});

/**
 * Toggle hide.
 *
 * @access public
 * @return void
 */
function toggleHide()
{
    $('.adshow').toggle(!$('#anonymous').prop('checked'));
}

/**
 * Show anonymous.
 *
 * @access public
 * @return void
 */
function showAnonymous()
{
  if($('#type').val() == 'ad')
  {
      $('#anonymous').parent().hide();
      $('#anonymous').prop('checked', false);
  }
  else
  {
      $('#anonymous').parent().show();
  }
  toggleHide();
}

/**
 * Import tip.
 *
 * @access public
 * @return void
 */
function importTip()
{
    $('#ldapForm .form-actions > a.btn-primary').removeAttr('disabled');

    var toImportUrl = $.createLink('user', 'importLDAP');
    $.zui.messager.success(importTipText,
    {
        time: 0,
        cssClass: 'messager-import-tip messagger-zt',
        actions:
        [
            {name: 'import', html: '<span class="text-primary" style="text-decoration: underline;">' + goImportText + '</span>', action: function(){$.apps.open(toImportUrl)}}
        ]
    });
}
