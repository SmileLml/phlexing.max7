# zentao

# 在extension-->max-->feedback模块中增加创建和编辑指派人为必填字段

## 字段：assignedTo

### 修改内容如下：

####    1、在feedback模块ui目录中的create.html.php文件，创建反馈时增加指派人字段为必填,代码如下:
```
        formRow
        (
            formGroup
            (
                set::label($lang->feedback->assignedTo),
                set::width('1/2'),
                set::required(true),
                picker
                (
                    set::name('assignedTo'),
                    set::items($users),
                    set::value(isset($feedback) ? $feedback->assignedTo : ''),
    
                )
            )
        ),
```
####    2、在feedback模块下ui目录中的edit.html.php文件，编辑反馈时增加指派人字段为必填，代码如下：
```
        formRow
        (
            formGroup
            (
                setID('assignedTo'),
                set::label($lang->feedback->assignedTo),
                set::width('1/2'),
                picker
                (
                    set::name('assignedTo'),
                    set::items($users),
                    set::value($feedback->assignedTo),
        
                ),
                set::required(true),
            )
        ),
```
####    3、以上代码增加后，需要在zt_workflowfield表中将assignedTo规则改为1必填，否则不生效，如图：
![assignedTo.png](extension/max/feedback/ui/assignedTo.png)

####    4、最后需要在zt_workflowlayout表中新增两条assignedTo的创建和编辑数据，并将order进行排序，如图：
![assignedTo-layout.png](extension/max/feedback/ui/assignedTo-layout.png)

####    5、数据库修改规则后，按照4步骤增加数据后，1、2代码增加才能生效

# 修改历史记录Bug

## 所属计划

### 修改内容如下：
    1、修改action模块，action目录中对应的config.php文件，修改增加以下内容：
``` 
    $config->action->userFields         = 'openedBy,addedBy,createdBy,editedBy,assignedTo,finishedBy,canceledBy,closedBy,activatedBy,resolvedBy,lastEditedBy,builder,owner,reviewedBy,forwardBy,scriptedBy,manager,commitedBy,archivedBy,PO,QD,RD,feedback,PM,account,changedBy,submitedBy,retractedBy,lastRunner,assignedBy,processedBy,fankuiquerenBy,pmclBy';
    $config->action->multipleUserFields = 'mailto,whitelist,reviewer,users,assignee,approver,PMT,committer,backReviewers,contributor,reviewers,Validator'; 
    $config->action->objectPlan         = 'plan,qiwangjiaofubanben,jihuajiaofubanben';
    $config->action->objectBuild     = 'JieJueBanBenXin,Realizebuild,openedBuild,JiHuaHeRuBanBen,resolveBuild,sjfbbb,resolvedBuild';
    $config->action->objectStory        = 'story';
    $config->action->objectModule       = 'module,ModuleAdd';
    $config->action->objectBug        = 'duplicateBug';
    $config->action->objectJson    = 'Deliver,CustomerProject,CustomerPerception,subStatus';
    $config->action->objectClientPriority = 'ClientPriority';
```

    2、修改action模块，action模块中对应model.php文件，修改内容如下：
