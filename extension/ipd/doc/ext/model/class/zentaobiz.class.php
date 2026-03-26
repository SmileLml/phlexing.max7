<?php
class zentaobizDoc extends docModel
{
    public function mergeFiles($doc)
    {
        $dbDocFiles = $this->dao->select('files')->from(TABLE_DOCCONTENT)->where('doc')->eq($doc->id)->andWhere('version')->eq($doc->version)->fetch('files');
        $dbDocFiles = explode(',', $dbDocFiles);
        sort($dbDocFiles);
        $docFiles = array();
        foreach($dbDocFiles as $fileID)
        {
            if(!isset($doc->files[$fileID])) continue;
            $file = $doc->files[$fileID];
            $docFiles[$fileID] = 'File #' . $fileID;
            if($file)
            {
                $docFiles[$fileID] .= ' ' . $file->title;
                if(stripos($file->title, ".{$file->extension}") === false) $docFiles[$fileID] .= '.' . $file->extension;
            }
        }
        return join("\n", $docFiles);
    }

    /**
     * Diff doc.
     *
     * @param  string $fromText
     * @param  string $toText
     * @access public
     * @return array
     */
    public function diff($fromText, $toText)
    {
        $chineseReg = '[\x{4e00}-\x{9fa5}]';
        if(!class_exists('FineDiff')) $this->app->loadClass('finediff', true);

        /* Add space for chinese. */
        $fromText = preg_replace("/($chineseReg)/u", ' $1 ', $fromText);
        $fromText = str_replace(array('<', '>'), array(' <', '> '), $fromText);
        $toText   = preg_replace("/($chineseReg)/u", ' $1 ', $toText);
        $toText   = str_replace(array('<', '>'), array(' <', '> '), $toText);

        /* Get diff. */
        $diffOpcodes  = FineDiff::getDiffOpcodes($fromText, $toText, FineDiff::$sentenceGranularity);
        $renderedDiff = FineDiff::renderDiffToHTMLFromOpcodes($fromText, $diffOpcodes);

        /* Remove space for revert string. */
        $renderedDiff = preg_replace("/ ($chineseReg) /u", '$1', $renderedDiff);
        $renderedDiff = preg_replace("/ ?(<ins>|<del>)($chineseReg) {1,2}/u", '$1$2', $renderedDiff);
        $renderedDiff = preg_replace("/ ?(<\/ins>|<\/del>)($chineseReg) {1,2}/u", '$1$2', $renderedDiff);
        $renderedDiff = str_replace(array(' &lt;', '&gt; '), array('&lt;', '&gt;'), $renderedDiff);
        $renderedDiff = str_replace(array(' <ins>&lt;', ' <del>&lt;'), array('<ins>&lt;', '<del>&lt;'), $renderedDiff);
        preg_match_all("/<(ins|del)>([^<]*)<\/\\1>/", $renderedDiff, $matches);
        foreach($matches[0] as $i => $match)
        {
            $adjustMatch  = preg_replace("/^<{$matches[1][$i]}>(\/&gt;)/", "\$1<{$matches[1][$i]}>", $match);
            $adjustMatch  = preg_replace("/(&lt;\w+ *)<\/{$matches[1][$i]}>$/", "\$1/&gt;</{$matches[1][$i]}>", $adjustMatch);
            $adjustMatch  = preg_replace('/(&lt;\w+&gt;)/', "\$1<{$matches[1][$i]}>", $adjustMatch);
            $adjustMatch  = preg_replace('/(&lt;\/\w+&gt;)/', "</{$matches[1][$i]}>\$1", $adjustMatch);
            $match        = preg_replace("/(&lt;\w+ *)<\/{$matches[1][$i]}>$/", "\$1</{$matches[1][$i]}>/&gt;", $match);
            $renderedDiff = str_replace($match, $adjustMatch, $renderedDiff);
        }
        $renderedDiff = str_replace(array('<ins></ins>', '<del></del>'), '', $renderedDiff);
        $renderedDiff = htmlspecialchars_decode($renderedDiff);

        return $renderedDiff;
    }

