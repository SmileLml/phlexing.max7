<?php
/**
 * The control file of report module of zentaopms.
 *
 * @copyright   copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     zpl (http://zpl.pub/page/zplv12.html)
 * @author      chunsheng wang <chunsheng@cnezsoft.com>
 * @package     report
 * @link        https://www.zentao.net
 */
helper::importControl('report');
class myReport extends report
{
    /**
     * Set the field name.
     *
     * @param  int    $reportID
     * @access public
     * @return void
     */
    public function ajaxSetLang($reportID = 0)
    {
        if($_POST)
        {
            $data  = fixer::input('post')->get();
            $langs = array();
            foreach($data->fieldName as $i => $fieldName)
            {
                $fieldName  = trim($fieldName);
                if(empty($fieldName)) continue;
                foreach($data->fieldValue as $fieldLang => $fieldValue)
                {
                    $fieldValue[$i] = trim($fieldValue[$i]);
                    if(empty($fieldValue[$i])) break;

                    $langs[$fieldName][$fieldLang] = $fieldValue[$i];
                }

            }

            $langs = json_encode($langs);
            $this->session->set('sqlLangs', $langs);
            if($reportID) $this->dao->update(TABLE_REPORT)->set('langs')->eq($langs)->where('id')->eq($reportID)->exec();
            die(js::closeModal('parent.parent'));
        }

        $langs    = $this->session->sqlLangs ? json_decode($this->session->sqlLangs) : array();
        $sqlLangs = new stdclass();
        $sqlLangs->fieldName  = array();
        $sqlLangs->fieldValue = array();

        $i = 0;
        foreach($langs as $fieldName => $fieldLangs)
        {
            foreach($fieldLangs as $fieldLang => $fieldValue)
            {
                $sqlLangs->fieldName[$i]  = $fieldName;
                $sqlLangs->fieldValue[$fieldLang][$i] = $fieldValue;
            }
            $i++;
        }

        $this->view->sqlLangs = $sqlLangs;
        $this->display();
    }
}
