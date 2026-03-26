<style>
#book .tree li.active>span.item{font-weight: 700; color: #0c64eb;}
#book .tree li.active>span.item a{font-weight: 700; color: #0c64eb;}
#book .tree li>span.item a{color: #0d3d88;}
</style>
<?php $serials  = $this->doc->computeSN($bookID, 'baseline'); ?>
<?php $nodeList = $this->doc->getNodeList($bookID);?>
<li>
  <?php echo "<i class='icon icon-folder-o'></i> " . $book->title;?>
  <ul>
    <?php foreach($nodeList as $nodeInfo):?>
    <?php $serial = $nodeInfo->type != 'book' ? $serials[$nodeInfo->id] : '';?>
    <?php $activeClass = (isset($doc->id) && $doc->id == $nodeInfo->id) ? 'active' : '';?>
      <li <?php echo "class='open $activeClass'";?>>
      <?php if($nodeInfo->type == 'chapter'):?>
      <?php echo "<span class='item'>{$serial} {$nodeInfo->title}</span>";?>
      <?php if($nodeInfo->chapterType == 'system') echo $this->doc->getCmStructure($nodeInfo->template, $serial, $object);?>
      <?php elseif($nodeInfo->type == 'article'):?>
      <?php echo "<span class='item'>{$serial} " . html::a(helper::createLink('baseline', 'view', "docID=$nodeInfo->id"), $nodeInfo->title, '', 'data-app=admin') . '</span>';?>
      <?php endif;?>
      <?php if(!empty($nodeInfo->children)) echo $this->doc->getFrontCatalog($nodeInfo->children, $serials, isset($doc->id) ? $doc->id : 0);?>
      </li>
    <?php endforeach;?>
  </ul>
</li>
