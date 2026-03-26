<?php
class dataviewState
{
    /**
     * id
     *
     * @var int
     * @access public
     */
    public $id;

    /**
     * group
     *
     * @var string
     * @access public
     */
    public $group;

    /**
     * name
     *
     * @var string
     * @access public
     */
    public $name;

    /**
     * code
     *
     * @var string
     * @access public
     */
    public $code;

    /**
     * mode
     *
     * @var string
     * @access public
     */
    public $mode;

    /**
     * view
     *
     * @var string
     * @access public
     */
    public $view;

    /**
     * sql
     *
     * @var string
     * @access public
     */
    public $sql;

    /**
     * fields
     *
     * @var array
     * @access public
     */
    public $fields;

    /**
     * langs
     *
     * @var array
     * @access public
     */
    public $langs;

    /**
     * fieldSettings
     *
     * @var array
     * @access public
     */
    public $fieldSettings;

    /**
     * clientLang
     *
     * @var string
     * @access public
     */
    public $clientLang;

    /**
     * error
     *
     * @var bool
     * @access public
     */
    public $error = false;

    /**
     * errorMsg
     *
     * @var string
     * @access public
     */
    public $errorMsg = '';

    /**
     * queryCols
     *
     * @var array
     * @access public
     */
    public $queryCols = array();

    /**
     * queryData
     *
     * @var array
     * @access public
     */
    public $queryData = array();

    /**
     * pager
     *
     * @var int
     * @access public
     */
    public $pager;

    /**
     * canChangeMode
     *
     * @var bool
     * @access public
     */
    public $canChangeMode = true;

    /**
     * sqlbuilder
     *
     * @var array
     * @access public
     */
    public $sqlbuilder = array();

    /**
     * triggerQuery
     *
     * @var bool
     * @access public
     */
    public $triggerQuery = false;

    /**
     * __construct method.
     *
     * @access public
     * @return void
     */
    public function __construct($dataview, $clientLang = 'zh-cn', $sqlbuilder = array())
    {
        $this->id    = $dataview->id;
        $this->group = $dataview->group;
        $this->name  = $dataview->name;
        $this->code  = $dataview->code;
        $this->mode  = $dataview->mode;
        $this->view  = $dataview->view;
        $this->sql   = $dataview->sql;

        $this->fields = $this->json2Array($dataview->fields);
        $this->langs  = $this->json2Array($dataview->langs);

        $this->sqlbuilder    = $sqlbuilder;
        $this->clientLang    = $clientLang;
        $this->fieldSettings = array_merge_recursive((array)$this->fields, $this->langs);
        $this->setPager();
    }

    /**
     * Update from $_POST.
     *
     * @param  array    $post
     * @access public
     * @return void
     */
    public function updateFromPost($post)
    {
        if(!isset($post['data'])) return;

        $data = json_decode($post['data'], true);
        foreach($data as $key => $value)
        {
            if(!empty($value['sql'])) $value['sql'] = base64_encode($value['sql']);
            $this->$key = $key == 'sql' ? base64_decode($value) : $value;
        }

        $this->processFieldSettingsLang();
        $this->canChangeMode = $this->setCanChangeMode();
    }

    /**
     * Set can change mode.
     *
     * @access public
     * @return bool
     */
    public function setCanChangeMode()
    {
        if($this->mode == 'builder') return true;
        if($this->mode == 'text' && empty($this->sql)) return true;

        return false;
    }

    /**
     * Clear properies before query sql.
     *
     * @access public
     * @return void
     */
    public function beforeQuerySql()
    {
        $this->error         = false;
        $this->errorMsg      = '';
        $this->queryCols     = array();
        $this->queryData     = array();
    }

    /**
     * Set fieldSettings with merge.
     *
     * @param  array    $settings
     * @access public
     * @return void
     */
    public function setFieldSettings($settings)
    {
        $settings         = (array)$settings;
        $oldFieldSettings = !empty($this->fieldSettings) ? $this->fieldSettings : array();
        $newFieldSettings = array();

        foreach($settings as $field => $setting)
        {
            $oldSetting = isset($oldFieldSettings[$field]) ? $oldFieldSettings[$field] : array();
            if(!empty($oldSetting) && isset($oldSetting['type']))
            {
                $newFieldSettings[$field] = $this->processFieldSettingLang($field, $oldSetting, $setting);

                if($setting['type'] != $oldSetting['type'] && in_array($setting['type'], array('user', 'type'))) $newFieldSettings[$field]['type'] = $setting['type'];
            }
            else
            {
                $newFieldSettings[$field] = $this->matchFieldSettingFromBuilder($field, $setting);
            }
        }

        $this->fields        = $this->json2Array($newFieldSettings);
        $this->fieldSettings = $newFieldSettings;
    }

    /**
     * Set field related object.
     *
     * @param  array  $relatedObject
     * @access public
     * @return void
     */
    public function setFieldRelatedObject($relatedObject)
    {
        $this->relatedObject = $relatedObject;
    }

