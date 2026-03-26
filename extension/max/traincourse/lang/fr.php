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
$lang->traincourse->createCourse       = 'Create Course';
$lang->traincourse->createChapter      = 'Create Chapter';
$lang->traincourse->createCategory     = 'Create Category';
$lang->traincourse->viewCourse         = 'View Course';
$lang->traincourse->viewChapter        = 'View Chapter';
$lang->traincourse->browse             = 'Course';
$lang->traincourse->upCourse           = 'Up Course';
$lang->traincourse->offCourse          = 'Off Course';
$lang->traincourse->manageCourse       = 'Manage Course';
$lang->traincourse->manageChapter      = 'Manage Chapter';
$lang->traincourse->manageCategory     = 'Manage Category';
$lang->traincourse->editCourse         = 'Edit Course';
$lang->traincourse->editChapter        = 'Edit Chapter';
$lang->traincourse->editCategory       = 'Edit Category';
$lang->traincourse->browseCategory     = 'Browse Category';
$lang->traincourse->categoryChildren   = 'Category Children';
$lang->traincourse->changeStatus       = 'Change Status';
$lang->traincourse->deleteCourse       = 'Delete Course';
$lang->traincourse->deleteChapter      = 'Delete Chapter';
$lang->traincourse->deleteCategory     = 'Delete Category';
$lang->traincourse->uploadCourse       = 'Upload Course';
$lang->traincourse->upload             = 'Upload';
$lang->traincourse->batchImport        = 'Import From Server';
$lang->traincourse->cloudImport        = 'Import From Cloud';
$lang->traincourse->practice           = 'Practice Library';
$lang->traincourse->practiceBrowse     = 'Practice List';
$lang->traincourse->practiceView       = 'View Practice';
$lang->traincourse->updatePractice     = 'Update Data';
$lang->traincourse->ajaxUpdatePractice = 'Update Data';

$lang->traincourse->adminAction    = 'Train Admin';
$lang->traincourse->practiceAction = 'Practice Library Home';
$lang->traincourse->browseAction   = 'Course List';

/* Fields. */
$lang->traincourse->course   = 'Course';
$lang->traincourse->admin    = 'Admin';
$lang->traincourse->category = 'Category';
$lang->traincourse->byQuery  = 'Search';

$lang->traincourse->all            = 'All Course';
$lang->traincourse->createdByMe    = 'Created By Me';
$lang->traincourse->youCould       = 'You Could';

$lang->traincourse->courseName  = 'Course Name';
$lang->traincourse->courseDesc  = 'Course Desc';
$lang->traincourse->teacher     = 'Teacher';
$lang->traincourse->courseCover = 'Course Cover';
$lang->traincourse->uploadCover = 'Upload Cover';

$lang->traincourse->id             = 'ID';
$lang->traincourse->category       = 'Category';
$lang->traincourse->name           = 'Course Name';
$lang->traincourse->status         = 'Status';
$lang->traincourse->importedStatus = 'Import Status';
$lang->traincourse->desc           = 'Desc';
$lang->traincourse->createdBy      = 'Create By';
$lang->traincourse->createdDate    = 'Create Date';
$lang->traincourse->editedBy       = 'Edited By';
$lang->traincourse->editedDate     = 'Edited Date';

$lang->traincourse->type          = 'Type';
$lang->traincourse->chapterName   = 'Chapter Name';
$lang->traincourse->chapterDesc   = 'Chapter Desc';
$lang->traincourse->parentChapter = 'Parent Chapter';
$lang->traincourse->file          = 'File';

$lang->traincourse->parentCategory = 'Parent Category';
$lang->traincourse->categoryName   = 'Category Name';

$lang->traincourse->unimportedCourse = 'Unimported Courses';
$lang->traincourse->courseCategory   = 'Category';
$lang->traincourse->chapterCount     = 'Chapter Count';

