<?php
$lang->convert->jira->zentaoObjectList['feedback']   = '反馈';
$lang->convert->jira->zentaoObjectList['ticket']     = '工单';
$lang->convert->jira->zentaoObjectList['add_custom'] = '新增成工作流';

$lang->convert->jira->zentaoLinkTypeList['add_relation'] = '新增关系';

$lang->convert->confluence = new stdclass();
$lang->convert->confluence->import       = '导入Confluence';
$lang->convert->confluence->notice       = 'Confluence导入提示';
$lang->convert->confluence->domain       = 'Confluence域名';
$lang->convert->confluence->admin        = 'Confluence管理员帐号';
$lang->convert->confluence->token        = 'Confluence密码/Token';
$lang->convert->confluence->apiError     = '无法连接到ConfluenceAPI接口，请检查您的Confluence域名和帐号、密码/Token信息。';
$lang->convert->confluence->importDesc   = '使用REST API接口导入数据，请保证与Confluencee服务器的网络连接畅通。';
$lang->convert->confluence->importSpace  = '产品空间或项目空间必须选择一个产品或项目。';
$lang->convert->confluence->mapSpaceDesc = '"只对我可见"的空间默认映射到禅道"我的空间"下，并自动创建同空间名称的库，其他空间默认不映射。';
$lang->convert->confluence->mapToZentao  = '设置Jira空间与禅道数据对应关系';
$lang->convert->confluence->importUser   = '导入Confluence用户';
$lang->convert->confluence->importData   = '导入Confluence数据';
$lang->convert->confluence->successfully = 'Confluence导入完成！';
$lang->convert->confluence->defaultSpace = 'Confluence空间';
$lang->convert->confluence->space        = 'Confluence空间';
$lang->convert->confluence->zentaoSpace  = '禅道空间';
$lang->convert->confluence->spaceKey     = '空间标识';
$lang->convert->confluence->archived     = '已归档';
$lang->convert->confluence->undefined    = '未命名';
$lang->convert->confluence->spaceMap     = 'Confluence空间映射';
$lang->convert->confluence->userNotice   = '系统将根据邮箱地址自动合并重复用户，保证每个邮箱仅对应一个账号。';

$lang->convert->confluence->importSteps[1] = '备份禅道数据库，备份Confluence数据库。';
$lang->convert->confluence->importSteps[2] = '导入数据时使用禅道会给服务器造成性能压力，请尽量保证导入数据时无人使用禅道。';
$lang->convert->confluence->importSteps[3] = "将Confluence附件目录<strong class='text-danger'> attachments</strong> 放到 <strong class='text-danger'>%s</strong> 下，确保禅道服务器磁盘空间足够。";
$lang->convert->confluence->importSteps[4] = "填写当前Confluence环境的域名、管理员帐号、密码/Token。";
$lang->convert->confluence->importSteps[5] = "上述步骤完成后，点击下一步。";

$lang->convert->confluence->objectList['user']       = '用户';
$lang->convert->confluence->objectList['space']      = '空间';
$lang->convert->confluence->objectList['folder']     = '文件夹';
$lang->convert->confluence->objectList['page']       = '文档';
$lang->convert->confluence->objectList['embed']      = '智能链接';
$lang->convert->confluence->objectList['whiteboard'] = '白板';
$lang->convert->confluence->objectList['database']   = '数据库';
$lang->convert->confluence->objectList['blogpost']   = '博文';
$lang->convert->confluence->objectList['draft']      = '草稿文档';
$lang->convert->confluence->objectList['archived']   = '已归档文档';
$lang->convert->confluence->objectList['version']    = '文档历史版本';
$lang->convert->confluence->objectList['comment']    = '文档评论';
$lang->convert->confluence->objectList['attachment'] = '附件';

$lang->convert->confluence->zentaoSpaceList = array();
$lang->convert->confluence->zentaoSpaceList['product'] = '产品空间';
$lang->convert->confluence->zentaoSpaceList['project'] = '项目空间';
$lang->convert->confluence->zentaoSpaceList['custom']  = '团队空间';
$lang->convert->confluence->zentaoSpaceList['mine']    = '我的空间';
