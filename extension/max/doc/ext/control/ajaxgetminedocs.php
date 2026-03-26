<?php
class myDoc extends doc
{
    /**
     * Ajax get mine docs.
     *
     * @param  string $keyword
     * @access public
     * @return void
     */
    public function ajaxGetMineDocs($keyword = '')
    {
        $this->app->loadClass('pager', true);
        $pager = new pager(0, 30, 1);

        $docs    = $this->doc->getMySpaceDocs($keyword ? 'all' : 'view', 'bykeyword', $keyword, $keyword ? '' : 't2.date desc', $pager);
        $docList = array();
        foreach($docs as $doc) $docList[] = array('text' => $doc->title, 'value' => $doc->id, 'docVersion' => $doc->version);

        return $this->send($docList);
    }
}
