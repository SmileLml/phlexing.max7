<?php
/**
 * 用对象数据创建度量。
 * Create a metric.
 *
 * @param  object $metric
 * @access public
 * @return int|false
 */
class zentaobizMetric extends metricModel
{
    public function create($metric)
    {
        $this->dao->insert(TABLE_METRIC)->data($metric)
            ->autoCheck()
            ->checkIF(!empty($metric->name), 'name', 'unique', "`deleted` = '0'")
            ->checkIF(!empty($metric->code), 'code', 'unique', "`deleted` = '0'")
            ->exec();
        if(dao::isError()) return false;
        $metricID = $this->dao->lastInsertID();

        $this->loadModel('action')->create('metric', $metricID, 'opened', '', '', $this->app->user->account);

        return $metricID;
    }

    /**
     * 更新度量项。
     * Update a metric.
     *
     * @param  int    $id
     * @param  object $metric
     * @access public
     * @return int|false
     */
    public function update($id, $metric)
    {
        $oldMetric = $this->getByID($id);

        $this->dao->update(TABLE_METRIC)->data($metric)
            ->autoCheck()
            ->checkIF(!empty($metric->name) && $metric->name != $oldMetric->name, 'name', 'unique', "`deleted` = '0'")
            ->checkIF(!empty($metric->code) && $metric->code != $oldMetric->code, 'code', 'unique', "`deleted` = '0'")
            ->where('id')->eq($id)
            ->exec();

        if(dao::isError()) return false;

        $changes = common::createChanges($oldMetric, $metric);
        if($changes)
        {
            $actionID = $this->loadModel('action')->create('metric', $id, 'edited', '', '', $this->app->user->account);
            $this->action->logHistory($actionID, $changes);
        }

        return $changes;
    }

    /**
     * 根据度量项信息生成度量项php模板内容。
     * Generante php template content from metric information.
     *
     * @param  int    $metricID
     * @access public
     * @return array
     */
    public function getMetricPHPTemplate($metricID)
    {
        $metric = $this->getByID($metricID);
        $metricLang = $this->lang->metric;

        $metric->nameEN   = ucfirst(str_replace('_', ' ', $metric->code));
        $metric->scope    = $metricLang->scopeList[$metric->scope];
        $metric->object   = $metricLang->objectList[$metric->object];
        $metric->purpose  = $metricLang->purposeList[$metric->purpose];
        $metric->dateType = $metricLang->dateTypeList[$metric->dateType];
        $metric->unit     = isset($metricLang->unitList[$metric->unit]) ? $metricLang->unitList[$metric->unit] : $metric->unit;

        $replaceFields = array('name', 'nameEN', 'code', 'scope', 'object', 'purpose', 'unit', 'desc', 'definition', 'dateType');

        $extModulePath = $this->app->getExtensionRoot() . $this->config->edition . DS . 'metric' . DS;
        $templatePath  = $extModulePath . 'template' . DS . 'metric.php.tmp';
        $content       = file_get_contents($templatePath);

        foreach($replaceFields as $replaceField)
        {
            $replaceContent = $this->replaceCRLF($metric->$replaceField);
            $content = str_replace("{{{$replaceField}}}", $replaceContent, $content);
        }

        return array("{$metric->code}.php", $content);
    }

    /**
     * 更新度量项。
     * Updata metric.
     *
     * @param  object $metric
     * @access public
     * @return void
     */
    public function updateMetric($metric)
    {
        $this->dao->update(TABLE_METRIC)->data($metric)
            ->where('id')->eq($metric->id)
            ->exec();
    }

    /**
     * 检查度量项计算文件是否存在。
     * Check if the calculator file exists or not.
     *
     * @param  string $code
     * @access public
     * @return array
     */
    public function checkCustomCalcExists($code)
    {
        $calcName  = $this->getCustomCalcFile($code);
        $fileExist = file_exists($calcName);

        return $this->getVerifyError(__FUNCTION__, $fileExist);
    }

    /**
     * 检查度量项计算文件语法错误。
     * Check syntax error of the calculator file.
     *
     * @param  string $code
     * @access public
     * @return array
     */
    public function checkCustomCalcSyntax($code)
    {
        $calcName = $this->getCustomCalcFile($code);

        $fileContent = file_get_contents($calcName);
        $fileContent = $this->removeFirstLine($fileContent);
        $fileContent = $this->calcAddReturn($fileContent);

        try
        {
            eval($fileContent);
        }
        catch(ParseError $e)
        {
            $errorLine = $e->getLine();
            return $this->getVerifyError(__FUNCTION__, false, "Line {$errorLine} error: {$e->getMessage()}");
        }

        return $this->getVerifyError(__FUNCTION__, true);
    }

