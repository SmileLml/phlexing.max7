<?php
/**
 * ZenTaoPHP的dao和sql类。
 * The dao and sql class file of ZenTaoPHP framework.
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 *
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */

/**
 * Dameng类。
 * Dameng driver.
 *
 * @package framework
 */
class dm extends dao
{
    /**
     * 设置$table属性。
     * Set the $table property.
     *
     * @param  string $table
     * @access public
     * @return void
     */
    public function setTable($table)
    {
        $this->table = trim($table, '`');
    }

    /**
     * 设置$fields属性。
     * Set the $fields property.
     *
     * @param  string $fields
     * @access public
     * @return void
     */
    public function setFields($fields)
    {
        $this->fields = [];
        foreach(explode(',', $fields) as $field)
        {
            $field = str_replace(array(' ', '`', '"', "'"), '', $field);
            if(!$field) continue;

            if($field == '*')
            {
                $this->fields[] = $field;
                continue;
            }

            if(strpos($field, '.') !== false)
            {
                $table = substr($field, 0, strpos($field, '.'));
                $field = substr($field, strpos($field, '.') + 1);
                $this->fields[] = $table . '."' . $field . '"';
                continue;
            }

            $this->fields[] = '"' . $field . '"';
        }

        $this->fields = implode(',', $this->fields);
    }

    /**
     * Show tables.
     *
     * @access public
     * @return array
     */
    public function showTables()
    {
        $sql = "SELECT \"table_name\" FROM all_tables WHERE OWNER = '{$this->config->db->name}'";
        return $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get table engines.
     *
     * @access public
     * @return array
     */
    public function getTableEngines()
    {
        $tables = $this->query("SELECT \"table_name\" FROM all_tables WHERE OWNER = '{$this->config->db->name}'")->fetchAll();

        $tableEngines = array();
        foreach($tables as $table) $tableEngines[$table->table_name] = 'InnoDB';

        return $tableEngines;
    }

    /**
     * 类MySQL的DESC语法。
     * Desc table, show fields.
     *
     * @param  string $tableName
     * @access public
     * @return array
     */
    public function descTable($tableName)
    {
        $tableName = trim($tableName, '`');
        if(isset(dao::$tablesDesc[$tableName])) return dao::$tablesDesc[$tableName];

        $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
        $sql       = "SELECT COLUMN_NAME AS field, LOWER(DATA_TYPE) AS type, DATA_LENGTH AS length, NULLABLE AS \"null\", DATA_DEFAULT AS \"default\" FROM ALL_TAB_COLUMNS WHERE OWNER = '{$this->config->db->name}' AND TABLE_NAME = '{$tableName}'";
        $fields    = $this->dbh->query($sql)->fetchAll();
        $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);

        $fields = array();
        foreach($fields as $field)
        {
            if($field->type == 'char' || $field->type == 'varchar') $field->type .= '(' . $field->length . ')';
            if($field->type == 'integer') $field->type = 'int';
            $field->null = $field->null == 'Y' ? 'YES' : 'NO';
        }

        dao::$tablesDesc[$tableName] = $fields;

        return $fields;
    }

    /**
     * select方法，调用sql::select()。
     * The select method, call sql::select().
     *
     * @param  string $fields
     * @access public
     * @return static|sql|baseDAO the dao object self.
     */
    public function select($fields = '*')
    {
        /* Split by ','. */
        $fieldList = preg_split("/,(?![^(]+\))/", $fields);
        foreach($fieldList as $key => $field)
        {
            $field = trim($field);
            $pos   = strrpos($field, ' ');
            if($pos)
            {
                $originField = substr($field, 0, $pos);
                $alias       = trim(substr($field, $pos));

                $fieldList[$key] = $this->formatField($originField, $alias);
            }
            else
            {
                $fieldList[$key] = $this->formatField($field);
            }
        }

        return parent::select(implode(',', $fieldList));
    }