```   
    elseif(strpos(",{$this->config->action->objectPlan},", ",{$history->field},") !== false)
        {
            if(!empty($history->old))
            {
                $history->oldValue = $this->dao->select('title')->from(TABLE_PRODUCTPLAN)->where('id')->eq($history->old)->fetch('title');
                $history->oldValue = trim($history->oldValue, ',');
            }

            if(!empty($history->new))
            {
                $history->newValue = $this->dao->select('title')->from(TABLE_PRODUCTPLAN)->where('id')->eq($history->new)->fetch('title');
                $history->newValue = trim($history->newValue, ',');
            }
        }
        elseif(strpos(",{$this->config->action->objectStory},", ",{$history->field},") !== false)
        {
            if(!empty($history->old))
            {
                $history->oldValue = $this->dao->select('title')->from(TABLE_STORY)->where('id')->eq($history->old)->fetch('title');
                $history->oldValue = trim($history->oldValue, ',');
            }

            if(!empty($history->new))
            {
                $history->newValue = $this->dao->select('title')->from(TABLE_STORY)->where('id')->eq($history->new)->fetch('title');
                $history->newValue = trim($history->newValue, ',');
            }
        }
        elseif(strpos(",{$this->config->action->objectModule},", ",{$history->field},") !== false)
        {
            if(!empty($history->old))
            {
                $history->oldValue = $this->dao->select('name')->from(TABLE_MODULE)->where('id')->eq($history->old)->fetch('name');
                $history->oldValue = trim($history->oldValue, ',');
            }

            if(!empty($history->new))
            {
                $history->newValue = $this->dao->select('name')->from(TABLE_MODULE)->where('id')->eq($history->new)->fetch('name');
                $history->newValue = trim($history->newValue, ',');
            }
        }
        elseif(strpos(",{$this->config->action->objectBug},", ",{$history->field},") !== false)
        {
            if(!empty($history->old))
            {
                $history->oldValue = $this->dao->select('title')->from(TABLE_BUG)->where('id')->eq($history->old)->fetch('title');
                $history->oldValue = trim($history->oldValue, ',');
            }

            if(!empty($history->new))
            {
                $history->newValue = $this->dao->select('title')->from(TABLE_BUG)->where('id')->eq($history->new)->fetch('title');
                $history->newValue = trim($history->newValue, ',');
            }
        }
        elseif(strpos(",{$this->config->action->objectBuild},", ",{$history->field},") !== false)
        {
            if(!empty($history->old))
            {
                $history->oldValue = '';
                $oldValues = explode(',', $history->old);
                $result = $this->dao->select('name')->from(TABLE_BUILD)->where('id')->in($oldValues)->fetchAll();
                $names = array_column($result, 'name');
                $history->oldValue = implode(',', $names);
            }

            if(!empty($history->new))
            {
                $history->newValue = '';
                $newValues = explode(',', $history->new);
                $result = $this->dao->select('name')->from(TABLE_BUILD)->where('id')->in($newValues)->fetchAll();
                $names = array_column($result, 'name');
                $history->newValue = implode(',', $names);
            }
        }
        elseif(strpos(",{$this->config->action->objectJson},", ",{$history->field},") !== false)
        {
            $objects = $this->dao->select('datasource')->from(TABLE_WORKFLOWDATASOURCE)->where('code')->eq($history->field)->fetch('datasource');
            $keysToFind = ['jh', 'yjj', 'ygb', 'notbug', 'pending', 'yyz'];
            if(!empty($objects))
            {
                $object = json_decode($objects, true);
            } else {
                $objects = $this->dao->select('options')->from(TABLE_WORKFLOWFIELD)->where('field')->eq($history->field)->andWhere('module')->eq('bug')->fetch('options');
                $object = json_decode($objects, true);

            }
            if(!empty($history->old))
            {
                if(in_array($history->old, $keysToFind))
                {
                    $history->oldValue = $this->getOptionValue($object, $history->old);
                }else {
                    $history->oldValue = $object[$history->old];
                    $history->oldValue = trim($history->oldValue, ',');
                }

            }

            if(!empty($history->new))
            {
                if(in_array($history->new, $keysToFind))
                {
                    $history->newValue = $this->getOptionValue($object, $history->new);
                }else {
                    $history->newValue = $object[$history->new];
                    $history->newValue = trim($history->newValue, ',');
                }
            }
        }
        elseif(strpos(",{$this->config->action->objectClientPriority},", ",{$history->field},") !== false)
        {
            $map = [
                '1' => '高',
                '2' => '中',
                '3' => '低'
            ];
            if(!empty($history->old))
            {
                $history->oldValue = $map[$history->old];
            }

            if(!empty($history->new))
            {
                $history->newValue = $map[$history->new];
            }
        }
```
### 将修改的原文件与修改后的文件都保存在git上，保证代码代码能够及时恢复


# # 修改block模块中lang文件中的zh-cn.php文件中的文本信息

### 修改内容如下：
```
    $lang->block->welcome->reviewByMe = '待我处理的评审单';
```


# 增加Bug模块中当前指派人为必填字段

## 字段：assignedTo

### 修改内容如下：
    1、首先修改bug模块下config目录中的form.php文件，使当前指派人字段为必填,代码如下:
```
    $config->bug->form->create['assignedTo']  = array('required' => true, 'type' => 'string', 'default' => '');
```
    2、再次修改bug模块下ui目录中的create.field.php文件，使前端显示该字段为红色*，代码如下：
```
    $fields->field('assignedTo')
    ->label($lang->bug->lblAssignedTo)
    ->className('w-1/2 full:w-1/2')
    ->hidden($noMultipleProject)
    ->required(true)
    ->foldable();
```

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
        }

    if($flows[$objectType]->table == 'zt_flow_docreview' && $flows[$objectType]->module == 'docreview')
        {
            $objectGroup[$objectType] = $this->dao->select('*')->from($table)
                ->where('deleted')->eq('0')
                ->andWhere("(createdBy='{$this->app->user->account}' and reviewStatus in ('reject','reverting','wait')) or (reviewers like '%{$this->app->user->account}%' and reviewStatus='doing')) ",1)
                ->fetchAll('id');
        } else {
            $objectGroup[$objectType] = $this->dao->select('*')->from($table)->where('id')->in($idList)->andWhere('deleted')->eq('0')->fetchAll('id');
        }
