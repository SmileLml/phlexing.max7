# transfer中修改model.php文件

## 修复富文本字段导出格式过滤

### 修改内容如下：
####    
```
       if($this->config->edition != 'open')
        {
            list($fields, $rows) = $this->loadModel('workflowfield')->appendDataFromFlow($fields, $rows, $module);
            foreach($fields as $key => $name)
            {
                $field = $this->workflowfield->getByField($module, $key);

                if($field->control == 'richtext')
                {
                    if(!isset($this->config->excel->editor[$module])) $this->config->excel->editor[$module] = array();
                    $this->config->excel->editor[$module][] = $key;
                }
            }
        }
```