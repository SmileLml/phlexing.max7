<?php
/**
 * Assign a doc.
 *
 * @param  int    $docID
 * @access public
 * @return array|bool
 */
public function assign($docID)
{
    $oldDoc = $this->getByID($docID);

    $now = helper::now();
    $doc = fixer::input('post')
        ->add('editedBy', $this->app->user->account)
        ->add('editedDate', $now)
        ->add('assignedDate', $now)
        ->stripTags($this->config->doc->editor->assignto['id'], $this->config->allowedTags)
        ->remove('uid,comment,files,label')
        ->get();

    $this->dao->update(TABLE_DOC)->data($doc)->autoCheck()->where('id')->eq((int)$docID)->exec();

    if(!dao::isError()) return common::createChanges($oldDoc, $doc);
    return false;
}

/**
 * Get doc list.
 *
 * @param  int|array|string $docList
 * @access public
 * @return array
 */
public function getByList($docList = 0)
{
    return $this->dao->select('*')->from(TABLE_DOC)
        ->where('deleted')->eq(0)
        ->beginIF($docList)->andWhere('id')->in($docList)->fi()
        ->fetchAll('id');
}

/**
 * Get node list.
 *
 * @param  int    $bookID
 * @access public
 * @return array
 */
public function getNodeList($bookID)
{
    $stmt = $this->dbh->query($this->dao->select('id, lib, type, chapterType, template, path, `order`, parent, grade, title')->from(TABLE_DOC)->where('template')->eq($bookID)->andWhere('deleted')->eq(0)->orderBy('grade_desc,`order`, id')->get());

    $parent = array();
    while($node = $stmt->fetch())
    {
        if(!isset($parent)) $parent = array();

        if(isset($parent[$node->id]))
        {
            $node->children = $parent[$node->id]->children;
            unset($parent[$node->id]);
        }
        if(!isset($parent[$node->parent])) $parent[$node->parent] = new stdclass();
        $parent[$node->parent]->children[] = $node;
    }

    $nodeList = array();
    foreach($parent as $node)
    {
        foreach($node->children as $children)
        {
            if($children->parent != 0 && !empty($nodeList))
            {
                foreach($nodeList as $firstChildren)
                {
                    if($firstChildren->id == $children->parent) $firstChildren->children[] = $children;
                }
            }
            $nodeList[] = $children;
        }
    }

    return $nodeList;
}

/**
 * Sync book.
 *
 * @param  int    $templateID
 * @param  int    $docID
 * @param  string $templateType
 * @access public
 * @return void
 */
public function syncBook($templateID, $docID, $templateType)
{
    $doc       = $this->getByID($docID);
    $catalogs  = $this->dao->select('*')->from(TABLE_DOC)->where('template')->eq($templateID)->andWhere('deleted')->eq(0)->fetchAll();

    $pairs = array();
    $pairs[$templateID] = $docID;
    foreach($catalogs as $catalog)
    {
        $oldID = $catalog->id;
        $catalog->template = $docID;
        unset($catalog->id);

        $this->dao->insert(TABLE_DOC)->data($catalog)->exec();
        $newID = $this->dao->lastInsertID();
        $this->dao->update(TABLE_DOC)->set('path')->eq(",$newID,")->where('id')->eq($newID)->exec();
        $pairs[$oldID] = $newID;

        $content = $this->dao->select('*')->from(TABLE_DOCCONTENT)->where('doc')->eq($oldID)->fetch();
        unset($content->id);
        $content->doc = $newID;
        $this->dao->insert(TABLE_DOCCONTENT)->data($content)->exec();
    }

    foreach($pairs as $oldID => $newID) $this->dao->update(TABLE_DOC)->set('parent')->eq($newID)->where('parent')->eq($oldID)->andWhere('template')->eq($docID)->exec();

    /* Compute path and parent. */
    $newCatalog = $this->dao->select('*')->from(TABLE_DOC)
        ->where('template')->eq($docID)
        ->andWhere('grade')->ge(1)
        ->fetchAll();

    foreach($newCatalog as $catalog) $this->computePath($catalog->id, $catalog->parent, $catalog->path, TABLE_DOC, $catalog->grade);
}

/**
 * Sync doc module.
 *
 * @param  int    $docLibID
 * @access public
 * @return void
 */