    public function isImage($text)
    {
        $text = strip_tags($text, '<img>');
        preg_match_all('/^<img [^\/]*src="([^"]+)"[^\/]+\/>$/', $text, $matches);
        if($matches[0]) return $matches[1][0];
        return false;
    }

    public function diffImage($image1, $image2)
    {
        if(!class_exists('imagick')) return false;

        $image1 = $this->getRealPath($image1);
        $image2 = $this->getRealPath($image2);

        $diffSavePath = $this->app->getCacheRoot() . 'diffimage/';
        if(!is_dir($diffSavePath))@mkdir($diffSavePath, 0777, true);
        $diffImage = $diffSavePath . md5($image1 . $image2);
        if(file_exists($diffImage)) return $diffImage;

        $image1 = new imagick($image1);
        $image2 = new imagick($image2);

        $result = $image1->compareImages($image2, Imagick::METRIC_MEANSQUAREERROR);
        if($result[1] > 0.01) return false;

        $result[0]->setImageFormat("png");
        file_put_contents($diffImage, $result[0]);
        return $diffImage;
    }

    public function getRealPath($image)
    {
        $this->loadModel('file');
        $readLinkReg = helper::createLink('file', 'read', 'fileID=(%fileID%)', '%viewType%');
        $readLinkReg = str_replace(array('%fileID%', '%viewType%', '?', '/'), array('[0-9]+', '\w+', '\?', '\/'), $readLinkReg);
        preg_match_all("/$readLinkReg/", $image, $matches);
        if($matches[0])
        {
            $file = $this->file->getById($matches[1][0]);
            return $file->realPath;
        }
        elseif(strpos($image, 'data/upload') === 0)
        {
            $savePath = substr($this->file->savePath, 0, strpos($this->file->savePath, 'data'));
            return $savePath . $image;
        }

        return $image;
    }

    /**
     * @param object|bool|null $object
     * @param string $extra
     * @param string $docID
     */
    public function checkPrivLib($object, $extra = '', $docID = '')
    {
        if(empty($object)) return false;

        if($object->acl == 'dept' and $object->users)
        {
            $user = $this->loadModel('user')->getById($object->users);
            if($user and $user->dept == $this->app->user->dept) return true;
        }
        return parent::checkPrivLib($object, $extra, $docID);
    }

    /**
     * @param object $doc
     * @param bool $checkLib
     */
    public function checkPrivDoc($doc, $checkLib = true)
    {
        if(!isset($doc->acl)) return false;
        if($doc->acl == 'dept' and $doc->users)
        {
            $user = $this->loadModel('user')->getById($doc->users);
            if($user and $user->dept == $this->app->user->dept) return true;
        }
        return parent::checkPrivDoc($doc, $checkLib);
    }

    /**
     * @return int|bool
     * @param object $lib
     * @param string $type
     * @param string $libType
     */
    public function createLib($lib, $type = '', $libType = '')
    {
        $libID = parent::createLib($lib, $type, $libType);
        if(!dao::isError() and $this->post->acl == 'dept') $this->dao->update(TABLE_DOCLIB)->set('users')->eq($this->app->user->account)->where('id')->eq($libID)->exec();
        return $libID;
    }

    /**
     * @return mixed[]|bool
     * @param int $libID
     * @param object $lib
     */
    public function updateLib($libID, $lib)
    {
        $changes = parent::updateLib($libID, $lib);
        if(!dao::isError() and $this->post->acl == 'dept')
        {
            $libCreatedBy = $this->dao->select('*')->from(TABLE_ACTION)->where('objectType')->eq('doclib')->andWhere('objectID')->eq($libID)->andWhere('action')->eq('created')->fetch('actor');
            if(empty($libCreatedBy)) $libCreatedBy = $this->app->user->account;
            $this->dao->update(TABLE_DOCLIB)->set('users')->eq($libCreatedBy)->where('id')->eq($libID)->exec();
        }
        return $changes;
    }