    /**
     * Build cols for query sql with lang.
     *
     * @access public
     * @return object
     */
    public function buildQuerySqlCols()
    {
        $cols = array();
        $lang = $this->clientLang;
        foreach($this->fieldSettings as $field => $settings)
        {
            $settings = (array)$settings;
            $title    = isset($settings[$lang]) ? $settings[$lang] : $field;

            $cols[] = array('name' => $field, 'title' => $title, 'sortType' => false);
        }

        $this->queryCols = $cols;
        return $this;
    }

    /**
     * Match field setting from builder.
     *
     * @param  string $key
     * @param  array  $setting
     * @access public
     * @return array
     */
    public function matchFieldSettingFromBuilder($key, $setting)
    {
        if($this->mode == 'text') return $setting;
        $selects = array_merge($this->sqlbuilder->getSelects(), $this->sqlbuilder->getFuncSelects());
        foreach($selects as $select)
        {
            list($table, $field, $alias) = $select;
            if($key != $alias) continue;

            $fieldList = $this->sqlbuilder->getTableDescList($table);
            $name = zget($fieldList, $field, $field);
            $setting[$this->clientLang] = $name;
            $setting['field']           = $field;
        }
        return $setting;
    }

    /**
     * Process fieldSettings lang.
     *
     * @access public
     * @return void
     */
    public function processFieldSettingsLang()
    {
        if(empty($this->fieldSettings)) return;
        foreach($this->fieldSettings as $field => $fieldSetting)
        {
            $this->fieldSettings[$field] = $this->processFieldSettingLang($field, $fieldSetting);
        }
    }

    /**
     * Process fieldSetting lang.
     *
     * @param  string  $field
     * @param  array   $fieldSetting
     * @param  array   $newSetting
     * @access public
     * @return array
     */
    public function processFieldSettingLang($field, $oldSetting, $newSetting = array())
    {
        $lang = $this->clientLang;
        if(!empty($oldSetting[$lang])) return $oldSetting;

        if(!empty($newSetting[$lang]))
        {
            $oldSetting[$lang] = $newSetting[$lang];
            return $oldSetting;
        }

        $oldSetting[$lang] = isset($oldSetting['name']) ? $oldSetting['name'] : $field;
        return $oldSetting;
    }

    /**
     * Get langs.
     *
     * @param  string $type
     * @access public
     * @return object|string
     */
    public function getFields($type = 'object')
    {
        $fieldSettings = $this->fieldSettings;

        if(empty($fieldSettings)) return null;

        $fields = array();
        $keys   = array('object', 'field', 'type');
        foreach($fieldSettings as $fieldKey => $fieldSetting)
        {
            $field = array();
            foreach($keys as $key)
            {
                if(isset($fieldSetting[$key])) $field[$key] = $fieldSetting[$key];
            }

            $fields[$fieldKey] = $field;
        }
        return $type == 'object' ? $fields : json_encode($fields);
    }

    /**
     * Get langs.
     *
     * @param  string $type
     * @access public
     * @return object|string
     */
    public function getLangs($type = 'object')
    {
        $fieldSettings = $this->fieldSettings;

        if(empty($fieldSettings)) return null;

        $langs = array();
        $keys  = array('object', 'field', 'type');
        foreach($fieldSettings as $fieldKey => $fieldSetting)
        {
            $lang = array();
            foreach($fieldSetting as $key => $value)
            {
                if(!in_array($key, $keys)) $lang[$key] = $value;
            }

            $langs[$fieldKey] = $lang;
        }

        return $type == 'object' ? $langs : json_encode($langs);
    }

    /**
     * Judge is error.
     *
     * @access public
     * @return bool
     */
    public function isError()
    {
        return $this->error;
    }

    /**
     * Get error message.
     *
     * @access public
     * @return string
     */
    public function getError()
    {
        return $this->errorMsg;
    }

    /**
     * Set error.
     *
     * @param  string    $msg
     * @access public
     * @return object
     */
    public function setError($msg)
    {
        $this->error    = true;
        $this->errorMsg = $msg;

        return $this;
    }

    /**
     * Set pager
     *
     * @param  int    $total
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function setPager($total = 0, $recPerPage = 10, $pageID = 1)
    {
        $this->pager = array();
        $this->pager['total']      = $total;
        $this->pager['recPerPage'] = $recPerPage;
        $this->pager['pageID']     = $pageID;
        $this->pager['pageTotal']  = $total % $recPerPage == 0 ? (int)($total / $recPerPage) : (int)($total / $recPerPage) + 1;
    }

    /**
     * Convert json string to array.
     *
     * @param  string|object|array|null    $json
     * @access public
     * @return array
     */
    private function json2Array($json)
    {
        if(empty($json)) return array();
        if(is_string($json)) return json_decode($json, true);
        if(is_object($json) || is_array($json)) return json_decode(json_encode($json), true);

        return $json;
    }
}
