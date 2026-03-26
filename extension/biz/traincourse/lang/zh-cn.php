<?php
/**
 * The traincourse module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@cnezsoft.com>
 * @package     company
 * @version     $Id: zh-cn.php 4129 2022-02-09 08:18:14Z wwccss $
 * @link        http://www.zentao.net
 */

/* Actions. */
$lang->traincourse->createCourse       = '创建课程';
$lang->traincourse->createChapter      = '添加章节';
$lang->traincourse->createCategory     = '新建分类';
$lang->traincourse->viewCourse         = '查看课程';
$lang->traincourse->viewChapter        = '查看章节';
$lang->traincourse->browse             = '课程';
$lang->traincourse->upCourse           = '上线课程';
$lang->traincourse->offCourse          = '下线课程';
$lang->traincourse->manageCourse       = '维护课程';
$lang->traincourse->manageChapter      = '维护章节';
$lang->traincourse->manageCategory     = '维护分类';
$lang->traincourse->editCourse         = '编辑课程';
$lang->traincourse->editChapter        = '编辑章节';
$lang->traincourse->editCategory       = '编辑分类';
$lang->traincourse->browseCategory     = '浏览分类';
$lang->traincourse->categoryChildren   = '添加子分类';
$lang->traincourse->changeStatus       = '课程上线/下线';
$lang->traincourse->deleteCourse       = '删除课程';
$lang->traincourse->deleteChapter      = '删除章节';
$lang->traincourse->deleteCategory     = '删除分类';
$lang->traincourse->uploadCourse       = '上传课程';
$lang->traincourse->upload             = '上传';
$lang->traincourse->batchImport        = '从服务器导入';
$lang->traincourse->cloudImport        = '从云端导入';
$lang->traincourse->practice           = '实践库';
$lang->traincourse->practiceBrowse     = '实践列表';
$lang->traincourse->practiceView       = '实践详情';
$lang->traincourse->updatePractice     = '更新数据';
$lang->traincourse->ajaxUpdatePractice = '更新数据';

$lang->traincourse->adminAction    = '学堂后台';
$lang->traincourse->practiceAction = '实践库首页';
$lang->traincourse->browseAction   = '课程列表';

/* Fields. */
$lang->traincourse->course   = '课程';
$lang->traincourse->admin    = '后台';
$lang->traincourse->category = '分类';
$lang->traincourse->byQuery  = '搜索';

$lang->traincourse->all            = '所有课程';
$lang->traincourse->createdByMe    = '由我创建';
$lang->traincourse->youCould       = '您可以';

$lang->traincourse->courseName  = '课程名称';
$lang->traincourse->courseDesc  = '课程简介';
$lang->traincourse->teacher     = '授课老师';
$lang->traincourse->courseCover = '课程封面';
$lang->traincourse->uploadCover = '添加封面';

$lang->traincourse->id             = '编号';
$lang->traincourse->category       = '所属分类';
$lang->traincourse->name           = '课程名称';
$lang->traincourse->status         = '状态';
$lang->traincourse->importedStatus = '导入状态';
$lang->traincourse->desc           = '简介';
$lang->traincourse->createdBy      = '由谁创建';
$lang->traincourse->createdDate    = '创建日期';
$lang->traincourse->editedBy       = '由谁编辑';
$lang->traincourse->editedDate     = '编辑日期';

$lang->traincourse->type          = '类型';
$lang->traincourse->chapterName   = '标题';
$lang->traincourse->chapterDesc   = '简介';
$lang->traincourse->parentChapter = '所属章节';
$lang->traincourse->file          = '上传文件';

$lang->traincourse->parentCategory = '上级分类';
$lang->traincourse->categoryName   = '分类名称';

$lang->traincourse->unimportedCourse = '未导入的课程';
$lang->traincourse->courseCategory   = '课程分类';
$lang->traincourse->chapterCount     = '章节数量';

$lang->traincourse->more               = '更多';
$lang->traincourse->noDesc             = '暂无简介。';
$lang->traincourse->noCourses          = '暂时还没有课程。';
$lang->traincourse->noRecommendCourses = '暂无相关的课程推荐。';
$lang->traincourse->noCollectedCourses = '暂时还没有收藏的课程。';
$lang->traincourse->noFinishedCourses  = '暂时还没有完成的课程。';
$lang->traincourse->drag               = '请拖拽文件到此处。';
$lang->traincourse->confirmDelete      = '您确定要删除吗?';
$lang->traincourse->browseTip          = '点击左侧树目录查看课程章节和内容详情，您也可以继续';
$lang->traincourse->addCatalogTip      = '您的课程还没有章节和内容，您可以添加';
$lang->traincourse->noModule           = '<div>您现在还没有分类信息</div>';

$lang->traincourse->statusList = array();
$lang->traincourse->statusList['offline'] = '待上线';
$lang->traincourse->statusList['online']  = '已上线';

$lang->traincourse->importedStatusList = array();
$lang->traincourse->importedStatusList['']      = '';
$lang->traincourse->importedStatusList['wait']  = '等待中';
$lang->traincourse->importedStatusList['doing'] = '导入中';
$lang->traincourse->importedStatusList['done']  = '导入完成';

$lang->traincourse->progressList = array();
$lang->traincourse->progressList['']      = '';
$lang->traincourse->progressList['wait']  = '未开始';
$lang->traincourse->progressList['doing'] = '正在学习';
$lang->traincourse->progressList['done']  = '已完成';

$lang->traincourse->featureBar = array();
$lang->traincourse->featureBar['admin']['all']     = $lang->traincourse->all;
$lang->traincourse->featureBar['admin']['offline'] = $lang->traincourse->statusList['offline'];
$lang->traincourse->featureBar['admin']['online']  = $lang->traincourse->statusList['online'];