    /**
     * @param mixed[]|bool $labels
     * @return mixed[]|bool|string
     * @param object $doc
     */
    public function create($doc, $labels = false)
    {
        $result = parent::create($doc, $labels);
        if($result and isset($result['status']) and $result['status'] == 'new' and $this->post->acl == 'dept') $this->dao->update(TABLE_DOC)->set('users')->eq($this->app->user->account)->where('id')->eq($result['id'])->exec();
        if($result and isset($result['status']) and $result['status'] == 'new' and $this->post->type && strpos($this->config->doc->officeTypes, $this->post->type) !== false)
        {
            $extension = '';
            if($this->post->type == 'word')  $extension = 'docx';
            if($this->post->type == 'ppt')   $extension = 'pptx';
            if($this->post->type == 'excel') $extension = 'xlsx';
            if(empty($extension)) return $result;

            $templateFile = $this->app->getTmpRoot() . 'template' . DS . "empty.$extension";
            $docID        = $result['id'];

            $doc = new stdclass();
            $doc->extension  = $extension;
            $doc->pathname   = $this->loadModel('file')->setPathName(0, $extension);
            $doc->title      = htmlspecialchars($this->post->title);
            $doc->size       = file_exists($templateFile) ? filesize($templateFile) : 0;
            $doc->objectType = 'doc';
            $doc->objectID   = $docID;
            $doc->addedBy    = $this->app->user->account;
            $doc->addedDate  = helper::now();
            $this->dao->insert(TABLE_FILE)->data($doc)->exec();
            if(!dao::isError())
            {
                if(file_exists($templateFile)) copy($templateFile, $this->file->savePath . DS . str_replace(".{$extension}", '', $doc->pathname));

                $fileID = $this->dao->lastInsertId();
                $this->dao->update(TABLE_DOCCONTENT)->set('files')->eq($fileID)->where('doc')->eq($docID)->exec();
                $this->loadModel('action')->create('doc', $docID, 'Created');

                unset($_GET['onlybody']);

                $response['result']     = 'success';
                $response['message']    = $this->lang->saveSuccess;
                $response['closeModal'] = true;
                $response['callback']   = "openEditURL($docID, $fileID)";
                die(json_encode($response));
            }
        }
        return $result;
    }

    /**
     * @return mixed[]|string|bool
     * @param int $docID
     * @param object $doc
     * @param object|null $oldDoc
     */
    public function update($docID, $doc, $oldDoc = null)
    {
        $changes = parent::update($docID, $doc, $oldDoc);
        if(dao::isError()) return false;

        $oldDoc = $this->dao->select('*')->from(TABLE_DOC)->where('id')->eq((int)$docID)->fetch();
        if(!dao::isError() and $this->post->acl == 'dept') $this->dao->update(TABLE_DOC)->set('users')->eq($oldDoc->addedBy)->where('id')->eq($docID)->exec();
        if(!dao::isError() and ($oldDoc->type == 'chapter' or $oldDoc->type == 'article')) $this->fixPath($oldDoc->lib);
        return $changes;
    }

