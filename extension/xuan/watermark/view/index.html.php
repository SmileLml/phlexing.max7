<?php
/**
 * The watermark view file of watermark module of XXB.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd., www.zentao.net)
 * @license     ZOSL (https://zpl.pub/page/zoslv1.html)
 */
?>
<?php include '../../common/view/header.html.php';?>
<div style="display: flex;">
  <div class='panel'>
    <div class='panel-heading'>
      <strong><?php echo $lang->watermark->common;?></strong>
    </div>
    <form method="post" class="form-ajax" id="watermark-ajax-form">
      <table class="table table-form">
        <tr>
          <th class="w-120px"><?php echo $lang->watermark->switch?></th>
          <td class='w-p50'>
            <?= $type == 'edit'
              ? html::radio('enabled', $lang->watermark->switchList, $enabled)
              : $lang->watermark->switchList[$enabled]
            ?>
          </td>
          <td></td>
        </tr>
        <?php if(($enabled == 1 && $type !== 'edit') || $type == 'edit'): ?>
        <tr id="watermark-tr">
          <th class="w-120px"><?= $lang->watermark->content; ?></th>
          <td class="w-p50">
            <?= html::textarea('content', $content, ('rows="3" class="form-control"' . ($type === 'edit' ? '' : ' disabled'))); ?>
          </td>
          <td></td>
        </tr>
        <?php endif; ?>
        <?php if($type == 'edit'): ?>
        <tr id="watermark-tip-tr">
          <th></th>
          <td>
          <div>
              <p><i class="icon icon-exclamation-sign" style="color: orange; margin-right: 4px;"></i><?= $lang->watermark->varTip; ?></p>
              <table class="table-bordered">
                <tbody>
                  <tr>
                    <th>displayName</th>
                    <td><?= $lang->watermark->displayName; ?></td>
                  </tr>
                  <tr>
                    <th>account</th>
                    <td><?= $lang->watermark->account; ?></td>
                  </tr>
                  <tr>
                    <th>email</th>
                    <td><?= $lang->watermark->email; ?></td>
                  </tr>
                  <tr>
                    <th>phone</th>
                    <td><?= $lang->watermark->phone; ?></td>
                  </tr>
                  <tr>
                    <th>date</th>
                    <td><?= $lang->watermark->date; ?></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </td>
          <td></td>
        </tr>
        <?php endif; ?>
        <tr>
          <?php if($type == 'edit'): ?>
            <th></th>
            <td><?= html::submitButton(); ?>&nbsp;&nbsp;&nbsp;<?= html::backButton(); ?></td>
            <td></td>
          <?php else: ?>
            <th></th>
            <td colspan="2"><?= '<a class="btn btn-primary" href="' . helper::createLink('watermark', 'index', 'type=edit') . '">' . $lang->edit; ?></td>
            <td></td>
          <?php endif; ?>
        </tr>
      </table>
    </form>
  </div>
  <?php if(($enabled == 1 && $type !== 'edit') || $type == 'edit'): ?>
  <div id="watermark-preview" class='panel' style="margin-left: 20px;">
    <div class='panel-heading'>
      <strong><?= $lang->watermark->preview; ?></strong>
    </div>
    <div id="preview-container"></div>
  </div>
  <?php endif;?>
</div>
<style>
  #preview-container {
    position: relative;
    min-width: 600px;
    min-height: 600px;
  }
</style>
<?php
    $jsRoot    = $webRoot . "js/";
    js::import($jsRoot  . 'watermark/index.iife.min.js');
?>
<script>
    const displayName = `<?= $displayName; ?>`;
    const account = `<?= $account; ?>`;
    const email = `<?= $email; ?>`;
    const phone = `<?= $phone; ?>`;
    let watermark = null;

    function setContentPreview(value)
    {
        const parent = document.querySelector('#preview-container');
        if(!value || !parent) return;

        const content = value.replace(/\$displayName/g, displayName)
            .replace(/\$account/g, account)
            .replace(/\$email/g, email)
            .replace(/\$phone/g, phone)
            .replace(/\$date/g, getCurrentDateFormatted())
            .split('\n')
            .filter(line => line.trim().length > 0)
            .join('\n');

        if(watermark) watermark.destroy();
        watermark = new WatermarkPlus.Watermark({
            content,
            contentType: 'multi-line-text',
            rotate: 35,
            parent: parent,
            fontSize: '20px',
            fontColor: 'rgba(0,0,0,0.15)',
            zIndex: 0,
        })
        watermark.create();
    }

    function getCurrentDateFormatted()
    {
        const today = new Date();
        const year = today.getFullYear();
        const month = ('0' + (today.getMonth() + 1)).slice(-2); // 月份是从0开始的，所以需要+1
        const day = ('0' + today.getDate()).slice(-2); // 日期

        return `${year}-${month}-${day}`;
    }

    function showWatermarkDom()
    {
        $('#watermark-tr').removeClass('hidden');
        $('#watermark-tip-tr').removeClass('hidden');
        $('#watermark-preview').removeClass('hidden');
    }

    function hideWatermarkDom()
    {
        $('#watermark-tr').addClass('hidden');
        $('#watermark-tip-tr').addClass('hidden');
        $('#watermark-preview').addClass('hidden');
    }

    function toggleWatermarkDom(enabled)
    {
        if(enabled == 1) showWatermarkDom();
        else             hideWatermarkDom();
    }

    $(() => {
        $.setAjaxForm('#watermark-ajax-form');
        const $content = $('#content');
        $content.on('input', function() {
            setContentPreview($(this).val());
        });
        setContentPreview($content.val());

        $('input[name="enabled"]').on('change', function() {
            toggleWatermarkDom($(this).val());
        });
        const enabled = $('input[name="enabled"]:checked').length
            ? $('input[name="enabled"]:checked').val()
            : <?= $enabled; ?>;
        toggleWatermarkDom(enabled);
    });
</script>
<?php include '../../common/view/footer.html.php';?>