$lang->traincourse->featureBar['browse']['all']   = '全部课程';
$lang->traincourse->featureBar['browse']['wait']  = $lang->traincourse->progressList['wait'];
$lang->traincourse->featureBar['browse']['doing'] = $lang->traincourse->progressList['doing'];
$lang->traincourse->featureBar['browse']['done']  = $lang->traincourse->progressList['done'];

$lang->traincourse->typeList = array();
$lang->traincourse->typeList['chapter'] = '章节';
$lang->traincourse->typeList['video']   = '内容';

$lang->traincourse->allCourses          = '所有课程';
$lang->traincourse->notStart            = '未开始';
$lang->traincourse->inProgress          = '正在学习';
$lang->traincourse->finished            = '已完成';
$lang->traincourse->noNotStartCourses   = '没有未开始的课程';
$lang->traincourse->noInProgressCourses = '没有正在进行的课程';
$lang->traincourse->noFinishedCourses   = '没有已完成的课程';
$lang->traincourse->learnProgress       = '学习进度';
$lang->traincourse->fileNotEmpty        = '文件不能为空。';
$lang->traincourse->onlySupportZIP      = '只支持ZIP格式上传，请转换ZIP格式再上传。';
$lang->traincourse->noYamlFile          = '没有找到YAML文件，请联系禅道客服获取正确的ZIP压缩文件。';
$lang->traincourse->yamlFileError       = '解析YAML文件错误，请联系禅道客服获取正确的ZIP压缩文件。';
$lang->traincourse->playFailed          = '当前视频有误，无法播放，请联系管理员';
$lang->traincourse->confirmClose        = '还有课程未上传完成，点击关闭后将终止上传流程，是否关闭？';
$lang->traincourse->batchImportTips1    = '请将课程包上传到服务器 <strong>%s</strong> 目录下，然后点击『导入』按钮。';
$lang->traincourse->batchImportTips2    = '课程包应该是zip格式的压缩包，无需解压。';
$lang->traincourse->batchImportTips3    = '课程包较大时导入耗时较长。您可以离开此页面处理其他事情，待导入完成后刷新页面即可。';
$lang->traincourse->importTip           = '因课程文件较大，课程正在后台导入中，您可以正常关闭弹窗，导入成功后会邮件通知您。';

$lang->traincourse->view          = '课程详情';
$lang->traincourse->fullscreen    = '全屏';
$lang->traincourse->retrack       = '收起';
$lang->traincourse->chapter       = '章节';
$lang->traincourse->finish        = '本节学习完成';
$lang->traincourse->next          = '下一章节';
$lang->traincourse->relatedInfo   = '相关信息';
$lang->traincourse->allCourse     = '所有课程';
$lang->traincourse->continueStudy = '继续学习';
$lang->traincourse->beginStudy    = '开始学习';
$lang->traincourse->manage        = '管理课程';
$lang->traincourse->chapterInfo   = '进度：%s/%s';
$lang->traincourse->allVideo      = '共 %s 节';

$lang->traincourse->addFile         = '添加文件';
$lang->traincourse->beginUpload     = '开始上传';
$lang->traincourse->importSuccess   = '导入成功';
$lang->traincourse->noCourse2Import = '没有可以导入的课程包';
$lang->traincourse->mailTip         = '学堂导入课程完成，具体内容如下：';

$lang->traincourse->importResult['success'] = '导入成功';
$lang->traincourse->importResult['fail']    = '导入失败';

$lang->practice = new stdClass();
$lang->practice->all               = '全部实践';
$lang->practice->rongpm            = '融管理社区推荐实践库';
$lang->practice->latest            = '最新实践';
$lang->practice->recommend         = '推荐实践';
$lang->practice->update            = '更新数据';
$lang->practice->viewPractice      = '查看实践';
$lang->practice->introduce         = '实践库是综合多种模型框架体系，结合国内外已经形成共识的推荐实践，加以整理、阐述而成。旨在为软件项目管理的从业人员提供系统、完整、落地的推荐实践集。您可以通过角色、模型、阶段选择适用于自己团队或职能角色的实践；通过“何时使用”以及“如何使用”来确定可以引入团队的实践方法；通过“实践出处”来追本溯源。您可以根据团队的实际情况灵活采用其中的任意实践。';
$lang->practice->source            = <<<EOF
内容来源于融管理平台：<a target="_blank" href="https://www.rongpm.com/rrpl.html" title="融管理平台" style="color: #2E7FFF">https://www.rongpm.com/rrpl.html</a>
EOF;
$lang->practice->thanks            = <<<EOF
感谢融管理平台的团队共创，成员名单详见：<a target="_blank" href="https://www.rongpm.com/thanks.html" title="融管理成员名单" style="color: #2E7FFF">https://www.rongpm.com/thanks.html</a>
EOF;
$lang->practice->contributorCommon = '贡献人';
$lang->practice->contributor       = '贡献人：%s';
$lang->practice->updatePractice    = '<i class="icon icon-refresh"></i> 更新数据';
$lang->practice->updatePracticeTip = '确定要更新实践库中的实践吗？';
$lang->practice->allPractice       = '所有实践';
$lang->practice->search            = '搜索';
$lang->practice->searchPlaceholder = '请输入关键词搜索';
$lang->practice->intranetUpdateTip = '请连接网络进行更新';
$lang->practice->loadImages        = '加载实践图片';
$lang->practice->loadImagesTip     = <<<EOF
<ol>
  <li>访问 <a target="_blank" href="https://dl.cnezsoft.com/zentao/rongpm/%s/images.zip" style="color: #2E7FFF">https://dl.cnezsoft.com/zentao/rongpm/%s/images.zip</a> 下载实践图片；</li>
  <li>上传至www目录下，解压。</li>
<ol>
EOF;