public function syncDocModule($docLibID)
{
    $catalog = $this->dao->select('*')->from(TABLE_MODULE)
        ->where('root')->eq(0)
        ->andWhere('type')->eq('doc')
        ->orderBy('grade_asc')
        ->fetchAll();

    /* Cpoy module and mark new id. */
    $pairs = array();
    foreach($catalog as $module)
    {
        $oldID = $module->id;
        $module->root = $docLibID;
        unset($module->id);
        unset($module->path);
        $this->dao->insert(TABLE_MODULE)->data($module)->exec();
        $newID = $this->dao->lastInsertID();

        $this->dao->update(TABLE_MODULE)->set('path')->eq(",$newID,")->where('id')->eq($newID)->exec();
        $pairs[$oldID] = $newID;
    }

    foreach($pairs as $oldID => $newID) $this->dao->update(TABLE_MODULE)->set('parent')->eq($newID)->where('parent')->eq($oldID)->andWhere('id')->in(array_values($pairs))->exec();

    /* Compute path and parent. */
    $newCatalog = $this->dao->select('*')->from(TABLE_MODULE)
        ->where('root')->eq($docLibID)
        ->andWhere('type')->eq('doc')
        ->andWhere('parent')->ne(0)
        ->fetchAll();

    foreach($newCatalog as $module) $this->computePath($module->id, $module->parent, $module->path, TABLE_MODULE, $module->grade);
}

/**
 * Compute path.
 *
 * @param  int    $moduleID
 * @param  int    $parentID
 * @param  string $path
 * @param  string $table
 * @param  int    $grade
 * @access public
 * @return void
 */
public function computePath($moduleID, $parentID, $path = '', $table = TABLE_MODULE, $grade = 1)
{
    if($grade == 1)
    {
        $this->dao->update($table)->set('path')->eq($path)->where('id')->eq($moduleID)->exec();
        return true;
    }
    $parent = $this->dao->select('*')->from($table)->where('id')->eq($parentID)->fetch();
    $path   = ',' . $parent->id . $path;

    $this->computePath($moduleID, $parent->parent, $path, $table, $parent->grade);
}

/**
 * Get doc structure.
 *
 * @param  int    $bookID
 * @param  string $serial
 * @param  object $object
 * @access public
 * @return string
 */
