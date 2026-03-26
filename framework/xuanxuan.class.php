<?php
include 'router.class.php';
class xuanxuan extends router
{
    /**
     * The input params.
     *
     * @var array
     * @access public
     */
    public $input = array();

    /**
     *  The request params.
     *
     * @var array
     * @access public
     */
    public $params = array();

    /**
     * 构造方法, 设置路径，类，超级变量等。注意：
     * 1.应该使用createApp()方法实例化router类；
     * 2.如果$appRoot为空，框架会根据$appName计算应用路径。
     *
     * The construct function.
     * Prepare all the paths, classes, super objects and so on.
     * Notice:
     * 1. You should use the createApp() method to get an instance of the router.
     * 2. If the $appRoot is empty, the framework will compute the appRoot according the $appName
     *
     * @param string $appName   the name of the app
     * @param string $appRoot   the root path of the app
     * @access public
     * @return void
     */
    public function __construct($appName = 'sys', $appRoot = '')
    {
        parent::__construct($appName, $appRoot);

        $this->setViewType();
        $this->setXuanClientLang();
        $this->setXuanDebug();
    }

    /**
     * Set view type.
     *
     * @access public
     * @return void
     */
    public function setViewType()
    {
        $this->viewType = RUN_MODE == 'xuanxuan' ? 'json' : 'html';
    }

    /**
     * 根据用户浏览器的语言设置和服务器配置，选择显示的语言。
     * 优先级：$lang参数 > session > cookie > 浏览器 > 配置文件。
     *
     * Set the language.
     * Using the order of method $lang param, session, cookie, browser and the default lang.
     *
     * @param   string $lang  zh-cn|zh-tw|zh-hk|en
     * @access  public
     * @return  void
     */
    public function setXuanClientLang()
    {
        $row  = $this->dbh->query('SELECT `value` FROM ' . TABLE_CONFIG . " WHERE `owner`='system' AND `module`='common' AND `section`='xuanxuan' AND `key`='backendLang'")->fetch();
        $lang = empty($row) ? 'zh-cn' : $row->value;

        parent::setClientLang($lang);
    }

    /**
     * Set debug.
     *
     * @access public
     * @return void
     */
    public function setXuanDebug()
    {
        $row = $this->dbh->query('SELECT `value` FROM ' . TABLE_CONFIG . " WHERE `owner`='system' AND `module`='common' AND `section`='xuanxuan' AND `key`='debug'")->fetch();
        $this->debug = empty($row) ? false : ($row->value == 1);
    }

    /**
     * Set input params.
     *
     * @access public
     * @return void
     */
    public function setInput()
    {
        if(RUN_MODE == 'xuanxuan')
        {
            $input = empty($_POST) ? file_get_contents("php://input") : $_POST;

            /* Decrypt only if AES is enabled. */
            $enableAES = $this->dbh->query('SELECT `value` FROM ' . TABLE_CONFIG . " WHERE `owner`='system' AND `module`='common' AND `section`='xuanxuan' AND `key`='aes'")->fetch();
            if(empty($enableAES) || $enableAES->value == 'on')   $this->enableAES = true;
            if(!empty($enableAES) && $enableAES->value == 'off') $this->enableAES = false;

            if($this->enableAES) $this->initAES();
            $input = $this->decrypt($input);

            $this->input['rid']     = zget($input, 'rid'    , '');
            $this->input['userID']  = zget($input, 'userID' , '');
            $this->input['client']  = zget($input, 'client' , '');
            $this->input['module']  = zget($input, 'module' , 'im');
            $this->input['method']  = zget($input, 'method' , '');
            $this->input['lang']    = zget($input, 'lang'   , 'zh-cn');
            $this->input['params']  = zget($input, 'params' , array());
            $this->input['version'] = zget($input, 'version', '');
            $this->input['device']  = zget($input, 'device' , 'desktop');
        }
        else
        {
            $this->input['module'] = 'im';
            $this->input['method'] = 'debug';
            $this->input['params'] = array();
        }
    }

