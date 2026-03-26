<?php
class exportbase extends control
{
    /**
     * Init for word
     *
     * @param  string $headerName
     * @access public
     * @return void
     */
    public function init($headerName = '')
    {
        // post content: kind(string), exportFields(array), fields(array), rows(array), tableName(string), style(array), header(array).
        $this->kind            = $this->post->kind;
        $this->libID           = $this->post->libID;
        $this->docID           = $this->post->docID;
        $this->exportedArticle = $this->post->range == 'current' && $this->post->docID;
        $this->exportFields    = $this->config->word->{$this->kind}->exportFields;

        $this->loadModel('file');
        $this->filePath = $this->file->savePath;
        $this->sysURL   = common::getSysURL();
        $this->order    = 0;

        $this->initPHPWord($headerName);

        unset($_GET['onlybody']);
    }

    /**
     * Create word.
     *
     * @param  object $module
     * @param  int    $step
     * @param  string $order
     * @access public
     * @return void
     */
    public function createWord($module, $step = 1, $order = 0)
    {
        $this->createTitle($module, $step, $order);
        if(isset($this->chapters[$module->id]) and $module->type != 'article')
        {
            foreach($this->chapters[$module->id] as $subModule)
            {
                $order = $this->getNextOrder($this->order, $step + 1);
                $this->createWord($subModule, $step + 1, $order);
            }
        }
    }

    /**
     * Create dir for zip
     *
     * @param  object  $module
     * @param  string  $savePath
     * @access public
     * @return void
     */
    public function createDir($module, $savePath)
    {
        $isArticle = in_array($module->type, explode(',', $this->config->file->docExportType));
        if($isArticle)
        {
            if(!file_exists($savePath)) mkdir($savePath, 0777, true);
            $this->initPHPWord($module->title);
            $articleSavePath = $savePath . $this->getSafeFileName($module->title) . '.docx';
            $this->saveSingleWord($this->articles[$module->parent][$module->id], $articleSavePath);
        }

        $savePath .= $this->getSafeFileName($module->title) . DS;
        if(!$isArticle and !file_exists($savePath)) mkdir($savePath, 0777, true);
        if(isset($this->chapters[$module->id]) and $module->type != 'article')
        {
            foreach($this->chapters[$module->id] as $subModule) $this->createDir($subModule, $savePath);
        }
    }

    /**
     * Add doc title.
     *
     * @param  string $title
     * @access public
     * @return void
     */
    public function addDocTitle($title, $step)
    {
        $order = $this->getNextOrder($this->order, $this->step);
        $this->section->addTitle($order . ' ' . $title, $this->step);
        $this->section->addTextBreak(1);
        $this->step++;
    }

    /**
     * Create title for word.
     *
     * @param  object $module
     * @param  int    $step
     * @param  string $order
     * @access public
     * @return void
     */
    public function createTitle($module, $step, $order)
    {
        if($module->type == 'doc')
        {
            $this->section->addTitle($order . " " . $module->title, $step + 1);
            $this->section->addTextBreak(1);
        }
        elseif($module->type == 'api')
        {
            $this->section->addTitle($order . " " . $module->title, $step + 1);
            $this->section->addTextBreak(1);
        }
        elseif($module->type == 'chapter')
        {
            $this->section->addTitle($order . " " . $module->title, $step + 1);
            $this->section->addTextBreak(1);
        }
        elseif($module->type == 'article')
        {
            $this->createContent($this->articles[$module->parent][$module->id], $step, $order);
        }
        elseif(in_array($module->type, explode(',', $this->config->file->docFileType)))
        {
            $this->createContent($this->articles[$module->parent][$module->id], $step, $order);
        }
    }

