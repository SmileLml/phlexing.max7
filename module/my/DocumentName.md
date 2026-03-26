# 增加my模块中待我处理文档的逻辑以及文档标题显示的规则

## 字段：PingShenBiaoTi

### 修改内容如下：
####    1、首先修改my模块中，model.php文件，修改待处理文档取值逻辑
```
    $stmt = $this->dao->select('t2.objectType,t2.objectID')->from(TABLE_APPROVALNODE)->alias('t1')
            ->leftJoin(TABLE_APPROVALOBJECT)->alias('t2')->on('t2.approval = t1.approval')
            ->where('t2.objectType')->ne('review')
            ->beginIF($objectType != 'all')->andWhere('t2.objectType')->eq($objectType)->fi()
            ->andWhere('t1.account')->eq($this->app->user->account)
            ->andWhere("t1.status in ('wait','doing') or t1.result='fail'",1)
            ->markRight(1)
            ->andWhere('t1.type')->eq('review')
            ->orderBy("t2.{$orderBy}")
            ->query();
    
    if(empty($objectIdList) && empty($flows))
	{
		$objectGroup['docreview'] = $this->dao->select('*')->from('zt_flow_docreview')
			->where('deleted')->eq('0')
			->andWhere("(createdBy='{$this->app->user->account}' and reviewStatus in ('reject','reverting','wait')) or (reviewers like '%{$this->app->user->account}%' and reviewStatus='doing')) ",1)
			->fetchAll('id');
		$objectGroup['task'] = $this->dao->select('*')->from('zt_task')
			->where('deleted')->eq('0')
			->andWhere("(openedBy='{$this->app->user->account}' and reviewStatus in ('reject','reverting','wait')) or (reviewers like '%{$this->app->user->account}%' and reviewStatus='doing')) ",1)
			->fetchAll('id');
	}

    if($flows[$objectType]->table == 'zt_flow_docreview' && $flows[$objectType]->module == 'docreview')
	{
		$objectGroup[$objectType] = $this->dao->select('*')->from($table)
			->where('deleted')->eq('0')
			->andWhere("(createdBy='{$this->app->user->account}' and reviewStatus in ('reject','reverting','wait')) or (reviewers like '%{$this->app->user->account}%' and reviewStatus='doing')) ",1)
			->fetchAll('id');
	}
	elseif($flows[$objectType]->table == 'zt_task' && $flows[$objectType]->module == 'task')
	{
		$objectGroup[$objectType] = $this->dao->select('*')->from($table)
			->where('deleted')->eq('0')
			->andWhere("(openedBy='{$this->app->user->account}' and reviewStatus in ('reject','reverting','wait')) or (reviewers like '%{$this->app->user->account}%' and reviewStatus='doing')) ",1)
			->fetchAll('id');
	}
	else {
		$objectGroup[$objectType] = $this->dao->select('*')->from($table)->where('id')->in($idList)->andWhere('deleted')->eq('0')->fetchAll('id');
	}
```
####    2、再次修改tao.php文件,修改文档title、status取值逻辑
```
    if($flows[$objectType]->table == 'zt_flow_docreview' && $flows[$objectType]->module == 'docreview')
	{
		$data->title   = empty($object->$titleFieldName) || !isset($object->PingShenBiaoTi) ? $object->PingShenBiaoTi . " #{$object->id}" : $object->{$titleFieldName} . " #{$object->id}";
		$data->status  = $objectType == 'docreview' ? $object->reviewStatus : 'doing';
	}
	elseif($flows[$objectType]->table == 'zt_task' && $flows[$objectType]->module == 'task'){
		$data->title   = empty($titleFieldName) || !isset($object->$titleFieldName) ? $title . " #{$object->id}" : $object->{$titleFieldName};
		$data->status  = $objectType == 'task' ? $object->reviewStatus : 'doing';
	}
	else {
		$data->title   = empty($titleFieldName) || !isset($object->$titleFieldName) ? $title . " #{$object->id}" : $object->{$titleFieldName};
		$data->status  = $objectType == 'charter' ? $object->reviewStatus : 'doing';
	}
```