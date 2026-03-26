<?php
helper::importControl('bug');
class myBug extends bug
{
    /**
     * @param int $productID
     * @param string $branch
     * @param string $extras
     */
    public function create($productID, $branch = '', $extras = '')
    {
        $realExtras = $extras;
        $extras     = str_replace(array(',', ' '), array('&', ''), $extras);
        parse_str($extras, $params);

        $this->view->from     = !empty($params['from'])     ? $params['from']     : '';
        $this->view->fromType = !empty($params['fromType']) ? $params['fromType'] : '';
        $this->view->fromID   = !empty($params['fromID'])   ? $params['fromID']   : '';

        if(empty($params['fromType'])) return parent::create($productID, $branch, $extras);

        /* Set feedback Menu. */
        if($params['fromType'] == 'feedback')
        {
            $this->lang->feedback->menu->browse['subModule'] = 'bug';
            $this->loadModel('feedback')->setMenu($productID, 'feedback', $realExtras);
        }

        return parent::create($productID, $branch, $extras);
    }
}
