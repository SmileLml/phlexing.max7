<?php
$reviewDesc =
'<div class="detail">
  <div class="detail-title">' . $this->lang->file->common . ' <i class="icon icon-paper-clip icon-sm"></i></div>
  <div class="detail-content">
    <ul class="files-list">';
      foreach($files as $file)
      {
          $uploadDate = $this->lang->file->uploadDate . substr($file->addedDate, 0, 10);
          $fileTitle  = "<i class='icon icon-file-text'></i> &nbsp;" . $file->title;
          if(strpos($file->title, ".{$file->extension}") === false && $file->extension != 'txt') $fileTitle .= ".{$file->extension}";
          $imageWidth = 0;
          if(stripos('jpg|jpeg|gif|png|bmp', $file->extension) !== false)
          {
              $imageSize  = $this->file->getImageSize($file);
              $imageWidth = $imageSize[0];
          }

          $fileSize = 0;
          /* Show size info. */
          if($file->size < 1024)
          {
              $fileSize = $file->size . 'B';
          }
          elseif($file->size < 1024 * 1024)
          {
              $file->size = round($file->size / 1024, 2);
              $fileSize = $file->size . 'K';
          }
          elseif($file->size < 1024 * 1024 * 1024)
          {
              $file->size = round($file->size / (1024 * 1024), 2);
              $fileSize = $file->size . 'M';
          }
          else
          {
              $file->size = round($file->size / (1024 * 1024 * 1024), 2);
              $fileSize = $file->size . 'G';
          }

          $downloadLink  = helper::createLink('file', 'download', "fileID=$file->id");
          $previewLink   = helper::createLink('file', 'preview', "fileID=$file->id");
          $reviewDesc .= "<li class='file' title='{$uploadDate}'>" . html::a($downloadLink, $fileTitle . " <span class='text-muted'>({$fileSize})</span>", '_blank', "id='fileTitle$file->id'  onclick=\"return downloadFile($file->id, '$file->extension', $imageWidth, '$file->title')\"");


          $reviewDesc .= "<span class='right-icon hidden'>&nbsp; ";

          /* Determines whether the file supports preview. */
          if($file->extension == 'txt')
          {
              $extension = 'txt';
              if(($postion = strrpos($file->title, '.')) !== false) $extension = substr($file->title, $postion + 1);
              if($extension != 'txt') $mode = 'down';
              $file->extension = $extension;
          }

          /* For the open source version of the file judgment. */
          if(stripos('txt|jpg|jpeg|gif|png|bmp|mp4', $file->extension) !== false and common::hasPriv('file', 'preview'))
          {
              $reviewDesc .= html::a($previewLink, "<i class='icon icon-eye'></i>", '_blank', "class='fileAction btn btn-link text-primary' title='{$this->lang->file->preview}' onclick=\"return downloadFile($file->id, '$file->extension', $imageWidth, '$file->title', 'preview')\"");
          }

          /* For the max version of the file judgment. */
          if(isset($this->config->file->libreOfficeTurnon) and $this->config->file->libreOfficeTurnon == 1 and !($this->config->file->convertType == 'collabora' and $this->config->requestType == 'GET') and common::hasPriv('file', 'preview'))
          {
              $officeTypes = 'doc|docx|xls|xlsx|ppt|pptx|pdf';
              if(stripos($officeTypes, $file->extension) !== false)
              {
                  $reviewDesc .= html::a($previewLink, "<i class='icon icon-eye'></i>", '_blank', "class='fileAction btn btn-link text-primary' title='{$this->lang->file->preview}' onclick=\"return downloadFile($file->id, '$file->extension', $imageWidth, '$file->title', 'preview')\"");
              }
          }

          if(common::hasPriv('file', 'download')) $reviewDesc .= html::a($downloadLink, "<i class='icon icon-download'></i>", '_blank', "class='fileAction btn btn-link text-primary' title='{$this->lang->file->downloadFile}'");

          if($showEdit and common::hasPriv('file', 'edit')) $reviewDesc .= html::a('###', "<i class='icon icon-pencil-alt'></i>", '', "id='renameFile$file->id' class='fileAction btn btn-link edit text-primary' onclick='showRenameBox($file->id)' title='{$this->lang->file->edit}'");
          if($showDelete and common::hasPriv('file', 'delete')) $reviewDesc .= html::a('###', "<i class='icon icon-trash'></i>", '', "class='fileAction btn btn-link text-primary' onclick='deleteFile($file->id, this)' title='{$this->lang->delete}'");

          $reviewDesc .= '</span>';

          $reviewDesc .= '</li>';

          $reviewDesc .= "<li class='file hidden'>
            <div>";
              if(strrpos($file->title, '.') !== false)
              {
                  /* Fix the file name exe.exe */
                  $title     = explode('.', $file->title);
                  $extension = end($title);
                  if($file->extension == 'txt' && $extension != $file->extension) $file->extension = $extension;
                  array_pop($title);
                  $file->title = join('.', $title);
              }
             $reviewDesc .= " <div class='renameFile w-300px' id='renameBox$file->id'>
                <i class='icon icon-file-text'></i>
                <div class='input-group'>";
              $reviewDesc .= '<input type="text" id="fileName' . $file->id . '" value="' . $file->title .'" class="form-control"/>
                  <input type="hidden" id="extension' . $file->id . '" value="' . $file->extension .'"/>
                  <strong class="input-group-addon">.' . $file->extension . '</strong>
                </div>
                <div class="input-group-btn">
                  <button type="button" class="btn btn-success file-name-confirm" onclick="setFileName(' . $file->id . ')" style="border-radius: 0px 2px 2px 0px; border-left-color: transparent;"><i class="icon icon-check"></i></button>
                  <button type="button" class="btn btn-gray file-name-cancel" onclick="showFile(' . $file->id . ')" style="border-radius: 0px 2px 2px 0px; border-left-color: transparent;"><i class="icon icon-close"></i></button>
                </div>
              </div>
            </div>
          </li>';
    }
    $reviewDesc .= '</ul>
  </div>
</div>';
return $reviewDesc;
