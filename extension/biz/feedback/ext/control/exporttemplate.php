<?php
helper::importControl('feedback');
class myfeedback extends feedback
{
    /**
     * Export template.
     *
     * @access public
     * @return void
     */
    public function exportTemplate()
    {
        if($_POST)
        {
            $this->config->feedback->dtable->fieldList['product']['dataSource'] = array('module' => 'feedback', 'method' => 'getGrantProducts', 'params' => 'true');
            $this->feedback->setListValue();
            $this->fetch('transfer', 'exportTemplate', 'model=feedback');
        }

        $this->loadModel('transfer');

        $this->display();
    }
}
