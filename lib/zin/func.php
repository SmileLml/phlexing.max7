<?php
/**
 * The functions of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'core' . DS . 'zin.func.php';
require_once __DIR__ . DS . 'core' . DS . 'render.func.php';
require_once __DIR__ . DS . 'zui' . DS . 'zui.func.php';
require_once __DIR__ . DS . 'zentao' . DS . 'zentao.func.php';
require_once __DIR__ . DS . 'zentao' . DS . 'field.func.php';
require_once __DIR__ . DS . 'zentao' . DS . 'bind.class.php';

function input() {return createWg('input', func_get_args());}
function textarea() {return createWg('textarea', func_get_args());}
function radio() {return createWg('radio', func_get_args());}
function switcher() {return createWg('switcher', func_get_args());}
function checkbox() {return createWg('checkbox', func_get_args());}
function checkboxGroup() {return createWg('checkboxGroup', func_get_args());}
function formBase() {return createWg('formBase',  func_get_args());}
function form() {return createWg('form',  func_get_args());}
function formPanel() {return createWg('formPanel', func_get_args());}
function formGridPanel() {return createWg('formGridPanel', func_get_args());}
function formBatch() {return createWg('formBatch', func_get_args());}
function formBatchItem() {return createWg('formBatchItem', func_get_args());}
function formBatchPanel() {return createWg('formBatchPanel', func_get_args());}
function batchActions() {return createWg('batchActions', func_get_args());}
function content() {return createWg('content', func_get_args());}
function idLabel() {return createWg('idLabel', func_get_args());}
function listItem() {return createWg('listitem', func_get_args());}
function simpleList() {return createWg('simplelist', func_get_args());}
function entityList() {return createWg('entityList', func_get_args());}
function breadcrumb() {return createWg('breadcrumb', func_get_args());}
function datalist() {return createWg('datalist', func_get_args());}
function control() {return createWg('control', func_get_args());}
function select() {return createWg('select', func_get_args());}
function formLabel() {return createWg('formLabel', func_get_args());}
function formGroup() {return createWg('formGroup', func_get_args());}
function formRow() {return createWg('formRow', func_get_args());}
function formRowGroup() {return createWg('formRowGroup', func_get_args());}
function inputControl() {return createWg('inputControl', func_get_args());}
function inputGroup() {return createWg('inputGroup', func_get_args());}
function inputGroupAddon() {return createWg('inputGroupAddon', func_get_args());}
function checkList() {return createWg('checkList', func_get_args());}
function radioList() {return createWg('radioList', func_get_args());}
function checkBtn() {return createWg('checkBtn', func_get_args());}
function checkBtnGroup() {return createWg('checkBtnGroup', func_get_args());}
function checkColorGroup() {return createWg('checkColorGroup', func_get_args());}
function colorPicker() {return createWg('colorPicker', func_get_args());}
function datePicker() {return createWg('datePicker', func_get_args());}
function datetimePicker() {return createWg('datetimePicker', func_get_args());}
function timePicker() {return createWg('timePicker', func_get_args());}
function fileInput() {return createWg('fileInput', func_get_args());}
function pageForm() {return createWg('pageForm', func_get_args());}
function icon() {return createWg('icon', func_get_args());}
function btn() {return createWg('btn', func_get_args());}
function pageBase() {return createWg('pageBase', func_get_args());}
function page() {return createWg('page',    func_get_args());}
function fragment() {return createWg('fragment',    func_get_args());}
function btnGroup() {return createWg('btnGroup', func_get_args());}
function row() {return createWg('row', func_get_args());}
function col() {return createWg('col', func_get_args());}
function center() {return createWg('center', func_get_args());}
function cell() {return createWg('cell', func_get_args());}
function divider() {return createWg('divider', func_get_args());}
function actionItem() {return createWg('actionItem', func_get_args());}
function nav() {return createWg('nav', func_get_args());}
function label() {return createWg('label', func_get_args());}
function statusLabel() {return createWg('statusLabel', func_get_args());}
function branchLabel() {return createWg('branchLabel', func_get_args());}
function dtable() {return createWg('dtable', func_get_args());}
function menu() {return createWg('menu', func_get_args());}
function dropdown() {return createWg('dropdown', func_get_args());}
function header() {return createWg('header', func_get_args());}
function heading() {return createWg('heading', func_get_args());}
function navbar() {return createWg('navbar', func_get_args());}
function dropmenu() {return createWg('dropmenu', func_get_args());}
function dropPicker() {return createWg('droppicker', func_get_args());}
function main() {return createWg('main', func_get_args());}
function sidebar() {return createWg('sidebar', func_get_args());}
function featureBar() {return createWg('featureBar', func_get_args());}
function avatar() {return createWg('avatar', func_get_args());}
function userAvatar() {return createWg('userAvatar', func_get_args());}
function pager() {return createWg('pager', func_get_args());}
function modal() {return createWg('modal', func_get_args());}
function modalTrigger() {return createWg('modalTrigger', func_get_args());}
function modalHeader() {return createWg('modalHeader', func_get_args());}
function modalDialog() {return createWg('modalDialog', func_get_args());}
function tabs() {return createWg('tabs', func_get_args());}
function tabPane() {return createWg('tabPane', func_get_args());}
function panel() {return createWg('panel', func_get_args());}
function pasteDialog() {return createWg('pasteDialog', func_get_args());}
function tooltip() {return createWg('tooltip', func_get_args());}
function toolbar() {return createWg('toolbar', func_get_args());}
function searchToggle() {return createWg('searchToggle', func_get_args());}
function searchForm() {return createWg('searchForm', func_get_args());}
function programMenu() {return createWg('programMenu', func_get_args());}
function productMenu() {return createWg('productMenu', func_get_args());}
function moduleMenu() {return createWg('moduleMenu', func_get_args());}
function docMenu() {return createWg('docMenu', func_get_args());}
function docApp() {return createWg('docApp', func_get_args());}
function tree() {return createWg('tree', func_get_args());}
function treeEditor() {return createWg('treeEditor', func_get_args());}
function fileList() {return createWg('fileList', func_get_args());}
function history() {return createWg('history', func_get_args());}
function floatToolbar() {return createWg('floatToolbar', func_get_args());}
function formItemDropdown() {return createWg('formItemDropdown', func_get_args());}
function editor() {return createWg('editor', func_get_args());}
function pageEditor() {return createWg('pageEditor', func_get_args());}
function commentBtn() {return createWg('commentBtn', func_get_args());}
function commentDialog() {return createWg('commentDialog', func_get_args());}
function commentForm() {return createWg('commentForm', func_get_args());}
function priLabel() {return createWg('priLabel', func_get_args());}
function riskLabel() {return createWg('riskLabel', func_get_args());}
function severityLabel() {return createWg('severityLabel', func_get_args());}
function dashboard() {return createWg('dashboard', func_get_args());}
function blockPanel() {return createWg('blockPanel', func_get_args());}
function section() {return createWg('section', func_get_args());}
function sectionCard() {return createWg('sectionCard', func_get_args());}
function sectionList() {return createWg('sectionList', func_get_args());}
function entityTitle() {return createWg('entityTitle',func_get_args());}
function entityLabel() {return createWg('entityLabel',func_get_args());}
function tableData() {return createWg('tableData',func_get_args());}
function detail() {return createWg('detail', func_get_args());}
function detailCard() {return createWg('detailCard', func_get_args());}
function detailHeader() {return createWg('detailHeader', func_get_args());}
function detailSide() {return createWg('detailSide', func_get_args());}
function detailBody() {return createWg('detailBody', func_get_args());}
function detailForm() {return createWg('detailForm', func_get_args());}
function echarts() {return createWg('echarts', func_get_args());}
function graph() {return createWg('graph', func_get_args());}
function popovers() {return createWg('popovers', func_get_args());}
function backBtn() {return createWg('backBtn', func_get_args());}
function collapseBtn() {return createWg('collapseBtn', func_get_args());}
function mainNavbar() {return createWg('mainNavbar', func_get_args());}
function floatPreNextBtn() {return createWg('floatPreNextBtn', func_get_args());}
function fileSelector() {return createWg('fileSelector', func_get_args());}
function imageSelector() {return createWg('imageSelector', func_get_args());}
function upload() {return createWg('upload', func_get_args());}
function uploadImgs() {return createWg('uploadImgs', func_get_args());}
function burn() {return createWg('burn', func_get_args());}
function monaco() {return createWg('monaco', func_get_args());}
function dynamic() {return createWg('dynamic', func_get_args());}
function formSettingBtn() {return createWg('formSettingBtn', func_get_args());}
function overviewBlock() {return createWg('overviewBlock', func_get_args());}
function statisticBlock() {return createWg('statisticBlock', func_get_args());}
function picker() {return createWg('picker', func_get_args());}
function priPicker() {return createWg('priPicker', func_get_args());}
function severityPicker() {return createWg('severityPicker', func_get_args());}
function hr() {return createWg('hr', func_get_args());}
function globalSearch() {return createWg('globalSearch', func_get_args());}
function stepsEditor() {return createWg('stepsEditor', func_get_args());}
function tableChart() {return createWg('tableChart', func_get_args());}
function password() {return createWg('password', func_get_args());}
function mindmap() {return createWg('mindmap', func_get_args());}
function treemap() {return createWg('treemap', func_get_args());}
function imgCutter() {return createWg('imgCutter', func_get_args());}
function modalNextStep() {return createWg('modalNextStep', func_get_args());}
function navigator() {return createWg('navigator', func_get_args());}
function gantt() {return createWg('gantt', func_get_args());}
function roadMap() {return createWg('roadmap', func_get_args());}
function progressBar() {return createWg('progressBar', func_get_args());}
function progressCircle() {return createWg('progressCircle', func_get_args());}
function filter() {return createWg('filter', func_get_args());}
function resultFilter() {return createWg('resultFilter', func_get_args());}
function contactList() {return createWg('contactList', func_get_args());}
function userPicker() {return createWg('userPicker', func_get_args());}
function mailto() {return createWg('mailto', func_get_args());}
function whitelist() {return createWg('whitelist', func_get_args());}
function modulePicker() {return createWg('modulePicker', func_get_args());}
function visionSwitcher() {return createWg('visionSwitcher', func_get_args());}
function chatBtn() {return createWg('chatBtn', func_get_args());}
function storyList() {return createWg('storyList', func_get_args());}
function linkedStoryList() {return createWg('linkedStoryList', func_get_args());}
function twinsStoryList() {return createWg('twinsStoryList', func_get_args());}
function executionTaskList() {return createWg('executionTaskList', func_get_args());}
function relatedList() {return createWg('relatedList', func_get_args());}
function storyRelatedList() {return createWg('storyRelatedList', func_get_args());}
function storyBasicInfo() {return createWg('storyBasicInfo', func_get_args());}
function storyLifeInfo() {return createWg('storyLifeInfo', func_get_args());}
function taskTeam() {return createWg('taskTeam', func_get_args());}
function taskBasicInfo() {return createWg('taskBasicInfo', func_get_args());}
function taskEffortInfo() {return createWg('taskEffortInfo', func_get_args());}
function taskLifeInfo() {return createWg('taskLifeInfo', func_get_args());}
function taskMiscInfo() {return createWg('taskMiscInfo', func_get_args());}
function caseBasicInfo() {return createWg('caseBasicInfo', func_get_args());}
function caseTimeInfo() {return createWg('caseTimeInfo', func_get_args());}
function caseRelatedList() {return createWg('caseRelatedList', func_get_args());}
function demandBasicInfo() {return createWg('demandBasicInfo', func_get_args());}
function demandLifeInfo() {return createWg('demandLifeInfo', func_get_args());}
function flowSubTable() {return createWg('flowSubTable', func_get_args());}
function contactUs() {return createWg('contactUs', func_get_args());}
function thumbnail() {return createWg('thumbnail', func_get_args());}
function thinkNode() {return createWg('thinkNode', func_get_args());}
function thinkStep() {return createWg('thinkStep', func_get_args());}
function thinkQuestion() {return createWg('thinkQuestion', func_get_args());}
function thinkStepBase() {return createWg('thinkStepBase', func_get_args());}
function thinkTransition() {return createWg('thinkTransition', func_get_args());}
function thinkBaseCheckbox() {return createWg('thinkBaseCheckbox', func_get_args());}
function thinkRadio(){return createWg('thinkRadio', func_get_args());}
function thinkCheckbox() {return createWg('thinkCheckbox', func_get_args());}
function thinkOptions(){return createWg('thinkOptions', func_get_args());}
function thinkTableInput() {return createWg('thinkTableInput', func_get_args());}
function thinkInput() {return createWg('thinkInput', func_get_args());}
function thinkMatrixOptions() {return createWg('thinkMatrixOptions', func_get_args());}
function thinkMulticolumn() {return createWg('thinkMulticolumn', func_get_args());}
function thinkScore() {return createWg('thinkScore', func_get_args());}
function thinkStepMenu() {return createWg('thinkStepMenu', func_get_args());}
function thinkResult() {return createWg('thinkResult', func_get_args());}
function thinkSwot() {return createWg('thinkSwot', func_get_args());}
function thinkModel() {return createWg('thinkModel', func_get_args());}
function thinkCover() {return createWg('thinkCover', func_get_args());}
function affected() {return createWg('affected', func_get_args());}
function thinkPffa() {return createWg('thinkPffa', func_get_args());}
function thinkPestel() {return createWg('thinkPestel', func_get_args());}
function think4p() {return createWg('think4p', func_get_args());}
function think3c() {return createWg('think3c', func_get_args());}
function thinkAnsoff() {return createWg('thinkAnsoff', func_get_args());}
function thinkAppeals() {return createWg('thinkAppeals', func_get_args());}
function thinkBcg() {return createWg('thinkBcg', func_get_args());}
function thinkVennLink() {return createWg('thinkVennLink', func_get_args());}
function thinkStepQuote() {return createWg('thinkStepQuote', func_get_args());}
function sqlBuilderControl() {return createWg('sqlBuilderControl', func_get_args());}
function sqlBuilderPicker() {return createWg('sqlBuilderPicker', func_get_args());}
function sqlBuilderInput() {return createWg('sqlBuilderInput', func_get_args());}
function joinCondition() {return createWg('joinCondition', func_get_args());}
function fieldSelectPanel() {return createWg('fieldSelectPanel', func_get_args());}
function sqlBuilderEmptyContent() {return createWg('sqlBuilderEmptyContent', func_get_args());}
function sqlBuilderFuncRow() {return createWg('sqlBuilderFuncRow', func_get_args());}
function sqlBuilderWhereGroup() {return createWg('sqlBuilderWhereGroup', func_get_args());}
function sqlBuilderWhereItem() {return createWg('sqlBuilderWhereItem', func_get_args());}
function sqlBuilderGroupBy() {return createWg('sqlBuilderGroupBy', func_get_args());}
function sqlBuilderQueryFilter() {return createWg('sqlBuilderQueryFilter', func_get_args());}
function sqlBuilderHelpIcon() {return createWg('sqlBuilderHelpIcon', func_get_args());}
function sqlBuilder() {return createWg('sqlBuilder', func_get_args());}
function queryBase() {return createWg('queryBase', func_get_args());}
function queryFilterModal() {return createWg('queryFilterModal', func_get_args());}
function pivotTable() {return createWg('pivotTable', func_get_args());}
function pivotConfig() {return createWg('pivotConfig', func_get_args());}
function iconPicker() {return createWg('iconPicker', func_get_args());}
function relatedObjectList() {return createWg('relatedObjectList', func_get_args());}
function taskAssignedTo() {return createWg('taskAssignedTo', func_get_args());}
function docList() {return createWg('docList', func_get_args());}
function deliverable() {return createWg('deliverable', func_get_args());}

if(is_dir(__DIR__ . DS . 'wg' . DS . 'schedule'))
{
    function schedule() {return createWg('schedule', func_get_args());}
}

if(is_dir(__DIR__ . DS . 'wg' . DS . 'boardeditor'))
{
    function boardEditor() {return createWg('boardeditor', func_get_args());}
}
