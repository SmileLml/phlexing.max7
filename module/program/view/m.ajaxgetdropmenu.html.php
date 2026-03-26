<?php $programGroup = $this->dao->select('*')->from(TABLE_PROJECT)->where('deleted')->eq('0')->andWhere('type')->eq('program')->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->programs)->fi()->orderBy('grade desc, `order`')->fetchGroup('parent', 'id');?>
<div class='heading divider'>
  <span class='title'>
    <input type='text' class='input' id='search' value='' placeholder='<?php echo $this->app->loadLang('search')->search->common;?>'/>
  </span>
  <nav class='nav'>
    <a data-dismiss='display'><i class='icon-remove muted'></i></a>
  </nav>
</div>
<div id='searchResult' class='content'>
  <div id='defaultMenu' class='search-list'>
    <div id='activedItems'>
      <?php echo $this->program->printWebTree(0, $programGroup, $module, $method);?>
    </div>
  </div>
</div>
