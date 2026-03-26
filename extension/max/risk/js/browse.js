$(function()
{
    /* Get checked risks. */
    $('#importToLib').on('click', function()
    {
        var riskIDList = '';
        $("input[name^='riskIDList']:checked").each(function()
        {
            riskIDList += $(this).val() + ',';
            $('#riskIDList').val(riskIDList);
        });
    });

    $('#riskForm').table(
    {
        statisticCreator: function(table)
        {
            var $table       = table.getTable();
            var $checkedRows = $table.find(table.isDataTable ? '.datatable-row-left.checked' : 'tbody>tr.checked');
            var $originTable = table.isDataTable ? table.$.find('.datatable-origin') : null;
            var checkedTotal = $checkedRows.length;
            var $rows        = checkedTotal ? $checkedRows : $table.find(table.isDataTable ? '.datatable-rows .datatable-row-left' : 'tbody>tr');

            var checkedActive   = 0;
            var checkedHangup   = 0;
            $rows.each(function()
            {
                var $row = $(this);
                if($originTable) $row = $originTable.find('tbody>tr[data-id="' + $row.data('id') + '"]');

                var data = $row.data();

                if(data.status === 'active')   checkedActive++;
                if(data.status === 'hangup')   checkedHangup++;
            });

            if(browseType != 'all') return (checkedTotal ? checkedRiskSummary : pageRiskSummary).replace('%s', $rows.length);
            return (checkedTotal ? checkedSummary : pageSummary).replace('%total%', $rows.length).replace('%active%', checkedActive).replace('%hangup%', checkedHangup);
        }
    })
})
