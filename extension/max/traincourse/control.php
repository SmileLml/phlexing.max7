<?php
/**
 * The control file of traincourse module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@cnezsoft.com>
 * @package     task
 * @version     $Id: control.php 5106 2022-02-08 17:15:54Z $
 * @link        https://www.zentao.net
 */
class traincourse extends control
{
    const NEW_CHILD_COUNT = 5;

    /**
     * Construct function, check user priv.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Browse manage courses list.
     *
     * @param  string    $browseType
     * @param  int       $categoryID
     * @param  string    $orderBy
     * @param  int       $recTotal
     * @param  int       $recPerPage
     * @param  int       $pageID
     * @access public
     * @return void
     */
    public function admin($browseType = 'all', $categoryID = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->session->set('courseList', $this->app->getURI(true));

        $browseType = strtolower($browseType);

        if($browseType == 'bymodule') setcookie('courseModule', (int)$categoryID, 0, $this->config->webRoot);
        if($browseType != 'bymodule') $this->session->set('courseManageBrowseType', $browseType);
        $categoryID = ($browseType == 'bymodule') ? $categoryID : ($browseType == 'bysearch' ? 0 : ($this->cookie->courseModule ? $this->cookie->courseModule : 0));

        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $this->view->title      = $this->lang->traincourse->admin;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->courses    = $this->traincourse->getList4Manage($browseType, $categoryID, $orderBy, $pager);
        $this->view->categoryID = $categoryID;
        $this->view->browseType = $browseType;
        $this->view->moduleTree = $this->traincourse->getTreeMenu();
        $this->view->category   = $this->traincourse->getCategoryByID($categoryID);
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->display();
    }

    /**
     * Browse courses list.
     *
     * @param  string    $browseType
     * @param  int       $categoryID
     * @param  string    $orderBy
     * @param  int       $queryID
     * @param  int       $recTotal
     * @param  int       $recPerPage
     * @param  int       $pageID
     * @access public
     * @return void
     */
    public function browse($browseType = 'all', $categoryID = 0, $orderBy = 'id_desc', $queryID = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        if($browseType == 'byModule') setcookie('courseModule', (int)$categoryID, 0, $this->config->webRoot);
        if($browseType != 'byModule') $this->session->set('courseBrowseType', $browseType);
        $categoryID = ($browseType == 'byModule') ? $categoryID : ($browseType == 'bySearch' ? 0 : ($this->cookie->courseModule ? $this->cookie->courseModule : 0));

        $this->loadModel('file');

        $queryID = $browseType == 'bySearch' ? (int)$queryID : 0;
        $courses = $this->traincourse->getCourseList($browseType, $categoryID, $orderBy, $queryID, $pager);
        foreach($courses as $courseID => $course)
        {
            $courses[$courseID]->files = $this->file->getByObject('traincourse', $courseID);
        }

        /* Build the search form. */
        $actionURL = $this->createLink('traincourse', 'browse', "browseType=bySearch&category=$categoryID&orderBy=$orderBy&queryID=myQueryID");
        $this->traincourse->buildSearchForm($queryID, $actionURL);

        unset($this->lang->traincourse->progressList['']);

        $this->view->title       = $this->lang->traincourse->course;
        $this->view->courses     = $courses;
        $this->view->categoryID  = $categoryID;
        $this->view->browseType  = $browseType;
        $this->view->moduleTree  = $this->traincourse->getTreeMenu();
        $this->view->category    = $this->traincourse->getCategoryByID($categoryID);
        $this->view->pager       = $pager;
        $this->display();
    }

    /**
     * Browse the categories and print manage links.
     *
     * @param  string $type
     * @param  int    $categoryID
     * @param  string $originalType
     * @access public
     * @return void
     */
    public function browseCategory($type = 'trainskill', $categoryID = 0, $originalType = '')
    {
        $originalType = $originalType ? $originalType : $type;
        if(isset($this->lang->admin->menu->$originalType))
        {
            $menu = $this->lang->admin->menu->$originalType;
            $menu['subModule'] = isset($menu['subMenu']) ? $menu['subModule'] . ',category' : 'category';

            $this->lang->admin->menu->$originalType = $menu;
        }

        $this->view->title        = $this->lang->traincourse->browseCategory;
        $this->view->type         = $type;
        $this->view->originalType = $originalType;
        $this->view->categoryID   = $categoryID;
        $this->view->categoryMenu = $this->traincourse->getCategoryTreeMenu($type, 0, array('traincourseModel', 'createManageCategoryLink'));
        $this->view->children     = $this->traincourse->getCategoryChildren($categoryID);

        $this->display();
    }