    public function getAdminCatalog($bookID, $nodeID, $serials)
    {
        $catalog = '';

        $book = $this->getLibById($bookID);
        $node = $this->getById($nodeID);
        if(!$node)
        {
            $node = new stdclass();
            $node->id    = $book->id;
            $node->title = $book->name;
            $node->type  = $book->type;
        }

        $children = $this->getChildren($bookID, $nodeID);
        if($node->type != 'book') $serial = $serials[$nodeID];

        $anchor      = "name='node{$node->id}' id='node{$node->id}'";
        $titleLink   = ($node->type == 'book' or $node->type == 'article') ? $node->title : html::a(helper::createLink('doc', 'catalog', "bookID=$bookID&node=$node->id"), $node->title, '', "title={$node->title}");
        $editLink    = commonModel::hasPriv('doc', 'edit')    ? html::a(helper::createLink('doc', 'edit', "nodeID=$node->id&comment=false&objectType=book&objectID=0&libID=$bookID"), $this->lang->edit, '', $anchor) : '';
        $delLink     = commonModel::hasPriv('doc', 'delete')  ? html::a(helper::createLink('doc', 'delete', "nodeID=$node->id"), $this->lang->delete, 'hiddenwin') : '';
        $catalogLink = commonModel::hasPriv('doc', 'catalog') ? html::a(helper::createLink('doc', 'catalog', "bookID=$bookID&nodeID=$node->id"), $this->lang->doc->catalog) : '';
        $moveLink    = commonModel::hasPriv('doc', 'sort')    ? html::a('javascript:;', "<i class='icon-move'></i>", '', "class='sort sort-handle'") : '';

        $childrenHtml = '';
        if($children)
        {
            $childrenHtml .= '<dl>';
            foreach($children as $child) $childrenHtml .=  $this->getAdminCatalog($bookID, $child->id, $serials);
            $childrenHtml .= '</dl>';
        }

        if($node->type == 'book')    $catalog .= $childrenHtml;

        if($node->type == 'chapter') $catalog .= "<dd class='catalog chapter' data-id='" . $node->id . "'><strong><span class='order'>" . $serial . '</span>&nbsp;' . $titleLink . '</strong><span class="actions">' . $editLink . $catalogLink . $delLink . $moveLink . '</span>' . $childrenHtml . '</dd>';

        if($node->type == 'article') $catalog .= "<dd class='catalog article' data-id='" . $node->id . "'><strong><span class='order'>" . $serial . '</span>&nbsp;' . $node->title . '</strong> ' . '<span class="actions">' . $editLink . $delLink . $moveLink . '</span>' . $childrenHtml . '</dd>';

        return $catalog;
    }

    public function computeSN($bookID, $from = 'doc')
    {
        /* Get all children of the startNode. */
        $nodes = $this->dao->select('id, lib, parent, `order`, path')->from(TABLE_DOC)
            ->where('deleted')->eq(0)
            ->beginIF($from == 'baseline')->andWhere('template')->eq($bookID)->fi()
            ->beginIF($from == 'doc')->andWhere('lib')->eq($bookID)->fi()
            ->andWhere('type')->in('chapter,article')
            ->orderBy('grade, `order`, id')
            ->fetchAll('id');

        /* Group them by their parent. */
        $groupedNodes = array();
        foreach($nodes as $node) $groupedNodes[$node->parent][$node->id] = $node;

        $serials = array();
        foreach($nodes as $node)
        {
            $path      = explode(',', $node->path);
            $bookID    = $node->lib;
            $startNode = $path[1];

            $serial = '';
            foreach($path as $nodeID)
            {
                /* If the node id is empty or is the bookID, skip. */
                if(!$nodeID) continue;

                /* Compute the serial. */
                if(isset($nodes[$nodeID]))
                {
                    $parentID = $nodes[$nodeID]->parent;
                    $brothers = $groupedNodes[$parentID];
                    $serial  .= array_search($nodeID, array_keys($brothers)) + 1 . '.';
                }
            }

            $serials[$node->id] = rtrim($serial, '.');
        }

        return $serials;
    }

    public function getChildren($bookID, $nodeID = 0)
    {
        return $this->dao->select('*')->from(TABLE_DOC)
            ->where('lib')->eq($bookID)
            ->andWhere('deleted')->eq(0)
            ->andWhere('parent')->eq($nodeID)
            ->orderBy('`order`, id')
            ->fetchAll('id');
    }

