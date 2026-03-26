<?php
require_once 'exportbase.php';
class file extends exportbase
{
    /**
     * Export to Word
     *
     * @access public
     * @return void
     */
    public function doc2Word()
    {
        $headerName = $this->post->fileName;
        $this->init($headerName);

        if($this->exportedArticle)
        {
            $headerName = $this->getSafeFileName($headerName);

            /* Single article. */
            $article = $this->articles = $this->dao->select('t1.id,t1.module,t1.lib,t1.path,t1.type,t1.parent,t1.grade,t1.`order`,t2.title,t2.content,t2.files,t2.type as contentType,t2.rawContent')->from(TABLE_DOC)->alias('t1')
            ->leftJoin(TABLE_DOCCONTENT)->alias('t2')->on('t1.id=t2.doc && t1.version=t2.version')
            ->where('t1.lib')->eq($this->libID)
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t1.type')->in($this->config->file->docExportType)
            ->andWhere('t1.id')->eq($this->post->docID)
            ->andWhere('t2.version')->eq($this->post->version)
            ->fetch();

            array_shift($this->exportFields);
            if(!empty($article->files)) $this->files = $this->dao->select('id,pathname,title,extension')->from(TABLE_FILE)->where('id')->in($article->files)->fetchAll('id');

            $this->section->addTitle($article->title, 1);
            $this->section->addTextBreak(2);

            if($article->contentType == 'doc' && empty($article->rawContent) && !empty($article->content)) $article->content = $this->lang->doc->notSupportExport;

            $this->createContent($article, 1, '');
            return helper::end($this->sendDownWordHeader($headerName));
        }
        else
        {
            $exportLibs = $this->file->getExportLibs();
            $exportLibs = $this->file->processLibs($exportLibs);

            if($this->post->format == 'doc')
            {
                $this->section->addTitle($headerName, 1);
                $this->section->addTextBreak(2);

                $fontStyle12 = array('spaceAfter' => 60, 'size' => 12);
                $this->section->addTOC($fontStyle12);
                $this->section->addPageBreak();

                /* All articles. */
                foreach($exportLibs as $lib)
                {
                    $this->step = 1;
                    if(is_array($lib))
                    {
                        $this->addDocTitle($lib['name'], $this->step);
                        $childStep = $this->step;
                        foreach($lib['children'] as $i => $subLib)
                        {
                            $this->step = $childStep;
                            if(count($lib['children']) > 1) $this->addDocTitle($subLib->name, $this->step);

                            $this->getExportData($subLib);
                            $this->exportLib2Doc($this->tops, $this->step);
                        }
                    }
                    else
                    {
                        if(count($exportLibs ) > 1) $this->addDocTitle($lib->name, $this->step);

                        $this->getExportData($lib);
                        $this->exportLib2Doc($this->tops, $this->step);
                    }
                }

                /* 如果不需要导出附件库，直接导出。 */
                if($this->post->kind == 'api' || strpos('productAll|projectAll|executionAll', $this->post->range) === false) return helper::end($this->sendDownWordHeader($headerName));

                /* 获取导出基础链接。 */
                $savePath = $this->app->getCacheRoot() . DS . $this->app->user->account . time() . DS . $headerName . DS;
                if(!file_exists($savePath)) mkdir($savePath, 0777, true);

                /* 获取附件库的文件，将word文档和附件打包成压缩包导出。 */
                $this->step = 1;
                $this->addDocTitle($this->lang->doc->showFiles, 1);

                $type         = str_replace('All', '', $this->post->range);
                $objectID     = $this->post->{$type . 'ID'};
                $files        = $this->loadModel('doc')->getLibFiles($type, $objectID);
                $fileBasePath = $this->app->wwwRoot . "data/upload/{$this->app->company->id}" . DS;
                foreach($files as $fileID => $file)
                {
                    /* 将附件文件名称导出到word文档。 */
                    $this->section->addLink($file->title, $file->title, array('color' => '0000FF', 'underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE));
                    $this->section->addTextBreak();

                    /* 复制附件到导出目录。 */
                    $filename = str_replace(strrchr($file->pathname, '.'), '', $file->pathname);
                    $dotPos   = strrpos($file->title, '.');
                    $realname = substr($file->title, 0, $dotPos);
                    $realExt  = substr($file->title, $dotPos + 1);

                    $extension    = !empty($realExt) ? $realExt : $file->extension;
                    $fileSavePath = $savePath . $realname . '.' . $extension;
                    if(file_exists($fileSavePath))
                    {
                        if(md5_file($fileBasePath . $filename) == md5_file($fileSavePath)) continue;
                        $fileSavePath = $savePath . $realname . '_' . $fileID . '.' . $extension;
                    }
                    copy($fileBasePath . $filename, $fileSavePath);
                }

                /* 复制word文档到导出目录。 */
                $tmpName  = $savePath . DS . $headerName . '.docx';
                $wordWriter = \PhpOffice\PhpWord\IOFactory::createWriter($this->phpWord, 'Word2007');
                $wordWriter->save($tmpName);
                rename($tmpName, $savePath);

                /* 将word文档和附件打包成压缩包导出。 */
                $parentSavePath = dirname($savePath);
                $zipSavePath    = $parentSavePath . DS . $headerName . '.zip';

                helper::cd($parentSavePath);
                $this->app->loadClass('pclzip', true);
                $zip = new pclzip($zipSavePath);
                $zip->create($headerName);

                helper::cd();
                $fileData = file_get_contents($zipSavePath);
                $zfile    = $this->app->loadClass('zfile');
                $zfile->removeDir($parentSavePath);
                $this->file->sendDownHeader($headerName . '.zip', 'zip', $fileData);
            }
            elseif($this->post->format == 'zip')
            {
                $headerName = $this->getSafeFileName($headerName);
                $saveBasePath = $this->app->getCacheRoot() . DS . $this->app->user->account . time() . DS . $headerName . DS;
                if(!file_exists($saveBasePath)) mkdir($saveBasePath, 0777, true);

                foreach($exportLibs as $lib)
                {
                    $savePath = $saveBasePath;
                    if(is_array($lib))
                    {
                        $savePath .= $this->getSafeFileName($lib['name']) . DS;
                        if(!file_exists($savePath)) mkdir($savePath, 0777, true);
                        foreach($lib['children'] as $subLib)
                        {
                            $saveSubPath = $savePath;
                            if(count($lib['children']) > 1) $saveSubPath = $savePath . $this->getSafeFileName($subLib->name) . DS;
                            if(!file_exists($saveSubPath)) mkdir($saveSubPath, 0777, true);

                            $this->getExportData($subLib);
                            $this->exportLib2Zip($this->tops, $saveSubPath);
                        }
                    }
                    else
                    {
                        if(count($exportLibs ) > 1) $savePath .= $this->getSafeFileName($lib->name) . DS;
                        if(!file_exists($savePath)) mkdir($savePath, 0777, true);

                        $this->getExportData($lib);
                        $this->exportLib2Zip($this->tops, $savePath);
                    }
                }

                $parentSavePath = dirname($saveBasePath);
                $zipSavePath    = $parentSavePath . DS . $headerName . '.zip';

                helper::cd($parentSavePath);
                $this->app->loadClass('pclzip', true);
                $zip = new pclzip($zipSavePath);
                $zip->create($headerName);

                helper::cd();
                $fileData = file_get_contents($zipSavePath);
                $zfile    = $this->app->loadClass('zfile');
                $zfile->removeDir($parentSavePath);
                $this->file->sendDownHeader($headerName . '.zip', 'zip', $fileData);
            }
        }
    }
}
