<div id='mainContent' class='main-content'>
  <div class="display-content">
    <div class="cell">
      <div class='panel panel-position panel-padding'>
        <div class='panel-heading'>
          <div class='panel-title ptitle'>
            <?php echo $pivot->name;?>
            <?php if(common::hasPriv('pivot', 'export')):?>
              <a href="#" class="btn btn-link design-export pull-right hidden" data-toggle="modal" data-target="#export"><?php echo '<i class="icon-export muted"> </i>' . $lang->export;?></a>
            <?php endif;?>
          </div>
        </div>
        <div class="panel-body">
          <div id="datagridSpanExample4" class="datagrid">
            <div class='panel-body clear-padding clear-overflow'>
              <div id='filterContent' class='filterContent'>
                <div id="filterItems"></div>
                <div id='queryButton4' class='queryButton'>
                  <button type="button" onclick='queryFilter()' class="btn btn-secondary save-step"><?php echo $lang->pivot->query;?></button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="config-content" id="sidebar">
    <div class="cell">
      <div class='panel'>
        <div class='panel-heading border-bottom lang-heading'>
          <div class='panel-title'>
            <?php echo $lang->pivot->legendBasicInfo;?>
            <div class="pull-right lang-set">
              <?php echo html::commonButton($lang->pivot->setLang, "data-toggle='modal' data-name='{$lang->pivot->setLang}' data-target='#setLang'", 'btn btn-info add-filter')?>
            </div>
          </div>
        </div>
        <div class=''>
          <div class='basic-padding'>
            <div class='tabs clear-padding'>
              <div class='tab-content'>
                <div class='tab-pane active' id='legendBasicInfo'>
                  <form method="post" class="form-ajax" id="releaseForm">
                  <table class="table table-form">
                    <tbody>
                      <tr>
                        <th class='basic-width basic-thpadding'><?php echo $lang->pivot->group;?></th>
                        <td>
                          <?php echo html::select('group[]', $groups, $pivot->group, "class='chosen form-control' data-max_drop_width='200' onchange='basicChange(\"group\", this)' multiple");?>
                        </td>
                      </tr>
                      <tr>
                        <th class='basic-width basic-thpadding'><?php echo $lang->pivot->name;?></th>
                        <td class='w-400px required'>
                          <?php $clientLang = $this->app->getClientLang();?>
                          <?php echo html::input("name[$clientLang]", zget($pivot->names, $clientLang, ''), "class='form-control' data-lang='{$clientLang}' onchange='basicChange(\"names\", this)'");?>
                        </td>
                      </tr>
                      <tr>
                        <th class='basic-width basic-thpadding'><?php echo $lang->pivot->desc;?></th>
                        <td><?php echo html::textarea("desc[$clientLang]", zget($pivot->descs, $clientLang, ''), "rows='7' class='form-control' data-lang='{$clientLang}' onchange='basicChange(\"descs\", this)'");?></td>
                      </tr>
                    </tbody>
                  </table>
                  </form>
                </div>
                <div class='tab-pane' id='legendConfig'>
                  <table class="table table-data">
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class='panel pull-bottom'>
        <div class='panel-heading'>
          <div class='panel-footer'>
            <?php echo '<button type="button" class="btn btn-primary btn-publish" onclick="publish()">' . $lang->pivot->publish . '</button>';?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<template id='queryFilterItemTpl'>
  <div class='filter-item filter-item-{index} input-group' data-index='{index}'>
    <span class='field-name input-group-addon'>{name}</span>
    <div class="default-block hidden"><?php echo html::input('default', '', "class='form-control form-input '")?></div>
    <div class="default-block hidden"><?php echo html::input('default', '', "class='form-control form-date '")?></div>
    <div class="default-block hidden"><?php echo html::input('default', '', "class='form-control form-datetime '")?></div>
    <div class="default-block hidden"><?php echo html::select('default', '', '', "class='form-control form-select multiple'");?></div>
  </div>
</template>