public function getCmStructure($bookID, $serial, $object)
{
    $structure = '';
    $data = json_decode($object->data);

    if(in_array($object->category, array('SRS', 'URS', 'ERS')))
    {
        $stories = isset($data->story) ? (array)$data->story : array();
        if(empty($stories)) return '';
        foreach($stories as $id => $story) $story->id = $id;
        $moduleIdList = helper::arrayColumn($stories, 'module');

        $modules = $this->dao->select('*')->from(TABLE_MODULE)
            ->where('root')->eq($object->product)
            ->andWhere('id')->in($moduleIdList)
            ->andWhere('type')->eq('story')
            ->andWhere('deleted')->eq(0)
            ->orderBy('`order` asc')
            ->fetchAll();

        /* Get all path in modules. */
        $pathModules = array();
        foreach($modules as $module)
        {
            if($module->grade == 1)
            {
                $pathModules[$module->id] = $module->id;
            }
            else
            {
                $moduleBases = explode(',', trim($module->path, ','));
                $pathModules += array_combine($moduleBases, $moduleBases);
            }
        }

        $modules = $this->dao->select('*')->from(TABLE_MODULE)
            ->where('root')->eq($object->product)
            ->andWhere('id')->in($pathModules)
            ->andWhere('type')->eq('story')
            ->andWhere('grade')->eq('1')
            ->andWhere('deleted')->eq(0)
            ->orderBy('`order` asc')
            ->fetchAll();

        $serials = $this->computeModuleSN($object->product, $serial, array_keys($stories));

        $structure .= '<ul>';
        foreach($stories as $id => $story)
        {
            if($story->module == 0)
            {
                $structure .= "<li class='story'><span class='item' title='{$story->title}'>" . zget($serials, "s$story->id", '') . ' ' . html::a(helper::createLink('story', 'storyView', "objectID=$story->id&version=$story->version&param=$object->project") . "#app={$this->app->tab}", $story->title) . "</li>";
                unset($stories[$id]);
            }
        }

        $structure .= '</ul>';
        $structure .= $this->getStoryStructure((array)$stories, $modules, $object, $serials, $pathModules);
    }
    elseif(in_array($object->category, array('HLDS', 'DDS', 'DBDS', 'ADS')))
    {
        $designs = (array)$data->design;
        if(empty($designs)) return;

        $structure .= '<ul>';
        foreach($designs as $id => $design)
        {
            if(empty($id)) continue;
            $structure .= "<li class='design' data-id={$id}><span class='item' title='{$design->name}'>" . html::a(helper::createLink('design', 'view', "id=$id"), "#$id" . '：' . $design->name, '', "data-toggle='modal' data-size='lg'") . "</span></li>";
        }

        $structure .= '</ul>';
    }
    elseif(in_array($object->category, array('ITTC', 'STTC')))
    {
        $cases = (array)$data->case;
        if(empty($cases)) return;

        $moduleIdList = helper::arrayColumn($cases, 'module');

        $stage   = $object->category == 'STTC' ? 'system' : 'intergrate';
        $modules = $this->dao->select('*')->from(TABLE_MODULE)
            ->where('root')->eq($object->product)
            ->andWhere('type')->in('story,case')
            ->andWhere('id')->in($moduleIdList)
            ->andWhere('deleted')->eq(0)
            ->orderBy('`order` asc')
            ->fetchAll();

        /* Get all path in modules. */
        $pathModules = array();
        foreach($modules as $module)
        {
            if($module->grade == 1)
            {
                $pathModules[$module->id] = $module->id;
            }
            else
            {
                $moduleBases = explode(',', trim($module->path, ','));
                $pathModules += array_combine($moduleBases, $moduleBases);
            }
        }

        $modules = $this->dao->select('*')->from(TABLE_MODULE)
            ->where('root')->eq($object->product)
            ->andWhere('id')->in($pathModules)
            ->andWhere('type')->in('story,case')
            ->andWhere('grade')->eq('1')
            ->andWhere('deleted')->eq(0)
            ->orderBy('`order` asc')
            ->fetchAll();

        $structure .= '<ul>';
        foreach($cases as $id => $case)
        {
            if($case->module == 0)
            {
                $structure .= "<li class='case' data-id={$id}><span class='item' title='{$case->title}'>" . html::a(helper::createLink('testcase', 'view', "caseID=$id"), "#$id" . '：' . $case->title, '', "data-toggle='modal' data-size='lg'") . "</span></li>";
                unset($cases->$id);
            }
        }

        $structure .= '</ul>';
        $structure .= $this->getCaseStructure($modules, $cases, $pathModules);
    }

    return $structure;
}

/**
 * Get story structure.
 *
 * @param  array  $stories
 * @param  object $modules
 * @param  object $book
 * @param  string $serials
 * @param  array  $moduleIDList
 * @access public
 * @return string
 */
public function getStoryStructure($stories, $modules, $book, $serials, $moduleIDList)
{
    $html  = '';
    $html .= '<ul class="object">';
    foreach($modules as $module)
    {
        if(!in_array($module->id, $moduleIDList)) continue;

        $html .= "<li class='module'>" . "<span class='item'>" . $serials[$module->id] . '</span> ' . $module->name;
        $html .= '<ul>';
        foreach($stories as $id => $story)
        {
            if($story->module == $module->id)
            {
                $html .= "<li class='story' data-id={$story->id}>" . "<span class='item'>" . zget($serials, "s$story->id", '') . ' ' . html::a(helper::createLink('story', 'storyView', "objectID=$story->id&version=$story->version&param={$this->session->project}") . "#app={$this->app->tab}", $story->title) . '</span>' . "</li>";
                unset($stories[$id]);
            }
        }

        $html .= '</ul>';

        $childModules = $this->getChildModules($module->id);
        if($childModules) $html .= $this->getStoryStructure($stories, $childModules, $book, $serials, $moduleIDList);
        $html .= '</li>';
    }

    $html .= '</ul>';

    return $html;
}

/**
 * Get design structure.
 *
 * @param  object $book
 * @param  string $serial
 * @access public
 * @return string
 */