    /**
     * Format field: date => "date", t1.date => t1."date"
     *
     * @param string $originField
     * @param string $alias
     * @access private
     * @return tring
     */
    private function formatField($originField, $alias = '')
    {
        /* Format originField. */
        $replace = array(
            'GROUP_CONCAT' => 'WM_CONCAT',
        );

        if(stripos($originField, 'if(') !== false)
        {
            $originField = $this->dbh->formatDmIfFunction($originField);
        }
        elseif(strcasecmp($originField, 'distinct') !== 0)
        {
            $tableField = explode('.', $originField);
            if(count($tableField) == 2 and ctype_alnum($tableField[1]) and $tableField[1] != '*')
            {
                $originField = $tableField[0] . '."' . $tableField[1] . '"';
            }
            elseif(count($tableField) == 1 and ctype_alnum($tableField[0]) and $tableField[0] != '*')
            {
                $originField = '"' . $tableField[0] . '"';
            }
            $originField = str_ireplace(array_keys($replace), array_values($replace), $originField);
        }

        /* Format field: `field` => "field", (field => ("field". */
        $originField = str_replace('`', '"', $originField);
        $originField = preg_replace('/^(\(+)(\w+)+$/', '$1"$2"', $originField);

        /* Format alias. */
        if($alias and !is_numeric($alias) and ctype_alnum($alias)) $alias = '"' . $alias . '"';

        return $originField . ' ' .  $alias;
    }

    /**
     * Format if function.
     *
     * @param  string $field
     * @access private
     * @return string
     */
    /*
    private function formatIfFunction($field)
    {
        preg_match('/if\(.+\)+/i', $field, $matches);

        $if = $matches[0];
        if(substr_count($if, '(') == 1)
        {
            $pos = strpos($if, ')');
            $if  = substr($if, 0, $pos+1);
        }

        // * fix sum(if(..., 1, 0)) , count(if(..., 1, 0)) * //
        if(substr($if, strlen($if)-2) == '))' and (stripos($field, 'sum(') == 0 or stripos($field, 'count(') == 0)) $if = substr($if, 0, strlen($if)-1);

        $parts = explode(',', substr($if, 3, strlen($if)-4)); // remove 'if(' and ')'
        $case  = 'CASE WHEN ' . implode(',', array_slice($parts, 0, count($parts)-2)) . ' THEN ' . $parts[count($parts)-2] . ' ELSE ' . $parts[count($parts)-1] . ' END';
        $field = str_ireplace($if, $case, $field);

        return $field;
    }
     */

    /**
     * 创建WHERE部分。
     * Create the where part.
     *
     * @param  string $arg1     the field name
     * @param  string $arg2     the operator
     * @param  string $arg3     the value
     * @access public
     * @return static|sql the sql object.
     */
    public function where($arg1 = '', $arg2 = null, $arg3 = null)
    {
        $arg1 = $this->formatWhere($arg1);
        return parent::where($arg1, $arg2, $arg3);
    }

    /**
     * 创建AND部分。
     * Create the AND part.
     *
     * @param  string $condition
     * @access public
     * @return static|sql the sql object.
     */
    public function andWhere($condition = '', $addMark = false)
    {
        $condition = $this->formatWhere($condition);
        return parent::andWhere($condition, $addMark);
    }

    /**
     * 创建OR部分。
     * Create the OR part.
     *
     * @param  bool  $condition
     * @access public
     * @return static|sql the sql object.
     */
    public function orWhere($condition)
    {
        $condition = $this->formatWhere($condition);
        return parent::orWhere($condition);
    }

    private function formatWhere($condition)
    {
        $condition = trim($condition);
        if($condition == '1') return '1 = 1';

        $pos = strrpos($condition, ' ');
        if($pos)
        {
            $originField = substr($condition, 0, $pos);
            return $this->formatField($originField) . substr($condition, $pos);
        }
        else
        {
            return $this->formatField($condition);
        }
    }

