<?php
/**
 * The view file of dashboard of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: browse.html.php 5096 2013-07-11 07:02:43Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('dashboard', $dashboard);?>
<?php js::import($jsRoot . 'kindeditor/kindeditor.min.js'); ?>
<?php js::import($jsRoot . "moment/moment.min.js");?>
<?php js::import($jsRoot . 'vue/vue.js');?>
<?php js::import($jsRoot . 'vue/vue-grid-layout.umd.min.js');?>
<?php js::import($jsRoot . 'vue/antd.min.js');?>
<?php js::import($jsRoot . 'echarts/echarts.common.min.js');?>
<?php js::import($jsRoot . 'vue/vue-echarts.js');?>
<?php js::set('lang', $lang->dashboard);?>
<?php js::set('sysOptions', $sysOptions);?>
<?php js::set('defaults', $defaults);?>
<?php js::set('filters', $filters);?>
<?php css::import($jsRoot . "vue/antd.min.css");?>
<template>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php echo html::a(inLink('browse'), "<i class='icon icon-back icon-sm'>$lang->goback</i>", '', 'class="btn"');?>
    <div class="divider"></div>
    <div class="page-title"><span class="text"><?php echo $dashboard->name;?></span></div>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('dashboard', 'design')):?>
    <button @click="design" data-placement="bottom" class="btn btn-secondary"><?php echo $lang->dashboard->design;?></button>
    <?php endif;?>
    <!--button type="submit" id="submit" @click="exportData" data-placement="bottom" class="btn btn-primary"><?php echo $lang->dashboard->export;?></button-->
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col">
    <div id="dashboard-container" class="cell">
      <div class="filter-container">
        <div class="filter-condition">
        <div v-for="(filter, index) in filters" class="filter">
          <div v-if="filter.type == 'select'" class="input-group">
            <span v-if="filter.name != ''" class="input-group-addon text-ellipsis" :title="filter.name">{{filter.name}}</span>
            <a-select :mode="filter.multiple == 1 ? 'multiple' : ''" v-model:value="filterValues[filter.field]" :showSearch="true" option-filter-prop="label" :style="{width: fieldMap[filter.field].type == 'option' ? (filter.multiple ? '280px' : '150px') : '250px'}" :options="fieldMap[filter.field].type == 'option' ? fieldMap[filter.field].options : sysOptions[fieldMap[filter.field].type]" max-tag-count="1" placeholder="" class="form-control" @change="changeFilter(filter.field)"/>
          </div>
          <div v-else-if="filter.type == 'tree'" class="input-group">
            <span v-if="filter.name != ''" class="input-group-addon text-ellipsis" :title="filter.name">{{filter.name}}</span>
            <a-tree-select :mode="filter.multiple == 1 ? 'multiple' : ''" v-model:value="filterValues[filter.field]" :style="{width: fieldMap[filter.field].type == 'option' ? (filter.multiple ? '280px' : '150px') : '250px'}" :tree-data="fieldMap[filter.field].type == 'option' ? fieldMap[filter.field].options : sysOptions[fieldMap[filter.field].type]" tree-checkable tree-default-expand-all max-tag-count="1" show-checked-strategy='1' placeholder="" class="form-control" @change="changeFilter(filter.field)"/>
          </div>
          <div v-else-if="filter.type == 'date'" class="input-group">
            <span v-if="filter.name != ''" class="input-group-addon text-ellipsis" :title="filter.name">{{filter.name}}</span>
            <a-date-picker v-model:value="filterValues[filter.field]" :style="{width: '200px'}" placeholder="" class="form-control" @change="changeFilter"/>
          </div>
          <div v-else-if="filter.type == 'dateRange'" class="input-group">
            <span v-if="filter.name != ''" class="input-group-addon text-ellipsis" :title="filter.name">{{filter.name}}</span>
            <a-range-picker v-model:value="filterValues[filter.field]" :placeholder="[lang.begin, lang.end]" :style="{width: '200px'}" class="form-control" @change="changeFilter"/>
          </div>
        </div>
        </div>
        <div class="filter-action">
          <button type="submit" id="submit" @click="reset" class="btn">{{lang.reset}}</button>
          <button type="submit" id="submit" @click="search" class="btn btn-primary">{{lang.query}}</button>
        </div>
      </div>
      <grid-layout ref="gridlayout" :layout.sync="layout" :auto-size="true" :col-num="12" :row-height="30" :is-draggable="draggable" :is-resizable="resizable" :vertical-compact="true" :use-css-transforms="true">
        <grid-item v-for="item in layout" :static="item.static" :x="item.x" :y="item.y" :w="item.w" :h="item.h" :i="item.i" :ref="`widget_${item.i.id}`">
          <span class="text">{{itemTitle(item)}}</span>
          <span v-if="item.i.type.indexOf('Report') !== -1" class="text edit">
            <a-dropdown v-model:visible="settingVisible" :trigger="['click']">
              <a class="ant-dropdown-link" @click.prevent>
                <i class="icon-dashboard-design icon-backend"> {{lang.settings}}</i>
              </a>
              <template #overlay>
                <a-menu>
                  <a-menu-item @click="editReport(item.i)" key="1">{{lang.editReport}}</a-menu-item>
                  <!--a-menu-item key="2">{{lang.saveTemplate}}</a-menu-item-->
                </a-menu>
              </template>
            </a-dropdown>
          </span>
          <div v-if="item.i.type == 'table'" class="tablebox" :id="'chart' + item.i.id">
            <div class="table-title"></div>
            <a-table ref="table" :columns="item.i.data.columns" :data-source="item.i.data.source" :pagination="false" bordered></a-table>
          </div>
          <div v-else-if="item.i.type.indexOf('Report') !== -1" class="reportbox" :id="'chart' + item.i.id"></div>
          <div v-else class="chartbox" :id="'chart' + item.i.id">
            <v-chart autoresize class="my-chart" :option="item.i.data"/>
          </div>
        </grid-item>
      </grid-layout>
    </div>
  </div>
</div>
</template>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
