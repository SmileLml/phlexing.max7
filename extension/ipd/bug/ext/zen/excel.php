<?php
/**
 * 为批量导入 bugs 构造数据。
 * Build bugs for the batch import.
 *
 * @access protected
 * @return array
 */
protected function buildBugsForImport()
{
    /* Get bug ID list. */
    $bugIdList = $this->post->id ? $this->post->id : array();
    if(empty($bugIdList)) return array();

    /* Get bugs. */
    $bugs = form::batchData($this->config->bug->form->import)->get();

    $this->app->loadClass('purifier', true);
    $purifierConfig = HTMLPurifier_Config::createDefault();
    $purifierConfig->set('Filter.YouTube', 1);
    $purifier = new HTMLPurifier($purifierConfig);

    /* Process bugs. */
    foreach($bugs as $index => $bug)
    {
        if(is_array($bug->os))          $bug->os          = implode(',', $bug->os);
        if(is_array($bug->browser))     $bug->browser     = implode(',', $bug->browser);
        if(is_array($bug->openedBuild)) $bug->openedBuild = implode(',', $bug->openedBuild);

        $bug->steps = nl2br($purifier->purify($bug->steps));
    }
    return $bugs;
}