    /**
     * Create word content.
     *
     * @param  object $article
     * @param  int    $step
     * @param  string $order
     * @access public
     * @return void
     */
    public function createContent($article, $step, $order)
    {
        if(empty($article)) return;

        foreach($this->exportFields as $exportField)
        {
            $fieldName = $exportField;
            $style = zget($this->config->word->{$this->kind}->style, $exportField, '');

            if($style == 'title')
            {
                $fieldContent = $order . ' ' . $article->$fieldName;
                $this->section->addTitle(html_entity_decode($fieldContent), $step + 1);
                $this->section->addTextBreak();
            }
            elseif($style == 'showImage')
            {
                $fieldContent = isset($article->$fieldName) ? $article->$fieldName : '';
                if(empty($fieldContent)) continue;

                $contentType = isset($article->contentType) ? $article->contentType : '';
                if($fieldName == 'content' && $contentType == 'doc' && empty($article->rawContent) && !empty($article->content))
                {
                    $fieldContent = $this->lang->doc->notSupportExport;
                }
                else
                {
                    /* Process special character */
                    $isMarkdown   = $contentType == 'markdown';
                    $fieldContent = preg_replace_callback('/<img(.+)src\s*=\s*[\"\']([^\"\']+)[\"\'](.*)\/?>/Ui', array(&$this, 'checkFileExist'), $article->$fieldName);
                    $fieldContent = html_entity_decode($fieldContent);
                    if(!$isMarkdown) $fieldContent = '<div>' . $fieldContent . '</div>';

                    /* 去除有序列表和无序列表中的p标签。 */
                    $pattern      = '/<li>\s*<p>(.*?)<\/p>\s*<\/li>/i';
                    $replacement  = '<li>$1</li>';
                    $fieldContent = preg_replace($pattern, $replacement, $fieldContent);

                    /* Process table with tbody and thead. */
                    if($isMarkdown || $contentType == 'doc')
                    {
                        $fieldContent = commonModel::processMarkdown($fieldContent);
                        $fieldContent = preg_replace('/th>/i', 'td>', $fieldContent);
                        $fieldContent = preg_replace('/<tbody>|<\/tbody>/i', '', $fieldContent);
                        $fieldContent = preg_replace('/<thead>|<\/thead>/i', '', $fieldContent);
                    }
                }

                \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
                $this->htmlDom->load('<html><body>' . $fieldContent . '</body></html>');
                $htmlDomArray = $this->htmlDom->find('html',0)->children();
                htmltodocx_insert_html($this->section, $htmlDomArray[0]->nodes, $this->initialState);
                $this->htmlDom->clear();
            }
            elseif($fieldName == 'files')
            {
                $this->formatFiles($article);
            }
            else
            {
                $textRun = $this->section->createTextRun('pStyle');
                $textRun->addText($this->fields[$fieldName] . "：", array('bold' => true));
                $textRun->addText($article->$fieldName, null);
            }
        }
        $this->section->addTextBreak();
    }

