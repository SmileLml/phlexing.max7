<?php
/**
 * The edit view file of dashboard of ZenTaoPMS.
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
<?php js::import($jsRoot . "moment/moment.min.js");?>
<?php js::import($jsRoot . 'vue/vue.js');?>
<?php js::import($jsRoot . 'vue/vue-grid-layout.umd.min.js');?>
<?php js::import($jsRoot . 'vue/antd.min.js');?>
<?php js::import($jsRoot . 'echarts/echarts.common.min.js');?>
<?php js::import($jsRoot . 'vue/vue-echarts.js');?>
<?php js::set('lang', $lang->dashboard);?>
<?php js::set('charts', $charts);?>
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
    <button type="submit" id="submit" @click="save" data-placement="bottom" class="btn btn-primary"><?php echo $lang->save;?></button>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col">
    <div id="dashboard-container" class="cell">
      <div v-if="filters.length > 0" class="filter-container">
        <div v-for="(filter, index) in filters" class="filter">
          <div v-if="filter.type == 'select'" class="input-group">
            <span v-if="filter.name != ''" class="input-group-addon text-ellipsis" :title="filter.name">{{filter.name}}</span>
            <a-select :mode="filter.multiple == 1 ? 'multiple' : ''" v-model:value="filterValues[filter.field]" :style="{width: fieldsMap[filter.field].type == 'option' ? (filter.multiple ? '320px' : '150px') : '300px'}" :options="fieldsMap[filter.field].type == 'option' ? fieldsMap[filter.field].options : sysOptions[fieldsMap[filter.field].type]" placeholder="" class="form-control" @change="changeFilter"/>
          </div>
          <div v-else-if="filter.type == 'tree'" class="input-group">
            <span v-if="filter.name != ''" class="input-group-addon text-ellipsis" :title="filter.name">{{filter.name}}</span>
            <a-tree-select :mode="filter.multiple == 1 ? 'multiple' : ''" v-model:value="filterValues[filter.field]" :style="{width: fieldsMap[filter.field].type == 'option' ? (filter.multiple ? '320px' : '150px') : '300px'}" :tree-data="fieldsMap[filter.field].type == 'option' ? fieldMap[filter.field].options : sysOptions[fieldsMap[filter.field].type]" tree-checkable tree-default-expand-all max-tag-count="1" show-checked-strategy='1' placeholder="" class="form-control" @change="changeFilter(filter.field)"/>
          </div>
          <div v-else-if="filter.type == 'date'" class="input-group">
            <span v-if="filter.name != ''" class="input-group-addon text-ellipsis" :title="filter.name">{{filter.name}}</span>
            <a-date-picker v-model:value="moment()" :style="{width: '200px'}" placeholder="" class="form-control" @change="changeFilter"/>
          </div>
          <div v-else-if="filter.type == 'dateRange'" class="input-group">
            <span v-if="filter.name != ''" class="input-group-addon text-ellipsis" :title="filter.name">{{filter.name}}</span>
            <a-range-picker :placeholder="[lang.begin, lang.end]" :style="{width: '200px'}" class="form-control" @change="changeFilter"/>
          </div>
          <div class="remove" @click="removeFilter(filter.field)"><i class="icon-common-delete icon-trash"></i></div>
        </div>
      </div>
      <grid-layout ref="gridlayout" :layout.sync="layout" :col-num="12" :row-height="30" :is-draggable="draggable" :is-resizable="resizable" :vertical-compact="true" :use-css-transforms="true">
        <grid-item v-for="(item, index) in layout" :static="item.static" :x="item.x" :y="item.y" :w="item.w" :h="item.h" :i="item.i" @resized="resizedEvent">
          <span class="text">{{itemTitle(item)}}</span>
          <div v-if="item.i.type == 'table'" class="chartbox" :id="'chart' + item.i.id">
            <a-table ref="table" :columns="item.i.data.columns" :data-source="item.i.data.source" :pagination="false" bordered></a-table>
          </div>
          <div v-else-if="item.i.type.indexOf('Report') !== -1" class="reportbox" :id="'chart' + item.i.id"></div>
          <div v-else class="chartbox" :id="'chart' + item.i.id">
            <v-chart autoresize class="my-chart" :option="item.i.data"/>
          </div>
          <div class="action">
            <i class="icon-common-delete icon-trash" @click="removeChart(item.i.id)"></i>
          </div>
        </grid-item>
      </grid-layout>
    </div>
  </div>
  <div class="side-col" id="sidebar">
    <a-tabs default-active-key="chart">
      <a-tab-pane key="filter" :tab="lang.filter">
        <div class="cell" :style="{'max-height': maxHeight + 'px'}">
          <div v-for="(filter, key) in lang.filterList" v-if="filter.show" class="filterbox" @click="setFilter(key)">
            <div class="icon"><i class="icon-plus"></i></div>
            <div class="name">{{filter.name}}</div>
            <div class="icon"><i :class="'icon-' + filter.icon"></i></div>
          </div>
        </div>
      </a-tab-pane>
      <a-tab-pane key="chart" :tab="lang.chart">
        <div class="cell" :style="{'max-height': maxHeight + 'px'}">
          <?php foreach($charts as $chart):?>
          <div class="chart" :class="layout.findIndex(item => item.i.id == <?php echo $chart->id?>) === -1 ? '' : 'disable'" @drag="drag" @dragend="dragend" :draggable="layout.findIndex(item => item.i.id == <?php echo $chart->id?>) === -1 ? true : false" unselectable="on" id="chart-<?php echo $chart->id;?>">
            <div class="title"><?php echo $chart->name;?></div>
            <div class="desc">
              <p><?php echo $lang->chart->typeList[$chart->type];?></p>
              <?php if($chart->dataset):?>
              <p><?php echo $lang->chart->dataset . ': ' . (strpos($chart->dataset, 'custom_') === 0 ? $datasets[substr($chart->dataset, 7)] : $lang->dataset->tables[$chart->dataset]['name']);?></p>
              <?php else:?>
              <p></p>
              <?php endif;?>
            </div>
          </div>
          <?php endforeach;?>
        </div>
      </a-tab-pane>
    </a-tabs>
  </div>
  <a-modal :title="lang.filterList[filter.type].name" width="700px" :visible="showModal" @ok="saveFilter" @cancel="resetFilter" ok-text="<?php echo $lang->save;?>" cancel-text="<?php echo $lang->cancel;?>">
    <div class="filter-row item">
      <div class="title">{{lang.display}}</div>
      <div class="content"><a-input v-model:value="filter.name" placeholder="" class="form-control"/></div>
    </div>
    <div v-if="filter.type == 'select' || filter.type == 'tree'" class="filter-row item">
      <div class="title">{{lang.multiple}}</div>
      <div class="content"><a-radio-group v-model="filter.multiple" :options="multiples"></a-radio-group></div>
    </div>
    <div v-if="filter.type == 'select' || filter.type == 'tree'" class="filter-row item">
      <div class="title">{{lang.selectField}}</div>
      <div class="content"><a-select v-model:value="filter.field" :options="optionFields" placeholder="" class="form-control"/></div>
    </div>
    <div v-if="filter.type == 'date' || filter.type == 'dateRange'" class="filter-row item">
      <div class="title">{{lang.selectField}}</div>
      <div class="content"><a-select v-model:value="filter.field" :options="dateFields" placeholder="" class="form-control"/></div>
    </div>
  </a-modal>
</div>
</template>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
