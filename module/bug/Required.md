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