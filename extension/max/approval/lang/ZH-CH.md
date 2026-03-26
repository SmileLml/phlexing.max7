# 在extension->max->approval->lang模块中增加状态枚举类型

## 字段：$lang->approval->nodeList

### 修改内容如下：
####    1、首先修改lang模块中，zh-cn.php文件，修改待处理文档取值逻辑
```
        $lang->approval->nodeList['wait']  = '待审批';
        $lang->approval->nodeList['reject']  = '不通过';
```