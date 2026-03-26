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