    /**
     * Init aes object.
     *
     * @access public
     * @return void
     */
    public function initAES()
    {
        $row = $this->dbh->query('SELECT `value` FROM ' . TABLE_CONFIG . " WHERE `owner`='system' AND `module`='common' AND `section`='xuanxuan' AND `key`='key'")->fetch();
        $key = $row->value;
        $iv  = substr($key, 0, 16);

        $this->aes = $this->loadClass('phpaes');
        $this->aes->init($key, $iv);
        if($this->debug)
        {
            $this->log("engine: " . $this->aes->getEngine());
        }
    }

    /**
     * Set params.
     *
     * @param  array  $params
     * @access public
     * @return void
     */
    public function setParams($params = array())
    {
        $this->params = $params;
    }

    /**
     * 解析本次请求的入口方法，根据请求的类型(PATH_INFO GET)，调用相应的方法。
     * The entrance of parseing request. According to the requestType, call related methods.
     *
     * @access public
     * @return void
     */
    public function parseRequest()
    {
        $this->setInput();
        extract($this->input);

        $module = strtolower($module);
        $method = strtolower($method);

        if(RUN_MODE == 'xuanxuan')
        {
            if(!isset($this->config->xuanxuan->enabledMethods[$module][$method]))
            {
                $data = new stdclass();
                $data->module = 'im';
                $data->method = 'error';
                $data->data   = 'Illegal Request.';
                $this->output($this->encrypt($data));
                return false;
            }

            /* Check for default parameters of the method if params is an object instead of an array. */
            $filledWithDefaults = false;
            if(is_object($params)) // cannot check ($params !== array_values($params)) for now because the decoder could not tell if data type name is needed and the name is always set.
            {
                $inputParams   = (object)$params;
                $defaultParams = $this->getFunctionParams($method, $module);
                $filledParams  = array();
                foreach($defaultParams as $name => $defaultValue)
                {
                    if(in_array($name, array('userID', 'version', 'device'))) continue;
                    $filledParams[] = isset($inputParams->$name) ? $inputParams->$name : $defaultValue;
                }
                $filledWithDefaults = true;
                $params = $filledParams;
            }

            /* Unset servername param of handshake methods. */
            $handshakeMethods = array('userlogin', 'sysgetserverinfo');
            if(!$filledWithDefaults && $module == 'im' && in_array(strtolower($method), $handshakeMethods) && is_array($params)) unset($params[0]);

            if(is_array($params))
            {
                $params[] = $userID;
                $params[] = $version;
                $params[] = $device;
            }

            $this->session->set('userID', $userID);
            $this->session->set('clientIP', $client);
            $this->session->set('clientLang', $lang);
        }
        elseif($module != 'im' or $method != 'debug')
        {
            $this->output('Access Denied');
            return false;
        }

        $this->setModuleName($module);
        $this->setMethodName($method);
        $this->setParams($params);
        $this->setControlFile();
        return true;
    }

    /**
     * 使用反射机制获取函数参数的默认值。
     * Get the default settings of the method to be called using the reflecting.
     *
     * @param  string  $methodName
     * @param  string  $moduleName
     * @access public
     * @return array
     */
    public function getFunctionParams($methodName, $moduleName = 'im')
    {
        $control = $this->getExtensionRoot() . "xuan/" . $moduleName . DS . 'control.php';
        $moduleExtPaths = $this->getModuleExtPath($moduleName, 'control');
        if(!empty($moduleExtPaths))
        {
            $extActionFile = $moduleExtPaths['common'] . $methodName . '.php';
            if(file_exists($extActionFile))
            {
                $control = $extActionFile;
            }
            elseif(isset($moduleExtPaths['xuan']))
            {
                $extActionFile = $moduleExtPaths['xuan'] . $methodName . '.php';
                if(file_exists($extActionFile)) $control = $extActionFile;
            }
        }

        helper::cd(dirname($control));
        helper::import($control);
        helper::cd();
        $moduleName = class_exists("my$moduleName") ? "my$moduleName" : $moduleName;

        $defaultParams = array();
        $methodReflect = new reflectionMethod($moduleName, $methodName);
        foreach($methodReflect->getParameters() as $param)
        {
            $name = $param->getName();
            $default = '_NOT_SET';
            if($param->isDefaultValueAvailable()) $default = $param->getDefaultValue();
            $defaultParams[$name] = $default;
        }

        return $defaultParams;
    }

