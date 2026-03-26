<?php
class zentaobizFile extends fileModel
{
    public function convertOffice($file, $type = '')
    {
        if(!$this->config->file->libreOfficeTurnon) return false;

        $sofficePath = $this->config->file->sofficePath;
        if(empty($sofficePath) or !file_exists($sofficePath)) return false;

        $fileName = basename($file->realPath);
        if(($position = strpos($fileName, ".{$file->extension}")) !== false) $fileName = substr($fileName, 0, $position);
        $convertPath = $this->app->getCacheRoot() . 'convertoffice/' . $fileName . $file->size;
        if(!is_dir($convertPath)) mkdir($convertPath, 0777, true);
        if(empty($type)) $type = strpos('xlsx|xls', $file->extension) !== false ? 'html' : 'pdf';
        $convertedFile = $convertPath . '/' . $fileName . '.' . $type;
        if(file_exists($convertedFile)) return  $convertedFile;

        $filterType = $type;
        if($type == 'html' and strpos('xlsx|xls', $file->extension) !== false) $filterType = 'html:"XHTML Calc File:UTF8"';
        if($type == 'txt') $filterType = 'txt:"Text (encoded):UTF8"';

        set_time_limit(0);
        session_write_close();

        $lockFile = dirname($convertPath) . '/lock';
        if(file_exists($lockFile) and (time() - filemtime($lockFile)) <= 60 * 30) return false;

        touch($lockFile);
        ob_start();
        if(strtoupper(PHP_OS) == 'WINNT')
        {
            $cmd = "SET HOME=" . dirname($convertPath) . "\n $sofficePath --invisible --headless --convert-to $filterType --outdir $convertPath {$file->realPath} 2>&1";
            $batFile = $convertPath . '/' . $fileName . '.bat';
            file_put_contents($batFile, $cmd);
            echo system("start /b $batFile", $result);
            unlink($batFile);
        }
        else
        {
            echo system("HOME=" . dirname($convertPath) . ";export HOME;$sofficePath --invisible --headless --convert-to $filterType --outdir $convertPath {$file->realPath} 2>&1", $return);
        }
        $message = ob_get_contents();
        ob_end_clean();
        unlink($lockFile);

        if(!file_exists($convertedFile))
        {
            $this->app->saveError(E_USER_WARNING, $message, __FILE__, __LINE__);
            return false;
        }

        if($type == 'html')
        {
            $handle = fopen($convertedFile, "r");
            $processedLines = '';
            if($handle)
            {
                while(!feof($handle))
                {
                    $line = fgets($handle);
                    if(strpos($line, '</head><body') !== false) $line = preg_replace('/<\/head><body [^>]*>.*<table/Ui', "</head><body><table", $line);
                    $processedLines .= $line;
                }
                fclose($handle);
            }
            if($processedLines)file_put_contents($convertedFile, $processedLines);
        }

        return $convertedFile;
    }

    public function getCollaboraDiscovery($collaboraPath = '')
    {
        if(empty($collaboraPath) and !empty($this->config->file->collaboraPath)) $collaboraPath = $this->config->file->collaboraPath;
        if(empty($collaboraPath)) return array();

        $context   = stream_context_create(array("ssl" => array("verify_peer" => false, "verify_peer_name" => false)));
        $discovery = file_get_contents(trim($collaboraPath, '/') . '/hosting/discovery', false, $context);
        preg_match_all('|<action(.+)/>|', $discovery, $results);

        $files = array();
        foreach($results[1] as $key => $action)
        {
            preg_match_all('|ext="([^"]*)"|', $action, $output);
            if($output[1]) $extension = $output[1][0];
            if(empty($extension)) continue;

            preg_match_all('|name="([^"]*)"|', $action, $output);
            if($output[1]) $name = $output[1][0];

            preg_match_all('|urlsrc="([^"]*)"|', $action, $output);
            if($output[1]) $urlsrc = $output[1][0];

            $files[$extension]['action'] = $name;
            $files[$extension]['urlsrc'] = $urlsrc;
        }
        return $files;
    }

