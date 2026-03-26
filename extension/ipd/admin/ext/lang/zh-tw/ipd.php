<?php
unset($lang->admin->menuList->system['subMenu']['mode']);
unset($lang->admin->menuList->system['menuOrder']['5']);

if(!empty($lang->admin->menuList->feature['tabMenu']['product']['epic']))        $lang->admin->menuList->feature['tabMenu']['product']['epic']['alias']        = 'epicgrade';
if(!empty($lang->admin->menuList->feature['tabMenu']['product']['requirement'])) $lang->admin->menuList->feature['tabMenu']['product']['requirement']['alias'] = 'requirementgrade';

$lang->admin->menuList->feature['tabMenu']['product']['story']['alias'] = 'storygrade';
$lang->admin->menuList->feature['subMenu']['product']['alias']          = 'browsestoryconcept,product,storygrade,requirementgrade,epicgrade';
