<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<style>
.card-container {display: grid; grid-template-columns: repeat(5, 1fr); gap: 4px 20px; background-color: unset;}
.card {position: relative; border-radius: 4px;}
.card::before {content: ' '; display: block; padding-bottom: 56.25%;}
.con {display: flex; justify-content: center; align-items: center; position: absolute; top: 0; bottom: 0; left: 0; right: 0;}
.title {color: white; display: inline-block; text-align: left; padding: 0 20px; max-height: 100%; overflow: hidden; text-overflow: ellipsis;}
.card-container li {list-style-type: none;}
.item li.file {padding: 10px 10px 0px 10px;}
.right-icon {padding-left: 6px;}
.item li.file a span { display: inline-block;height: 1.2em;overflow: hidden;line-height: 1.2;}
.label-primary.label-outline {border: none; font-size: 12px; font-weight: 600;}
</style>

<div id="mainContent" class="main-row">
  <div class="main-col col-12">
    <div class="cell">
      <div class="detail">
        <div class="detail-title">
          <?php echo $market->name;?>
          <span class="label label-released label-primary label-outline"><?php echo zget($lang->market->strategyList, $market->strategy);?></span>
          <?php if($market->deleted):?>
          <span class='label label-danger'><?php echo $lang->market->deleted;?></span>
          <?php endif; ?>
        </div>
        <div class="detail-content article-content">
          <?php echo !empty($market->desc) ? $market->desc : "<div class='text-center text-muted'>" . $lang->noData . '</div>';?>
        </div>
      </div>
      <div class="detail">
        <div class="detail-content">
          <table class="table table-data data-basic">
            <tbody>
              <tr>
                <th><?php echo $lang->market->industry;?></th>
                <td><strong><?php echo $market->industry;?></strong></td>
                <th><?php echo $lang->market->scale;?></th>
                <td><strong><?php echo $market->scale != 0 ? $market->scale . " " . $lang->market->hundredMillion : '';?></strong></td>
                <th></th>
                <td></td>
              </tr>
              <tr>
                <th><?php echo $lang->market->maturity;?></th>
                <td><strong><?php echo zget($lang->market->maturityList, $market->maturity);?></strong></td>
                <th><?php echo $lang->market->speed;?></th>
                <td><strong><?php echo zget($lang->market->speedList, $market->speed);?></strong></td>
              </tr>
              <tr>
                <th><?php echo $lang->market->competition;?></th>
                <td><strong><?php echo zget($lang->market->competitionList, $market->competition);?></strong></td>
                <th><?php echo $lang->market->ppm;?></th>
                <td><strong><?php echo zget($lang->market->ppmList, $market->ppm);?></strong></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <?php if($reportGroup):?>
    <?php $canView = common::hasPriv('marketreport', 'view');?>
    <?php foreach($reportGroup as $researchName => $reports):?>
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $researchName;?></div>
        <div class="detail-content">
          <div class="card-container cards cards-borderless">
            <?php foreach($reports as $report):?>
            <div class="item">
              <a href="<?php echo $canView ? $this->createLink('marketreport', 'view', "id=$report->id&fromMarket=$report->market") : 'javascript:void(0)';?>" title="<?php echo $report->name;?>">
                <div class="card bg-primary">
                  <div class="con">
                  <h2 class="title"><?php echo $report->name;?></h2>
                  </div>
                </div>
              </a>
              <div>
                <span class="right-icon">
                  <?php common::printIcon('marketreport', 'edit', "id=$report->id", $report, 'list', 'pencil-alt', '', 'btn-link text-primary');?>
                  <?php common::printIcon('marketreport', 'delete', "id=$report->id", $report, 'list', 'trash', 'hiddenwin', 'btn-link text-primary');?>
                </span>
              </div>
            </div>
            <?php endforeach;?>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach;?>
    <?php endif;?>
    <?php $actionFormLink = $this->createLink('action', 'comment', "objectType=market&objectID=$market->id");?>
    <div class="cell"><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php common::printBack(inlink('browse'));?>
        <div class='divider'></div>
        <?php echo $this->market->buildOperateViewMenu($market);?>
      </div>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
