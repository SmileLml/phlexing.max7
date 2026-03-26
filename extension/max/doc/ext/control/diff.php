<?php
helper::importControl('doc');
class mydoc extends doc
{
    /**
     * Diff doc.
     *
     * @param  string $objectType
     * @param  int    $docID
     * @param  int    $newVersion
     * @param  int    $oldVersion
     * @access public
     * @return void
     */
    public function diff($objectType, $docID, $newVersion, $oldVersion = '')
    {
        if(empty($oldVersion)) $oldVersion = $newVersion - 1;
        if(empty($oldVersion) or empty($newVersion)) die(js::error($this->lang->doc->versionNotFound) . js::locate('back'));

        $newDoc = $this->doc->getById($docID, $newVersion, true);
        $oldDoc = $this->doc->getById($docID, $oldVersion, true);
        if(!$newDoc) die(js::error($this->lang->notFound) . js::locate('back'));

        /* Merge files in doc. */
        $oldDoc->files = $this->doc->mergeFiles($oldDoc);
        $newDoc->files = $this->doc->mergeFiles($newDoc);

        /* Get diff. */
        $diff = new stdclass();
        $diff->title = $this->doc->diff($oldDoc->title,   $newDoc->title);

        $oldIsImage = $this->doc->isImage($oldDoc->content);
        $newIsImage = $this->doc->isImage($newDoc->content);
        if($oldIsImage and $newIsImage)
        {
            $diff->content = $this->doc->diffImage($oldIsImage, $newIsImage);
        }
        else
        {
            $diff->content = $this->doc->diff($oldDoc->content, $newDoc->content);
        }

        $oldIsImage = $this->doc->isImage($oldDoc->digest);
        $newIsImage = $this->doc->isImage($newDoc->digest);
        if($oldIsImage and $newIsImage)
        {
            $diff->digest = $this->doc->diffImage($oldIsImage, $newIsImage);
        }
        else
        {
            $diff->digest = $this->doc->diff($oldDoc->digest, $newDoc->digest);
        }

        $diff->files = $this->doc->diff($oldDoc->files, $newDoc->files);

        /* Check priv when lib is product or execution. */
        $lib  = $this->doc->getLibByID($newDoc->lib);
        $type = $lib->product ? 'product' : ($lib->execution ? 'execution' : 'custom');

        /* Set menu. */
        if($this->app->tab == 'product')
        {
            $this->product->setMenu($newDoc->product);
            unset($this->lang->product->menu->doc['subMenu']);
        }
        else if($this->app->tab == 'project')
        {
            $this->project->setMenu($newDoc->project);
            unset($this->lang->project->menu->doc['subMenu']);
        }
        else if($this->app->tab == 'execution')
        {
            $this->execution->setMenu($newDoc->execution);
            unset($this->lang->execution->menu->doc['subMenu']);
        }
        else
        {
            $this->app->rawMethod = $objectType;
            unset($this->lang->doc->menu->product['subMenu']);
            unset($this->lang->doc->menu->custom['subMenu']);
            unset($this->lang->doc->menu->execution['subMenu']);
            unset($this->lang->doc->menu->project['subMenu']);
        }

        $this->view->title      = "DOC #$newDoc->id $newDoc->title - " . $lib->name;
        $this->view->position[] = html::a($this->createLink('doc', 'browse', "libID=$newDoc->lib"), $lib->name);
        $this->view->position[] = $this->lang->doc->view;

        $this->view->newDoc     = $newDoc;
        $this->view->oldDoc     = $oldDoc;
        $this->view->docID      = $docID;
        $this->view->lib        = $lib;
        $this->view->type       = $type;
        $this->view->newVersion = $newVersion;
        $this->view->oldVersion = $oldVersion;
        $this->view->diff       = $diff;
        $this->view->objectType = $objectType;

        $this->display();
    }
}