    /**
     * 检查度量项计算文件是否定义了必要的类。
     * Check whether the necessary class exist in the file.
     *
     * @param  string $code
     * @access public
     * @return array
     */
    public function checkCustomCalcClassName($code)
    {
        if(!$this->checkCustomCalcExists($code)[0]) return $this->checkCustomCalcExists($code);

        try
        {
            $result = eval($this->genClassNameCheckCode($code));
            return $this->getVerifyError(__FUNCTION__, !empty($result));
        }
        catch(ParseError $e)
        {
            $errorLine = $e->getLine();
            return $this->getVerifyError(__FUNCTION__, false, "Line {$errorLine} error: {$e->getMessage()}");
        }
    }

    /**
     * 检查度量项计算文件中是否编写了必要的方法。
     * Check whether the necessary methods exist in the file.
     *
     * @param  string $code
     * @access public
     * @return array
     */
    public function checkCustomCalcClassMethod($code)
    {
        if(!$this->checkCustomCalcExists($code)[0]) return $this->checkCustomCalcExists($code);

        try
        {
            $result = eval($this->genClassMethodCheckCode($code));

            $methodNameList = json_decode($result);
            foreach($this->config->metric->necessaryMethodList as $method)
            {
                if(!in_array($method, $methodNameList)) return $this->getVerifyError(__FUNCTION__, false, $method);
            }

            return $this->getVerifyError(__FUNCTION__, true);
        }
        catch(ParseError $e)
        {
            $errorLine = $e->getLine();
            return $this->getVerifyError(__FUNCTION__, false, "Line {$errorLine} error: {$e->getMessage()}");
        }
    }

    /**
     * 检查度量项计算文件是否可以运行。
     * Check whether the calculator file can run.
     *
     * @param  string  $code
     * @access public
     * @return array
     */
    public function checkCustomCalcRuntime($code)
    {
        try
        {
            $currentDebug = $this->config->debug;
            $this->config->debug = 2;
            $output = $this->runCustomCalc($code);
            $this->config->debug = $currentDebug;
        }
        catch(Error $e)
        {
            return $this->getVerifyError(__FUNCTION__, false, strip_tags($e->getMessage()));
        }
        catch(Exception $e)
        {
            return $this->getVerifyError(__FUNCTION__, false, strip_tags($e->getMessage()));
        }

        if(dao::isError()) return $this->getVerifyError(__FUNCTION__, false, dao::getError());

        return $this->getVerifyError(__FUNCTION__, true);
    }

    /**
     * 运行用户自定义的度量项文件。
     * Run metric file by custom, get result.
     *
     * @param  string $code
     * @access public
     * @return array|false
     */
    public function runCustomCalc($code)
    {
        $metric = $this->dao->select('id,code,scope,purpose,dateType')->from(TABLE_METRIC)->where('code')->eq($code)->fetch();
        if(!$metric) return false;

        $calcPath = $this->getCustomCalcRoot() . $code . '.php';
        if(!is_file($calcPath)) return false;

        include_once $this->getBaseCalcPath();
        include_once $calcPath;
        $calculator = new $metric->code();
        $calculator->setHolidays($this->loadModel('holiday')->getList());
        $calculator->setWeekend(isset($this->config->project->weekend) ? $this->config->project->weekend : 2);

        if($calculator->reuse)
        {
            $reuseMetrics = array();
            foreach($calculator->reuseMetrics as $key => $reuseMetric)
            {
                $reuseMetrics[$key] = $this->getResultByCode($reuseMetric, $options, $type, $pager, $vision);
            }

            $calculator->calculate($reuseMetrics);
        }
        else
        {
            $statement = $this->getDataStatement($calculator);
            $rows = $statement->fetchAll();

            foreach($rows as $row) $calculator->calculate($row);
        }

        $results = $calculator->getResult();

        $now = date('Y-m-d H:i');

        foreach($results as $key => $result)
        {
            $results[$key]['date']         = $now;
            $results[$key]['calcType']     = 'cron';
            $results[$key]['calculatedBy'] = 'system';
        }

        return $results;
    }

    /**
     * 获得要将度量项移入的文件夹路径。
     * Get dir path where calc file move in.
     *
     * @param  object $metric
     * @access public
     * @return string
     */
    public function getCalcDir($metric)
    {
        return $this->getCalcRoot() . $metric->scope . DS . $metric->purpose;
    }

