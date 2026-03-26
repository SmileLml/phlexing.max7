<?php
/**
 * The footer mobile view of common module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2016 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fei Chen <chenfei@cnezsoft.com>
 * @package     common 
 * @version     $Id: header.lite.html.php 3299 2015-12-02 02:10:06Z daitingting $
 * @link        http://www.zentao.net
 */

if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}
if(isset($pageJS)) js::execute($pageJS);

/* Load hook files for current page. */
$extPath      = dirname(dirname(dirname(__FILE__))) . '/common/ext/view/';
$extHookRule  = $extPath . $config->devicePrefix['mhtml'] . 'footer.*.hook.php';
$extHookFiles = glob($extHookRule);
if($extHookFiles) foreach($extHookFiles as $extHookFile) include $extHookFile;
?>
<script>
$(document).ready(function()
{
    $(document).on('click', 'button[type="button"]', function()
    {
        $('button[type="button"]').attr('disabled', 'disabled').addClass('disabled loading');
        setTimeout(function()
        {
            $('button[type="button"]').attr('disabled', null).removeClass('disabled loading');
        }, 2000)
    });
    
    $(document).on('submit', 'form', function()
    {
        var $submitButton = $('button[type="submit"]');
        $submitButton.attr('disabled', 'disabled').addClass('disabled loading');
        setTimeout(function()
        {
            $submitButton.attr('disabled', null).removeClass('disabled loading');
        }, 2000)
    });
})
</script>
<?php if(isonlybody()):?>
<script>
href = location.href;
if(href.indexOf('onlybody=yes') > 0)
{
    href = href.replace('?onlybody=yes', '');
    location.href = href.replace('&onlybody=yes', '');
}
$(function()
{
    $('.display.modal #page.list-with-actions').removeClass('list-with-actions');
})
</script>
<?php return false;?>
<?php endif;?>
<iframe name="hiddenwin" id="hiddenwin" scrolling="no" class="debugwin hidden" frameborder="0"></iframe>
</body>
</html>