    public function manageCatalog($bookID, $parentNodeID = 0)
    {
        if($parentNodeID) $parentNode = $this->getById($parentNodeID);

        /* Init the catalogue object. */
        $now = helper::now();
        $node = new stdclass();
        $node->lib    = $bookID;
        $node->parent = $parentNodeID ? $parentNode->id : 0;
        $node->grade  = $parentNodeID ? $parentNode->grade + 1 : 1;

        $nodeContent = new stdclass();
        $data = fixer::input('post')->get();

        foreach($data->title as $key => $nodeTitle)
        {
            if(empty($nodeTitle)) continue;
            $mode = $data->mode[$key];

            /* First, save the child without path field. */
            $node->title      = $nodeTitle;
            $node->type       = $data->type[$key];
            $node->keywords   = $data->keywords[$key];
            $node->addedBy    = $this->app->user->account;
            $node->addedDate  = $now;
            $node->editedBy   = $this->app->user->account;
            $node->editedDate = $now;
            $node->order      = $data->order[$key];

            if($mode == 'new')
            {
                $this->dao->insert(TABLE_DOC)->data($node)->exec();

                /* After saving, update it's path. */
                $nodeID   = $this->dao->lastInsertID();
                $nodePath = $parentNodeID ? $parentNode->path . "$nodeID," : ",$nodeID,";
                $this->dao->update(TABLE_DOC)->set('path')->eq($nodePath)->where('id')->eq($nodeID)->exec();

                $nodeContent->doc     = $nodeID;
                $nodeContent->title   = $nodeTitle;
                $nodeContent->type    = 'html';
                $nodeContent->version = 1;
                $this->dao->insert(TABLE_DOCCONTENT)->data($nodeContent)->exec();
            }
            else
            {
                $nodeID = $key;
                $node->editedBy   = $this->app->user->account;
                $node->editedDate = $now;
                $this->dao->update(TABLE_DOC)->data($node)->autoCheck()->where('id')->eq($nodeID)->exec();

                $oldNode            = $this->getById($nodeID);
                $nodeContent        = new stdclass();
                $nodeContent->title = $nodeTitle;
                $this->dao->update(TABLE_DOCCONTENT)->data($nodeContent)->where('doc')->eq($nodeID)->andWhere('version')->eq($oldNode->version)->exec();
            }
        }

        return !dao::isError();
    }

