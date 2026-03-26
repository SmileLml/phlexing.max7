<?php
/**
 * The model file of excel module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yue Ma <mayue@easycorp.ltd>
 * @package     excel
 * @link        https://www.zentao.net
 */
class excelUser extends userModel
{
    /**
     * Set list value for export excel.
     *
     * @access public
     * @return void
     */
    public function setListValue()
    {
        $roleList   = $this->lang->user->roleList;
        $genderList = $this->lang->user->genderList;
        $typeList   = $this->lang->user->typeList;

        $depts = $this->loadModel('dept')->getOptionMenu();
        foreach($depts as $id => $dept) $depts[$id] = "$dept(#$id)";

        $this->post->set('deptList',   $depts);
        $this->post->set('roleList',   $roleList);
        $this->post->set('typeList',   $typeList);
        $this->post->set('genderList', $genderList);
        $this->post->set('listStyle', $this->config->user->export->listFields);
        $this->post->set('extraNum', 0);
    }

    /**
     * Create from excel.
     *
     * @access public
     * @return void
     */
    public function createFromImport()
    {
        if(empty($_POST['verifyPassword']) or $this->post->verifyPassword != md5($this->app->user->password . $this->session->rand)) dao::$errors['verifyPassword'] = $this->lang->user->error->verifyPassword;
        if(dao::isError()) return false;

        $users     = fixer::input('post')->get();
        $data      = array();
        $daoErrors = array();
        for($i = 1; $i <= $this->config->file->maxImport; $i++)
        {
            $users->account[$i] = empty($users->account[$i]) ? '' : trim($users->account[$i]);
            if(empty($users->account[$i])) continue;

            $users->password[$i] = $this->post->password[$i];
            if(isset($users->type[$i])) $users->type[$i] = $users->type[$i];
            $userGroup = isset($users->group[$i]) ? $users->group[$i] : array();
            $visions   = isset($users->visions[$i]) ? $users->visions[$i] : array();

            $data[$i] = new stdclass();
            $data[$i]->dept     = $users->dept[$i];
            $data[$i]->account  = $users->account[$i];
            $data[$i]->type     = !empty($users->type[$i]) ? $users->type[$i] : 'inside';
            $data[$i]->realname = $users->realname[$i];
            $data[$i]->role     = isset($users->role[$i]) ? $users->role[$i] : '';
            $data[$i]->group    = $userGroup;
            $data[$i]->email    = $users->email[$i];
            $data[$i]->gender   = isset($users->gender[$i]) ? $users->gender[$i] : 'm';
            $data[$i]->password = trim($users->password[$i]);
            $data[$i]->join     = !empty($users->join[$i]) ? $users->join[$i] : null;
            $data[$i]->qq       = $users->qq[$i];
            $data[$i]->weixin   = $users->weixin[$i];
            $data[$i]->mobile   = $users->mobile[$i];
            $data[$i]->phone    = $users->phone[$i];
            $data[$i]->address  = $users->address[$i];
            $data[$i]->visions  = join(',', $visions);

            /* Check required fields. */
            foreach(explode(',', $this->config->user->create->requiredFields) as $field)
            {
                $field = trim($field);
                if(empty($field)) continue;

                if(!isset($data[$i]->$field)) continue;
                if(!empty($data[$i]->$field)) continue;

                if($field == 'visions')
                {
                    dao::$errors["{$field}[{$i}][]"] = sprintf($this->lang->error->notempty, $this->lang->user->$field);
                }
                else
                {
                    dao::$errors["{$field}[{$i}]"] = sprintf($this->lang->error->notempty, $this->lang->user->$field);
                }
            }

            /* Change for append field, such as feedback. */
            if(!empty($this->config->user->batchAppendFields))
            {
                $appendFields = explode(',', $this->config->user->batchAppendFields);
                foreach($appendFields as $appendField)
                {
                    if(empty($appendField)) continue;
                    if(!isset($users->$appendField)) continue;
                    $fieldList = $users->$appendField;
                    $data[$i]->$appendField = $fieldList[$i];
                }
            }
        }
        if(dao::isError()) return false;

        $this->checkBeforeBatchCreate($data, $this->post->verifyPassword);
        if(dao::isError()) return false;

        $this->checkBeforeBatchCreate($data, $this->post->verifyPassword);
        if(dao::isError()) return false;

        $this->loadModel('mail');
        $userIDList = array();
        foreach($data as $user)
        {
            $userGroups = $user->group;
            $user->password = md5($user->password);
            $this->dao->insert(TABLE_USER)->data($user, 'group')->autoCheck()
                ->checkIF($user->email  != '', 'email',  'email')
                ->checkIF($user->phone  != '', 'phone',  'phone')
                ->checkIF($user->mobile != '', 'mobile', 'mobile')
                ->exec();

            /* Fix bug #2941 */
            $userID       = $this->dao->lastInsertID();
            $userIDList[] = $userID;
            $this->loadModel('action')->create('user', $userID, 'Created');

            if(dao::isError())
            {
                $daoErrors = dao::getError();
            }
            else
            {
                if(is_array($userGroups))
                {
                    foreach($userGroups as $group)
                    {
                        $groups = new stdClass();
                        $groups->account = $user->account;
                        $groups->group   = (int)$group;
                        $this->dao->insert(TABLE_USERGROUP)->data($groups)->exec();
                    }
                }

                $this->computeUserView($user->account);
                if($this->config->mail->mta == 'sendcloud' and !empty($user->email)) $this->mail->syncSendCloud('sync', $user->email, $user->realname);
            }
        }
        if(!empty($daoErrors)) dao::$errors = $daoErrors;

        $this->loadModel('instance');
        if(method_exists($this->instance, 'initSyncData')) $this->instance->initSyncData($userIDList);
        return $userIDList;
    }
}
