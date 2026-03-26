<?php
$config->story->dtable->defaultField = array_splice($config->story->dtable->defaultField, array_search('taskCount', $config->story->dtable->defaultField), 0, 'relatedObject');
