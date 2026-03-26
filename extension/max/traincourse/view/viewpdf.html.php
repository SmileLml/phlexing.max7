<style>
body{padding-bottom:0px;}
</style>
<iframe src='<?php echo $this->createLink('traincourse', 'viewPDF', "chapterID={$chapter->id}");?>' border='0' scrolling="no" allowfullscreen style='width:100%;display:block;top:0;z-index:60;' id='pdf' name='pdf' height='800'></iframe>
<script>
$(function()
{
    function fullscreen()
    {
        return document.getElementById('pdf').requestFullscreen();
    }

    $('#pdfFullscreen').click(fullscreen);
})
</script>