    /**
     * Format files.
     *
     * @param  string    $content
     * @access public
     * @return void
     */
    public function formatFiles($content)
    {
        if(empty($content->files)) return;
        $this->section->addText($this->lang->word->fileField . ':', array('bold' => true));

        $fileIdList = explode(',', $content->files);
        foreach($fileIdList as $fileID)
        {
            if(empty($fileID)) continue;
            if(!isset($this->files[$fileID])) continue;

            $file = $this->files[$fileID];
            if(in_array($file->extension, $this->config->word->imageExtension))
            {
                $inf = pathinfo($file->pathname);
                $file->pathname = strpos($file->pathname, '.') === false ? $file->pathname : substr($file->pathname, 0, strpos($file->pathname, '.'));
                if(!file_exists($this->filePath . $file->pathname)) continue;
                if(!isset($inf['extension']) or strtolower($file->extension) != strtolower($inf['extension'])) $file->pathname .= ".{$file->extension}";

                list($imageWidth, $imageHeight) = getimagesize($this->filePath . $file->pathname);
                $imageRate = (float)$imageHeight / $imageWidth;
                if($imageWidth >= 456)
                {
                    $imageWidth  = 456;
                    $imageHeight = $imageWidth * $imageRate;
                }

                $this->section->addImage($this->filePath . $file->pathname, array('width' => $imageWidth, 'height' => $imageHeight));
                $this->section->addTextBreak();
            }
            else
            {
                $inf = pathinfo($file->title);
                if(!isset($inf['extension']) or strtolower($file->extension) != strtolower($inf['extension'])) $file->title .= ".{$file->extension}";
                $this->section->addLink($this->sysURL . $this->createLink('file', 'download', "fileID={$file->id}", 'html'), $file->title, array('color' => '0000FF', 'underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE));
            }
        }
    }

    /**
     * Add title style.
     *
     * @param  object $phpWord
     * @param  int    $step
     * @access public
     * @return object
     */
    public function addTitleStyle($phpWord, $step)
    {
        $size = isset($this->config->word->size->titles[$step]) ? $this->config->word->size->titles[$step] : 12;
        $phpWord->addTitleStyle($step, array('size'=> $size, 'color'=>'010101', 'bold'=>true), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
        return $phpWord;
    }

    /**
     * Get next order
     *
     * @param  string $order
     * @param  int    $step
     * @access public
     * @return string
     */
    public function getNextOrder($order, $step)
    {
        $orders = explode('.', $order);
        if(count($orders) + 1 == $step)
        {
            $order .= '.1';
        }
        elseif(count($orders) + 1 > $step)
        {
            $orders[$step - 1] += 1;
            $orders = array_slice($orders, 0, $step);
            $order = join('.', $orders);
        }
        else
        {
            $orders[count($orders) - 1] = end($orders) + 1;
            $order = join('.', $orders);
        }
        $this->order = $order;
        return $order;
    }

    /**
     * Check file exist.
     *
     * @param  array    $matches
     * @access public
     * @return string
     */
    public function checkFileExist($matches)
    {
        $filePath     = $this->app->getWwwRoot();
        $fileSrc      = $matches[2];
        $realFilePath = $filePath . strstr($fileSrc, 'data');
        if(is_file($realFilePath)) return "<img{$matches[1]}src=\"{$realFilePath}\"{$matches[3]}/>";

        $file = null;
        if(strpos($fileSrc, '#g=') !== false)
        {
            $gid = base64_decode(substr($fileSrc, strpos($fileSrc, '#g=') + 3));
            $file = $this->file->getByGid($gid);
        }

        if(!$file)
        {
            $fileReg = '/^\/index\.php\?m=file&f=(?:read|ajaxQuery)&fileID=(\d+|[-A-Za-z0-9+\/]*={0,3})$/i';
            preg_match($fileReg, str_replace('&amp;', '&', $fileSrc), $out);
            if(!isset($out[1]))
            {
                $fileReg = '/^\/file-(?:read|ajaxQuery)-(\d+|[-A-Za-z0-9+\/]*={0,3})$/i';
                preg_match($fileReg, $fileSrc, $out);
            }
            if(isset($out[1]))
            {
                $fileID = $out[1];
                if(is_numeric($fileID))
                {

                    $file = $this->file->getById((int)$fileID);
                }
                else
                {
                    $gid = base64_decode($fileID);
                    if(strpos($gid, 'g-') == 0) $gid = substr($gid, 2);
                    $file = $this->file->getByGid($gid);
                }
            }
        }

        if(!$file)
        {
            preg_match_all('/^{(\d+)\.\w+}$/', $fileSrc, $out);
            if($out[0]) $file = $this->file->getById($out[1][0]);
        }

        if($file)
        {
            $realFilePath = $this->loadModel('file')->saveAsTempFile($file); // Download file from OSS server, and save to data/upload/.
            if(file_exists($realFilePath)) return "<img{$matches[1]}src=\"{$realFilePath}\"{$matches[3]}/>";
        }

        $parsedURL = parse_url(htmlspecialchars_decode($fileSrc));
        if(isset($parsedURL['query']))
        {
            parse_str($parsedURL['query'], $parsedQuery);
            if(isset($parsedQuery['pathname']))
            {
                $pathname = $parsedQuery['pathname'];
                $realFilePath = $this->file->savePath . $pathname;
                if(file_exists($realFilePath)) return "<img{$matches[1]}src=\"{$realFilePath}\"{$matches[3]}/>";

                $pathname = substr($pathname, 0, strrpos($pathname, '.'));
                $realFilePath = $this->file->savePath . $pathname;
                if(file_exists($realFilePath)) return "<img{$matches[1]}src=\"{$realFilePath}\"{$matches[3]}/>";
            }
            if(basename($parsedURL['path']) == 'file.php')
            {
                $pathname = $parsedQuery['f'];
                if(strrpos($pathname, '.') !== false) $pathname = substr($pathname, 0, strrpos($pathname, '.'));
                $realFilePath = $this->file->savePath . $pathname;
                if(file_exists($realFilePath)) return "<img{$matches[1]}src=\"{$realFilePath}\"{$matches[3]}/>";
            }
        }

        return '';
    }

    /**
     * Save single word.
     *
     * @param  object $article
     * @param  string $savePath
     * @access public
     * @return void
     */
    public function saveSingleWord($article, $savePath = '')
    {
        if(empty($savePath)) return false;
        $dirName  = dirname($savePath);
        $fileName = basename($savePath);
        $tmpName  = $dirName . DS . md5($fileName) . '.docx';

        $this->createContent($article, 1, '');
        $wordWriter = \PhpOffice\PhpWord\IOFactory::createWriter($this->phpWord, 'Word2007');
        $wordWriter->save($tmpName);
        rename($tmpName, $savePath);
    }

    /**
     * Send down word header.
     *
     * @param  string    $fileName
     * @access public
     * @return void
     */
    public function sendDownWordHeader($fileName)
    {
        $fileName = str_replace('+', ' ', urlencode($fileName));

        unset($this->htmlDom);
        setcookie('downloading', 1, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);
        header('Content-Type: application/vnd.ms-word');
        header("Content-Disposition: attachment;filename=\"{$fileName}.docx\"");
        header('Cache-Control: max-age=0');

        $wordWriter = \PhpOffice\PhpWord\IOFactory::createWriter($this->phpWord, 'Word2007');
        $wordWriter->save('php://output');

        return helper::end();
    }

    /**
     * Init PHPWord.
     *
     * @param  string $headerName
     * @access public
     * @return void
     */
    public function initPHPWord($headerName = '')
    {
        $this->app->loadClass('phpword', true);

        $this->htmlDom = new simple_html_dom();

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();

        foreach($this->config->word->size->titles as $id => $title) $phpWord = $this->addTitleStyle($phpWord, $id);
        $phpWord->addParagraphStyle('pStyle', array('lineHeight' => 1.5));

        //打开时自动重新计算字段
        if(!$this->exportedArticle) $phpWord->getSettings()->setUpdateFields(true);

        //关闭拼写和语法检查，大内容文档可以提高打开速度
        $phpWord->getSettings()->setHideGrammaticalErrors(true);
        $phpWord->getSettings()->setHideSpellingErrors(true);

        //设置页码
        $footer = $section->addFooter();
        /* bold 设置字体粗体；alignment：设置对齐方式。 */
        $footer->addPreserveText('{PAGE} / {NUMPAGES}', array('bold' => true), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END));

        if($headerName)
        {
            $phpWord->addParagraphStyle('headerStyle', array('align' => 'center'));
            $phpWord->addTitleStyle(1, array('size' => 20, 'color' => '010101', 'bold' => true));
            $phpWord->addTitleStyle(2, array('size' => 18, 'color' => '666666'));
            $phpWord->addTitleStyle(3, array('size' => 16, 'italic' => true));
            $phpWord->addTitleStyle(4, array('size' => 14));
        }

        $this->phpWord = $phpWord;
        $this->section = $section;
        $this->host    = 'http://' . $this->server->http_host;

        $this->initialState = array(
            'phpword_object' => &$this->phpWord,
            'base_root' => $this->host,
            'base_path' => '/',

            'current_style' => array('size' => '11', 'spaceAfter' => true),
            'parents' => array(0 => 'body'),
            'list_depth' => 0,
            'context' => 'section',
            'pseudo_list' => TRUE,
            'pseudo_list_indicator_font_name' => 'Wingdings',
            'pseudo_list_indicator_font_size' => '7',
            'pseudo_list_indicator_character' => 'l',
            'table_allowed' => TRUE,
            'treat_div_as_paragraph' => TRUE,

            'style_sheet' => htmltodocx_styles_example()
        );
    }

    /**
     * Get export data.
     *
     * @param  object $lib
     * @access public
     * @return void
     */
    public function getExportData($lib)
    {
        $libType = 'doc';
        if($lib->type == 'api')  $libType = 'api';
        if($lib->type == 'book') $libType = 'wiki';

        $func = '';
        if($libType == 'doc')  $func = 'getDocExportData';
        if($libType == 'api')  $func = 'getAPIExportData';
        if($libType == 'wiki') $func = 'getWikiExportData';

        $exportData = array();
        if($func) $exportData = $this->file->{$func}($lib->id);

        $this->tops     = zget($exportData, 'tops');
        $this->chapters = zget($exportData, 'chapters');
        $this->articles = zget($exportData, 'articles');
        $this->files    = zget($exportData, 'files');
    }

    /**
     * Export lib to doc.
     *
     * @param  array  $tops
     * @param  int    $step
     * @access public
     * @return void
     */
    public function exportLib2Doc($tops, $step)
    {
        foreach($tops as $top)
        {
            $order = $this->getNextOrder($this->order, $step);
            $this->createWord($top, $step, $order);
        }
    }

    /**
     * Export lib to ZIP.
     *
     * @param  array  $tops
     * @param  string $savePath
     * @access public
     * @return void
     */
    public function exportLib2Zip($tops, $savePath)
    {
        foreach($tops as $top) $this->createDir($top, $savePath);
    }

    /**
     * Get safe fileName.
     *
     * @param  string    $fileName
     * @access public
     * @return string
     */
    public function getSafeFileName($fileName)
    {
        $fileNameLength = 80;
        $fileName = preg_replace('/^[\+\-\.]+/', '', $fileName);
        $fileName = str_replace(array('\\', '/', ':', '*', '?', '"', '<', '>', '|'), '', $fileName);
        $fileName = mb_substr($fileName, 0, $fileNameLength);
        return $fileName;
    }
}
