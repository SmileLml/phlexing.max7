<?php
/**
 * The ajaxPreview view file of workflowreport module of ZDOO.
 *
 * @copyright   Copyright 2009-2020 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Dongdong Jia <jiadongdong@easycorp.ltd>
 * @package     workflowreport
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>

<?php js::set('chartDemo', $lang->workflowreport->demo);?>
<?php js::set('currentType', $report->type);?>
<?php js::set('currentCountType', $report->countType);?>
<?php js::set('reportFields', !empty($report->fields) ? sizeof($report->fields) : 0);?>
<?php js::set('displayType', $report->displayType);?>
<table class='table'>
  <tr>
    <td id='previewID'><?php echo $report->name;?></td>
    <td></td>
  </tr>
  <tr>
    <td style='border-bottom: unset;'><div id='container'></div></td>
    <td class="legendContainer"><span id="pieLegend" class="chart-legend"></span></td>
  </tr>
</table>
<script>
$(document).ready(function()
{
		var globalOption = {};
		if(displayType == 'percent') globalOption = {scaleLabel: "<%=value%>%", tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %>%",  multiTooltipTemplate: "<%if (datasetLabel){%><%=datasetLabel%>: <%}%><%= value %>%"};
    if(currentType == 'pie')
    {
        $('#container').html('<canvas id="previewChart" width="400" height="400"></canvas>');
        var data = [{value: 40, color: "blue", label: chartDemo.pie1 },
            {value: 30, color:"#F7464A", label: chartDemo.pie2 },
            {value: 20, color: 'green' , label: chartDemo.pie3 },
            {value: 10,  color: 'yellow', label: chartDemo.pie4 }];
				var options = globalOption;
        options["scaleShowLabels"] = true;
        var myPieChart = $("#previewChart").pieChart(data, options);
        $('#pieLegend').append(myPieChart.generateLegend());
    }
    else if(currentType == 'line')
    {
      $('#container').html('<canvas id="previewChart" width="800" height="400"></canvas>');
        var data = generateRandomData();
				var options = globalOption;
        options["responsive"] = true;
        var myLineChart = $("#previewChart").lineChart(data, options);
        if(reportFields > 1)
        {
            $('#container').prepend('<div id="lineLegend"></div>');
            $('#lineLegend').append(myLineChart.generateLegend());
        }
    }
    else if(currentType == 'bar')
    {
        $('#container').html('<canvas id="previewChart" width="800" height="400"></canvas>');
        var data = generateRandomData();
				var options = globalOption;
        options["responsive"]      = true;
				options["barValueSpacing"] = 20;
        var myBarChart = $('#previewChart').barChart(data, options);
        if(reportFields > 1)
        {
            $('#container').prepend('<div id="barLegend"></div>');
            $('#barLegend').append(myBarChart.generateLegend());
        }
    }

    /**
     * Generate random data of line chart or bar chart.
     *
     * @access public
     * @return void
     */
    function generateRandomData()
    {
        var datasets = new Array();
        if(currentCountType == 'count') reportFields = 1;
        for(var i = 1; i <= reportFields; i++)
        {
            var dataset = {};
            dataset.label = chartDemo.dataset + i;
            dataset.data  = new Array();
						randomArray   = new Array();
            for(var j = 0; j < 6; j++)
            {
                var rand = parseInt(Math.random() * 90 + 10);
								randomArray.push(rand);
						}

						/* Count percentage. */
						if(displayType == 'percent')
						{
								var sum = eval(randomArray.join("+"));
								$.each(randomArray, function(index, value){ randomArray[index] = (value / sum * 100).toFixed(1)});
						}

						dataset.data = randomArray;
            datasets.push(dataset);
        }
        var data = {labels: [chartDemo.month1, chartDemo.month2, chartDemo.month3, chartDemo.month4, chartDemo.month5, chartDemo.month6 ], datasets: datasets};
        return data;
    }
});
</script>
