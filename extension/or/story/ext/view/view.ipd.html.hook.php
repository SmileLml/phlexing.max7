<?php if($story->vision == 'rnd' and $this->app->tab == 'feedback'):?>
<?php js::set('storyType', $story->type);?>
<style>.main-actions {display: none;}</style>
<script>
$(function()
{
    if(storyType == 'story'){
        $('a:not([data-toggle="tab"]):not([data-app="product"])').each(function() {
            var text = $(this).text();
            $(this).replaceWith(text);
        });
    }
})
</script>
<?php endif;?>
<?php if(!empty($story->demand) and empty($story->retractedBy)):?>
<?php $demand = $this->loadModel('demand')->getByID($story->demand);?>
<div class='cell demandInfo'>
  <div class='detail'>
    <div class='detail-title'><?php echo $this->lang->story->demandInfo;?></div>
    <div class='detail-content article-content'>
      <table class='table table-hover table-fixed'>
        <thead>
          <tr class='text-center'>
            <th class='w-50px'> <?php echo $lang->demand->id;?></th>
            <th class='w-150px'><?php echo $lang->demand->pool;?></th>
            <th><?php echo $lang->demand->title;?></th>
            <th class='w-150px'><?php echo $lang->demand->status;?></th>
          </tr>
        </thead>
        <tbody>
          <tr class='text-center'>
            <td><?php echo $demand->id;?></td>
            <td title='<?php echo $demand->poolName;?>'><?php echo $demand->poolName;?></td>
            <td class='text-left' title='<?php echo $demand->title;?>'><a class="iframe" data-width="90%" href="<?php echo $this->createLink('demand', 'view', "demandID=$demand->id", '', true);?>"><?php echo $demand->title;?></a></td>
            <td><?php echo $this->processStatus('demand', $demand);?></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script>
    var html = $('.demandInfo');
    $('#mainContent .main-col .cell').first().after(html);
</script>
<?php endif;?>

<?php
$html  = "<tr>";
$html .= "<th>{$lang->story->duration}</th>";
$html .= "<td>" . zget($lang->demand->durationList, $story->duration, $story->duration) . "</td>";
$html .= "</tr>";
$html .= "<tr>";
$html .= "<th>{$lang->story->BSA}</th>";
$html .= "<td>" . zget($lang->demand->bsaList, $story->BSA, $story->BSA) . "</td>";
$html .= "</tr>";
?>
<script>
$('.categoryTR').after(<?php echo json_encode($html);?>);
</script>
