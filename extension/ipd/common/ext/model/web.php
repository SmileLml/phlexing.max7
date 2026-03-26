<?php
public static function printWebMainMenu()
{
    global $app, $lang;
    ksort($lang->webMenuOrder);

    $currentModule = $app->rawModule;
    $webMainMenus  = array();
    foreach($lang->webMenuOrder as $webMainMenuKey)
    {
        list($label, $moduleName, $methodName, $params) = explode('|', $lang->webMainNav->$webMainMenuKey);
        if(!common::hasPriv($moduleName, $methodName)) continue;

        $active = '';
        if($currentModule == $moduleName) $active = '1';
        if(isset($lang->navGroup->$currentModule) and ($lang->navGroup->$currentModule == $moduleName or $lang->navGroup->$currentModule == $webMainMenuKey)) $active = '1';
        if(isset($lang->webMenuGroup[$currentModule]) and $lang->webMenuGroup[$currentModule] == $webMainMenuKey) $active = '1';

        $webMainMenu = new stdclass();
        $webMainMenu->link   = helper::createLink($moduleName, $methodName, $params);
        $webMainMenu->active = $active;
        $webMainMenu->title  = $label;

        $webMainMenus[$webMainMenuKey] = $webMainMenu;
    }

    $maxBottomMenuCount = common::checkNotCN() ? 6 : 7;
    $i = 0;
    $count = count($webMainMenus);
    foreach($webMainMenus as $webMainMenuKey => $webMainMenu)
    {
        $i++;
        $active = $webMainMenu->active ? 'active' : '';
        if($count <= $maxBottomMenuCount or ($count > ($maxBottomMenuCount) && $i < ($maxBottomMenuCount)))
        {
            echo html::a($webMainMenu->link, "<div class='content'><div class='title'>{$webMainMenu->title}</div></div>", '', "class='item {$active}' data-id='$webMainMenuKey'");
        }

        if($count <= $maxBottomMenuCount) continue;

        if($i == ($maxBottomMenuCount))
        {
            $style = '{"position": "absolute", "top": "auto", "left": "auto", "bottom": 48, "right": 0}';
            echo "<a class='item' data-display='dropdown' data-placement='$style'>";
            echo "<div class='content'>";
            echo "<div class='title'>$lang->more</div>";
            echo "</div>\n</a>";
            echo "<div id='moreApp' class='list dropdown-menu'>";
        }
        if($i >= $maxBottomMenuCount)
        {
            echo "<a class='item text-center $active' href='{$webMainMenu->link}'>";
            echo "<div class='title'>{$webMainMenu->title}</div>";
            echo "</a>\n <div class='divider no-margin'></div>";
        }
        if($i == $count) echo "</div>";
    }
}

public static function printWebModuleMenu($moduleName)
{
    global $app, $lang;
    ksort($lang->$moduleName->webMenuOrder);

    $groupName = isset($lang->webMenuGroup[$moduleName]) ? $lang->webMenuGroup[$moduleName] : '';

    $currentModule  = strtolower($app->rawModule);
    $currentMethod  = strtolower($app->rawMethod);
    $moduleWebMenus = array();
    foreach($lang->$moduleName->webMenuOrder as $webMenuKey)
    {
        if($groupName)
        {
            $moduleWebMenu = $lang->$groupName->webMenu->$webMenuKey;
        }
        else
        {
            $moduleWebMenu = $lang->$moduleName->webMenu->$webMenuKey;
        }

        $link = is_array($moduleWebMenu) ? $moduleWebMenu['link'] : $moduleWebMenu;
        list($label, $linkModuleName, $linkMethodName, $params) = explode('|', $link);
        if(!common::hasPriv($linkModuleName, $linkMethodName)) continue;

        if(is_string($moduleWebMenu))
        {
            $moduleWebMenu = array();
            $moduleWebMenu['link'] = $link;
        }

        $active = '';
        if($currentModule == $linkModuleName and $currentMethod == strtolower($linkMethodName)) $active = '1';
        if($currentModule == $linkModuleName and isset($moduleWebMenu['alias']) and stripos(",{$moduleWebMenu['alias']},", ",{$currentMethod},") !== false) $active = '1';
        if(isset($moduleWebMenu['subModule']) and strpos(",{$moduleWebMenu['subModule']},", ",{$currentModule},") !== false) $active = '1';

        $moduleWebMenu = new stdclass();
        $moduleWebMenu->link   = helper::createLink($linkModuleName, $linkMethodName, $params);
        $moduleWebMenu->active = $active;
        $moduleWebMenu->title  = $label;

        $moduleWebMenus[$webMenuKey] = $moduleWebMenu;
    }

    foreach($moduleWebMenus as $webMenuKey => $moduleWebMenu)
    {
        $active = $moduleWebMenu->active ? 'active' : '';
        echo html::a($moduleWebMenu->link, $moduleWebMenu->title, '', "class='$active' data-id='{$webMenuKey}'");
    }
}