    /**
     * 创建GROUP BY部分。
     * Create the groupby part.
     *
     * @param  string $groupBy
     * @access public
     * @return static|sql the sql object.
     */
    public function groupBy($groupBy)
    {
        $groups = preg_split("/,(?![^(]+\))/", $groupBy);
        foreach($groups as $key => $group)
        {
            $groups[$key] = $this->formatField($group);
        }

        $groupBy = implode(',', $groups);
        return parent::groupBy($groupBy);
    }

    /**
     * 创建ORDER BY部分。
     * Create the order by part.
     *
     * @param  string $order
     * @access public
     * @return static|sql the sql object.
     */
    public function orderBy($order)
    {
        $order = str_replace('"', '', $order);
        return parent::orderBy($order);
    }

    /**
     * 创建ON部分。
     * Create the on part.
     *
     * @param  string $condition
     * @access public
     * @return static|sql the sql object.
     */
    public function on($condition)
    {
        $fieldList = explode('=', $condition);
        foreach($fieldList as $key => $field) $fieldList[$key] = $this->formatField(trim($field));
        $condition = implode(' = ', $fieldList);

        return parent::on($condition);
    }

    /**
     * 获取唯一索引的列。
     * Get unique columns.
     *
     * @access public
     * @return array
     */
    public function getUniqueColumns()
    {
        $sql = "SELECT * from user_ind_columns WHERE index_name IN (SELECT index_name FROM user_indexes WHERE table_owner='{$this->config->db->name}' AND table_name='{$this->table}' AND INDEX_TYPE = 'NORMAL' AND UNIQUENESS = 'UNIQUE')";

        $columns = $this->dbh->query($sql)->fetchAll();

        $cols = array();
        foreach($columns as $col) $cols[$col->COLUMN_NAME] = $col;
        return $cols;
    }

