<?php
class mhtFile extends fileModel
{
    public function getMhtDocument($content , $absolutePath = "", $isEraseLink = false)
    {
        $wwwRoot = $this->app->getWwwRoot();
        $mht     = $this->app->loadClass('mhtFileMaker');
        $webRoot = getWebRoot(true);
        if($isEraseLink) $content = preg_replace('/<a\s*.*?\s*>(\s*.*?\s*)<\/a>/i' , '$1' , $content);

        $images = array();
        $files  = array();
        if(preg_match_all('/<img.+src\s*=\s*[\"\']([^\"\']+)[\"\'](.*)\/>/Ui', $content, $matches))
        {
            foreach($matches[1] as $imgPath)
            {
                $imgPath = trim($imgPath);
                if(preg_match('/^data:image\/\w+;base64,/i', $imgPath)) continue;
                if($imgPath)
                {
                    $fileID = 0;
                    $readLinkReg = helper::createLink('file', 'read', 'fileID=(%fileID%)', '%viewType%');
                    $readLinkReg = str_replace(array('%fileID%', '%viewType%', '?', '/'), array('[0-9]+', '\w+', '\?', '\/'), $readLinkReg);
                    preg_match_all("/$readLinkReg/", $imgPath, $matches);
                    if($matches[0]) $fileID = $matches[1][0];
                    if(empty($fileID))
                    {
                        preg_match_all("/{(\d+)\.\w+}/", $imgPath, $matches);
                        if($matches[0]) $fileID = $matches[1][0];
                    }

                    if($fileID)
                    {
                        $file     = $this->getById($fileID);
                        $files[]  = $imgPath;
                        $images[] = $this->loadModel('file')->saveAsTempFile($file); // Save file to temp folder.
                        $content  = str_replace($matches[0][0], $imgPath, $content);
                    }
                    else
                    {
                        $files[] = $imgPath;
                        if(strpos($imgPath, $webRoot) !== false)
                        {
                            $imgPath = str_replace($webRoot, $wwwRoot, $imgPath);
                        }
                        else
                        {
                            $imgPath = $wwwRoot . ltrim($imgPath, '/');
                        }
                        $images[] = $imgPath;
                    }
                }
            }
        }
        $mht->AddContents("tmp.html", $mht->GetMimeType("tmp.html"), $content);

        foreach($images as $i => $image)
        {
            if(file_exists($image))
            {
                $imgContent = @file_get_contents($image);
                if($imgContent) $mht->AddContents($files[$i], $mht->GetMimeType($image), $imgContent);
            }
            else
            {
                echo "file:" . $image . " not exist!<br />";
            }
        }

        return $mht->GetFile();
    }
}