public function getDesignStructure($book, $serial)
{
    $designs = $this->dao->select('*')->from(TABLE_DESIGN)
        ->where('deleted')->eq(0)
        ->andWhere('product')->eq($book->product)
        ->andWhere('type')->eq($book->templateType)
        ->fetchAll('id');

    $i = 1;
    $structure  = '';
    $structure .= '<ul class="object">';
    foreach($designs as $id => $design)
    {
        $structure .= "<li class='design' data-id={$design->id}>" . "<span class='item'>" . $serial . '.' . $i . ' ' . html::a(helper::createLink('design', 'view', "objectID=$design->id"), $design->name, '', "data-toggle='modal' data-size='lg'") . "</span></li>";
        $i ++;
    }

    $structure .= '</ul>';

    return $structure;
}

/**
 * Get case structure.
 *
 * @param  object $modules
 * @param  object $cases
 * @param  array  $moduleIDList
 * @access public
 * @return string
 */
public function getCaseStructure($modules, $cases, $moduleIDList)
{
    $tree  = '';
    $tree .= '<ul>';
    foreach($modules as $module)
    {
        if(!in_array($module->id, $moduleIDList)) continue;

        $tree .= "<li class='module'>" . $module->name;
        $tree .= '<ul>';
        foreach($cases as $id => $case)
        {
            if($case->module == $module->id)
            {
                $tree .= "<li class='case' data-id={$case->id}><span class='item' title='{$case->title}'>" . html::a(helper::createLink('testcase', 'view', "caseID=$id"), "#$id" . '：' . $case->title, '', "data-toggle='modal' data-size='lg'") . "</span></li>";
                unset($cases->$id);
            }
        }
        $tree .= '</ul>';

        $childModules = $this->getChildModules($module->id);
        if($childModules) $tree .= $this->getCaseStructure($childModules, $cases, $moduleIDList);
        $tree .= '</li>';
    }

    $tree .= '</ul>';
    return $tree;
}

/**
 * Compute module SN.
 *
 * @param  int    $productID
 * @param  string $parentSerial
 * @param  array  $storyIdList
 * @access public
 * @return array
 */
public function computeModuleSN($productID, $parentSerial, $storyIdList = array())
{
    /* Get all children of the startNode. */
    $modules = $this->dao->select('id, parent, `order`, path')->from(TABLE_MODULE)
        ->where('root')->eq($productID)
        ->andWhere('deleted')->eq(0)
        ->andWhere('type')->eq('story')
        ->orderBy('grade, `order`, id')
        ->fetchAll('id');

    $stories = $this->dao->select('id, module')->from(TABLE_STORY)
        ->where('product')->eq($productID)
        ->andWhere('type')->eq('story')
        ->beginIF(!empty($storyIdList))->andWhere('id')->in($storyIdList)->fi()
        ->orderBy('id_desc')
        ->fetchAll('id');

    /* Push story to module array. */
    foreach($modules as $moduleID => $module)
    {
        foreach($stories as $storyID => $story)
        {
            $array    = array();
            $data     = new stdclass();
            $key      = 's' . $storyID;
            $data->id = $key;

            if($story->module == 0)
            {
                $data->parent = 0;
                $data->path   = ',' . $key . ',';
                $array[$key]  = $data;
                $modules      = $array + $modules;
                continue;
            }

            if($story->module == $moduleID)
            {
                $data->parent = $moduleID;
                $data->path   = $module->path . $key . ',';
                $array[$key]  = $data;
                $modules      = $array + $modules;
            }
        }
    }

    /* Group them by their parent. */
    $groupedModules = array();
    foreach($modules as $module) $groupedModules[$module->parent][$module->id] = $module;

    $serials = array();
    foreach($modules as $module)
    {
        $path = explode(',', $module->path);

        $serial = '';
        foreach($path as $moduleID)
        {
            if(!$moduleID) continue;

            /* Compute the serial. */
            if(isset($modules[$moduleID]))
            {
                $parentID = $modules[$moduleID]->parent;
                $brothers = $groupedModules[$parentID];
                $serial  .= array_search($moduleID, array_keys($brothers)) + 1 . '.';
            }
        }

        $serials[$module->id] = $parentSerial . '.' . rtrim($serial, '.');
    }

    return $serials;
}

/**
 * Import doc to asset lib.
 *
 * @param  array  $docIdList
 * @param  string $assetType
 * @access public
 * @return bool
 */