    /**
     * 执行SQL。query()会返回stmt对象，该方法只返回更改或删除的记录数。
     * Execute the sql. It's different with query(), which return the stmt object. But this not.
     *
     * @param  string $sql
     * @access public
     * @return int the modified or deleted records. 更改或删除的记录数。
     */
    public function exec($sql = '')
    {
        if(!empty(dao::$errors)) return $this;

        if($sql)
        {
            $this->sqlobj = new sql();
        }
        else
        {
            $sql = $this->processSQL();
        }

        $this->sqlobj->sql = $sql;

        if($this->method == 'replace' && !empty($this->sqlobj->data))
        {
            $insertSql = "INSERT INTO \"{$this->table}\" ";
            $fields = '(';
            $values = 'VALUES(';
            foreach($this->sqlobj->data as $field => $value)
            {
                $fields .= "`{$field}`,";
                if(is_string($value) or $value === null) $value = $this->sqlobj->quote($value);
                $values .= $value . ',';
            }
            $fields = substr($fields, 0, -1);
            $values = substr($values, 0, -1);
            $fields .= ')';
            $values .= ')';
            $insertSql .= $fields . ' ' . $values;

            $updateSql = str_replace(array('REPLACE INTO', 'REPLACE'), 'UPDATE', $sql);
            $cols      = $this->getUniqueColumns();

            /* No unique keys, no replace. */
            if(empty($cols)) return false;

            $conditions = array();
            foreach($cols as $colName => $col)
            {
                if(isset($this->sqlobj->data->{$colName})) $conditions[] = " \"{$colName}\" = '{$this->sqlobj->data->{$colName}}'";
            }
            if(!empty($conditions)) $updateSql .= ' WHERE ' . implode(' AND ', $conditions);

            $deleteSql = "DELETE FROM \"{$this->table}\" WHERE ";
            $ingore    = array();
            $ingore['`zt_config`'] = array('value');
            foreach($this->sqlobj->data as $field => $value)
            {
                if(isset($ingore[$this->table]) and in_array($field, $ingore[$this->table])) continue;
                if(!isset($cols[$field])) continue;
                $deleteSql .= "`{$field}` = ";
                $deleteSql .= is_string($value) ? "'{$value}'" : $value;
                $deleteSql .= ' AND ';
            }
            $deleteSql = rtrim($deleteSql, 'AND ');

            $sql = <<<EOT
DECLARE
  E_IDENTITY_INSERT EXCEPTION;
  E_INSTALL_DUP_VAL_ON_INDEX EXCEPTION;
  E_UPDATE_DUP_VAL_ON_INDEX EXCEPTION;
  PRAGMA EXCEPTION_INIT (E_IDENTITY_INSERT, -2723);
  PRAGMA EXCEPTION_INIT (E_INSTALL_DUP_VAL_ON_INDEX, -6602);
  PRAGMA EXCEPTION_INIT (E_UPDATE_DUP_VAL_ON_INDEX, -6610);

BEGIN
    BEGIN
        $insertSql;
    EXCEPTION
        WHEN DUP_VAL_ON_INDEX OR E_IDENTITY_INSERT THEN
            $updateSql;
        WHEN E_INSTALL_DUP_VAL_ON_INDEX THEN
            $updateSql;
    END;
EXCEPTION
    WHEN E_UPDATE_DUP_VAL_ON_INDEX THEN
      $deleteSql;
      $insertSql;
END;
EOT;
            self::$querys[] = $sql;
        }

        $sql = str_replace('`', '"', $sql);

        try
        {
            /* Real-time save log. */
            if(dao::$realTimeLog && dao::$realTimeFile) file_put_contents(dao::$realTimeFile, $sql . "\n", FILE_APPEND);

            $table = $this->table;

            $this->reset();

            /* Force to query from master db, if db has been changed. */
            $this->slaveDBH = false;

            $result = $this->dbh->exec($sql);

            $this->_lastInsertID = $this->dbh->lastInsertId();

            $this->setTableCache($sql);

            if($this->config->enableDuckdb && !empty($table))
            {
                $now  = helper::now();
                $sql  = "UPDATE \"zt_duckdbqueue\" SET \"updatedTime\" = '$now' WHERE \"object\" = '$table';";
                $sql .= "INSERT INTO \"zt_duckdbqueue\" SELECT '$table', '$now', NULL WHERE NOT EXISTS (SELECT 1 FROM \"zt_duckdbqueue\" WHERE \"object\" = '$table' );";
                $this->dbh->exec($sql);
                $this->setTableCache($sql);
            }

            return $result;
        }
        catch (PDOException $e)
        {
            $this->sqlError($e);
        }
    }

    /**
     * 获取一个记录。
     * Fetch one record.
     *
     * @param  string $field        如果已经设置获取的字段，则只返回这个字段的值，否则返回这个记录。
     *                              if the field is set, only return the value of this field, else return this record
     * @access public
     * @return object|mixed
     */
    public function fetch($field = '')
    {
        return parent::fetch($field);
    }

    /**
     * 获取所有记录。
     * Fetch all records.
     *
     * @param  string $keyField     返回以该字段做键的记录
     *                              the key field, thus the return records is keyed by this field
     * @param  bool   $autoExclude  是否排除text类型字段 exclude field type of text
     * @access public
     * @return array the records
     */
    public function fetchAll($keyField = '', $autoExclude = true)
    {
        return parent::fetchAll($keyField, $autoExclude);
    }

