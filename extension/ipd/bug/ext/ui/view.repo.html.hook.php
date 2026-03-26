<?php
namespace zin;
global $app;
if($app->tab == 'devops')
{
    $repoID = data('repoID');
    query('#heading')->append(
        dropmenu
        (
            set::module('repo'),
            set::tab('repo'),
            set::url(createLink('repo', 'ajaxGetDropMenu', "objectID=0&module=repo&method=review"))
        )
    );
}