$lang->traincourse->more               = 'More';
$lang->traincourse->noDesc             = 'No Desc.';
$lang->traincourse->noCourses          = 'No Course.';
$lang->traincourse->noRecommendCourses = 'No Recommend Courses.';
$lang->traincourse->noCollectedCourses = 'No Collected Courses';
$lang->traincourse->noFinishedCourses  = 'No Finished Courses';
$lang->traincourse->drag               = 'Please drag the file to here.';
$lang->traincourse->confirmDelete      = 'Do you want to delete it?';
$lang->traincourse->browseTip          = 'Click the category menu to view chapters.';
$lang->traincourse->addCatalogTip      = 'Course does not have a chapter, you can create.';
$lang->traincourse->noModule           = '<div>No category information.</div>';

$lang->traincourse->statusList = array();
$lang->traincourse->statusList['offline'] = 'Offline';
$lang->traincourse->statusList['online']  = 'Online';

$lang->traincourse->importedStatusList = array();
$lang->traincourse->importedStatusList['']      = '';
$lang->traincourse->importedStatusList['wait']  = 'Wait';
$lang->traincourse->importedStatusList['doing'] = 'Importing';
$lang->traincourse->importedStatusList['done']  = 'Import Completed';

$lang->traincourse->progressList = array();
$lang->traincourse->progressList['']      = '';
$lang->traincourse->progressList['wait']  = 'Wait';
$lang->traincourse->progressList['doing'] = 'Doing';
$lang->traincourse->progressList['done']  = 'Done';

$lang->traincourse->featureBar = array();
$lang->traincourse->featureBar['admin']['all']     = $lang->traincourse->all;
$lang->traincourse->featureBar['admin']['offline'] = $lang->traincourse->statusList['offline'];
$lang->traincourse->featureBar['admin']['online']  = $lang->traincourse->statusList['online'];

$lang->traincourse->featureBar['browse']['all']   = $lang->traincourse->all;
$lang->traincourse->featureBar['browse']['wait']  = $lang->traincourse->progressList['wait'];
$lang->traincourse->featureBar['browse']['doing'] = $lang->traincourse->progressList['doing'];
$lang->traincourse->featureBar['browse']['done']  = $lang->traincourse->progressList['done'];

$lang->traincourse->typeList = array();
$lang->traincourse->typeList['chapter'] = 'Chapter';
$lang->traincourse->typeList['video']   = 'Article';

$lang->traincourse->allCourses          = 'All Courses';
$lang->traincourse->notStart            = 'Not Start';
$lang->traincourse->inProgress          = 'In Progress';
$lang->traincourse->finished            = 'Finished';
$lang->traincourse->noNotStartCourses   = 'No Not Start Courses';
$lang->traincourse->noInProgressCourses = 'No In Progress Courses';
$lang->traincourse->noFinishedCourses   = 'No Finished Courses';
$lang->traincourse->learnProgress       = 'Learn Progress';
$lang->traincourse->fileNotEmpty        = 'File cannot be empty.';
$lang->traincourse->onlySupportZIP      = 'Only zip format upload is supported. Please convert zip format and upload again.';
$lang->traincourse->noYamlFile          = 'The YAML file is not found, please contact ZenTao customer service to obtain the correct ZIP file.';
$lang->traincourse->yamlFileError       = 'Error in parsing YAML file, please contact ZenTao customer service to obtain the correct ZIP file.';
$lang->traincourse->playFailed          = 'The current video is incorrect and cannot be played. Please contact the administrator';
$lang->traincourse->confirmClose        = 'There are still courses that have not been uploaded. Click Close to terminate the upload process. Do you want to close?';
$lang->traincourse->batchImportTips1    = 'Please upload the course package to the server <strong>%s</strong> directory, and then click the "Import" button.';
$lang->traincourse->batchImportTips2    = 'The course package should be a compressed package in zip format, no need to decompress.';
$lang->traincourse->batchImportTips3    = 'The course package should be a compressed package in zip format, no need to decompress.Importing takes longer when the course package is large. You can leave this page to do other things, just refresh the page after the import is complete.';
$lang->traincourse->importTip           = "As the course files are quite large, the course is currently being imported in the background. You'll get an email when it's done.";

