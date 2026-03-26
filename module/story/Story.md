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