    public function getFileInfo4Wopi($file, $canEdit = false)
    {
        $fileInfo = array();
        if(file_exists($file->realPath))
        {
            $contents = file_get_contents($file->realPath);
            $SHA256   = base64_encode(hash('sha256', $contents, true));

            $fileName = $file->title;
            if(!preg_match("/\.{$file->extension}$/", $fileName)) $fileName .= '.' . $file->extension;

            $fileInfo['BaseFileName']    = $fileName;
            $fileInfo['OwnerId']         = $file->addedBy;
            $fileInfo['UserId']          = $this->app->user->account;
            $fileInfo['UserFriendlyName']= $this->app->user->realname;
            $fileInfo['Size']            = filesize($file->realPath);
            $fileInfo['SHA256']          = $SHA256;
            $fileInfo['UserCanWrite']    = $canEdit;
            $fileInfo['LastModifiedTime']= date('Y-m-d\TH:i:s\Z', filemtime($file->realPath));
        }

        return json_encode($fileInfo);
    }

    /**
     * Get export libs.
     *
     * @access public
     * @return array
     */
    public function getExportLibs()
    {
        $this->loadModel('doc');
        $libs = array();
        if($this->post->range == 'listAll' or $this->post->range == 'selected')
        {
            $lib = $this->dao->select('*')->from(TABLE_DOCLIB)->where('deleted')->eq(0)->andWhere('id')->eq($this->post->libID)->fetch();
            if($lib->type == 'custom' && $lib->parent == 0)
            {
                $libs = $this->dao->select('*')->from(TABLE_DOCLIB)->where('deleted')->eq(0)->andWhere('parent')->eq($lib->id)->fetchAll('id');
            }
            else
            {
                $libs[$lib->id] = $lib;
            }
        }
        elseif($this->post->kind != 'api')
        {
            $libs = $this->dao->select('*')->from(TABLE_DOCLIB)->where('deleted')->eq(0)
                ->beginIF($this->post->range == 'productAll')->andWhere('product')->eq($this->post->productID)->andWhere('type')->eq('product')->fi()
                ->beginIF($this->post->range == 'projectAll')->andWhere('project')->eq($this->post->projectID)->andWhere('type')->eq('project')->fi()
                ->beginIF($this->post->range == 'executionAll')->andWhere('execution')->eq($this->post->executionID)->andWhere('type')->eq('execution')->fi()
                ->fetchAll('id');

            if($this->post->range == 'projectAll' and $this->post->kind != 'api')
            {
                $executions = $this->loadModel('execution')->getPairs($this->post->projectID, 'sprint,stage', 'multiple,leaf,noprefix');
                if($executions) $libs = arrayUnion($libs, $this->dao->select('*')->from(TABLE_DOCLIB)->where('deleted')->eq(0)->andWhere('execution')->in(array_keys($executions))->andWhere('type')->eq('execution')->orderBy('execution, `order`_desc')->fetchAll('id'));
            }
        }

        if(strpos('productAll|projectAll|executionAll|noLinkAll', $this->post->range) !== false)
        {
            $extraLibs = $this->dao->select('*')->from(TABLE_DOCLIB)->where('deleted')->eq(0)
                ->beginIF($this->post->range == 'productAll')->andWhere('product')->eq($this->post->productID)->andWhere('type')->eq('api')->fi()
                ->beginIF($this->post->range == 'projectAll')->andWhere('project')->eq($this->post->projectID)->andWhere('type')->eq('api')->fi()
                ->beginIF($this->post->range == 'executionAll')->andWhere('execution')->eq($this->post->executionID)->andWhere('type')->eq('api')->fi()
                ->beginIF($this->post->range == 'noLinkAll')->andWhere('product')->eq(0)->andWhere('project')->eq(0)->andWhere('execution')->eq(0)->andWhere('type')->eq('api')->fi()
                ->orderBy('`order`,id_desc')
                ->fetchAll('id');
            $libs = arrayUnion($libs, $extraLibs);
        }

        $hasPrivLibs = array();
        foreach($libs as $libID => $lib)
        {
            if($this->doc->checkPrivLib($lib)) $hasPrivLibs[$libID] = $lib;
        }

        return $hasPrivLibs;
    }