public function importToLib($docIdList = array(), $assetType = 'practice')
{
    $data = fixer::input('post')->get();
    if(empty($data->lib))
    {
        $libName = $assetType == 'practice' ? $this->lang->doc->practiceLib : $this->lang->doc->componentLib;
        dao::$errors['lib'] = sprintf($this->lang->error->notempty, $libName);
        return false;
    }

    $docs = $this->getByIdList($docIdList);
    $importedDocs = $this->dao->select('`from`,`fromVersion`')->from(TABLE_DOC)
        ->where('assetlib')->eq($data->lib)
        ->andWhere('`from`')->in($docIdList)
        ->fetchGroup('from');

    if(is_numeric($docIdList) and isset($importedDocs[$docIdList]))
    {
        dao::$errors['message'] = $assetType == 'practice' ? $this->lang->doc->isExistPracticeLib : $this->lang->doc->isExistComponentLib;
        return false;
    }

    /* Remove duplicate doc and save file id list. */
    $fileIDList = array();
    foreach($docs as $doc)
    {
        $fileIDList = array_merge($fileIDList, explode(',', $doc->files));
        if(isset($importedDocs[$doc->docID]))
        {
            foreach($importedDocs[$doc->docID] as $improtedDoc)
            {
                if($improtedDoc->fromVersion == $doc->version) unset($docs[$doc->docID]);
            }
        }
    }
    $fileIDList = array_unique($fileIDList);
    $files      = $this->dao->select('*')->from(TABLE_FILE)->where('id')->in($fileIDList)->andWhere('deleted')->eq(0)->fetchAll('id');

    $now           = helper::now();
    $today         = helper::today();
    $hasApprovePiv = false;
    if($assetType == 'practice' and (common::hasPriv('assetlib', 'approvePractice') or common::hasPriv('assetlib', 'batchApprovePractice'))) $hasApprovePiv = true;
    if($assetType == 'component' and (common::hasPriv('assetlib', 'approveComponent') or common::hasPriv('assetlib', 'batchApproveComponent'))) $hasApprovePiv = true;
    $this->loadModel('action');

    /* Create doc to asset lib. */
    $now = helper::now();
    $this->loadModel('action');
    foreach($docs as $doc)
    {
        $assetDoc = new stdclass();
        $assetDoc->title        = $doc->title;
        $assetDoc->keywords     = $doc->keywords;
        $assetDoc->type         = $doc->docType;
        $assetDoc->lib          = 0;
        $assetDoc->version      = 1;
        $assetDoc->acl          = 'open';
        $assetDoc->status       = $hasApprovePiv ? 'active' : 'draft';
        $assetDoc->assetLib     = $data->lib;
        $assetDoc->assetLibType = $assetType;
        $assetDoc->from         = $doc->docID;
        $assetDoc->fromVersion  = $doc->version;
        $assetDoc->addedBy      = $this->app->user->account;
        $assetDoc->addedDate    = $now;
        if(!empty($data->assignedTo)) $assetDoc->assignedTo = $data->assignedTo;
        if($hasApprovePiv)
        {
            $assetDoc->assignedTo   = $this->app->user->account;
            $assetDoc->approvedDate = $today;
        }

        $this->dao->insert(TABLE_DOC)->data($assetDoc)->exec();
        $assetDocID = $this->dao->lastInsertID();

        if(!dao::isError())
        {
            /* Copy file info. */
            $docFiles = explode(',', $doc->files);
            $newFiles = '';
            foreach($docFiles as $fileID)
            {
                if(isset($files[$fileID]))
                {
                    $file = $files[$fileID];
                    unset($file->id);
                    $file->objectID  = $assetDocID;
                    $file->addedBy   = $this->app->user->account;
                    $file->addedDate = helper::now();
                    $file->downloads = 0;

                    $this->dao->insert(TABLE_FILE)->data($file)->exec();
                    $copyFileID = $this->dao->lastInsertID();
                    $newFiles   = empty($newFiles) ? $copyFileID : "$newFiles,$copyFileID";
                }
            }

            $content = new stdclass();
            $content->doc     = $assetDocID;
            $content->title   = $doc->title;
            $content->digest  = $doc->digest;
            $content->content = $doc->content;
            $content->files   = $newFiles;
            $content->type    = $doc->contentType;
            $content->version = 1;

            $this->dao->insert(TABLE_DOCCONTENT)->data($content)->exec();

            $action = $assetType == 'practice' ? 'import2PracticeLib' : 'import2ComponentLib';
            $this->action->create('doc', $assetDocID, $action);
        }
    }

    return true;
}

