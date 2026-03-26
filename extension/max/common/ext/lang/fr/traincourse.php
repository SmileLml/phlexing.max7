<?php
$lang->navIcons['traincourse'] = "<i class='icon icon-college'></i>";

$lang->traincourse = new stdclass();
$lang->traincourse->common = 'Academy';

$lang->mainNav->traincourse = "{$lang->navIcons['traincourse']} {$lang->traincourse->common}|traincourse|browse|";

$lang->navGroup->traincourse = 'traincourse';

$lang->searchLang = 'Search';

$lang->traincourse->menu = new stdclass();
$lang->traincourse->menu->browse   = array('link' => 'Course|traincourse|browse', 'alias' => 'create,edit,view,viewcourse,viewchapter');
$lang->traincourse->menu->practice = array('link' => 'Practice Lib|traincourse|practice', 'alias' => 'practicebrowse,practiceview');
$lang->traincourse->menu->admin    = array('link' => 'Admin|traincourse|admin', 'alias' => 'create,edit,browsecategory,managecourse,createcourse,editchapter,managechapter,cloudimport');

$lang->traincourse->menuOrder[5]  = 'browse';
$lang->traincourse->menuOrder[10] = 'practice';
$lang->traincourse->menuOrder[15] = 'admin';
