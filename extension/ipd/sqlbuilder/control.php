<?php
/**
 * sqlbuilder
 *
 * @copyright Copyright 2009-2022 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author    Xinzhi Qi <qixinzhi@chandao.com>
 * @package
 * @uses      control
 * @Link      https://www.zentao.net
 */
?>
<?php
class sqlbuilder extends control
{
    /**
     * index
     *
     * @param  int    $objectID
     * @param  string $objectType
     * @access public
     * @return void
     */
    public function index($objectID, $objectType)
    {
        $this->loadModel('bi');

        $data = $this->sqlbuilder->getByObject($objectID, $objectType);
        if(!empty($_POST)) $data = $this->sqlbuilder->sqlBuilderAction();

        $this->view->objectID   = $objectID;
        $this->view->objectType = $objectType;
        $this->view->data       = $data;
        $this->view->tableList  = $this->bi->getTableList();
        $this->display();
    }

}