$lang->traincourse->view          = 'Details';
$lang->traincourse->fullscreen    = 'Fullscreen';
$lang->traincourse->retrack       = 'Retrack';
$lang->traincourse->chapter       = 'Chapter';
$lang->traincourse->finish        = 'Finish';
$lang->traincourse->next          = 'Next Chapter';
$lang->traincourse->relatedInfo   = 'Related Info';
$lang->traincourse->allCourse     = 'All Course';
$lang->traincourse->continueStudy = 'Continue Study';
$lang->traincourse->beginStudy    = 'Begin Study';
$lang->traincourse->manage        = 'Manege Course';
$lang->traincourse->chapterInfo   = 'Progress: %s/%s';
$lang->traincourse->allVideo      = 'In total: %s';

$lang->traincourse->addFile         = 'Add';
$lang->traincourse->beginUpload     = 'Upload';
$lang->traincourse->importSuccess   = 'Success';
$lang->traincourse->noCourse2Import = 'No course to import';
$lang->traincourse->mailTip         = 'The course import has been completed, and the specific contents are as follows:';

$lang->traincourse->importResult['success'] = 'Import Success';
$lang->traincourse->importResult['fail']    = 'Import Fail';

$lang->practice = new stdClass();
$lang->practice->all               = 'All Practice';
$lang->practice->rongpm            = 'Rong Management Community Recommended Practice Library';
$lang->practice->latest            = 'Latest Practice';
$lang->practice->recommend         = 'Recommended Practice';
$lang->practice->update            = 'Update Data';
$lang->practice->viewPractice      = 'View Practice';
$lang->practice->introduce         = 'The Practice Repository is a comprehensive collection of various model frameworks, combined with recommended practices that have been established both domestically and internationally, and organized and elaborated upon. Its aim is to provide software project management practitioners with a systematic, complete, and practical set of recommended practices. You can select the practices that are suitable for your team or functional role based on your role, model, and stage; determine the practices that can be introduced to the team through "when to use" and "how to use"; and trace the origin of the practices through "practice source". You can flexibly adopt any of the practices according to the actual situation of your team';
$lang->practice->source            = <<<EOF
The content comes from the Rong Management platform : <a target="_blank" href="https://www.rongpm.com/rrpl.html" title="Rong Management" style="color: #2E7FFF">https://www.rongpm.com/rrpl.html</a>
EOF;
$lang->practice->thanks            = <<<EOF
Thank you for the team of financial management platform, please see the list of members for details : <a target="_blank" href="https://www.rongpm.com/thanks.html" title="List of Rong Management members" style="color: #2E7FFF">https://www.rongpm.com/thanks.html</a>
EOF;
$lang->practice->contributorCommon = 'Contributory';
$lang->practice->contributor       = 'Contributory: %s';
$lang->practice->updatePractice    = '<i class="icon icon-refresh"></i> Update Data';
$lang->practice->updatePracticeTip = 'Are you sure you want to update the practices in the practices library?';
$lang->practice->allPractice       = 'All Practice';
$lang->practice->search            = 'Search';
$lang->practice->searchPlaceholder = 'Please enter keyword search';
$lang->practice->intranetUpdateTip = 'Please connect to the network to update.';
$lang->practice->loadImages        = 'Load practice pictures';
$lang->practice->loadImagesTip     = <<<EOF
<ol>
  <li>Visit <a target="_blank" href="https://dl.cnezsoft.com/zentao/rongpm/%s/images.zip" style="color: #2E7FFF">https://dl.cnezsoft.com/zentao/rongpm/%s/images.zip</a> to download the practice images;</li>
  <li>Upload them to the www directory and unzip.</li>
</ol>
EOF;