    /**
     * Process Libs for set group.
     *
     * @param  array  $libs
     * @access public
     * @return array
     */
    public function processLibs($libs)
    {
        if(strpos('productAll|projectAll|executionAll|noLinkAll', $this->post->range) === false) return $libs;

        $productIdList = $projectIdList = $executionIdList = array();
        foreach($libs as $lib)
        {
            if($lib->product)   $productIdList[$lib->product]     = $lib->product;
            if($lib->project)   $projectIdList[$lib->project]     = $lib->project;
            if($lib->execution) $executionIdList[$lib->execution] = $lib->execution;
        }

        $products   = $this->dao->select('id,name')->from(TABLE_PRODUCT)->where('id')->in($productIdList)->fetchPairs('id', 'name');
        $projects   = $this->dao->select('id,name')->from(TABLE_PROJECT)->where('id')->in($projectIdList)->fetchPairs('id', 'name');
        $executions = $this->dao->select('id,name')->from(TABLE_EXECUTION)->where('id')->in($executionIdList)->fetchPairs('id', 'name');

        $libGroups = array();
        foreach($libs as $lib)
        {
            $objectID   = 0;
            $objectList = array();
            if($lib->type == 'product')
            {
                $objectID   = $lib->product;
                $objectList = $products;
            }
            if($lib->type == 'project')
            {
                $objectID   = $lib->project;
                $objectList = $projects;
            }
            if($lib->type == 'execution')
            {
                $objectID   = $lib->execution;
                $objectList = $executions;
            }

            if($objectID)
            {
                if(!isset($libGroups[$objectID]))
                {
                    $libGroups[$objectID]['id']       = $objectID;
                    $libGroups[$objectID]['name']     = zget($objectList, $objectID, '');
                    $libGroups[$objectID]['children'] = array();
                }
                $libGroups[$objectID]['children'][] = $lib;
            }
            else
            {
                $libGroups[$lib->id] = $lib;
            }
        }

        return $libGroups;
    }