    /**
     * 处理sql语句，替换表和字段。
     * Process the sql, replace the table, fields.
     *
     * @access public
     * @return string the sql string after process.
     */
    public function processSQL()
    {
        $sql = $this->sqlobj->get();

        /* INSERT INTO table VALUES(...) */
        if($this->method == 'insert' and !empty($this->sqlobj->data))
        {
            $desc       = $this->descTable($this->table);
            $skipFields = $this->sqlobj->skipFields;
            $values     = array();
            foreach($this->sqlobj->data as $field => $value)
            {
                if(strpos($skipFields, ",$field,") !== false) continue;

                $values[$field] = $this->sqlobj->quote($value);
                unset($desc[$field]);
            }

            /* If field can not null, add this field use default value. */
            foreach($desc as $field)
            {
                if(strtolower($field->null) == 'yes') continue;
                if($field->field == 'id') continue;
                if($field->default !== '') continue;

                $values[$field->field] = "''";

                if(strpos($field->type, 'date')    !== false) $values[$field->field] = "1970-01-01";
                if(strpos($field->type, 'int')     !== false) $values[$field->field] = "0";
                if(strpos($field->type, 'float')   !== false) $values[$field->field] = "0";
                if(strpos($field->type, 'decimal') !== false) $values[$field->field] = "0";
                if(strpos($field->type, 'double')  !== false) $values[$field->field] = "0";
            }

            $sql .= '(`' . implode('`,`', array_keys($values)) . '`)' . ' VALUES(' . implode(',', $values) . ')';
        }

        /**
         * 如果是magic模式，处理表和字段。
         * If the mode is magic, process the $fields and $table.
         **/
        if($this->mode == 'magic')
        {
            if($this->fields == '') $this->fields = '*';
            if($this->table == '')  $this->app->triggerError('Must set the table name', __FILE__, __LINE__, $exit = true);
            $sql = sprintf($this->sqlobj->get(), $this->fields, $this->table);
        }

        /* If the method is select, update or delete, set the lang condition. */
        if($this->autoLang and $this->table != '' and $this->method != 'insert' and $this->method != 'replace')
        {
            $lang = $this->app->getClientLang();

            /* Get the position to insert lang = ?. */
            $wherePOS  = strrpos($sql, DAO::WHERE);             // The position of WHERE keyword.
            $groupPOS  = strrpos($sql, DAO::GROUPBY);           // The position of GROUP BY keyword.
            $havingPOS = strrpos($sql, DAO::HAVING);            // The position of HAVING keyword.
            $orderPOS  = strrpos($sql, DAO::ORDERBY);           // The position of ORDERBY keyword.
            $limitPOS  = strrpos($sql, DAO::LIMIT);             // The position of LIMIT keyword.
            $splitPOS  = $orderPOS  ? $orderPOS  : $limitPOS;   // If $orderPOS, use it instead of $limitPOS.
            $splitPOS  = $havingPOS ? $havingPOS : $splitPOS;   // If $havingPOS, use it instead of $orderPOS.
            $splitPOS  = $groupPOS  ? $groupPOS  : $splitPOS;   // If $groupPOS, use it instead of $havingPOS.

            /* Set the condition to be appended. */
            $tableName = !empty($this->alias) ? $this->alias : $this->table;

            if(!empty($this->app->config->cn2tw)) $lang = str_replace('zh-tw', 'zh-cn', $lang);

            $langCondition = " $tableName.lang in('{$lang}', 'all') ";

            /* If $splitPOS > 0, split the sql at $splitPOS. */
            if($splitPOS)
            {
                $firstPart = substr($sql, 0, $splitPOS);
                $lastPart  = substr($sql, $splitPOS);
                if($wherePOS)
                {
                    $sql = $firstPart . " AND $langCondition " . $lastPart;
                }
                else
                {
                    $sql = $firstPart . " WHERE $langCondition " . $lastPart;
                }
            }
            else
            {
                $sql .= $wherePOS ? " AND $langCondition" : " WHERE $langCondition";
            }
        }

        return $sql;
    }

    /**
     * 获取本次会话的 SQL 语句和执行时间。
     * Get SQL statements and execution time of current session.
     *
     * @access public
     * @return array
     */
    public function getProfiles()
    {
        return [];
    }
}
