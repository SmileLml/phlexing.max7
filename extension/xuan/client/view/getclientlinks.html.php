<?php include '../../common/view/header.lite.html.php';?>
<div id='' class='modal-content'>
  <div class='modal-body'>
    <?php if($links === false): ?>
    <?php js::set('hangWarning', $hangWarning); ?>
    <form method='post'>
      <table class='table table-form'>
        <tr>
          <th class='w-80px'><?php echo $lang->client->version;?></th>
          <td><?php echo $config->xuanxuan->version;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->client->os;?></th>
          <td><?php echo html::checkbox('os', $lang->client->osList, $os);?></td>
        </tr>
        <tr class='text-center'>
          <td colspan='2'><?php echo html::submitButton($lang->client->generate, 'btn btn-primary generate-btn', 'formTarget="iframe-ajaxModal" data-loading-text="' . $this->lang->loading . '"');?></td>
        </tr>
      </table>
      <script>
      $(function()
      {
          $('.generate-btn').click(function()
          {
            alert(v.hangWarning);
            var $btn = $(this);
            $btn.button('loading');
          });
      });
      </script>
    </form>
    <?php else: ?>
    <table class='table table-form'>
      <tr>
        <th class='w-80px'><?php echo $lang->client->version;?></th>
        <td><?php echo $config->xuanxuan->version;?></td>
      </tr>
      <tr>
        <th><?php echo $lang->client->links;?></th>
        <td><?php echo html::textarea('downloadLinks', $links, 'style="width:100%;height:150px;"');?></td>
      </tr>
      <tr class='text-center'>
        <td colspan='2'><?php echo html::commonButton($lang->client->copy, 'btn btn-primary', 'id="copy-btn"');?></td>

      </tr>
    </table>
    <script>
    $(function()
    {
        $('#copy-btn').click(function()
        {
            $('#downloadLinks').select();
            document.execCommand('copy');
            $('#copy-btn').removeClass('btn-primary').addClass('btn-success');
            setTimeout(function(){$('#copy-btn').removeClass('btn-success').addClass('btn-primary');}, 1000);
        });
    });
    </script>
    <?php endif; ?>
  </div>
</div>
<?php include '../../common/view/footer.modal.html.php';?>
