<?php js::set('productID', $productID);?>
<?php js::set('module', $module);?>
<?php js::set('method', $method);?>
<?php js::set('extra', $extra);?>
<style>
#tabContent {margin-top: 5px; z-index: 900; max-width: 220px}
.productTree ul {list-style: none; margin: 0}
.productTree .products>ul {padding-left: 7px;}
.productTree .products>ul>li>div {display: flex; flex-flow: row nowrap; justify-content: flex-start; align-items: center;}
.productTree .products>ul>li label {background: rgba(255,255,255,0.5); line-height: unset; color: #838a9d; border: 1px solid #d8d8d8; border-radius: 2px; padding: 1px 4px;}
.productTree li a i.icon {font-size: 15px !important;}
.productTree li a i.icon:before {min-width: 16px !important;}
.productTree li .label {position: unset; margin-bottom: 0;}
.productTree li>a, div.hide-in-search>a {display: block; padding: 2px 10px 2px 5px; overflow: hidden; line-height: 20px; text-overflow: ellipsis; white-space: nowrap; border-radius: 4px;}
.productTree .tree li>.list-toggle {line-height: 24px;}
.productTree .tree li.has-list.open:before {content: unset;}
.productTree li>a, div.hide-in-search>a {display: block; padding: 2px 10px 2px 5px; overflow: hidden; line-height: 20px; text-overflow: ellipsis; white-space: nowrap; border-radius: 4px;}

#swapper li>div.hide-in-search>a:focus, #swapper li>div.hide-in-search>a:hover {color: #838a9d; cursor: default;}
#swapper li > a {margin-top: 4px; margin-bottom: 4px;}
#swapper li>div.hide-in-search>a:focus, #swapper li>div.hide-in-search>a:hover {color: #838a9d; cursor: default;}
#swapper li {padding-top: 0; padding-bottom: 0;}
#swapper .tree li>.list-toggle {top: -1px;}

#subHeader .tree ul {display: block;}
div#closed {width: 90px; height: 25px; line-height: 25px; background-color: #ddd; color: #3c495c; text-align: center; margin-left: 15px; border-radius: 2px;}
#gray-line {width: 230px; height: 1px; margin-left: 10px; margin-bottom:2px; background-color: #ddd;}
#swapper li >.selected {color: #0c64eb!important;background: #e9f2fb!important;}
#dropMenu .col-footer .selected{color: #2e7fff!important;background: #e6f0ff!important; padding: 1px 10px;border-radius: 4px;}
#dropMenu .col-left {padding-bottom: 5px;}
</style>
<?php
$productCounts      = array();
$productNames       = array();
$normalProductsHtml = array();

foreach($products as $programID => $programProducts)
{
    $productCounts[$programID]['normal'] = 0;

    foreach($programProducts as $product)
    {
        $productCounts[$programID]['normal'] ++;
        $productNames[] = $product->name;
    }
}
$productsPinYin = common::convert2Pinyin($productNames);

$normalProductsHtml  = $config->systemMode == 'ALM' ? '<ul class="tree tree-angles" data-ride="tree">' : '<ul class="noProgram">';
$allProductlink      = $module == 'ticket' ? helper::createLink('ticket', 'browse', 'browseType=byProduct&param=all') : helper::createLink('feedback', 'admin', 'browseType=byProduct&param=all');
if($this->config->vision == 'lite' and $module != 'ticket') $allProductlink = helper::createLink('feedback', 'browse', 'browseType=byProduct&param=all');
$selected            = $productID == 'all' ? 'selected' : '';
$normalProductsHtml .= '<li>' . html::a($allProductlink, $lang->product->allProduct, '', "class='$selected clickable' title='{$lang->product->allProduct}'") . '</li>';

foreach($products as $programID => $programProducts)
{
    /* Add the program name before project. */
    if($programID and $config->systemMode == 'ALM')
    {
        $programName = zget($programs, $programID);

        if($productCounts[$programID]['normal']) $normalProductsHtml .= '<li><div class="hide-in-search"><a class="text-muted not-list-item" title="' . $programName . '">' . $programName . '</a> <label class="label">' . $lang->program->common . '</label></div><ul>';
    }

    foreach($programProducts as $index => $product)
    {
        $selected    = $product->id == $productID ? 'selected' : '';
        $productName = ($config->systemMode == 'ALM' and $product->line) ? zget($lines, $product->line, '') . ' / ' . $product->name : $product->name;
        $linkHtml    = $this->product->setParamsForLink($module, $link, $projectID, $product->id);
        $locateTab   = ($module == 'testtask' and $method == 'browseUnits' and $app->tab == 'project') ? '' : "data-app='$app->tab'";

        $normalProductsHtml .= '<li>' . html::a($linkHtml, $productName, '', "class='$selected clickable' title='{$productName}' data-key='" . zget($productsPinYin, $product->name, '') . "' data-app='$app->tab'") . '</li>';

        /* If the programID is greater than 0, the product is the last one in the program, print the closed label. */
        if($programID and !isset($programProducts[$index + 1]))
        {
            if($productCounts[$programID]['normal']) $normalProductsHtml .= '</ul></li>';
        }
    }
}
$normalProductsHtml .= '</ul>';
?>
<div class="table-row">
  <div class="table-col col-left">
    <div class='list-group'>
      <div class="tab-content productTree" id="tabContent">
        <div class="tab-pane products active">
          <?php echo $normalProductsHtml;?>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
$(function()
{
    scrollToSelected();

    $('#swapper [data-ride="tree"]').tree('expand');
    $('#swapper #dropMenu .search-box').on('onSearchChange', function(event, value)
    {
        if(value != '')
        {
            $('div.hide-in-search').siblings('i').addClass('hide-in-search');
            $('.nav-tabs li span').hide();
        }
        else
        {
            $('div.hide-in-search').siblings('i').removeClass('hide-in-search');
            $('li.has-list div.hide-in-search').removeClass('hidden');
            $('.nav-tabs li.active').find('span').show();
        }
    })
})
</script>
