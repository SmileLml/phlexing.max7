<?php
class myDoc extends doc
{
    /**
     * Ajax get docs by lib.
     *
     * @param  int    $libID
     * @param  string $viewType
     * @access public
     * @return void
     */
    public function ajaxGetDocs($libID, $viewType = 'html')
    {
        if(!$libID)
        {
            if($viewType == 'json') return print(json_encode(array()));
            return print(html::select('doc', '', '', "class='form-control chosen'"));
        }

        $docIdList = $this->doc->getPrivDocs(array($libID), 0);
        $docs = $this->dao->select('id, title')->from(TABLE_DOC)
            ->where('lib')->eq($libID)
            ->andWhere('deleted')->eq(0)
            ->andWhere('status')->eq('normal')
            ->andWhere('id')->in($docIdList)
            ->orderBy('order_asc,id_desc')
            ->fetchPairs();
        if($viewType == 'json')
        {
            $items = array();
            foreach($docs as $key => $value) $items[] = array('value' => $key, 'text' => $value);
            return print(json_encode($items));
        }

        return print(html::select('doc', $docs, '', "class='form-control chosen'"));
    }
}
