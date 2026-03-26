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
	   if($type == 'task')
		{
			$this->app->loadLang('task');
			$statusList = $lang->approval->statusList;
		}
```