    /**
     * Get for lib export data.
     *
     * @param  int    $libID
     * @access public
     * @return array
     */
    public function getDocExportData($libID)
    {
        if($this->post->range == 'selected')
        {
            $docID = $this->post->docID;
            if(empty($docID)) $this->post->docID = '0';

            $tops = $this->dao->select('t1.id,t1.module,t1.lib,t1.path,t1.title,t1.type,0 as parent,0 as grade,t1.`order`,t2.content,t2.files,t2.type as contentType,t2.rawContent')->from(TABLE_DOC)->alias('t1')
                ->leftJoin(TABLE_DOCCONTENT)->alias('t2')->on('t1.id=t2.doc && t1.version=t2.version')
                ->leftJoin(TABLE_MODULE)->alias('t3')->on('t1.module=t3.id')
                ->where('t1.type')->in($this->config->file->docFileType)
                ->andWhere('t1.deleted')->eq('0')
                ->andWhere('t1.id')->in($this->post->docID)
                ->orderBy("grade,`order`")
                ->fetchAll('id');
            $articles[0] = $tops;

            $files = implode(',', helper::arrayColumn($tops, 'files'));
            $files = implode(',', array_unique(explode(',', $files)));
            $files = $this->dao->select('id,pathname,title,extension')->from(TABLE_FILE)->where('id')->in($files)->fetchAll('id');

            return array('tops' => $tops, 'chapters' => array(), 'articles' => $articles, 'files' => $files);
        }

        $this->loadModel('doc');
        $moduleID = (int)$this->post->module;
        if($moduleID)
        {
            $module = $this->dao->select('id,root as lib,path,name as title,type,parent,grade,`order`')->from(TABLE_MODULE)->where('id')->eq($moduleID)->fetch();
            $tops   = array($module);
        }
        else
        {
            $tops = $this->dao->select('id,root as lib,path,name as title,type,parent,grade,`order`')->from(TABLE_MODULE)
                ->where('root')->eq($libID)
                ->andWhere('type')->eq('doc')
                ->andWhere('deleted')->eq('0')
                ->andWhere('parent')->eq(0)
                ->orderBy("grade,`order`")
                ->fetchAll();

            $docTops = $this->dao->select('id,lib,path,title,type,status,parent,grade,addedBy,acl,users,`groups`,`order`,assetLibType')->from(TABLE_DOC)
                ->where('lib')->eq($libID)
                ->andWhere('module')->eq($moduleID)
                ->andWhere('deleted')->eq('0')
                ->beginIF($this->post->range == 'listAll' && $this->session->docQueryCondition)->andWhere($this->session->docQueryCondition)->fi()
                ->orderBy("grade,`order`")
                ->fetchAll();

            foreach($docTops as $index => $doc) if(!$this->doc->checkPrivDoc($doc)) unset($docTops[$index]);
            if(!empty($docTops)) $tops = array_merge($docTops, $tops);
        }

        $moduleDB = $this->dao->select('id, root as lib, path, name as title, type, parent, grade,`order`')->from(TABLE_MODULE)
            ->where('root')->eq($libID)
            ->andWhere('type')->eq('doc')
            ->andWhere('deleted')->eq('0')
            ->orderBy("grade,`order`")
            ->fetchGroup('parent');

        $docDB = $this->dao->select('id,lib,module,path,title,type,status,parent,grade,addedBy,acl,users,`groups`,`order`,assetLibType')->from(TABLE_DOC)
            ->where('lib')->eq($libID)
            ->andWhere('type')->in($this->config->file->docFileType)
            ->beginIF($this->post->range == 'listAll' && $this->session->docQueryCondition)->andWhere($this->session->docQueryCondition)->fi()
            ->andWhere('deleted')->eq('0')
            ->orderBy("status, id_desc")
            ->fetchAll('id');

        /* Check doc priv. */
        foreach($docDB as $docID => $doc) if(!$this->doc->checkPrivDoc($doc)) unset($docDB[$docID]);
        $docIdList = array_keys($docDB);

        /* Make data. */
        foreach($moduleDB as $root)
        {
            $firstRoot = reset($root);
            foreach($docDB as $docID => $doc)
            {
                if($doc->module == $firstRoot->parent)
                {
                    $tmp = new StdClass();
                    $tmp->id     = $doc->id;
                    $tmp->lib    = $doc->lib;
                    $tmp->title  = $doc->title;
                    $tmp->type   = $doc->type;
                    $tmp->parent = $doc->module;
                    if(!isset($moduleDB[$firstRoot->parent])) $moduleDB[$firstRoot->parent] = array();
                    array_unshift($moduleDB[$firstRoot->parent], $tmp);
                    unset($docDB[$docID]);
                }
            }

            foreach($root as $subRoot)
            {
                foreach($docDB as $subDocID => $subDoc)
                {
                    if($subDoc->module == $subRoot->id)
                    {
                        $subtmp = new StdClass();
                        $subtmp->id     = $subDoc->id;
                        $subtmp->lib    = $subDoc->lib;
                        $subtmp->title  = $subDoc->title;
                        $subtmp->type   = $subDoc->type;
                        $subtmp->parent = $subDoc->module;
                        if(!isset($moduleDB[$subRoot->id])) $moduleDB[$subRoot->id] = array();
                        array_unshift($moduleDB[$subRoot->id], $subtmp);
                        unset($docDB[$subDocID]);
                    }
                }
            }
        }

        $chapters = $moduleDB;
        $articles = $this->dao->select('t1.id,t1.module,t1.lib,t1.path,t1.title,t1.type,t1.parent,t1.grade,t1.`order`,t2.content,t2.files,t2.type as contentType,t2.rawContent')->from(TABLE_DOC)->alias('t1')
            ->leftJoin(TABLE_DOCCONTENT)->alias('t2')->on('t1.id=t2.doc')
            ->leftJoin(TABLE_MODULE)->alias('t3')->on('t1.module=t3.id')
            ->where('t1.lib')->eq($libID)
            ->andWhere('t1.id')->in($docIdList)
            ->andWhere('t1.version=t2.version')
            ->fetchGroup('module', 'id');

        $files = '';
        foreach($articles as $parent => $subArticles)
        {
            foreach($subArticles as $articleID => $article) $files .= $article->files . ',';
        }
        foreach($tops as $docID => $doc)
        {
            if(in_array($doc->type, array('text', 'word', 'ppt', 'excel', 'url')) and isset($articles[$moduleID][$doc->id])) $tops[$docID] = $articles[$moduleID][$doc->id];
        }

        $files = array_unique(explode(',', $files));
        $files = $this->dao->select('id,pathname,title,extension')->from(TABLE_FILE)->where('id')->in($files)->fetchAll('id');

        return array('tops' => $tops, 'chapters' => $chapters, 'articles' => $articles, 'files' => $files);
    }

