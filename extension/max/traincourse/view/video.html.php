<div class='cell'>
  <div class='detail'>
    <div class='detail-content text-center videoplay'>
      <video src='<?php echo inlink('playVideo', "fileID=$file->id");?>' controls='controls' width='100%' controlsList='nodownload' onerror='showError()' ></video>
      <div class='playfailed hide'><?php echo $lang->traincourse->playFailed;?></div>
    </div>
  </div>
</div>