/**
 * Get template content by doc id.
 *
 * @param  int    $id
 * @access public
 * @return int
 */
public function getTemplateContentByID($id)
{
    return $this->dao->select('*')->from(TABLE_DOCCONTENT)->where('doc')->eq($id)->orderBy('version_desc')->fetch();
}

/**
 * @param int $templateID
 * @param int $docID
 */
public function getTemplateContent($templateID, $docID = 0)
{
    return $this->loadExtension('zentaomax')->getTemplateContent($templateID, $docID);
}

public function getProductStoryTableCols()
{
    return $this->loadExtension('zentaomax')->getProductStoryTableCols();
}

/**
 * @param mixed[] $product
 * @param string $searchTab
 */
public function getProductStoryTableData($product, $searchTab)
{
    return $this->loadExtension('zentaomax')->getProductStoryTableData($product, $searchTab);
}

public function getProjectStoryTableCols()
{
    return $this->loadExtension('zentaomax')->getProductStoryTableCols();
}

/**
 * @param mixed[] $project
 * @param mixed[] $product
 * @param string $searchTab
 */
public function getProjectStoryTableData($project, $product, $searchTab)
{
    return $this->loadExtension('zentaomax')->getProjectStoryTableData($project, $product, $searchTab);
}

public function getExecutionStoryTableCols()
{
    return $this->loadExtension('zentaomax')->getExecutionStoryTableCols();
}

/**
 * @param mixed[] $execution
 * @param string $searchTab
 */
public function getExecutionStoryTableData($execution, $searchTab)
{
    return $this->loadExtension('zentaomax')->getExecutionStoryTableData($execution, $searchTab);
}

public function getTaskTableCols()
{
    return $this->loadExtension('zentaomax')->getTaskTableCols();
}

/**
 * @param mixed[] $execution
 * @param string $searchTab
 */
public function getTaskTableData($execution, $searchTab)
{
    return $this->loadExtension('zentaomax')->getTaskTableData($execution, $searchTab);
}

public function getBugTableCols()
{
    return $this->loadExtension('zentaomax')->getBugTableCols();
}

/**
 * @param mixed[] $product
 * @param string $searchTab
 */
public function getBugTableData($product, $searchTab)
{
    return $this->loadExtension('zentaomax')->getBugTableData($product, $searchTab);
}

public function getProductCaseTableCols()
{
    return $this->loadExtension('zentaomax')->getProductCaseTableCols();
}

public function getProjectCaseTableCols()
{
    return $this->loadExtension('zentaomax')->getProductCaseTableCols();
}

/**
 * @param mixed[] $product
 * @param string $searchTab
 * @param string $caseStage
 */
public function getProductCaseTableData($product, $searchTab, $caseStage = '')
{
    if(empty(array_filter($product))) return array('result' => 'fail', 'message' => array('product' => sprintf($this->lang->error->notempty, $this->lang->doc->selectProduct)));
    return $this->loadExtension('zentaomax')->getProductCaseTableData($product, $searchTab, $caseStage);
}

/**
 * @param mixed[] $project
 * @param mixed[] $product
 * @param string $searchTab
 * @param string $caseStage
 */
public function getProjectCaseTableData($project, $product, $searchTab, $caseStage = '')
{
    if(empty(array_filter($project))) return array('result' => 'fail', 'message' => array('project' => sprintf($this->lang->error->notempty, $this->lang->doc->selectProject)));
    return $this->loadExtension('zentaomax')->getProductCaseTableData($product, $searchTab, $caseStage, $project);
}

public function getDesignTableCols()
{
    return $this->loadExtension('zentaomax')->getDesignTableCols();
}

/**
 * @param mixed[] $project
 * @param mixed[] $product
 * @param string $type
 */
public function getDesignTableData($project, $product, $type)
{
    return $this->loadExtension('zentaomax')->getDesignTableData($project, $product, $type);
}

/**
 * @param int $project
 */
public function getGanttData($project)
{
    return $this->loadExtension('zentaomax')->getGanttData($project);
}

public function getGanttFields()
{
    return $this->loadExtension('zentaomax')->getGanttFields();
}
