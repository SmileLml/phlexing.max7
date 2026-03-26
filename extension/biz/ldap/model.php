<?php
/**
 * The model file of ldap module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     ldap
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class ldapModel extends model
{
    public function saveSettings()
    {
        $settings = fixer::input('post')->get();
        if(isset($settings->anonymous))
        {
            $settings->admin    = '';
            $settings->password = '';
        }

        /* check empty. */
        foreach(explode(',', $this->config->ldap->set->requiredFields) as $requiredField)
        {
            if(isset($settings->anonymous) and $requiredField == 'admin') continue;
            if(empty($settings->$requiredField))
            {
                dao::$errors[$requiredField] = sprintf($this->lang->ldap->error->noempty, $this->lang->ldap->$requiredField);
                return false;
            }
        }

        /* check right param*/
        if($settings->turnon)
        {
            $ldapConn = $this->ldapConnect($settings);
            if(!$ldapConn)
            {
                dao::$errors[] = $this->lang->ldap->error->connect;
                return false;
            }

            ldap_set_option($ldapConn, LDAP_OPT_PROTOCOL_VERSION, $settings->version);
            ldap_set_option($ldapConn, LDAP_OPT_REFERRALS, 0);
            if(isset($settings->anonymous))
            {
                $ldapBind = ldap_bind($ldapConn);
            }
            else
            {
                $ldapBind = ldap_bind($ldapConn, $settings->admin, html_entity_decode($settings->password));
            }
            if(!$ldapBind)
            {
                dao::$errors[] = $this->lang->ldap->error->verify;
                return false;
            }
        }

        /* To lower param. */
        foreach($settings as $settingKey => $settingValue)
        {
            if($settingKey == 'password') $settingValue = helper::encryptPassword($settingValue);
            if($settingKey == 'admin' or $settingKey == 'password' or  $settingKey == 'baseDN' or $settingKey == 'host')
            {
                $settings->$settingKey = $settingValue;
                continue;
            }
            $settings->$settingKey = strtolower($settingValue);
        }

        /* edit param.*/
        $this->loadModel('setting');
        $this->setting->deleteItems('owner=system&module=ldap');
        $this->setting->setItems('system.ldap', $settings);
    }

    /**
     * Build search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildSearchForm($queryID, $actionURL)
    {
        $ldapConfig = $this->config->ldap;

        $ldapConfig->import->search['fields'][$ldapConfig->account]  = $this->lang->ldap->account;
        $ldapConfig->import->search['fields']['CUSTOM']              = $this->lang->ldap->custom;
        $ldapConfig->import->search['fields'][$ldapConfig->realname] = $this->lang->ldap->realname;
        $ldapConfig->import->search['fields'][$ldapConfig->email]    = $this->lang->ldap->email;
        $ldapConfig->import->search['fields'][$ldapConfig->mobile]   = $this->lang->ldap->mobile;
        $ldapConfig->import->search['fields'][$ldapConfig->phone]    = $this->lang->ldap->phone;

        $ldapConfig->import->search['params'][$ldapConfig->account]  = array('operator' => '=',  'control' => 'input',  'values' => '');
        $ldapConfig->import->search['params']['CUSTOM']              = array('operator' => '=',  'control' => 'input',  'values' => '');
        $ldapConfig->import->search['params'][$ldapConfig->realname] = array('operator' => '=',  'control' => 'input',  'values' => '');
        $ldapConfig->import->search['params'][$ldapConfig->email]    = array('operator' => '=',  'control' => 'input',  'values' => '');
        $ldapConfig->import->search['params'][$ldapConfig->mobile]   = array('operator' => '=',  'control' => 'input',  'values' => '');
        $ldapConfig->import->search['params'][$ldapConfig->phone]    = array('operator' => '=',  'control' => 'input',  'values' => '');

        $ldapConfig->import->search['actionURL'] = $actionURL;
        $ldapConfig->import->search['queryID']   = $queryID;

        $this->loadModel('search')->setSearchParams($ldapConfig->import->search);
    }

    /**
     * Build search query.
     *
     * @access public
     * @return void
     */
    public function buildQuery()
    {
        $this->loadModel('search');

        $groupItems   = $this->config->search->groupItems;
        $groupAndOr   = strtoupper($this->post->groupAndOr);
        $searchParams = 'ldapsearchParams';
        $fieldParams  = json_decode($_SESSION[$searchParams]['fieldParams']);

        if($groupAndOr != 'AND' and $groupAndOr != 'OR') $groupAndOr = 'AND';

        $conditions = array();
        for($i = 1; $i <= $groupItems * 2; $i ++)
        {
            /* Set var names. */
            $fieldName    = "field$i";
            $operatorName = "operator$i";
            $valueName    = "value$i";

            /* Fix bug #2704. */
            $field = $this->post->$fieldName;
            if(isset($fieldParams->$field) and $fieldParams->$field->control == 'input' and $this->post->$valueName === '0') $this->post->$valueName = 'ZERO';

            /* Skip empty values. */
            if($this->post->$valueName == false) continue;
            if($this->post->$valueName == 'ZERO') $this->post->$valueName = 0;   // ZERO is special, stands to 0.

            /* Set operator. */
            $value    = trim($this->post->$valueName);
            $operator = $this->post->$operatorName;
            if(!isset($this->lang->search->operators[$operator])) $operator = '=';

            /* Set condition. */
            if($field == 'CUSTOM')
            {
                $conditions[$i] = "($value)";
            }
            else
            {
                if($operator == "include")
                {
                    $conditions[$i] = "($field=*$value*)";
                }
                elseif($operator == "notinclude")
                {
                    $conditions[$i] = "(!($field=*$value*))";
                }
                elseif($operator == '!=')
                {
                    $conditions[$i] = "(!($field=$value))";
                }
                else
                {
                    $conditions[$i] = "($field=$value)";
                }
            }
        }

		$wheres = array();
        $where  = '';
        for($i = 1; $i <= $groupItems; $i++)
        {
            if(!isset($conditions[$i])) continue;

            $condition = $conditions[$i];
            $andOrName = "andOr$i";
            if(!isset($_POST[$andOrName]))
            {
                $where = $condition;
            }
            else
            {
                $andOr = strtoupper($this->post->$andOrName);
                if($andOr != 'AND' and $andOr != 'OR') $andOr = 'AND';
                $where = empty($where) ? $condition : '(' . ($andOr == 'AND' ? '&' : '|') . $where . $condition . ')';
            }
        }
        if(!empty($where)) $wheres[] = $where;

        $where = '';
        for($i = $groupItems + 1; $i <= $groupItems * 2; $i++)
        {
            if(!isset($conditions[$i])) continue;

            $condition = $conditions[$i];
            $andOrName = "andOr$i";
            if(!isset($_POST[$andOrName]))
            {
                $where = $condition;
            }
            else
            {
                $andOr = strtoupper($this->post->$andOrName);
                if($andOr != 'AND' and $andOr != 'OR') $andOr = 'AND';
                $where = empty($where) ? $condition : '(' . ($andOr == 'AND' ? '&' : '|') . $where . $condition . ')';
            }
        }
        if(!empty($where)) $wheres[] = $where;

        if($wheres) $where = '(' . ($groupAndOr == 'AND' ? '&' : '|') . join($wheres) . ')';
        if(empty($where)) $where = "({$this->config->ldap->account}=*)";

        $querySessionName = $this->post->module . 'Query';
        $formSessionName  = $this->post->module . 'Form';
        $this->session->set($querySessionName, $where);
        $this->session->set($formSessionName,  $_POST);
    }

    /**
     * conneck ldap.
     *
     * @param  object    $ldapConfig
     * @access public
     * @return object
     */
    public function ldapConnect($ldapConfig)
    {
        if(strpos($ldapConfig->host, '://') !== false) return ldap_connect($ldapConfig->host . ':' . $ldapConfig->port);
        return ldap_connect($ldapConfig->host, $ldapConfig->port);
    }
}