    /**
     * 执行临时的度量项计算文件。
     * Execute the temporary metric calculator file.
     *
     * @param  string  $filename
     * @param  string  $content
     * @access private
     * @return array
     */
    private function execTmpCalc($filename, $content)
    {
        file_put_contents($filename, $content);

        $output = array();
        $result = exec("php $filename", $output);

        unlink($filename);

        return array($result, $output);
    }

    /**
     * 获取计算度量项文件内容。
     * Get Calc metric file content.
     *
     * @param  string    $code
     * @access private
     * @return string
     */
    private function getCalcContent($code)
    {
        $baseCalcFile    = $this->getBaseCalcPath();
        $baseCalcContent = file_get_contents($baseCalcFile);
        $baseCalcContent = $this->removeFirstLine($baseCalcContent);

        $calcFile    = $this->getCustomCalcFile($code);
        $calcContent = file_get_contents($calcFile);
        $calcContent = $this->removeFirstLine($calcContent);

        return implode("\n", array($baseCalcContent, $calcContent));
    }

    /**
     * 生成度量计算文件类名检查代码。
     * Generate code to check class name of calculator file.
     *
     * @param  string  $code
     * @access private
     * @return string
     */
    private function genClassNameCheckCode($code)
    {
        $calcContent = $this->getCalcContent($code);

        $codes = array();
        $codes[] = $calcContent;
        $codes[] = "return class_exists('{$code}');";

        return implode("\n", $codes);
    }

    /**
     * 生成度量计算文件类方法检查代码。
     * Generate code to check class method of calculator file.
     *
     * @param  string  $code
     * @access private
     * @return string
     */
    private function genClassMethodCheckCode($code)
    {
        $calcContent = $this->getCalcContent($code);

        $codes = array();
        $codes[] = $calcContent;

        $codes[] = "\$classReflection = new ReflectionClass('{$code}');";
        $codes[] = "\$methodList = \$classReflection->getMethods();";
        $codes[] = "\$methodNameList = array();";
        $codes[] = "foreach(\$methodList as \$index => \$reflectionMethod)";
        $codes[] = "{";
        $codes[] = "    if(\$reflectionMethod->class == '{$code}') \$methodNameList[\$index] = \$reflectionMethod->name;";
        $codes[] = "}";
        $codes[] = "return json_encode(\$methodNameList);";

        return implode("\n", $codes);
    }

    /**
     * 获取验证错误信息。
     * Get verify error message.
     *
     * @param  string   $funcName
     * @param  bool     $result
     * @param  string   $extra
     * @access private
     * @return array
     */
    private function getVerifyError($funcName, $result, $extra = '')
    {
        $verifyCustom = $this->lang->metric->verifyCustom;
        $verifyError  = isset($verifyCustom->$funcName) ? $verifyCustom->$funcName['error'] : null;

        if(!empty($extra)) $verifyError = "$verifyError $extra";

        return array($result, $result ? null : $verifyError);
    }

    /**
     * 获取自定义的度量项计算文件的路径。
     * Get path of custom calculator file.
     *
     * @param  string  $code
     * @access private
     * @return string
     */
    public function getCustomCalcFile($code)
    {
        return $this->getCustomCalcRoot() . $code . '.php';
    }

    /**
     * 获取自定义的度量项计算文件的临时路径。
     * Get path of temporary calculator file.
     *
     * @param  string  $code
     * @access private
     * @return void
     */
    private function getCustomEvalPath($code)
    {
        return tempnam(sys_get_temp_dir(), "{$code}_");
    }

    /**
     * 获取用户自定义的度量项计算文件的根目录。
     * Get root of custom metric calculator.
     *
     * @access public
     * @return string
     */
    private function getCustomCalcRoot()
    {
        return $this->app->getTmpRoot() . 'metric' .DS;
    }

    /**
     * 删除第一行内容。
     * Remove the first line.
     *
     * @param  string $content
     * @access private
     * @return string
     */
    private function removeFirstLine($content)
    {
        $lines = explode("\n", $content);
        array_shift($lines);
        $content = implode("\n", $lines);

        return $content;
    }

    /**
     * 在内容的第一行添加return。
     * Add return to the first line.
     *
     * @param  string    $content
     * @access private
     * @return string
     */
    private function calcAddReturn($content)
    {
        $lines = explode("\n", $content);
        array_unshift($lines, "return;");
        $content = implode("\n", $lines);

        return $content;
    }
}
