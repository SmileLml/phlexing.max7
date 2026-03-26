<?php
helper::importControl('convert');
class myConvert extends convert
{
    /**
     * 将confluence对象映射到zentao。
     * Map confluence space to zentao.
     *
     * @access public
     * @return void
     */
    public function mapConfluence2Zentao()
    {
        if($_POST)
        {
            foreach($_POST['zentaoDocLib'] as $key => $value)
            {
                if(!empty($_POST['zentaoSpace'][$key]) && empty($value)) dao::$errors['message'] = $this->lang->convert->confluence->importSpace;
            }
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            foreach($_POST as $key => $value) $confluenceRelation[$key] = $value;
            $this->session->set('confluenceRelation', json_encode($confluenceRelation));

            return $this->send(array('result' => 'success', 'load' => inlink('initConfluenceUser')));
        }

        $relation = $this->session->confluenceRelation;
        $relation = $relation ? json_decode($relation, true) : array();

        $this->view->title       = $this->lang->convert->confluence->mapToZentao;
        $this->view->relation    = $relation;
        $this->view->spaceList   = $this->convert->getConfluenceData('space');
        $this->view->zentaoSpace = $this->convert->getZentaoSpace();
        $this->view->products    = $this->convert->getZentaoDocLib('product');
        $this->view->projects    = $this->convert->getZentaoDocLib('project');
        $this->display();
    }
}
