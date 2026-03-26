<div class='heading divider'>
  <span class='title'>
    <input type='text' class='input' id='search' value='' placeholder='<?php echo $this->app->loadLang('search')->search->common;?>'/>
  </span>
  <nav class='nav'>
    <a data-dismiss='display'><i class='icon-remove muted'></i></a>
  </nav>
<?php js::set('productID', $productID);?>
<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<?php js::set('extra', $extra);?>
</div>
<?php
$mines   = array();
$others  = array();
$closeds = array();
foreach($products as $programID => $programProducts)
{
    foreach($programProducts as $product)
    {
        if($product->status == 'normal' and $product->PO == $this->app->user->account) $mines[$programID][$product->id] = $product;
        if($product->status == 'normal' and !($product->PO == $this->app->user->account)) $others[$programID][$product->id] = $product;
        if($product->status == 'closed') $closeds[$programID][$product->id] = $product;
    }
}
?>
<div id='searchResult' class='content'>
  <div id='defaultMenu' class='search-list'>
    <div id='activedItems'>
      <?php
      if($mines)
      {
          echo "<div class='heading'>{$lang->product->mine}</div>";
          echo "<div class='list'>";
          foreach($mines as $programID => $products)
          {
              foreach($products as $product) echo html::a(sprintf($link, $product->id), "<i class='icon-cube'></i>&nbsp;{$product->name}", '', "class='mine text-important item' data-id='{$product->id}' data-tag=':{$product->status} @{$product->PO} @mine' data-key='{$product->code}'");
          }
          echo '</div>';
      }

      if($others)
      {
          echo "<div class='heading'>{$lang->product->other}</div>";
          $class = "other text-special";
          echo "<div class='list'>";
          foreach($others as $programID => $products)
          {
              foreach($products as $product) echo html::a(sprintf($link, $product->id), "<i class='icon-cube'></i>&nbsp;{$product->name}", '', "class='$class item' data-id='{$product->id}' data-tag=':{$product->status} @{$product->PO}' data-key='{$product->code}'");
          }
          echo '</div>';
      }
      if($closeds)
      {
          echo "<div class='heading'>{$lang->product->closed}</div>";
          echo "<div class='list'>";
          foreach($closeds as $programID => $products)
          {
              foreach($products as $product) echo html::a(sprintf($link, $product->id), "<i class='icon-cube'></i> {$product->name}", '', "data-id='{$product->id}' data-tag=':{$product->status} @{$product->PO}' class='closed item' data-key='{$product->code}'");
          }
          echo '</div>';
      }
      ?>
    </div>
  </div>
</div>