<div class="modal fade" id='setLang'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
        <h2 class="modal-title"><?php echo $lang->pivot->setLang;?></h2>
      </div>
      <div class="modal-body">
        <table class="table table-form">
          <tr>
            <th><?php echo $lang->pivot->name;?></th>
            <td>
              <ul class='nav nav-tabs'>
                <?php $clientLang = $this->app->getClientLang();?>
                <?php foreach($config->langs as $langKey => $currentLang):?>
                <?php $active = $langKey == $clientLang ? 'active' : ''?>
                <li class='<?php echo $active;?>'><?php echo html::a('#name_'. str_replace('-', '_', $langKey), $currentLang, '', "data-toggle='tab'");?></li>
                <?php endforeach;?>
              </ul>
              <div class='tab-content'>
                <?php foreach($config->langs as $langKey => $currentLang):?>
                <?php $active = $langKey == $clientLang ? 'active' : ''?>
                <div class='tab-pane <?php echo $active?>' id='name_<?php echo str_replace('-', '_', $langKey);?>'>
                  <?php echo html::input("name[$langKey]", zget($pivot->names, $langKey, ''), "id='name{$langKey}' data-lang='{$langKey}' class='form-control'");?>
                </div>
                <?php endforeach;?>
              </div>
              <div id='name'></div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->pivot->desc;?></th>
            <td>
              <ul class='nav nav-tabs'>
                <?php $clientLang = $this->app->getClientLang();?>
                <?php foreach($config->langs as $langKey => $currentLang):?>
                <?php $active = $langKey == $clientLang ? 'active' : ''?>
                <li class='<?php echo $active;?>'><?php echo html::a('#desc_'. str_replace('-', '_', $langKey), $currentLang, '', "data-toggle='tab'");?></li>
                <?php endforeach;?>
              </ul>
              <div class='tab-content'>
                <?php foreach($config->langs as $langKey => $currentLang):?>
                <?php $active = $langKey == $clientLang ? 'active' : ''?>
                <div class='tab-pane <?php echo $active?>' id='desc_<?php echo str_replace('-', '_', $langKey);?>'>
                  <?php echo html::textarea("desc[$langKey]", zget($pivot->descs, $langKey, ''), "id='desc{$langKey}' data-lang='{$langKey}' rows='5' class='form-control'");?>
                </div>
                <?php endforeach;?>
              </div>
              <div id='desc'></div>
            </td>
          </tr>
          <tr>
            <td colspan='2' class='form-actions text-center'>
              <?php echo html::commonButton($lang->save, "id='saveLang'", 'btn btn-primary');?>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
<script>
function basicChange(field, obj)
{
    var pivot = DataStorage.clone('pivot');

    if(!pivot['names']) pivot['names'] = {};
    if(!pivot['descs']) pivot['descs'] = {};

    if(pivot)
    {
        if(field == 'group')
        {
            pivot[field] = '';
            if($('#legendBasicInfo [name^=group]').val()) pivot[field] = $('#legendBasicInfo [name^=group]').val().join(',');
        }
        else
        {
            var lang = $(obj).data('lang');
            pivot[field][lang] = $(obj).val();
        }
    }
    DataStorage.pivot = pivot;
}

$('#setLang').on('show.zui.modal', function()
{
    initData();
    $('#saveLang').click(function()
    {
        $('#setLang').modal('hide');
    })
});

function initData()
{
    var pivot = DataStorage.clone('pivot');

    $('input[name^=name]').each(function()
    {
        var lang = $(this).data('lang');
        if(pivot['names']) $(this).val(pivot['names'][lang]);
    });

    $('textarea[name^=desc]').each(function()
    {
        var lang = $(this).data('lang');
        if(pivot['descs']) $(this).val(pivot['descs'][lang]);
    });
}

$('#setLang').on('hide.zui.modal', function()
{
    var pivot = DataStorage.clone('pivot');

    if(!pivot['names']) pivot['names'] = {};
    if(!pivot['descs']) pivot['descs'] = {};

    $('#setLang input[name^=name]').each(function()
    {
        var lang = $(this).data('lang');
        pivot['names'][lang] = $(this).val();
    });

    $('#setLang textarea[name^=desc]').each(function()
    {
        var lang = $(this).data('lang');
        pivot['descs'][lang] = $(this).val();
    });

    DataStorage.pivot = pivot;

    initData();
});
</script>