    /**
     * Get API export data.
     *
     * @param  int    $libID
     * @access public
     * @return array
     */
    public function getAPIExportData($libID)
    {
        $this->loadModel('api');

        $moduleID = (int)$this->post->module;
        if($this->post->release > 0)
        {
            $release = $this->api->getRelease(0, 'byId', $this->post->release);
            $modules = isset($release->snap['modules']) ? $release->snap['modules'] : array();
            $apis    = isset($release->snap['apis'])    ? $release->snap['apis']    : array();

            $tops = array();
            foreach($modules as $module)
            {
                $module = (object)$module;
                $module->lib   = $module->root;
                $module->title = $module->name;
                if($moduleID and $module->id == $moduleID)
                {
                    $tops[$module->order] = $module;
                    break;
                }
                if(empty($moduleID) and $module->parent == 0 and $module->deleted == 0) $tops[$module->order] = $module;
            }
            ksort($tops);
            $tops = array_values($tops);

            $where     = "lib = $libID";
            $condition = array();
            foreach($apis as $api) $condition[] = "(spec.doc = {$api['id']} AND spec.version = {$api['version']})";
            if($condition) $where .= ' AND (' . implode(' OR ', $condition) . ')';

            $articles = $this->dao->select('api.lib, spec.*, api.id')->from(TABLE_API)->alias('api')
                ->leftJoin(TABLE_API_SPEC)->alias('spec')->on('api.id = spec.doc')
                ->where($where)
                ->fetchGroup('module', 'id');

        }
        else
        {
            if($moduleID)
            {
                $module = $this->dao->select('id,root as lib,path,name as title,type,parent,grade,`order`')->from(TABLE_MODULE)->where('id')->eq($moduleID)->fetch();
                $tops   = array($module);
            }
            else
            {
                $tops = $this->dao->select('id,root as lib,path,name as title,type,parent,grade,`order`')->from(TABLE_MODULE)
                    ->where('root')->eq($libID)
                    ->andWhere('type')->eq('api')
                    ->andWhere('deleted')->eq('0')
                    ->andWhere('parent')->eq(0)
                    ->orderBy("grade,`order`")
                    ->fetchAll();
            }

            $articles = $this->dao->select('*')->from(TABLE_API)->where('lib')->eq($libID)->andWhere('deleted')->eq(0)->fetchGroup('module', 'id');
        }

        $chapters = $this->dao->select('id, root as lib, path, name as title, type, parent, grade, `order`')->from(TABLE_MODULE)
            ->where('root')->eq($libID)
            ->andWhere('type')->eq('api')
            ->andWhere('deleted')->eq('0')
            ->orderBy("grade,`order`")
            ->fetchGroup('parent');

        /* Make data. */
        foreach($chapters as $parent => $modules)
        {
            foreach($modules as $module)
            {
                if(!isset($articles[$module->id])) continue;

                foreach($articles[$module->id] as $articleID => $article)
                {
                    $article = $this->api->buildExportAPI($article);
                    $article->type   = 'article';
                    $article->parent = $module->id;

                    $chapters[$module->id][] = $article;
                }
            }
        }

        if(isset($articles[0]))
        {
            foreach($articles[0] as $articleID => $article)
            {
                $article = $this->api->buildExportAPI($article);
                $article->type   = 'article';
                $article->parent = 0;

                $chapters[0][] = $article;
            }
        }

        if(empty($moduleID) and isset($articles[0])) $tops = array_merge($tops, $articles[0]);

        return array('tops' => $tops, 'chapters' => $chapters, 'articles' => $articles, 'files' => array());
    }