```
####    2、再次修改tao.php文件,修改文档title、status取值逻辑
```
    if($flows[$objectType]->table == 'zt_flow_docreview' && $flows[$objectType]->module == 'docreview'){
        $data->title   = empty($object->$titleFieldName) || !isset($object->PingShenBiaoTi) ? $object->PingShenBiaoTi . " #{$object->id}" : $object->{$titleFieldName} . " #{$object->id}";
        $data->status  = $objectType == 'docreview' ? $object->reviewStatus : 'doing';
    } else {
        $data->title   = empty($titleFieldName) || !isset($object->$titleFieldName) ? $title . " #{$object->id}" : $object->{$titleFieldName};
        $data->status  = $objectType == 'charter' ? $object->reviewStatus : 'doing';
    }
```

# 增加task模块中当前指派人为必填字段

## 字段：assignedTo

### 修改内容如下：
    1、首先修改task模块下config目录中的form.php文件，使当前指派人字段为必填，修改单个任务创建和批量任务创建和编辑,代码如下:
```
    $config->task->form->create['assignedTo']   = array('type' => 'string',   'required' => true, 'default' => '');
    $config->task->form->batchedit['assignedTo']     = array('type' => 'string',   'required' => true, 'default' => '');
    $config->task->form->batchcreate['assignedTo']    = array('type' => 'string',   'required' => true, 'default' => '');
    
```
    2、再次修改task模块下ui目录中的create.field.php文件，使前端显示该字段为红色*，代码如下：
```
    $fields->field('assignedToBox')
    ->label($lang->task->assignedTo)
    ->checkbox(array('text' => $lang->task->multiple, 'name' => 'multiple', 'checked' => !empty(data('task.mode'))))
    ->required(true)
    ->control($buildAssignedTo);
```
    3、批量创建任务时，修改task模块下ui目录下batchcreate.html.php文件中assignedTo指派人为必填字段，使前端显示该字段为红色*，代码如下：
```
    formBatchItem
    (
        set::name('assignedTo'),
        set::label($lang->task->assignedTo),
        set::control(array('control' => 'taskAssignedTo','manageLink' => ($manageLink ? $manageLink : ''))),
        set::items($members),
        set::width('128px'),
        set::ditto(true),
        set::required(true)
    ),
```
    4、修复之前批量创建时，字段都为空的情况下，能提交成功的问题，修改task模块中，lang目录zh-cn.php文件，
```
    $lang->task->requiredFields      = "必填字段";
```
    5、修改task模块中zen.php文件684行，代码如下：
```
    if(empty($tasks)) dao::$errors[$this->lang->task->requiredFields] = sprintf($this->lang->error->notempty, $this->lang->task->requiredFields);
```


# 增加productplan模块中修改计划列表中的开始日期和结束日期

## 字段：begin、end

### 修改内容如下：
####    $lang->productplan->begin       = '版本计划冻结';
####    $lang->productplan->end         = '内部发布评审'; 

# 在extension->max->approval->lang模块中增加状态枚举类型

## 字段：$lang->approval->nodeList

### 修改内容如下：
####    1、首先修改lang模块中，zh-cn.php文件，修改待处理文档取值逻辑
```
        $lang->approval->nodeList['wait']  = '待审批';
        $lang->approval->nodeList['reject']  = '不通过';
```

# 增加my模块中待我处理文档中状态显示

## 字段：status

### 修改内容如下：
####    1、首先修改my模块中，audit.html.php文件，修改待处理文档取值逻辑
```
       if($type == 'docreview')
        {
            $this->app->loadLang('docreview');
            $statusList = $lang->approval->statusList;
        }
```
# 增加story模块中当前指派人为必填字段

## 字段：assignedTo

### 修改内容如下：
    1、首先修改task模块下config目录中的form.php文件，使当前指派人字段为必填，修改单个任务创建和批量任务创建和编辑,代码如下:
```
   $config->story->form->create['assignedTo']  = array('type' => 'string',  'control' => 'select','required' => true, 'default' => '', 'options' => 'users');
    
```
    2、再次修改story模块下ui目录中的create.field.php文件，使前端显示该字段为红色*，代码如下：
```
    $fields->field('assignedTo')
    ->required()
    ->control('inputGroup')
    ->items(false)
    ->itemBegin('assignedTo')->control('picker')->id('assignedToBox')->items($createFields['assignedTo']['options'])->value($createFields['assignedTo']['default'])->itemEnd();

```
    3、修改story模块中，ui目录edit.html.php文件，
```
    item
        (
            set::name($lang->story->assignedTo),
            set::required(true),
            formGroup
            (
                inputGroup
                (
                    picker
                    (
                        setID('assignedTo'),
                        set::name('assignedTo'),
                        set::items($assignedToList),
                        set::value($fields['assignedTo']['default'])
                    )
                )
            )
        )
```