    /**
     * Manage category children.
     *
     * @param  string    $type
     * @param  int       $category    the current category id.
     * @param  string    $originalType
     * @access public
     * @return void
     */
    public function categoryChildren($type, $category = 0, $originalType = '')
    {
        if(!empty($_POST))
        {
            $result = $this->traincourse->manageCategoryChildren($type, $this->post->parent, $this->post->children);

            $locate = $this->inLink('browseCategory', "type=$type&category={$this->post->parent}&originalType=$originalType");
            if($result === true) $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locate));
            $this->send(array('result' => 'fail', 'message' => dao::isError() ? dao::getError() : $result));
        }

        $this->view->title        = $this->lang->traincourse->manageCategory;
        $this->view->type         = $type;
        $this->view->originalType = $originalType;
        $this->view->children     = $this->traincourse->getCategoryChildren($category);
        $this->view->origins      = $this->traincourse->getCategoryOrigin($category);
        $this->view->parent       = $category;

        $this->display();
    }

    /**
     * Edit a category.
     *
     * $param  string  $type
     * @param  int     $categoryID
     * @access public
     * @return void
     */
    public function editCategory($type, $categoryID)
    {
        if(!empty($_POST))
        {
            $result = $this->traincourse->updateCategory($categoryID);
            if($result === true) $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
            $this->send(array('result' => 'fail', 'message' => dao::isError() ? dao::getError() : $result));
        }

        /* Get current category. */
        $category = $this->traincourse->getCategoryById($categoryID);

        /* Get option menu and remove the families of current category from it. */
        $optionMenu = $this->traincourse->getOptionMenu(0, false, 0, 'category');
        $families   = $this->traincourse->getCategoryFamily($categoryID);
        foreach($families as $member) unset($optionMenu[$member]);

        /* Assign. */
        $this->view->title      = $this->lang->traincourse->editCategory;
        $this->view->category   = $category;
        $this->view->optionMenu = $optionMenu;

        $this->display();
    }

    /**
     * Delete a category.
     *
     * @param  int    $categoryID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function deleteCategory($categoryID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->traincourse->confirmDelete, $this->createLink('traincourse', 'deleteCategory', "categoryID=$categoryID&confirm=yes")));
        }
        else
        {
            $this->traincourse->delete(TABLE_TRAINCATEGORY, $categoryID);
            return print(js::reload('parent'));
        }
    }

    /**
     * View a course.
     *
     * @param  int    $courseID
     * @param  string $type
     * @access public
     * @return void
     */
    public function viewCourse($courseID, $type = 'browse')
    {
        $this->session->set('chapterList', $this->app->getURI(true));

        $course = $this->traincourse->getById($courseID);
        if(empty($course)) $this->locate($this->createLink('traincourse', 'browse'));

        $course->files = $this->loadModel('file')->getByObject('traincourse', $courseID);

        $allCourseLink = $this->createLink('traincourse', 'browse');
        $position[]    = html::a($allCourseLink, $this->lang->traincourse->allCourse);
        $position[]    = $course->name;

        $activeChapterID = $this->traincourse->getActiveChapterID($courseID);

        $this->view->title    = $course->name;
        $this->view->position = $position;

        $this->view->chapterList     = $this->traincourse->getChildren($course->id, 'all', "type = 'video'");
        $this->view->users           = $this->loadModel('user')->getPairs('noletter');
        $this->view->course          = $course;
        $this->view->file            = empty($course->files) ? '' : reset($course->files);
        $this->view->catalog         = $this->traincourse->getAdminChapter($courseID, 0, $this->traincourse->computeSN($courseID), false, $activeChapterID);
        $this->view->activeChapterID = $activeChapterID;
        $this->view->chapter         = $this->traincourse->getChapterById($courseID);
        $this->view->type            = $type;

        $this->display();
    }

    /*
     * Create a course.
     *
     * @access public
     * @return void
     */
    public function createCourse()
    {
        if($_POST)
        {
            $result = $this->traincourse->createCourse();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $courseID = $result;
            $actionID = $this->loadModel('action')->create('traincourse', $courseID, 'Opened');
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('traincourse', 'admin')));
        }
        $this->loadModel('file');

        $this->view->title         = $this->lang->traincourse->createCourse;
        $this->view->module        = 'traincourse';
        $this->view->uid           = uniqid();
        $this->view->categoryPairs = arrayUnion(array(0 => ''), $this->traincourse->getOptionMenu(0, true, 0, 'category'));
        $this->display();
    }

    /**
     * Edit a course.
     *
     * @param  int    $courseID
     * @param  int    $comment
     * @access public
     * @return void
     */
    public function editCourse($courseID, $comment = false)
    {
        if(!empty($_POST))
        {
            if($comment == false)
            {
                $changes = $this->traincourse->updateCourse($courseID);
                if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

                $actionID = $this->loadModel('action')->create('traincourse', $courseID, 'edited');
                $this->action->logHistory($actionID, $changes);

                $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
            }
        }

        $this->loadModel('file');

        $course        = $this->traincourse->getByID($courseID);
        $course->files = $this->file->getByObject('traincourse', $courseID);

        $this->view->title         = $this->lang->traincourse->editCourse;
        $this->view->course        = $course;
        $this->view->file          = empty($course->files) ? '' : reset($course->files);
        $this->view->categoryPairs = $this->traincourse->getOptionMenu(0, true, 0, 'category');
        $this->display();
    }

    /**
     * Manage a course.
     *
     * @param  int $courseID
     * @param  int $nodeID
     * @access public
     * @return void
     */
    public function manageCourse($courseID = 0, $nodeID = 0)
    {
        $this->session->set('chapterList', $this->app->getURI(true));

        $this->view->title   = $this->lang->traincourse->manageCourse;
        $this->view->course  = $this->traincourse->getByID($courseID);
        $this->view->chapter = $this->traincourse->getAdminChapter($courseID, $nodeID, $this->traincourse->computeSN($courseID));
        $this->display();
    }

    /**
     * Delete a course.
     *
     * @param  int    $courseID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function deleteCourse($courseID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->traincourse->confirmDelete, $this->createLink('traincourse', 'deleteCourse', "courseID=$courseID&confirm=yes")));
        }
        else
        {
            $this->traincourse->deleteCourse($courseID);
            return print(js::locate($this->createLink('traincourse', 'admin'), 'parent'));
        }
    }

    /**
     * Change status.
     *
     * @param  int    $courseID
     * @param  string $status
     * @access public
     * @return void
     */
    public function changeStatus($courseID, $status)
    {
        $this->traincourse->changeStatus($courseID, $status);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->loadModel('action')->create('traincourse', $courseID, 'changestatus', '', $status);

        return $this->send(array('result' => 'success', 'load' => $this->createLink('traincourse', 'admin', "browseType={$status}")));
    }

    /**
     * Manage chapters.
     *
     * @param  int    $courseID
     * @param  int    $nodeID
     * @access public
     * @return void▫
     */
    public function manageChapter($courseID = 0, $nodeID = 0)
    {
        if($_POST)
        {
            $result = $this->traincourse->manageChapter($courseID, $nodeID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $link = $this->createLink('traincourse', 'manageCourse', "courseID=$courseID&nodeID=0");
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $link . "#node" . $nodeID));
        }

        $course = $this->traincourse->getById($courseID);

        $this->view->title      = $this->lang->traincourse->manageChapter;
        $this->view->position[] = $this->lang->traincourse->manageChapter;
        $this->view->course     = $course;
        $this->view->courseID   = $courseID;
        $this->view->node       = $this->traincourse->getChapterById($nodeID);
        $this->view->children   = $this->traincourse->getChapterChildren($courseID, $nodeID);

        $this->display();
    }

    /**
     * Edit a chapter.
     *
     * @param  int    $chapterID
     * @access public
     * @return void▫
     */
    public function editChapter($chapterID)
    {
        $this->loadModel('traincourse');
        $chapter = $this->traincourse->getChapterById($chapterID);

        if(!empty($_POST))
        {
            $this->traincourse->updateChapter($chapterID);

            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $chapterLink = $this->createLink('traincourse', 'manageCourse', "course=$chapter->course");
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $chapterLink));
        }

        $course = $this->traincourse->getByID($chapter->course);

        $this->view->title       = $this->lang->traincourse->editChapter;
        $this->view->chapter     = $chapter;
        $this->view->chapterList = $this->traincourse->getChapterPairs($chapter->course);
        $this->view->course      = $course;
        $this->view->courseID    = $course->id;
        $this->view->optionMenu  = $this->traincourse->getOptionMenu($chapter->course, $removeRoot = false, $chapter->id);
        $this->display();
    }

    /**
     * Play video.
     *
     * @param  int    $fileID
     * @access public
     * @return void
     */
    public function playVideo($fileID)
    {
        include './videostream.php';
        $file = $this->loadModel('file')->getById($fileID);

        $vs = new VideoStream($file->realPath);
        $vs->start();
    }

    /**
     * View chapter.
     *
     * @param  int    $chapterID
     * @access public
     * @return void
     */
    public function viewChapter($chapterID)
    {
        $chapter = $this->traincourse->getChapterById($chapterID);
        if(empty($chapter)) $this->locate($this->createLink('traincourse', 'browse'));
        $course  = $this->traincourse->getById($chapter->course);
        if(empty($course)) $this->locate($this->createLink('traincourse', 'browse'));

        $this->view->title         = $this->lang->traincourse->view;
        $this->view->moduleMenu    = false;
        $this->view->users         = $this->loadModel('user')->getPairs('noletter');
        $this->view->chapter       = $chapter;
        $this->view->course        = $course;
        $this->view->status        = $this->traincourse->getChapterStatus($chapterID);
        $this->view->catalog       = $this->traincourse->getAdminChapter($course->id, 0, $this->traincourse->computeSN($course->id), false, $chapterID);
        $this->view->file          = empty($chapter->files) ? '' : reset($chapter->files);
        $this->view->nextChapterID = $this->traincourse->getNextChapterID($course->id, $chapterID);
        $this->view->browseType    = $this->session->courseBrowseType;
        $this->display();
    }

    /**
     * Sort chapter order.
     *
     * @access public
     * @return void
     */
    public function sortChapterOrder()
    {
        $this->traincourse->sortChapterOrder();
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
    }

    /**
     * Finish a chapter.
     *
     * @param  int    $chapterID
     * @param  int    $courseID
     * @access public
     * @return void
     */
    public function ajaxFinishChapter($chapterID, $courseID)
    {
        $locate  = '';
        $message = '';

        $chapter = $this->traincourse->getChapterByID($chapterID);
        if(empty($chapter)) return print(json_encode(array('result' => false, 'locate' => $this->createLink('traincourse', 'viewCourse', "courseID=$courseID"))));
        $this->traincourse->finishChapter($chapterID, $this->app->user->account, $chapter->course);

        if(dao::isError())
        {
            $result  = false;
            $message = dao::getError();
        }
        else
        {
            $result        = 'success';
            $nextChapterID = $this->traincourse->getNextChapterID($chapter->course, $chapterID);
            $locate        = ($nextChapterID == 'end') ? $this->createLink('traincourse', 'viewCourse', "courseID={$chapter->course}") : $this->createLink('traincourse', 'viewChapter', "chapterID=$nextChapterID");
        }

        return print(json_encode(array('result' => $result, 'locate' => $locate, 'message' => $message)));
    }

    /**
     * Delete a chapter.
     *
     * @param  int    $chapterID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function deleteChapter($chapterID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->traincourse->confirmDelete, $this->createLink('traincourse', 'deleteChapter', "chapterID=$chapterID&confirm=yes")));
        }
        else
        {
            $this->traincourse->delete(TABLE_TRAINCONTENTS, $chapterID);
            return print(js::reload('parent'));
        }
    }

    /**
     * Upload course.
     *
     * @param  string $uid
     * @access public
     * @return void
     */
    public function uploadCourse($uid = '')
    {
        if(strtolower($this->server->request_method) == "post")
        {
            $savePath = $this->app->getWwwRoot() . $this->config->traincourse->uploadPath;

            $uploadedFiles = isset($_SESSION['courseFile']) ? $_SESSION['courseFile'] : '';
            if($uploadedFiles)
            {
                $this->loadModel('file');
                foreach($uploadedFiles as $realName => $file)
                {
                    $result = $this->traincourse->createByFile($file['title'], $file['realpath'], $savePath);
                    if(isset($result['result']) && $result['result'] == 'fail') return $this->send($result);
                }

                /* Remove upload image file and session. */
                $this->traincourse->removeSession();
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
        }

        $this->traincourse->removeSession();
        $this->app->loadLang('file');

        $this->view->uid = empty($uid) ? uniqid() : $uid;
        $this->display();
    }

    /**
     * Ajax upload large file.
     *
     * @param  string $uid
     * @access public
     * @return void
     */
    public function ajaxUploadLargeFile($uid = '')
    {
        $this->loadModel('file');

        if($_FILES)
        {
            $file = $this->traincourse->getUploadFile('file');
            if(empty($file)) return print(js::alert($this->lang->traincourse->fileNotEmpty));
            if($file['extension'] != 'zip') return print(js::alert($this->lang->traincourse->onlySupportZIP));

            $uploadedFile = $this->traincourse->saveUploadFile($file, $uid);
            if($uploadedFile === false)
            {
                die(json_encode(array('result' => 'fail', 'message' => $this->lang->file->errorFileMove)));
            }
            else
            {
                if(!empty($uploadedFile))
                {
                    $extension = $uploadedFile['extension'];

                    $sessionName   = 'courseFile';
                    $uploadedFiles = empty($_SESSION[$sessionName]) ? array() : $_SESSION[$sessionName];
                    $fileName      = basename($uploadedFile['title']);
                    $uploadedFiles[$fileName] = $uploadedFile;

                    $this->session->set($sessionName, $uploadedFiles);
                }
                die(json_encode(array('result' => 'success', 'file' => $file, 'message' => $this->lang->file->uploadSuccess)));
            }
        }

        $this->view->uid = empty($uid) ? uniqid() : $uid;
        $this->display();
    }

    /**
     * Batch import the course packages in the course directory.
     *
     * @access public
     * @return void
     */
    public function batchImport()
    {
        set_time_limit(0);

        $savePath = $this->app->getWwwRoot() . $this->config->traincourse->uploadPath;

        if(strtolower($this->server->request_method) == "post")
        {
            $this->loadModel('file');

            $imported  = false;
            $zFile     = $this->app->loadClass('zfile');
            $filePaths = glob($savePath . '*');
            foreach($filePaths as $filePath)
            {
                if(!is_file($filePath)) continue;

                $fileTitle = basename($filePath);
                $extension = $this->file->getExtension($fileTitle);
                if($extension != 'zip') continue;

                $fileName = str_replace('.zip', '', $fileTitle);
                if(is_dir($savePath . $fileName)) continue;

                $result = $this->traincourse->createByFile($fileTitle, $filePath, $savePath);
                if(isset($result['result']) && $result['result'] == 'fail') return $this->send($result);

                $imported = true;
                $zFile->removeFile($filePath);
            }

            $message = $imported ? $this->lang->traincourse->importSuccess : $this->lang->traincourse->noCourse2Import;
            return $this->send(array('result' => 'success', 'message' => $message, 'locate' => 'parent'));
        }

        $this->view->title    = $this->lang->traincourse->batchImport;
        $this->view->savePath = $savePath;
        $this->display();
    }

    /**
     * Import courses from cloud.
     *
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function cloudImport($recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
        $cloudCourseList = $this->traincourse->getCloudCourses();
        $existedCourses  = $this->dao->select('id,code,name,lastUpdatedTime')->from(TABLE_TRAINCOURSE)->fetchAll('code');

        if($_POST)
        {
            $this->traincourse->cloudImport($cloudCourseList, $existedCourses);

            $downloadUrl = common::getSysURL() . $this->inLink('downloadCourse', 'restart=false&toUser=' . $this->app->user->account);
            common::http($downloadUrl, null, array(), array(), 'data', 'GET', 1);

            return print(js::locate(inlink('admin'), 'parent'));
        }

        foreach($cloudCourseList as $index => $course)
        {
            if(isset($existedCourses[$course->code]) && $course->updateTime == $existedCourses[$course->code]->lastUpdatedTime) unset($cloudCourseList[$index]);
        }

        /* Pager. */
        $this->app->loadClass('pager', true);
        $recTotal = count($cloudCourseList);
        $pager    = new pager($recTotal, $recPerPage, $pageID);
        $courses  = array_chunk($cloudCourseList, $pager->recPerPage);

        $this->view->title   = $this->lang->traincourse->cloudImport;
        $this->view->courses = empty($courses) ? $courses : $courses[$pageID - 1];
        $this->view->pager   = $pager;

        $this->display();
    }

    /**
     * Download cloud courses.
     *
     * @param  bool   restart
     * @access public
     * @return void
     */
    public function downloadCourse($restart = false, $toUser = '')
    {
        set_time_limit(0);
        if(!$toUser) $toUser = $this->dao->select('account')->from(TABLE_USER)->where('deleted')->eq('0')->orderBy('id')->fetch('account');
        $cloudCourseList    = $this->traincourse->getCloudCourses();
        $importedStatusType = $restart ? 'wait,doing' : 'wait';
        $downloadingCourses = $this->dao->select('code')->from(TABLE_TRAINCOURSE)->where('importedStatus')->in($importedStatusType)->fetchPairs('code');

        $basePath = $this->app->tmpRoot . '/importcourses/';
        if(!file_exists($basePath)) mkdir($basePath, 0777, true);

        $courses = array();
        foreach($cloudCourseList as $cloudCourse)
        {
            if(!in_array($cloudCourse->code, $downloadingCourses)) continue;

            $this->dao->update(TABLE_TRAINCOURSE)->set('importedStatus')->eq('doing')->where('code')->eq($cloudCourse->code)->exec();

            preg_match('#[^/]+\.zip$#', $cloudCourse->link, $zipName);
            $filename    = $zipName[0];
            $tmpSavePath = $basePath . $filename;
            $file        = fopen($tmpSavePath, 'w+');
            $curlOption  = array(CURLOPT_FILE => $file);

            commonModel::http($cloudCourse->link, null, $curlOption, array(), 'data', 'GET', 0);

            fclose($file);

            $cloudCourse->result  = $this->traincourse->parseCloudCourse($filename, $tmpSavePath, $cloudCourse, $toUser);
            $courses[] = $cloudCourse;
        }

        if($courses) $this->sendMail($toUser, $courses);
    }

    /**
     * Decrypt and preview PDF file.
     *
     * @param  int    $chapterID
     * @access public
     * @return void
     */
    public function viewPDF($chapterID)
    {
        $chapter = $this->traincourse->getChapterById($chapterID);
        $file    = empty($chapter->files) ? '' : reset($chapter->files);

        if(!empty($file) && $file->extension != 'mp4')
        {
            header("Content-type: application/pdf");
            header("Content-Length: " . $file->size);

            $fileContents = '';
            $fileHandle   = fopen($file->realPath, 'rb');
            if($fileHandle)
            {
                $fileContents = fread($fileHandle, $file->size);
                fclose($fileHandle);
            }

            if($fileContents)
            {
                $reversedContent = ~$fileContents;

                for($i = 0; $i < strlen($reversedContent); $i ++)
                {
                    if($i % 11111 == 0)
                    {
                        $reversedContent[$i] = ~$reversedContent[$i];
                    }
                }
            }

            echo $reversedContent;
            flush();
        }
    }

    /**
     * Send mail after traincourse import.
     *
     * @param string $sendTo
     * @param string $subject
     * @param string $course
     * @access public
     * @return void
     */
    public function sendMail($sendTo = '', $courses = '')
    {
        $this->loadModel('mail');
        $modulePath = $this->app->getModulePath();
        $oldcwd     = getcwd();
        $viewFile   = $modulePath . 'view/sendmail.html.php';
        chdir($modulePath . 'view');
        if(file_exists($modulePath . 'ext/view/sendmail.html.php'))
        {
            $viewFile = $modulePath . 'ext/view/sendmail.html.php';
            chdir($modulePath . 'ext/view');
        }
        ob_start();
        include $viewFile;
        foreach(glob($modulePath . 'ext/view/sendmail.*.html.hook.php') as $hookFile) include $hookFile;
        $mailContent = ob_get_contents();
        ob_end_clean();
        chdir($oldcwd);

        $this->mail->send($sendTo, $this->lang->traincourse->mailTip, $mailContent, '', true);
        if($this->mail->isError()) error_log(join("\n", $this->mail->getError()));
    }

    /**
     * Show practice home page.
     *
     * @access public
     * @return void
     */
    public function practice()
    {
        $this->view->title                = $this->lang->traincourse->practice;
        $this->view->categories           = $this->traincourse->getPracticeCategories();
        $this->view->latestPractices      = !empty($this->config->global->latestPractices) ? json_decode($this->config->global->latestPractices, true) : array();
        $this->view->recommendedPractices = !empty($this->config->global->recommendedPractices) ? json_decode($this->config->global->recommendedPractices, true) : array();
        $this->view->practiceNum          = $this->dao->select('id')->from(TABLE_PRACTICE)->count();

        $this->display();
    }

    /**
     * Browse practice.
     *
     * @param  int    $moduleID
     * @param  string $browseType
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function practiceBrowse($moduleID = 0, $browseType = '', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Process search words. */
        $search = '';
        if($browseType == 'search')
        {
            $moduleID = 0;
            $search   = strip_tags(urldecode(urldecode($this->post->search)));

            if($search || $this->post->search === '') $this->session->set('practiceSearchWords', $search);
            if(!$search) $search = $this->session->practiceSearchWords;
        }
        if($browseType != 'search') unset($_SESSION['practiceSearchWords']);

        /* Pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $this->loadModel('tree');
        $moduleItem   = $moduleID > 0 ? $this->tree->getByID($moduleID) : null;
        $firstModule  = $moduleItem;
        $secondModule = null;
        if($moduleItem and ($moduleItem->parent > 0))
        {
            $firstModule  = $this->tree->getByID($moduleItem->parent);
            $secondModule = $moduleItem;
        }

        $this->view->title        = $this->lang->traincourse->practiceBrowse;
        $this->view->moduleItem   = $moduleItem;
        $this->view->moduleTree   = $this->tree->getPracticeTreeMenu(array('treeModel', 'createPracticeLink'));
        $this->view->firstModule  = $firstModule;
        $this->view->secondModule = $secondModule;
        $this->view->practices    = $this->traincourse->getPracticeList($moduleID, $search, $pager);
        $this->view->moduleID     = $moduleID;
        $this->view->search       = $search;
        $this->view->pager        = $pager;

        $this->display();
    }

    /**
     * View practice.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function practiceView($id)
    {
        $practice = $this->dao->findByID($id)->from(TABLE_PRACTICE)->fetch();
        if(!$practice) return print(js::error($this->lang->notFound) . js::locate($this->createLink('traincourse', 'practice')));

        $this->loadModel('tree');
        $secondModule = $this->tree->getByID($practice->module);
        $firstModule  = $this->tree->getByID($secondModule->parent);

        $this->view->title          = $this->lang->traincourse->practiceView;
        $this->view->practice       = $practice;
        $this->view->moduleTree     = $this->tree->getPracticeTreeMenu(array('treeModel', 'createPracticeLink'), 'view');
        $this->view->firstModule    = $firstModule;
        $this->view->secondModule   = $secondModule;
        $this->view->additionalHTML = $this->traincourse->buildAdditionalHTML($practice);
        $this->display();
    }

    /**
     * Update practice data.
     *
     * @access public
     * @return void
     */
    public function updatePractice()
    {
        if(helper::isIntranet()) return $this->send(array('result' => 'fail', 'message' => $this->lang->practice->intranetUpdateTip));

        $this->loadModel('action')->create('practice', 0, 'updated');

        session_write_close();
        $this->traincourse->updatePractices($isIntranet = false);

        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        return $this->send(array('result' => 'success'));
    }

    /**
     * Ajax update practice.
     *
     * @access public
     * @return void
     */
    public function ajaxUpdatePractice()
    {
        $isIntranet = helper::isIntranet();

        /* If it is an intranet environment and practice data exists, it is not updated. */
        $practiceCount = $this->dao->select('id')->from(TABLE_PRACTICE)->count();
        if($isIntranet)
        {
            if($practiceCount > 0) $this->send(array('result' => 'success', 'count' => $practiceCount));
        }

        $lastUpdate = $this->dao->select('id,`date`')->from(TABLE_ACTION)
            ->where('objectType')->eq('practice')
            ->andWhere('action')->eq('updated')
            ->orderBy('id_desc')
            ->fetch();
        $nextUpdateTime = time() + $this->config->practice->updateInterval;
        if($lastUpdate and (strtotime($lastUpdate->date) < $nextUpdateTime)) $this->send(array('result' => 'success', 'count' => $practiceCount));

        $this->loadModel('action')->create('practice', 0, 'updated', $comment = '', $extra = '', $actor = $this->lang->action->system);

        session_write_close();
        $this->traincourse->updatePractices($isIntranet);

        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success', 'count' => $practiceCount));
    }
}