    /**
     * 加载一个模块：
     * 1. 引入控制器文件或扩展的方法文件；
     * 2. 创建control对象；
     * 3. 解析url，得到请求的参数；
     * 4. 使用call_user_function_array调用相应的方法。
     *
     * Load a module.
     * 1. include the control file or the extension action file.
     * 2. create the control object.
     * 3. set the params passed in through url.
     * 4. call the method by call_user_function_array
     *
     * @access public
     * @return bool|object  if the module object of die.
     */
    public function loadModule()
    {
        $appName    = $this->appName;
        $moduleName = $this->moduleName;
        $methodName = $this->methodName;

        /*
         * 引入该模块的control文件。
         * Include the control file of the module.
         **/
        $file2Included = $this->setActionExtFile() ? $this->extActionFile : $this->controlFile;
        chdir(dirname($file2Included));
        helper::import($file2Included);

        /*
         * 设置control的类名。
         * Set the class name of the control.
         **/
        $className = class_exists("my$moduleName") ? "my$moduleName" : $moduleName;
        if(!class_exists($className))
        {
            $this->triggerError("the control $className not found", __FILE__, __LINE__);
            return false;
        }

        /*
         * 创建control类的实例。
         * Create a instance of the control.
         **/
        $module = new $className();
        if(!method_exists($module, $methodName))
        {
            $this->triggerError("the module $moduleName has no $methodName method", __FILE__, __LINE__);
            return false;
        }
        /* If the db server restarted, must reset dbh. */
        $this->control = $module;

        /* include default value for module*/
        $defaultValueFiles = glob($this->getTmpRoot() . "defaultvalue/*.php");
        if($defaultValueFiles) foreach($defaultValueFiles as $file) include $file;

        /*
         * 使用反射机制获取函数参数的默认值。
         * Get the default settings of the method to be called using the reflecting.
         *
         * */
        $defaultParams = array();
        $methodReflect = new reflectionMethod($className, $methodName);
        foreach($methodReflect->getParameters() as $param)
        {
            $name = $param->getName();

            $default = '_NOT_SET';
            if(isset($paramDefaultValue[$appName][$className][$methodName][$name]))
            {
                $default = $paramDefaultValue[$appName][$className][$methodName][$name];
            }
            elseif(isset($paramDefaultValue[$className][$methodName][$name]))
            {
                $default = $paramDefaultValue[$className][$methodName][$name];
            }
            elseif($param->isDefaultValueAvailable())
            {
                $default = $param->getDefaultValue();
            }

            $defaultParams[$name] = $default;
        }

        /* Merge params. */
        $mergedParams = array();
        if(isset($this->params))
        {
            $mergedParams = $this->mergeParams($defaultParams, (array)$this->params);
        }
        else
        {
            $this->triggerError("param error: {$this->request->raw}", __FILE__, __LINE__);
            return false;
        }

        /* Call the method. */
        $this->response = call_user_func_array(array($module, $methodName), $mergedParams);
        return true;
    }

    /**
     * 合并请求的参数和默认参数，这样就可以省略已经有默认值的参数了。
     * Merge the params passed in and the default params. Thus the params which have default values needn't pass value, just like a function.
     *
     * @param   array $defaultParams     the default params defined by the method.
     * @param   array $passedParams      the params passed in through url.
     * @access  public
     * @return  array the merged params.
     */
    public function mergeParams($defaultParams, $passedParams)
    {
        /* Remove these two params. */
        unset($passedParams['HTTP_X_REQUESTED_WITH']);

        /* Check params from URL. */
        foreach($passedParams as $param => $value)
        {
            if(preg_match('/[^a-zA-Z0-9_\.]/', $param))
            {
                $this->output('Bad Request!');
                return array();
            }
        }

        $passedParams = array_values($passedParams);
        $i = 0;
        foreach($defaultParams as $key => $defaultValue)
        {
            if(isset($passedParams[$i]))
            {
                $defaultParams[$key] = $passedParams[$i];
            }
            else
            {
                if($defaultValue === '_NOT_SET') $this->triggerError("The param '$key' should pass value. ", __FILE__, __LINE__, $exit = true);
            }
            $i++;
        }

        return $defaultParams;
    }

