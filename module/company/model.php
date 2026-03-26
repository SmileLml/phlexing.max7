<?php
/**
 * The model file of company module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     company
 * @version     $Id: model.php 5086 2013-07-10 02:25:22Z wyd621@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php
class companyModel extends model
{
    /**
     * 获取第一家公司。
     * Get the first company.
     *
     * @access public
     * @return object|bool
     */
    public function getFirst()
    {
        return $this->dao->select('*')->from(TABLE_COMPANY)->orderBy('id')->limit(1)->fetch();
    }

    /**
     * 根据id获取公司信息。
     * Get company info by id.
     *
     * @param  int    $companyID
     * @access public
     * @return object|bool
     */
    public function getByID($companyID)
    {
        return $this->dao->findById($companyID)->from(TABLE_COMPANY)->fetch();
    }

    /**
     * 获取用户。
     * Get users.
     *
     * @param  string     $browseType
     * @param  string     $type
     * @param  string|int $queryID
     * @param  int        $deptID
     * @param  string     $sort
     * @param  object     $pager
     * @access public
     * @return array
     */
    public function getUsers($browseType = 'inside', $type = '', $queryID = 0, $deptID = 0, $sort = '', $pager = null)
    {
        if($type == 'bydept')
        {
            $childDeptIds = $this->loadModel('dept')->getAllChildID($deptID);
            return $this->dept->getUsers($browseType, $childDeptIds, $sort, $pager);
        }
        else
        {
            if($queryID)
            {
                $query = $this->loadModel('search')->getQuery($queryID);
                if($query)
                {
                    $this->session->set('userQuery', $query->sql);
                    $this->session->set('userForm', $query->form);
                }
                else
                {
                    $this->session->set('userQuery', ' 1 = 1');
                }
            }
            return $this->loadModel('user')->getByQuery($browseType, $this->session->userQuery, $pager, $sort);
        }
    }

    /**
     * 获取外部公司。
     * Get outside companies.
     *
     * @access public
     * @return array
     */
    public function getOutsideCompanies()
    {
        return $this->dao->select('id, name')->from(TABLE_COMPANY)->where('id')->ne(1)->fetchPairs();
    }

    /**
     * 更新公司信息。
     * Update a company.
     *
     * @param  int    $companyID
     * @param  object $compnay
     * @access public
     * @return bool
     * @param object $company
     */
    public function update($companyID, $company)
    {
        $this->dao->update(TABLE_COMPANY)
            ->data($company)
            ->autoCheck()
            ->batchCheck($this->config->company->edit->requiredFields, 'notempty')
            ->batchCheck('name', 'unique', "id != '$companyID'")
            ->where('id')->eq($companyID)
            ->exec();

        return !dao::isError();
    }

    /**
     * 搭建搜索表单。
     * Build search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildSearchForm($queryID, $actionURL)
    {
        $this->config->company->browse->search['actionURL'] = $actionURL;
        $this->config->company->browse->search['queryID']   = $queryID;
        $this->config->company->browse->search['params']['dept']['values']    = $this->loadModel('dept')->getOptionMenu();
        $this->config->company->browse->search['params']['visions']['values'] = getVisions();

        $this->loadModel('search')->setSearchParams($this->config->company->browse->search);
    }
}
