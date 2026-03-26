<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('updatePracticeTip', $lang->practice->updatePracticeTip);?>
<div id='mainContent' class="main-row">
  <div class="col-12 main-col">
    <div class="row">
      <div class="col-sm-12 block-introduce">
        <div class="panel">
          <div class="col-sm-2 introduce-image">
            <?php echo html::image($config->webRoot . 'theme/default/images/main/rong_introduce.png');?>
          </div>
          <div class="col-sm-10 panel-heading">
            <div class="panel-title"><?php echo $lang->practice->rongpm;?></div>
          </div>
          <div class="col-sm-10 panel-body">
            <div class="introduce-content">
              <?php echo $lang->practice->introduce;?>
            </div>
          </div>
          <br>
          <div class="introduce-source">
            <?php echo $lang->practice->source;?>
          </div>
        </div>
      </div>
      <div class="practice-data">
        <div class="col-sm-8 block-all-practice">
          <div class="panel">
            <div class="panel-heading all-practice-heading">
              <div class="panel-title">
                <?php echo $lang->practice->all . " ($practiceNum)";?>
                <?php if(!helper::isIntranet() and common::hasPriv('traincourse', 'updatePractice')):?>
                <div class="update-practice btn btn-info pull-right">
                  <?php echo $lang->practice->updatePractice;?>
                </div>
                <?php endif;?>
                <?php if(helper::isIntranet()):?>
                <div class="load-images btn btn-info pull-right" data-toggle="modal" data-target="#loadImages">
                  <i class="icon icon-spinner-indicator"></i>
                  <?php echo $lang->practice->loadImages;?>
                </div>
                <div class="modal fade" id="loadImages">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                        <strong class="modal-title"><?php echo $lang->practice->loadImages;?></strong>
                      </div>
                      <div class="modal-body">
                        <?php
                          $this->app->loadConfig('upgrade');
                          $currentVersion = str_replace('.', '_', $config->version);
                          $versions       = $config->upgrade->{$config->edition . 'Version'};
                          $version        = str_replace('_', '.', $versions[$currentVersion]);
                        ?>
                        <?php echo sprintf($lang->practice->loadImagesTip, $version, $version);?>
                      </div>
                    </div>
                  </div>
                </div>
                <?php endif;?>
              </div>
            </div>
            <div class="panel-body">
              <div id='cards' class="clearfix">
                <?php foreach($categories as $firstCategoryID => $category):?>
                  <div class='col-sm-3'>
                    <div class='panel-content category'>
                      <div class="panel-heading">
                        <div class="panel-title first-category">
                          <?php echo html::a(helper::createLink('traincourse', 'practicebrowse', "moduleID={$firstCategoryID}"), $category['name'] . '<i class="icon icon-angle-right"></i>', '', "title='{$category['name']}'");?>
                        </div>
                      </div>
                      <?php if(!empty($category['children'])):?>
                      <div class="panel-body second-category">
                        <ul>
                          <?php
                            $i = 0;
                            $childrenCount = count($category['children']);
                          ?>
                          <?php foreach($category['children'] as $childID => $childName):?>
                            <?php
                              $i ++;
                              if($i == 4 and $childrenCount > 4)
                              {
                                echo '<li>···</li>';
                                break;
                              }
                              if($i > 5) break;
                            ?>
                          <li>
                            <div class="childrenName">
                              <?php echo html::a(helper::createLink('traincourse', 'practicebrowse', "moduleID={$childID}"), $childName, '', "title='$childName'");?>
                            </div>
                          </li>
                          <?php endforeach;?>
                        </ul>
                      </div>
                      <?php endif;?>
                    </div>
                  </div>
                <?php endforeach;?>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-4 block-latest-practice">
          <div class="panel">
            <div class="panel-heading">
              <div class="panel-title"><?php echo $lang->practice->latest;?></div>
            </div>
            <div class="panel-body">
              <?php foreach($latestPractices as $latestPractice):?>
              <div class="latest-practice">
                <a <?php echo common::hasPriv('traincourse', 'practiceview') ? "href='" . helper::createLink('traincourse', 'practiceview', "id={$latestPractice['id']}") . "'" : '';?>" title="<?php echo $latestPractice['title'];?>">
                  <div class="col-sm-8">
                    <?php $labelStyle = !empty($latestPractice['labels']) ? 'style="max-width: 70%;"' : 'style="max-width: 100%;"';?>
                    <div class="pull-left practice-name" <?php echo $labelStyle;?>><?php echo $latestPractice['title'];?></div>
                    <?php if(!empty($latestPractice['labels'])):?>
                      <?php $label = explode(',', $latestPractice['labels'])[0];?>
                      <div class="pull-left label-item <?php echo $config->practice->labelClassList[0];?>"><?php echo $label;?></div>
                    <?php endif;?>
                  </div>
                </a>
                <?php if(!empty($latestPractice['contributor'])):?>
                <div class="col-sm-4 contributor" title='<?php echo $latestPractice['contributor'];?>'><?php echo sprintf($lang->practice->contributor, $latestPractice['contributor']);?></div>
                <?php endif;?>
              </div>
              <?php endforeach;?>
            </div>
          </div>
        </div>
        <div class="col-sm-4 block-recommend-practice">
          <div class="panel">
            <div class="panel-heading">
              <div class="panel-title"><?php echo $lang->practice->recommend;?></div>
            </div>
            <div class="panel-body">
              <?php foreach($recommendedPractices as $recommendedPractice):?>
                <div class="recommend-practice">
                  <a <?php echo common::hasPriv('traincourse', 'practiceview') ? "href='" . helper::createLink('traincourse', 'practiceview', "id={$recommendedPractice['id']}") . "'" : '';?>" title="<?php echo $recommendedPractice['title'];?>">
                    <div class="col-sm-8">
                      <?php $labelStyle = !empty($recommendedPractice['labels']) ? 'style="max-width: 70%;"' : 'style="max-width: 100%;"';?>
                      <div class="pull-left practice-name" <?php echo $labelStyle;?>><?php echo $recommendedPractice['title'];?></div>
                      <?php if(!empty($recommendedPractice['labels'])):?>
                        <?php $label = explode(',', $recommendedPractice['labels'])[0];?>
                        <div class="pull-left label-item <?php echo $config->practice->labelClassList[0];?>"><?php echo $label;?></div>
                      <?php endif;?>
                    </div>
                  </a>
                  <?php if(!empty($recommendedPractice['contributor'])):?>
                  <div class="col-sm-4 contributor" title='<?php echo $recommendedPractice['contributor'];?>'><?php echo sprintf($lang->practice->contributor, $recommendedPractice['contributor']);?></div>
                  <?php endif;?>
                </div>
              <?php endforeach;?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="thanks">
      <?php echo $lang->practice->thanks;?>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
