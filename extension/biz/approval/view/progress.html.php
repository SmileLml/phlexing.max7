<?php include $app->getModuleRoot() . 'common/view/header.html.php'?>
<style>
ul.timeline + ul.timeline {margin-top: 20px;}
ul.timeline > li.reviewer:before{width:0px; height:7px; left:-13px; border:none; border-left:1px solid #eee;}
ul.timeline > li.node:before{width:10px; height:10px; background-color:unset;left:-18px;}
ul.timeline > li.node.pass:before {width:0px; height:0px; background-color:unset;left:-13px;border:none}
ul.timeline > li.node.pass > div:before{position: absolute; content: "\e92f"; color:#00D293; left:-22px; top:5px; font-size:18px; font-family: ZentaoIcon;z-index:50}
ul.timeline > li.node.reverted:before {width:0px; height:0px; background-color:unset;left:-13px;border:none}
ul.timeline > li.node.reverted > div:before{position: absolute; content: "\e9d3"; color:#313c52; left:-22px; top:5px; font-size:18px; font-family: ZentaoIcon;z-index:50}
ul.timeline > li.node.doing:before {width:18px; height:18px; background-color:#00B3FF;left:-21px;top:5px;border-color:#00B3FF}
ul.timeline > li.node.doing > div:before{position: absolute; content: "..."; color:#FFF; left:-19px; top:0px; font-size:18px; font-family: ZentaoIcon;z-index:50}
ul.timeline > li.node.fail:before {width:18px; height:18px; background-color:#FF4550;left:-21px;top:5px;border-color:#FF4550}
ul.timeline > li.node.fail > div:before{position: absolute; content: "\e936"; color:#FFF; left:-18px; top:3px; font-size:12px; font-family: ZentaoIcon;z-index:50}
ul.timeline > li .timeline-text .result.reviewing {color:#00B3FF; padding-left:3px; font-weight:bolder;}
ul.timeline > li .timeline-text .result.pass      {color:#00D293; padding-left:3px; font-weight:bolder;}
ul.timeline > li .timeline-text .result.fail      {color:#FF4550; padding-left:3px; font-weight:bolder;}
ul.timeline > li .opinion {padding: 8px; background-color:#F5F5F5;}
.approvalNotice {padding-left: 8px;}
</style>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <span class="text"><?php echo $title;?></span>
    </div>
    <?php $i = 1;?>
    <?php foreach($nodeGroup as $approvalID => $nodes):?>
    <ul class='timeline timeline-tag-right'>
      <?php
      foreach($nodes as $node)
      {
          if(empty($node->id)) continue;
          if(($node->type == 'start' || $node->type == 'end') && $i == 1)
          {
              echo $this->approval->buildReviewDesc($node, array('users' => $users, 'approval' => $approval));
          }
          elseif($node->type == 'branch')
          {
              foreach($node->branches as $branchNodes)
              {
                  foreach($branchNodes->nodes as $branchNode)
                  {
                      echo $this->approval->buildReviewDesc($branchNode, array('users' => $users, 'allReviewers' => $reviewerGroup[$approvalID], 'reviewers' => zget($reviewerGroup[$approvalID], $branchNode->id, array())), $nodePairs);
                  }
              }
          }
          elseif(isset($reviewerGroup[$approvalID][$node->id]))
          {
              echo $this->approval->buildReviewDesc($node, array('users' => $users, 'reviewers' => $reviewerGroup[$approvalID][$node->id]), $nodePairs);
          }
          elseif(!empty($node->reviewers))
          {
              echo $this->approval->buildReviewDesc($node, array(), $nodePairs);
          }
      }
      ?>
    </ul>
    <hr>
    <?php $i++;?>
    <?php endforeach;?>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
