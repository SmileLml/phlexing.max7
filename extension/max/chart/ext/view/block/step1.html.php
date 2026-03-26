<div id='mainContent' class='main-row'>
  <div class='main-col'>
    <style><?php include $this->app->getModuleRoot() . 'dataview/css/querybase.css';?></style>
    <?php include $this->app->getModuleRoot() . 'dataview/view/querybase.html.php';?>
    <script><?php include $this->app->getModuleRoot() . 'dataview/js/querybase.js';?></script>
  </div>
</div>

<div class="pull-right heading-actions">
    <button id="previewSql" type="button" class="btn hidden"><?php echo $lang->bi->previewSql;?></button>
    <button id="changeMode" type="button" class="btn"><i class="icon icon-exchange"></i><?php echo $lang->bi->toggleSqlBuilder;?></button>
    <button id="changeModeDisabled" type="button" class="btn disabled hidden" disabled title="<?php echo $lang->bi->modeDisableTip;?>"><i class="icon icon-exchange"></i><?php echo $lang->bi->toggleSqlBuilder;?></button>
</div>

<iframe id='sqlbuilder' class='hidden' style="width: 100%; border: none; height: 384px;" src=""></iframe>

<script>

window.sqlBuilderChange = function(builder)
{
    $('#sql').val(builder.sql);
    DataStorage.sqlbuilder = builder;
    $('.error').addClass('hidden');
}

window.toggleChangeMode = function()
{
    if($('#sql').val()?.length)
    {
        $('#changeMode').addClass('hidden');
        $('#changeModeDisabled').removeClass('hidden');
    }
    else
    {
        $('#changeMode').removeClass('hidden');
        $('#changeModeDisabled').addClass('hidden');
    }
}

window.doChangeMode = function()
{
    DataStorage.mode = DataStorage.mode == 'text' ? 'builder' : 'text';
    updateMode();
}

window.updateMode = function()
{
    if(DataStorage.mode == 'text')
    {
        $('#dictionary').removeClass('hidden');
        $('#queryPanel').css('width', '85%');
        $('#sql').removeClass('hidden');
        $('#sqlbuilder').addClass('hidden');
        $('#previewSql').addClass('hidden');
        $('#changeMode').html('<i class="icon icon-exchange"></i><?php echo $lang->bi->toggleSqlBuilder;?>');
        $('#sqlbuilder').attr('src', '');
        toggleChangeMode();
    }
    else
    {
        $('#dictionary').addClass('hidden');
        $('#queryPanel').css('width', '100%');
        $('#sql').addClass('hidden');
        $('#sqlbuilder').removeClass('hidden');
        $('#previewSql').removeClass('hidden');
        $('#changeMode').html('<i class="icon icon-exchange"></i><?php echo $lang->bi->toggleSqlText;?>');
        $('#sqlbuilder').attr('src', `<?php echo $this->createLink('sqlbuilder', 'index', "objectID={$chart->id}&objectType=chart");?>`);
    }
}

$(function()
{
    $('#dataform .btn.query').after(<?php echo json_encode('<button type="button" class="btn btn-primary btn-next-step pull-right" onclick="nextStep()">' . $lang->chart->nextStep . '</button>');?>);
    $('.heading-actions').appendTo($('.main-col .panel-heading').first());
    $('#sql').after($('#sqlbuilder'));
    updateMode();

    $(document).on('change', '#sql', function()
    {
        toggleChangeMode();
    })

    $('#changeMode').click(function()
    {
        if(DataStorage.mode == 'builder')
        {
            bootbox.confirm({message: '<?php echo $lang->bi->changeModeTip;?>', callback: function(res) {if(res) doChangeMode()}});
        }
        else
        {
            doChangeMode();
        }

    });

    $('#previewSql').click(function()
    {
        var sql = $('#sql').val();
        if(!sql?.length) sql = '<?php echo $lang->bi->noSql;?>';

        sql = sql.replace(/\n/g, '<br />');
        bootbox.confirm({message: `<div>${sql}</div>`, callback:function(){}});
    })
});
</script>