    /**
     * Get Wiki export data.
     *
     * @param  int    $libID
     * @access public
     * @return array
     */
    public function getWikiExportData($libID)
    {
        if($this->post->range == 'selected')
        {
            $tops = $this->dao->select('t1.id,t1.lib,t1.path,t1.title,t1.type,0 as parent,0 as grade,t1.`order`,t2.content,t2.files,t2.type as contentType')->from(TABLE_DOC)->alias('t1')
                ->leftJoin(TABLE_DOCCONTENT)->alias('t2')->on('t1.id=t2.doc && t1.version=t2.version')
                ->where('t1.deleted')->eq('0')
                ->andWhere('t1.type')->eq('article')
                ->andWhere('t1.id')->in($this->post->docID)
                ->orderBy("grade,`order`")
                ->fetchAll('id');
            $articles[0] = $tops;

            $files = array_unique(helper::arrayColumn($tops, 'files'));
            $files = $this->dao->select('id,pathname,title,extension')->from(TABLE_FILE)->where('id')->in($files)->fetchAll('id');

            return array('tops' => $tops, 'chapters' => array(), 'articles' => $articles, 'files' => $files);
        }

        $moduleID = (int)$this->post->module;
        if($moduleID)
        {
            $module = $this->dao->select('id,lib,path,title,type,parent,grade,`order`')->from(TABLE_DOC)->where('id')->eq($moduleID)->fetch();
            $tops   = array($module);
        }
        else
        {
            $tops = $this->dao->select('id,lib,path,title,type,parent,grade,`order`')->from(TABLE_DOC)
                ->where('lib')->eq($libID)
                ->andWhere('deleted')->eq('0')
                ->andWhere('parent')->eq(0)
                ->orderBy("grade,`order`")
                ->fetchAll('id');
        }

        $chapters = $this->dao->select('id,lib,path,title,type,parent,grade,`order`')->from(TABLE_DOC)
            ->where('lib')->eq($libID)
            ->andWhere('deleted')->eq('0')
            ->orderBy("grade,`order`")
            ->fetchGroup('parent', 'id');

        $articles = $this->dao->select('t1.id,t1.lib,t1.path,t1.title,t1.type,t1.parent,t1.status,t1.grade,t1.`order`,t2.content,t2.files,t2.type as contentType')->from(TABLE_DOC)->alias('t1')
            ->leftJoin(TABLE_DOCCONTENT)->alias('t2')->on('t1.id=t2.doc && t1.version=t2.version')
            ->where('t1.lib')->eq($libID)
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t1.type')->eq('article')
            ->orderBy("status, id_desc")
            ->fetchGroup('parent', 'id');

        $files = '';
        foreach($articles as $parent => $subArticles)
        {
            foreach($subArticles as $articleID => $article) $files .= $article->files . ',';
        }

        foreach($tops as $docID => $doc)
        {
            if($doc->type == 'article' and isset($articles[$moduleID][$docID])) $tops[$docID] = $articles[$moduleID][$docID];
        }

        $files = array_unique(explode(',', $files));
        $files = $this->dao->select('id,pathname,title,extension')->from(TABLE_FILE)->where('id')->in($files)->fetchAll('id');

        return array('tops' => $tops, 'chapters' => $chapters, 'articles' => $articles, 'files' => $files);
    }

    /**
     * Copy object files.
     *
     * @param  object $object
     * @param  string $objectType
     * @access public
     * @return array
     */
    public function copyObjectFiles($objectType, $object = null)
    {
        if(!isset($object) && $this->post->feedback) $object = $this->loadModel('feedback')->getById($this->post->feedback);
        if(!isset($object) && $this->post->ticket)   $object = $this->loadModel('ticket')->getByID($this->post->ticket);

        $sourceFiles = isset($object->files) ? $object->files : array();
        $fileIDPairs = array();
        if(!empty($sourceFiles))
        {
            $now = helper::today();
            foreach($sourceFiles as $sourceFile)
            {
                $pos                 = strrpos($sourceFile->pathname, '.');
                $sourcePathnameNoExt = $sourceFile->pathname;
                if($pos !== false) $sourcePathnameNoExt = substr($sourceFile->pathname, 0, $pos);
                if(!is_file($this->savePath . $sourcePathnameNoExt)) continue;

                $pathname      = $this->setPathName($sourceFile->id, $sourceFile->extension);
                $pathnameNoExt = $pathname;
                $pos           = strrpos($pathname, '.');
                if($pos !== false) $pathnameNoExt = substr($pathname, 0, $pos);

                $sourceFile->addedDate  = $now;
                $sourceFile->addedBy    = $this->app->user->account;
                $sourceFile->objectType = $objectType;
                $sourceFile->objectID   = 0;
                $sourceFile->downloads  = 0;
                $sourceFile->deleted    = '0';
                if($objectType == 'ticket') $sourceFile->extra = 'create';
                if(copy($this->savePath . $sourcePathnameNoExt, $this->savePath . $pathnameNoExt))
                {
                    $sourceFile->pathname = $pathname;
                    $this->dao->insert(TABLE_FILE)->data($sourceFile, 'id,webPath,realPath,name,url')->exec();
                    $fileIDPairs[$sourceFile->id] = $this->dao->lastInsertID();
                }
            }
        }

        if(!empty($_POST['deleteFiles']))
        {
            foreach($_POST['deleteFiles'] as $fileID)
            {
                unset($_POST['deleteFiles'][$fileID]);
                $destFileID = $fileIDPairs[$fileID];
                $_POST['deleteFiles'][$destFileID] = $destFileID;
            }
            $this->dao->delete()->from(TABLE_FILE)->where('id')->in($_POST['deleteFiles'])->exec();
        }

        return $fileIDPairs;
    }
}
