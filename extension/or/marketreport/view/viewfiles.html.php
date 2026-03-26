<style>
.card-container {display: grid; grid-template-columns: repeat(3, 1fr); gap: 4px 20px; background-color: unset;}
.card {position: relative; border-radius: 4px;}
.card::before {content: ' '; display: block; padding-bottom: 56.25%;}
.con {display: flex; justify-content: center; align-items: center; position: absolute; top: 0; bottom: 0; left: 0; right: 0;}
.title {color: white; display: inline-block; text-align: left; padding: 0 20px; max-height: 100%; overflow: hidden; text-overflow: ellipsis;}
.card-container li {list-style-type: none;}
.item li.file {padding-left: 6px;}
</style>

<div class="card-container cards cards-borderless">
  <?php foreach($files as $file):?>
    <div class="item">
      <div class="card bg-primary">
        <div class="con">
        <h2 id=<?php echo 'cardContent' . $file->id?> class="title">
          <?php
            $fileTitle = $file->title;
            if(strpos($file->title, ".{$file->extension}") === false && $file->extension != 'txt') $fileTitle .= ".{$file->extension}";
            $fileName = substr($fileTitle, 0, strrpos($fileTitle, '.', 0));
            echo $fileName;
          ?>
          </h2>
        </div>
      </div>
      <div>
        <?php
        $uploadDate = $lang->file->uploadDate . substr($file->addedDate, 0, 10);
        $imageWidth = 0;
        if(stripos('jpg|jpeg|gif|png|bmp', $file->extension) !== false)
        {
            $imageSize  = $this->loadModel('file')->getImageSize($file);
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

        $downloadLink  = $this->createLink('file', 'download', "fileID=$file->id");
        $previewLink   = $this->createLink('file', 'preview', "fileID=$file->id");
        $objectType    = zget($this->config->file->objectType, $file->objectType);
        if(common::hasPriv($objectType, 'view', $object))
        {
            echo "<span class='right-icon invisible'>";

            /* Determines whether the file supports preview. */
            if($file->extension == 'txt')
            {
                $extension = 'txt';
                if(($postion = strrpos($file->title, '.')) !== false) $extension = substr($file->title, $postion + 1);
                if($extension != 'txt') $mode = 'down';
                $file->extension = $extension;
            }

            /* For the open source version of the file judgment. */
            if(stripos('txt|jpg|jpeg|gif|png|bmp', $file->extension) !== false and common::hasPriv('file', 'preview'))
            {
                echo html::a($previewLink, "<i class='icon icon-eye'></i>", '_blank', "class='fileAction btn btn-link text-primary' title='{$lang->file->preview}' onclick=\"return downloadFile($file->id, '$file->extension', $imageWidth, '$file->title', 'preview')\"");
            }

            /* For the max version of the file judgment. */
            if(isset($this->config->file->libreOfficeTurnon) and $this->config->file->libreOfficeTurnon == 1 and !($this->config->file->convertType == 'collabora' and $this->config->requestType == 'GET') and common::hasPriv('file', 'preview'))
            {
                $officeTypes = 'doc|docx|xls|xlsx|ppt|pptx|pdf';
                if(stripos($officeTypes, $file->extension) !== false)
                {
                    echo html::a($previewLink, "<i class='icon icon-eye'></i>", '_blank', "class='fileAction btn btn-link text-primary' title='{$lang->file->preview}' onclick=\"return downloadFile($file->id, '$file->extension', $imageWidth, '$file->title', 'preview')\"");
                }
            }

            if(common::hasPriv($objectType, 'edit', $object))
            {
                if($showEdit and common::hasPriv('file', 'edit')) echo html::a('###', "<i class='icon icon-pencil-alt'></i>", '', "id='renameFile$file->id' class='fileAction btn btn-link edit text-primary' onclick='showRenameBox($file->id)' title='{$lang->file->edit}'");
            }
            common::printLink('file', 'download', "fileID=$file->id", "<i class='icon icon-download'></i>", '_blank', "class='fileAction btn btn-link text-primary' title='{$lang->file->downloadFile}'");
            if(common::hasPriv($objectType, 'edit', $object))
            {
                if($showDelete and common::hasPriv('file', 'delete')) echo html::a('###', "<i class='icon icon-trash'></i>", '', "class='fileAction btn btn-link text-primary' onclick='deleteFile($file->id, this)' title='$lang->delete'");
            }
            echo '</span>';
        }
        ?>

        <li class='file hidden'>
          <div>
            <?php
            if(strrpos($file->title, '.') !== false)
            {
                /* Fix the file name exe.exe */
                $title     = explode('.', $file->title);
                $extension = end($title);
                if($file->extension == 'txt' && $extension != $file->extension) $file->extension = $extension;
                array_pop($title);
                $file->title = join('.', $title);
            }
            ?>
            <div class='renameFile w-300px' id='renameBox<?php echo $file->id;?>'>
              <div class='input-group'>
                <input type="text" id="<?php echo 'fileName' . $file->id?>" value="<?php echo $file->title;?>" class='form-control'/>
                <input type="hidden" id="extension<?php echo $file->id?>" value="<?php echo $file->extension;?>"/>
                <strong class='input-group-addon'>.<?php echo $file->extension;?></strong>
              </div>
              <div class="input-group-btn">
                <button type="button" class="btn btn-success file-name-confirm" onclick="setFileName(<?php echo $file->id;?>)" style="border-radius: 0px 2px 2px 0px; border-left-color: transparent;"><i class="icon icon-check"></i></button>
                <button type="button" class="btn btn-gray file-name-cancel" onclick="showFile(<?php echo $file->id;?>)" style="border-radius: 0px 2px 2px 0px; border-left-color: transparent;"><i class="icon icon-close"></i></button>
              </div>
            </div>
          </div>
        </li>
      </div>
    </div>
  <?php endforeach;?>
</div>
<div id="showError" style="display: none"></div>