    public function getBookStructure($bookID)
    {
        $stmt = $this->dbh->query($this->dao->select('id, lib, type, path, `order`, parent, grade, title, editedBy, editedDate')->from(TABLE_DOC)->where('lib')->eq($bookID)->andWhere('deleted')->eq(0)->orderBy('grade_desc,`order`, id')->get());

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

    public function getFrontCatalogItems($nodes, $serials, $articleID = 0)
    {
        $nodeItems = array();
        foreach($nodes as $childNode)
        {
            $serial = $childNode->type != 'book' ? $serials[$childNode->id] : '';
            $users  = $this->loadModel('user')->getPairs('noletter');

            $nodeItem = array();
            if($childNode->type == 'chapter')
            {
                $nodeItem['text']   = array('html' => "<span class='module-name' title='{$childNode->title}'>{$serial} {$childNode->title}</span>");
                $nodeItem['actions']= array();
                if($this->app->getModuleName() != 'review' && $this->app->getMethodName() == 'view' && common::hasPriv('baseline', 'editbook')) $nodeItem['actions']['items'][] = array('key' => 'edit', 'icon' => 'edit', 'data-toggle' => 'modal', 'title' => $this->lang->doc->editChapter, 'url' => helper::createLink('baseline', 'editbook', "docID={$childNode->id}"));
            }
            elseif($childNode->type == 'article')
            {
                $text = html::a(helper::createLink('baseline', 'view', "docID=$childNode->id"), "<i class='icon icon-file-text text-muted'></i> &nbsp;" . $serial . ' ' . $childNode->title, '', "class='item doc-title' title='{$childNode->title}'");
                if($this->app->getModuleName() == 'tablecontents') $text = "{$text}<span class='tail-info'>" . zget($users, $childNode->editedBy) . " {$childNode->editedDate}</span>";

                $nodeItem['text']    = array('html' => $text);
                $nodeItem['actions'] = array();
                if($this->app->getModuleName() != 'review' && $this->app->getMethodName() == 'view' && common::hasPriv('baseline', 'editbook')) $nodeItem['actions']['items'][] = array('key' => 'edit', 'icon' => 'edit', 'data-toggle' => 'modal', 'title' => $this->lang->doc->edit, 'url' => helper::createLink('baseline', 'editbook', "docID={$childNode->id}"));
            }
            if(!empty($childNode->children)) $nodeItem['children'] = $this->getFrontCatalogItems($childNode->children, $serials, $articleID);
            $nodeItems[] = $nodeItem;
        }
        return $nodeItems;
    }

    public function getFrontCatalog($nodes, $serials, $articleID = 0)
    {
        echo '<ul>';
        foreach($nodes as $childNode)
        {
            $serial = $childNode->type != 'book' ? $serials[$childNode->id] : '';
            $users  = $this->loadModel('user')->getPairs('noletter');
            $class  = "class='open'";
            if($childNode->type == 'article') $class = "class='open chapterNode'";
            if($articleID && $articleID == $childNode->id) $class = "class='open active'";
            echo "<li $class>";
            echo "<div class='tree-group'>";
            if($childNode->type == 'chapter')
            {
                echo "<span class='module-name'><a class='item' title='{$childNode->title}'>{$serial} {$childNode->title}</a></span>";
                if($this->app->getMethodName() == 'view' and common::hasPriv('baseline', 'editbook'))
                {
                    echo "<div class='tree-actions'>";
                    echo html::a(helper::createLink('baseline', 'editbook', "docID=$childNode->id"), "<i class='icon icon-edit'></i>", '', "title={$this->lang->doc->editChapter}");
                    echo "</div>";
                }
            }
            elseif($childNode->type == 'article')
            {
                if($this->app->getMethodName() == 'tablecontents') echo '<span class="tail-info">' . zget($users, $childNode->editedBy) . ' &nbsp;' . $childNode->editedDate .  '</span>';
                echo html::a(helper::createLink('baseline', 'view', "docID=$childNode->id"), "<i class='icon icon-file-text text-muted'></i> &nbsp;" . $serial . ' ' . $childNode->title, '', "class='item doc-title' title='{$childNode->title}'");
                if($this->app->getMethodName() == 'view' and common::hasPriv('doc', 'edit'))
                {
                    echo "<div class='tree-actions'>";
                    echo html::a(helper::createLink('baseline', 'editbook', "docID=$childNode->id"), "<i class='icon icon-edit'></i>", '', "title={$this->lang->doc->edit}");
                    echo "</div>";
                }
            }
            echo '</div>';
            if(!empty($childNode->children)) $this->getFrontCatalog($childNode->children, $serials, $articleID);
            echo '</li>';
        }
        echo '</ul>';
    }

    public function sortBookOrder()
    {
        $nodes = fixer::input('post')->get();
        foreach($nodes->sort as $id => $order)
        {
            $order = explode('.', $order);
            $num   = end($order);
            $this->dao->update(TABLE_DOC)->set('`order`')->eq($num)->where('id')->eq($id)->exec();
        }
        return !dao::isError();
    }

    /**
     * Build query for wiki.
     *
     * @param  int    $bookID
     * @param  int    $nodeID
     * @access public
     * @return void
     */
    public function buildWikiQuery($bookID = 0, $nodeID = 0)
    {
        return $this->dao->select('*')->from(TABLE_DOC)
            ->where('type')->eq('chapter')
            ->beginIF($this->app->rawModule == 'baseline')->andWhere('template')->eq($bookID)->fi()
            ->beginIF($this->app->tab == 'doc')->andWhere('lib')->eq($bookID)->fi()
            ->andWhere('deleted')->eq(0)
            ->andWhere('path')->notlike("%$nodeID%")
            ->orderBy('grade desc, `order`, id')
            ->get();
    }


    /**
     * Get book list for option menu.
     *
     * @param  int    $bookID
     * @param  bool   $removeRoot
     * @param  int    $nodeID
     * @access public
     * @return void
     */
    public function getBookOptionMenu($bookID = 0, $removeRoot = false, $nodeID = 0)
    {
        /* First, get all catalogues. */
        $treeMenu   = array();
        $stmt       = $this->dbh->query($this->buildWikiQuery($bookID, $nodeID));
        $catalogues = array();
        while($catalogue = $stmt->fetch()) $catalogues[$catalogue->id] = $catalogue;

        /* Cycle them, build the select control.  */
        foreach($catalogues as $catalogue)
        {
            $origins = explode(',', $catalogue->path);
            $catalogueTitle = '/';
            foreach($origins as $origin)
            {
                if(empty($origin)) continue;
                $catalogueTitle .= $catalogues[$origin]->title . '/';
            }
            $catalogueTitle = rtrim($catalogueTitle, '/');
            $catalogueTitle .= "|$catalogue->id\n";

            if(isset($treeMenu[$catalogue->id]) and !empty($treeMenu[$catalogue->id]))
            {
                if(isset($treeMenu[$catalogue->parent]))
                {
                    $treeMenu[$catalogue->parent] .= $catalogueTitle;
                }
                else
                {
                    $treeMenu[$catalogue->parent] = $catalogueTitle;;
                }

                $treeMenu[$catalogue->parent] .= $treeMenu[$catalogue->id];
            }
            else
            {
                if(isset($treeMenu[$catalogue->parent]) and !empty($treeMenu[$catalogue->parent]))
                {
                    $treeMenu[$catalogue->parent] .= $catalogueTitle;
                }
                else
                {
                    $treeMenu[$catalogue->parent] = $catalogueTitle;
                }
            }
        }

        $topMenu = @array_pop($treeMenu);
        $topMenu = explode("\n", is_string($topMenu) ? trim($topMenu) : '');
        if(!$removeRoot) $lastMenu[$bookID] = '/';

        foreach($topMenu as $menu)
        {
            if(!strpos($menu, '|')) continue;

            $menu        = explode('|', $menu);
            $label       = array_shift($menu);
            $catalogueID = array_pop($menu);

            $lastMenu[$catalogueID] = $label;
        }

        return $lastMenu;
    }

    public function fixPath($bookID)
    {
        /* Get all nodes grouped by parent. */
        $groupNodes = $this->dao->select('id, parent')->from(TABLE_DOC)
            ->where('lib')->eq($bookID)
            ->andWhere('deleted')->eq(0)
            ->fetchGroup('parent', 'id');

        $nodes = array();

        /* Cycle the groupNodes until it has no item any more. */
        while(count($groupNodes) > 0)
        {
            /* Record the counts before processing. */
            $oldCounts = count($groupNodes);

            foreach($groupNodes as $parentNodeID => $childNodes)
            {
                /**
                 * If the parentNode doesn't exsit in the nodes, skip it.
                 * If exists, compute it's child nodes.
                 */
                if(!isset($nodes[$parentNodeID]) and $parentNodeID != 0) continue;

                if($parentNodeID == 0)
                {
                    $parentNode = new stdclass();
                    $parentNode->grade = 0;
                    $parentNode->path  = ',';
                }
                else
                {
                    $parentNode = $nodes[$parentNodeID];
                }

                /* Compute it's child nodes. */
                foreach($childNodes as $childNodeID => $childNode)
                {
                    $childNode->grade = $parentNode->grade + 1;
                    $childNode->path  = $parentNode->path . $childNode->id . ',';

                    /**
                     * Save child node to nodes,
                     * thus the child of child can compute it's grade and path.
                     */
                    $nodes[$childNodeID] = $childNode;
                }

                /* Remove it from the groupNodes.*/
                unset($groupNodes[$parentNodeID]);
            }

            /* If after processing, no node processed, break the cycle. */
            if(count($groupNodes) == $oldCounts) break;
        }

        /* Save nodes to database. */
        foreach($nodes as $node)
        {
            $this->dao->update(TABLE_DOC)->data($node)
                ->where('id')->eq($node->id)
                ->exec();
        }
    }

    public function getChildModules($parentID)
    {
        return $this->dao->select('*')->from(TABLE_MODULE)
            ->where('deleted')->eq(0)
            ->andWhere('parent')->eq($parentID)
            ->orderBy('`order` asc')
            ->fetchAll();
    }

    /**
     * @param int $docID
     * @param int $version
     */
    public function setDocPOST($docID, $version = 0)
    {
        $doc = $this->getById($docID, $version);

        $_POST['fileName'] = $doc->title;
        $_POST['version']  = $doc->version;
        $_POST['format']   = 'doc';
        $_POST['range']    = 'current';
    }
}
