<?php include $app->getModuleRoot() . 'common/view/m.header.html.php';?>
<section id='page' class='section list-with-actions list-with-pager'>
  <div class='heading gray'>
    <div class='title'>
      <span class='prefix'><strong><?php echo $deploy->id?></strong></span>
      <strong><?php echo $deploy->name?></strong>
    </div>
    <nav class='nav'><a href="javascript:history.go(-1);" class='btn primary'><?php echo $lang->goback;?></a></nav>
  </div>
  <div class='box'>
    <?php foreach($lang->deploy->stageList as $stage => $name):?>
    <table class="table bordered">
      <thead>
      <tr>
        <th><?php echo $name;?></th>
        <th class="w-120px"></th>
        <th class="w-70px"></th>
      </tr>
      </thead>
      <tbody>
      <?php if($stage == 'testing' and !empty($stepGroups['cases'])):?>
      <?php foreach($stepGroups['cases'] as $case):?>
      <tr>
        <td><?php echo $case->title;?></td>
        <?php $result = zget($results, $case->id, '');?>
        <td><?php echo $result ? zget($users, $result->lastRunner) : '';?></td>
        <td><?php echo $result ? zget($lang->testcase->resultList, $result->caseResult) : '';?></td>
      </tr>
      <?php endforeach;?>
      <?php else:?>
      <?php if(!isset($stepGroups[$stage])) $stepGroups[$stage] = array();?>
      <?php foreach($stepGroups[$stage] as $step):?>
      <tr>
        <td><?php echo $step->title;?></td>
        <td><?php echo substr($step->begin, 11, 5) . ' ~ ' . substr($step->end, 11, 5);?></td>
        <td><?php echo zget($lang->deploy->statusList, $step->status);?></td>
      </tr>
      <?php endforeach;?>
      <?php endif;?>
      </tbody>
    </table>
    <?php endforeach;?>
  </div>
</section>
<?php include $app->getModuleRoot() . 'common/view/m.footer.html.php';?>