    /**
     * Decrypt an input string.
     *
     * @param  string $input
     * @access public
     * @return object
     */
    public function decrypt($input = '')
    {
        if($this->enableAES) $input = $this->aes->decrypt($input);
        if($this->debug) $this->log("decrypt: " . $input);

        $input = json_decode($input);
        if(!$input) $this->triggerError('Input data is not json.', __FILE__, __LINE__);
        if($input && is_object($input)) return $input;
        return $this->decodeInput($input);
    }

    /**
     * Encrypt an output object.
     *
     * @param  mixed  $output   array | object
     * @access public
     * @return string
     */
    public function encrypt($output = null)
    {
        if(is_array($output) or is_object($output)) $output = json_encode($output);
        if($this->debug)
        {
            $this->log("encrypt: " . $output);
        }
        if($this->enableAES) $output = $this->aes->encrypt($output);
        return helper::removeUTF8Bom($output);
    }

    /**
     * Decode input params.
     *
     * @param  array    $input
     * @access public
     * @return array
     */
    public function decodeInput($inputArray)
    {
        list($api, $input) = $inputArray;
        $maps = zget($this->config->maps, $api, array());
        /* Using requestPack as fallback maps  */
        if(empty($maps)) $maps = zget($this->config->maps, 'requestPack', array());

        if($this->debug && empty($maps))
        {
            $this->log("warning: decode maps is empty for api \"$api\"");
        }

        $decodedParams = self::decodeArray($maps, $input);

        if($this->debug)
        {
            $this->log("decode input($api): " . json_encode($decodedParams));
        }
        return $decodedParams;
    }

    /**
     * Decode array to assorted array.
     *
     * @param  array    $maps
     * @param  array    $data
     * @static
     * @access public
     * @return array
     */
    public static function decodeArray($maps, $data)
    {
        $params = array();

        if(empty($maps)) return array();

        /* $maps should be a type with dataType set. */
        foreach($maps['dataType'] as $key => $prop)
        {
            /* Skip extra dataTypes that do not exist in the $data array. */
            if(!isset($data[$key])) continue;

            /* Basic type may contain options that allow using an index to represent a value defined in the scheme. */
            if($prop['type'] == 'basic')
            {
                if(isset($prop['options']))
                {
                    $params[$prop['name']] = zget($prop['options'], $data[$key]);
                }
                else
                {
                    $params[$prop['name']] = zget($data, $key, '');
                }
                continue;
            }

            /* Object type may have a dataType of nested or mixed types. */
            if($prop['type'] == 'object')
            {
                $params[$prop['name']] = self::decodeArray($prop, $data[$key]);
                continue;
            }

            /* List type describes an array of its dataType instances. */
            if($prop['type'] == 'list')
            {
                $params[$prop['name']] = self::decodeArray(array('dataType' => array($prop['dataType'])), $data[$key]);
            }
        }
        return $params;
    }

    /**
     * Output message with echo.
     *
     * @param  string $message
     * @access public
     * @return void
     */
    public function output($message)
    {
        echo $message;
    }

    /**
     * Save a log.
     *
     * @param  string $log
     * @param  string $file
     * @param  string $line
     * @access public
     * @return void
     */
    public function log($message, $file = '', $line = '')
    {
        $log = "\n" . date('H:i:s') . " $message";
        if($file) $log .= " in <strong>$file</strong>";
        if($line) $log .= " on line <strong>$line</strong> ";
        $file = $this->getLogRoot() . 'xuanxuan.' . date('Ymd') . '.log.php';
        if(!is_file($file)) file_put_contents($file, "<?php\n die();\n?>\n");

        $fh = @fopen($file, 'a');
        if($fh) fwrite($fh, $log) && fclose($fh);
    }
}
