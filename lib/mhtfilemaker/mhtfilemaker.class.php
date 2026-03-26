<?php
/***********************************************************************
Class:        Mht File Maker
Version:      1.2 beta
Date:         02/11/2007
Author:       Wudi <wudicgi@yahoo.de>
Description:  The class can make .mht file.
***********************************************************************/

class MhtFileMaker
{
    var $config = array();
    var $headers = array();
    var $headersExists = array();
    var $files = array();
    var $boundary;
    var $dirBase;
    var $pageFirst;

    public function __construct($config = array())
    {
        $this->config = $config;
    }

    public function setHeader($header)
    {
        $this->headers[] = $header;
        $key = strtolower(substr($header, 0, strpos($header, ':')));
        $this->headersExists[$key] = TRUE;
    }

    public function setFrom($from)
    {
        $this->setHeader("From: $from");
    }

    public function setSubject($subject)
    {
        $this->setHeader("Subject: $subject");
    }

    public function setDate($date = NULL, $istimestamp = FALSE)
    {
    	if($date == NULL) $date = time();
        if($istimestamp)  $date = date('D, d M Y H:i:s O', $date);
        $this->setHeader("Date: $date");
    }

    public function setBoundary($boundary = NULL)
    {
        $this->boundary = $boundary;
        if($boundary == NULL) $this->boundary = '--' . strtoupper(md5(mt_rand())) . '_MULTIPART_MIXED';
    }

    public function setBaseDir($dir)
    {
        $this->dirBase = str_replace("\\", "/", realpath($dir));
    }

    public function setFirstPage($filename)
    {
        $this->pageFirst = str_replace("\\", "/", realpath("{$this->dirBase}/$filename"));
    }

    public function autoAddFiles()
    {
        if(!isset($this->pageFirst)) die('Not set the first page.');

        $filepath = str_replace($this->dirBase, '', $this->pageFirst);
        $filepath = 'http://mhtfile' . $filepath;
        $this->addFile($this->pageFirst, $filepath, NULL);
        $this->addDir($this->dirBase);
    }

    public function addDir($dir)
    {
        $handleDir = opendir($dir);
        while($filename = readdir($handleDir))
        {
            if(($filename != '.') && ($filename != '..') && ("$dir/$filename" != $this->pageFirst))
            {
                if(is_dir("$dir/$filename"))
                {
                    $this->addDir("$dir/$filename");
                }
                elseif(is_file("$dir/$filename"))
                {
                    $filepath = str_replace($this->dirBase, '', "$dir/$filename");
                    $filepath = 'http://mhtfile' . $filepath;
                    $this->addFile("$dir/$filename", $filepath, NULL);
                }
            }
        }
        closedir($handleDir);
    }

    public function addFile($filename, $filepath = NULL, $encoding = NULL)
    {
        if($filepath == NULL) $filepath = $filename;

        $mimetype = $this->GetMimeType($filename);
        $filecont = file_get_contents($filename);
        $this->addContents($filepath, $mimetype, $filecont, $encoding);
    }

    public function addContents($filepath, $mimetype, $filecont, $encoding = NULL)
    {
        if($encoding == NULL)
        {
            $filecont = chunk_split(base64_encode($filecont), 76);
            $encoding = 'base64';
        }
        $this->files[] = array('filepath' => $filepath,
                               'mimetype' => $mimetype,
                               'filecont' => $filecont,
                               'encoding' => $encoding);
    }

    public function checkHeaders()
    {
        if(!array_key_exists('date', $this->headersExists)) $this->setDate(NULL, TRUE);
        if ($this->boundary == NULL) $this->SetBoundary();
    }

    public function checkFiles()
    {
        if(count($this->files) == 0) return FALSE;
        return TRUE;
    }

    public function getFile()
    {
        $this->checkHeaders();
        if(!$this->checkFiles()) die('No file was added.');

        $contents = implode("\r\n", $this->headers);
        $contents .= "\r\n";
        $contents .= "MIME-Version: 1.0\r\n";
        $contents .= "Content-Type: multipart/related;\r\n";
        $contents .= "\tboundary=\"{$this->boundary}\";\r\n";
        $contents .= "\ttype=\"" . $this->files[0]['mimetype'] . "\"\r\n";
        $contents .= "X-MimeOLE: Produced By Mht File Maker v1.0 beta\r\n";
        $contents .= "\r\n";
        $contents .= "This is a multi-part message in MIME format.\r\n";
        $contents .= "\r\n";

        foreach($this->files as $file)
        {
            $contents .= "--{$this->boundary}\r\n";
            $contents .= "Content-Type: $file[mimetype]\r\n";
            $contents .= "Content-Transfer-Encoding: $file[encoding]\r\n";
            $contents .= "Content-Location: $file[filepath]\r\n";
            $contents .= "\r\n";
            $contents .= $file['filecont'];
            $contents .= "\r\n";
        }
        $contents .= "--{$this->boundary}--\r\n";
        return $contents;
    }

    public function makeFile($filename)
    {
        $contents = $this->getFile();
        $fp = fopen($filename, 'w');
        fwrite($fp, $contents);
        fclose($fp);
    }

    public function getMimeType($filename)
    {
        $pathinfo = pathinfo($filename);
        switch($pathinfo['extension'])
        {
            case 'htm':  $mimetype = 'text/html'; break;
            case 'html': $mimetype = 'text/html'; break;
            case 'txt':  $mimetype = 'text/plain'; break;
            case 'cgi':  $mimetype = 'text/plain'; break;
            case 'php':  $mimetype = 'text/plain'; break;
            case 'css':  $mimetype = 'text/css'; break;
            case 'jpg':  $mimetype = 'image/jpeg'; break;
            case 'jpeg': $mimetype = 'image/jpeg'; break;
            case 'jpe':  $mimetype = 'image/jpeg'; break;
            case 'gif':  $mimetype = 'image/gif'; break;
            case 'png':  $mimetype = 'image/png'; break;
            default: $mimetype = 'application/octet-stream'; break;
        }
        return $mimetype;
    }
}
?>
