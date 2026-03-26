<?php
/**
 * Model file of im module.
 *
 * @copyright Copyright 2009-2023 ZenTao Software (Qingdao) Co., Ltd. (www.zentao.net)
 * @author    Xiying Guan <guanxiying@cnezsoft.com>
 * @package   im
 * @license   ZOSL (http://zpl.pub/page/zoslv1.html)
 * @version   $Id$
 * @Link      http://xuan.im
 */
class imModel extends model
{
    /**
     * Sub-model list, these models are stored at im/model/.
     *
     * @access public
     */
    public $models = array('chat', 'message', 'user', 'conference', 'bot');

    /**
     * @var imChat
     */
    public $chat;

    /**
     * @var imMessage
     */
    public $message;

    /**
     * @var imUser
     */
    public $user;

    /**
     * @var imConference
     */
    public $conference;

    /**
     * @var imBot
     */
    public $bot;

    /**
     * __construct loads and inits sub-models.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();dao::$autoExclude = false;

        if((isset($_SERVER['RR_RELAY']) || isset($_SERVER['RR_MODE'])) && !commonModel::isLicensedMethod('im', 'roadrunner')) die;

        $modelPath = dirname(__FILE__) . DS . "model" . DS;

        foreach($this->models as $model)
        {
            helper::import($modelPath . "$model.php");
            $className = "im$model";
            $this->$model = new $className($this->appName, $this);
        }
    }

    /**
     * Get apiScheme info.
     *
     * @param  string $key
     * @access public
     * @return mixed
     */
    public function getApiScheme($key = '')
    {
        $schemeFile = $this->app->getExtensionRoot() . 'xuan/im/apischeme.json';
        $scheme = json_decode(trim(file_get_contents($schemeFile)), true);
        if(!$key) return $scheme;
        return zget($scheme, $key, '');
    }

    /**
     * Send formatted output.
     *
     * @param  array|object $output Example: array('result' => 'success', 'data' => $messages, 'users' => $userIdList, ...);
     * @param  string       $schemeName
     * @access public
     * @return void
     */
    public function sendOutput($output, $schemeName = 'RAW')
    {
        $output = $this->formatOutput($output, $schemeName);
        $output = trim($output);
        return $this->app->output($this->app->encrypt($output));
    }

    /**
     * Send formatted output group.
     *
     * @param  array|object $outputs Example: array('result' => 'success', 'data' => $messages, 'users' => $userIdList, ...);
     * @access public
     * @return void
     */
    public function sendOutputGroup($outputs)
    {
        $formats = '';
        foreach($outputs as $output)
        {
            if(is_array($output))
            {
                $output = (object)$output;
            }
            if(!isset($output->method))
            {
                $stack = debug_backtrace(false, 2);
                $output->method = isset($stack[1]) ? $stack[1]['function'] : '';
            }
            $formatedOutput = $this->formatOutput($output, strtolower($output->method) . 'Response');
            $formats .= $formatedOutput . "\n";
        }
        $formats = trim($formats);

        return $this->app->output($this->app->encrypt($formats));
    }

    /**
     * Format output data.
     *
     * @param  array|object    $data Example: array('result' => 'success', 'data' => $messages, 'users' => $userIdList, ...);
     * @param  string          $map
     * @param  bool            $returnRaw
     * @param  bool            $prependName
     * @access public
     * @return object|string
     */
    public function formatOutput($data, $map = 'RAW', $returnRaw = false, $prependName = true)
    {
        if(!empty($this->app->debug)) $this->app->log("format output($map): " . json_encode($data));

        $output = new stdclass();

        foreach($data as $key => $value)
        {
            $output->$key = $value;
        }

        $output->device  = zget($this->app->input, 'device', 'desktop');
        $output->userID  = zget($data, 'userID', '0');
        $output->result  = zget($data, 'result', 'success');
        $output->method  = zget($data, 'method', $this->app->getMethodName());
        $output->rid     = zget($this->app->input, 'rid');

        if(!empty($output->users))
        {
            if(is_numeric($output->users)) $output->users = array((int)$output->users); // Convert single user id to array.

            $output->users = array_map('intval', $output->users);
            $output->users = array_filter($output->users);
            $output->users = array_values($output->users);
        }

        if(isset($output->userID) && is_string($output->userID))
        {
            $output->userID = (int)$output->userID;
        }

        if($map != 'RAW')
        {
            $maps = zget($this->config->maps, $map, array());
            /* Using requestPack as fallback maps  */
            if(empty($maps)) $maps = zget($this->config->maps, 'responsePack');

            $data = self::encodeOutput($output, $maps);
        }
        else
        {
            $map = strtolower($output->method) . 'Response';
        }

        $data = $prependName ? array($map, $data) : $data;

        if(!empty($this->app->debug)) $this->app->log("encoded output($map): " . json_encode($data));
        if($returnRaw) return $data;

        $users = isset($output->users) ? $output->users : array();

        $header = $this->appendResponseHeader($output->userID, $users, $output->method, $output->result);

        return json_encode($header) . "\n" . json_encode($data);
    }

    /**
     * Encode output.
     *
     * @param  object                $output
     * @param  object|string|boolean $map
     * @static
     * @access public
     * @return array|object
     */
    public static function encodeOutput($output, $map)
    {
        if(empty($map)) return $output;

        $output = (array) $output;

        /* If map is not final map array, decode with map's dataType setting.*/
        if(isset($map['name']) and isset($map['dataType'])) $map = $map['dataType'];
        if(isset($map['name']) and !isset($map['dataType'])) $map = array($map);

        $data = array();
        foreach($map as $key => $prop)
        {
            $indexName = $prop['name'];

            if($prop['type'] == 'basic')
            {
                if(isset($prop['options']))
                {
                    $options = array_flip($prop['options']);
                    $data[$key] = zget($options, zget($output, $indexName, ''));
                }
                else
                {
                    $data[$key] = zget($output, $indexName, '');
                }
                continue;
            }

            if($prop['type'] == 'object')
            {
                if(isset($output[$indexName])) $data[$key] = self::encodeOutput($output[$indexName], $prop['dataType']);
                continue;
            }

            if($prop['type'] == 'list')
            {
                $tmpOutput = array();
                if(isset($output[$indexName]))
                {
                    foreach($output[$indexName] as $item)
                    {
                        $tmpOutput[] = self::encodeOutput($item, $prop['dataType']);
                    }
                }

                $data[$key] = $tmpOutput;
            }
        }
        return $data;
    }

    /**
     * Batch encode output.
     *
     * @param  array              $array
     * @param  bool|object|string $map
     * @static
     * @access public
     * @return array
     */
    public static function batchEncodeOutput($array, $map)
    {
        $data = array();
        if(empty($array)) return $data;
        foreach($array as $output) $data[] = self::encodeOutput($output, $map);
        return $data;
    }

    /**
     * Append header for xxd to response.
     *
     * @param  array            $output
     * @param  string           $from    current user id
     * @param  string|int|array $to      id list of users to notify : 123,2,3,4,76,423
     * @param  string           $method
     * @param  string           $result
     * @access public
     * @return string
     */
    public function appendResponseHeader($from, $to = 0, $method = '', $result = 'success')
    {
        $header = array();
        if(empty($to)) $to = array($this->app->input['userID']);
        if(!is_array($to)) $to = array($to);

        if(!$method) $method = strtolower($this->app->getMethodName());

        $device = $this->app->input['device'];
        $lang   = $this->app->input['lang'];
        if(!isset($from) || empty($from)) $from = $this->app->input['userID'];

        $header[] = $to;
        $header[] = $from;
        $header[] = $method;
        $header[] = $result;
        $header[] = $device;
        $header[] = $lang;

        return $header;
    }

    /**
     * Get output data of user list.
     *
     * @param  array  $identities
     * @param  int    $userID
     * @param  bool   $returnRaw
     * @access public
     * @return object
     */
    public function getUserListOutput($identities, $userID, $returnRaw = false)
    {
        $output = new stdclass();
        $users = $this->userGetList($status = '', $identities, $idAsKey = false);
        if(dao::isError())
        {
            $output->result  = 'fail';
            $output->message = 'Get userlist failed.';
            return $this->formatOutput($output, 'messageResponsePack', $returnRaw);
        }
        else
        {
            $output->result = 'success';
            $output->users  = $userID;
            $output->data   = $users;
            $output->method = 'usergetlist';

            if(empty($identities))
            {
                $this->app->loadLang('user');
                $roles = $this->lang->user->roleList;

                $allDepts = $this->loadModel('dept')->getListByType('dept');
                $depts = array();
                foreach($allDepts as $id => $dept)
                {
                    $depts[$id] = array('name' => $dept->name, 'order' => (int)$dept->order, 'parent' => (int)$dept->parent);
                }
                $output->roles = $roles;
                $output->depts = $depts;
            }
            else
            {
                $output->partial = $identities;
            }
        }
        return $this->formatOutput($output, 'usergetlistResponse', $returnRaw);
    }

	/**
	 * Create gid.
	 * @access public
	 * @return string
	 */
	public static function createGID()
	{
        $id = md5(microtime() . mt_rand());
        return substr($id, 0, 8) . '-' . substr($id, 8, 4) . '-' . substr($id, 12, 4) . '-' . substr($id, 16, 4) . '-' . substr($id, 20, 12);
	}

    /**
     * Download xxd.
     *
     * @param  object $setting
     * @param  string $downloadType
     * @access public
     * @return array
     */
    public function downloadXXD($setting, $downloadType)
    {
        set_time_limit(0);
        $system          = $this->getSystem($setting->os);
        $version         = $this->config->xuanxuan->version;
        $xxdDirectory    = $this->app->tmpRoot . 'xxd' . DS . $version;
        $basePackage     = $xxdDirectory . DS . $system .  '.base.zip';
        $xxdFileName     = "xxd.$version.$system.zip";
        $downloadCDNLink = $this->config->im->xxdDownloadUrl . $version . '/' . $xxdFileName;

        if(!is_dir($xxdDirectory)) mkdir($xxdDirectory, 0777, true);
        if(!file_exists($basePackage) && $downloadType == 'package')
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $downloadCDNLink);
            $data = curl_exec($ch);
            curl_close($ch);
            if(empty($data)) return array('result' => 'fail', 'message' => 'Failed to download xxd package.');
            $fopenPackage = fopen($basePackage, 'w');
            fwrite($fopenPackage, $data);
        }

        $data                  = new stdClass();
        $data->xxdDirectory    = $xxdDirectory;
        $data->sslcrt          = $setting->sslcrt;
        $data->sslkey          = $setting->sslkey;
        $data->basePackage     = $basePackage;
        $data->xxdFileName     = $xxdFileName;
        $data->host            = trim($this->getServer(), '/') . (zget($this->config->xuanxuan, 'backend', 'xxb') == 'ranzhi' ? dirname($this->config->webRoot) : $this->config->webRoot);
        $data->ip              = $setting->ip ?: '0.0.0.0';
        $data->commonPort      = $setting->commonPort ?: '11443';
        $data->chatPort        = $setting->chatPort ?: '11444';
        $data->https           = $setting->https ?: 'on';
        $data->enableAES       = $setting->aes == 'off' ? 0 : 1;
        $data->uploadPath      = 'files/';
        $data->uploadFileSize  = $setting->uploadFileSize ?: '20';
        $data->pollingInterval = isset($this->config->xuanxuan->pollingInterval) ? $this->config->xuanxuan->pollingInterval : 15;
        $data->maxOnlineUser   = isset($setting->maxOnlineUser) ? $setting->maxOnlineUser : 0;
        $data->logPath         = 'log/';
        $data->certPath        = 'cert/';
        $data->debug           = 0;
        $data->key             = $this->config->xuanxuan->key;
        $data->syncConfig      = 1;
        $data->thumbnail       = 1;

        if($downloadType == 'config')
        {
            $configContent = $this->createXxdConfigFile($data);
            if(!empty($configContent)) $this->loadModel('file')->sendDownHeader('xxd.conf', 'conf', $configContent['zh']);
        }
        elseif($downloadType == 'package')
        {
            $packageFileName = $this->createXxdPackage($data);
            if(!empty($packageFileName)) return array('result' => 'success', 'message' => helper::createLink('im', 'downloadXxdPackage', "xxdFileName=$xxdFileName"));
        }

        return array('result' => 'fail', 'message' => 'error');
    }

    /**
     * create xxd config file
     *
     * @param  object $setting
     * @access public
     * @return array
     */
    public function createXxdConfigFile($setting)
    {
        $configParamsList = $this->config->im->xxdConfig;

        // Replace template variable.
        $lineMaxLength = 0;
        foreach($configParamsList as  $configParams)
        {
            if($configParams == 'host' || $configParams == 'key')
            {
                $config[$configParams] = $setting->$configParams;
            }
            elseif(strpos($configParams, '=') !== false)
            {
                $configItem = explode('=', $configParams);
                $config[$configItem[0]] = $configItem[0] . '=' . $configItem[1];
            }
            else
            {
                $config[$configParams] = $configParams . '=' . $setting->$configParams;
                if($configParams == 'uploadFileSize') $config[$configParams] .= 'M';
            }
            $lineMaxLength = strlen($configParams) > $lineMaxLength ? strlen($configParams) : $lineMaxLength;
        }
        $lineMaxLength += 10;

        // Add parameter notes
        $contentZH = '[server]' . "\n";
        $contentEN = '[server]' . "\n";
        foreach($config as $type => $configValue)
        {
            if($type == 'host' || $type == 'key') continue;
            $configValue  = str_replace(PHP_EOL, '', $configValue);
            $configlength = strlen($configValue);

            for($i = 0; $i < ($lineMaxLength - $configlength); $i++) $configValue .= ' ';
            $contentZH .= $configValue . $this->lang->im->xxdConfigNote['zh'][$type] . "\n";
            $contentEN .= $configValue . $this->lang->im->xxdConfigNote['en'][$type] . "\n";
        }

        // Add backend
        $backend     = "\n" . '[backend]' . "\n";
        $backendFoot = 'default=' . $config['host'] . 'x.php,' . $config['key'];
        $backendFoot = str_replace(PHP_EOL, '', $backendFoot) . "\n";
        $backendZH   = $backend . $this->lang->im->xxdConfigNote['zh']['backend'] . "\n" . $backendFoot;
        $backendEN   = $backend . $this->lang->im->xxdConfigNote['en']['backend'] . "\n" . $backendFoot;
        $contentZH  .= $backendZH;
        $contentEN  .= $backendEN;

        return array('zh' => $contentZH, 'en' => $contentEN);
    }

    /**
     * create xxd package
     *
     * @param object
     * @access public
     * @return string
     */
    public function createXxdPackage($setting)
    {
        $configContent = $this->createXxdConfigFile($setting);
        if(empty($configContent)) return false;

        // unzip package
        $this->app->loadClass('pclzip', true);
        $basePackage = new pclzip($setting->basePackage);
        $result  = $basePackage->extract(
            PCLZIP_OPT_PATH, $setting->xxdDirectory
        );
        if($result == 0) $basePackage->errorInfo(true);

        // Replace config file.
        $baseFilePath = $result[0]['filename'];
        $packageName  = $result[0]['stored_filename'];
        unlink($baseFilePath . 'config/xxd.conf');
        unlink($baseFilePath . 'config/xxd.en.conf');
        file_put_contents($baseFilePath . 'config/xxd.conf', $configContent['zh']);
        file_put_contents($baseFilePath . 'config/xxd.en.conf', $configContent['en']);

        // https add certificate
        if(isset($setting->https) && $setting->https == 'on')
        {
            if(!is_dir($baseFilePath . 'cert')) mkdir($baseFilePath . 'cert', 0777);
            file_put_contents($baseFilePath . 'cert/xxd.crt', $setting->sslcrt);
            file_put_contents($baseFilePath . 'cert/xxd.key', $setting->sslkey);
        }

        // zip xxd file
        chdir($setting->xxdDirectory);
        $xxdZipName = $setting->xxdDirectory . "/" . $setting->xxdFileName;
        $xxdZip     = new pclzip($xxdZipName);
        $xxdResult  = $xxdZip->create($packageName, PCLZIP_OPT_TEMP_FILE_ON);
        if($xxdResult == 0) return false;
        return $xxdResult[0]['filename'];
    }

    /**
     * revise operating system name.
     *
     * @param string $os name
     * @access public
     * @return string
     */
    public function getSystem($os)
    {
        return zget($this->config->im->osMap, $os, 'win64');
    }

    /**
     * Get server, if server is localhost, try get from stored login url.
     *
     * @access public
     * @return string
     */
    public function getServer()
    {
        $server = commonModel::getSysURL();
        if(!empty($this->config->xuanxuan->server)) $server = $this->config->xuanxuan->server;

        $serverURLComponents = parse_url($server);
        if(!empty($serverURLComponents['host']) && in_array($serverURLComponents['host'], array('127.0.0.1', 'localhost', '::1')))
        {
            $loginURL = $this->loadModel('setting')->getItem("owner=system&module=im&section=loginurl&key={$this->app->session->userID}");
            if(!empty($loginURL))
            {
                $loginURLComponents = parse_url($loginURL);
                if(!empty($loginURLComponents['host'])) $server = substr_replace($server, $loginURLComponents['host'], strpos($server, $serverURLComponents['host']), strlen($serverURLComponents['host']));
            }
        }

        return $server;
    }

    /**
     * UploadFile a file.
     *
     * @param  string $fileName
     * @param  string $path
     * @param  int    $size
     * @param  int    $time
     * @param  int    $userID
     * @param  string $users
     * @param  object $chat
     * @access public
     * @return int
     */
    public function uploadFile($fileName, $path, $size, $time, $userID, $users, $chat)
    {
        $user      = $this->userGetByID($userID);
        $extension = $this->loadModel('file')->getExtension($fileName); // if file has no extension or is "danger",return "txt, but $fileName is the origin file name"

        $file = new stdclass();
        $file->pathname    = $path;
        $file->title       = preg_replace("/\.$extension$/", '', $fileName);
        $file->extension   = $extension;
        $file->size        = $size;
        $file->objectType  = 'chat';
        $file->objectID    = $chat->id;
        $file->createdBy   = !empty($user->account) ? $user->account : '';
        $file->createdDate = date(DT_DATETIME1, $time);

        $this->dao->insert(TABLE_FILE)->data($file)->exec();

        $fileID = $this->dao->lastInsertID();
        $path  .= md5($fileName . $fileID . $time);
        $this->dao->update(TABLE_FILE)->set('pathname')->eq($path)->where('id')->eq($fileID)->exec();

        return $fileID;
    }

    /**
     * Save xxd start time.
     *
     * @access public
     * @return bool
     */
    public function setXxdStartTime()
    {
        $this->loadModel('setting')->setItem('system.common.xxd.start', helper::now());
        return !dao::isError();
    }

    /**
     * update last poll.
     *
     * @access public
     * @return void
     */
    public function updateLastPoll()
    {
        $this->loadModel('setting')->setItem('system.common.xxd.lastPoll', helper::now());
    }

    /**
     * check xxb config.
     *
     * @access public
     * @return bool
     */
    public function checkXXBConfig()
    {
        $xxbConfig      = $this->config->xuanxuan;
        $notEmptyFields = array('key', 'server', 'ip', 'chatPort', 'commonPort');

        foreach($notEmptyFields as $field) if(empty($xxbConfig->$field)) return false;
        if($xxbConfig->https == 'on' && (empty($xxbConfig->sslcrt) || empty($xxbConfig->sslkey))) return false;

        return true;
    }

    /**
     * Get xxd run time.
     *
     * @param  int    $timestamp
     * @param  int    $count
     * @access public
     * @return string
     */
    public function getXxdRunTime($timestamp, $count = 0)
    {
        if($count > 1) return '';

        if($timestamp > 86400)
        {
            return floor($timestamp / 86400) . $this->lang->im->day . $this->getXxdRunTime($timestamp%86400, ++$count);
        }
        else if($timestamp > 3600)
        {
            return floor($timestamp / 3600) . $this->lang->im->hours . $this->getXxdRunTime($timestamp%3600, ++$count);
        }
        else if($timestamp > 60)
        {
            return floor($timestamp / 60) . $this->lang->im->minute . $this->getXxdRunTime($timestamp%60, ++$count);
        }
        else
        {
            return $timestamp . $this->lang->im->secs;
        }
    }

    /**
     * Get xxd status.
     *
     * @access public
     * @return string
     */
    public function getXxdStatus()
    {
        $this->app->loadLang('client');
        $now          = helper::now();
        $xxdStatus    = 'offline';
        $polling      = empty($this->config->xuanxuan->pollingInterval) ? 60 : $this->config->xuanxuan->pollingInterval;
        $lastPoll     = $this->loadModel('setting')->getItem("owner=system&module=common&section=xxd&key=lastPoll");
        $xxdStartDate = zget($this->config->xxd, 'start', $this->lang->client->noData);

        if((strtotime($now) - strtotime($xxdStartDate) < $polling) || (strtotime($now) - strtotime($lastPoll)) < (3 + $polling))
        {
            $xxdStatus = 'online';
        }
        else if((strtotime($now) - strtotime($lastPoll)) > (3 + $polling))
        {
            $xxdStatus = 'offline';
        }

        return $xxdStatus;
    }

    /**
     * Get signed time.
     * Other program can extend this function.
     *
     * @param  string $account
     * @access public
     * @return string | int
     */
    public function getSignedTime($account = '')
    {
        return 0;
    }

    /**
     * Get extension list.
     * @param $userID
     * @return array
     */
    public function getExtensionList($userID)
    {
        $entries    = array();
        $allEntries = array();
        $time       = time();
        $baseURL    = commonModel::getSysURL();
        $entryList  = $this->dao->select('*')->from(TABLE_ENTRY)->orderBy('`order`, id')->fetchAll();
        $files      = $this->dao->select('id, pathname, objectID')->from(TABLE_FILE)->where('objectType')->eq('entry')->fetchAll('objectID');

        foreach($entryList as $entry)
        {
            $data = new stdclass();
            $data->id     = $entry->id;
            $data->url    = strpos($entry->login, 'http') !== 0 ? str_replace('../', $baseURL . $this->config->webRoot, $entry->login) : $entry->login;
            $allEntries[] = $data;
        }

        $_SERVER['SCRIPT_NAME'] = str_replace('x.php', 'index.php', $_SERVER['SCRIPT_NAME']);
        foreach($entryList as $entry)
        {
            if($entry->status != 'online') continue;
            if(strpos(',' . $entry->platform . ',', ',xuanxuan,') === false) continue;

            $token = '';
            if(isset($files[$entry->id]->pathname))
            {
                $token = '&time=' . $time . '&token=' . md5($files[$entry->id]->pathname . $time);
            }
            $data = new stdClass();
            $data->entryID     = (int)$entry->id;
            $data->name        = $entry->code;
            $data->displayName = $entry->name;
            $data->abbrName    = $entry->abbr;
            $data->optional    = $entry->optional;
            $data->enable      = $entry->enable;
            $data->webViewUrl  = strpos($entry->login, 'http') !== 0 ? str_replace('../', $baseURL . $this->config->webRoot, $entry->login) : $entry->login;
            $data->download    = empty($entry->package) ? '' : $baseURL . helper::createLink('file', 'download', "fileID={$entry->package}&mouse=" . $token);
            $data->md5         = empty($entry->package) ? '' : md5($entry->package);
            $data->logo        = empty($entry->logo)    ? '' : $baseURL . $this->config->webRoot . ltrim($entry->logo, '/');

            if($entry->sso) $data->data = $allEntries;

            $entries[] = $data;
        }

        return $entries;
    }

    /**
     * transfer ip to number
     *
     * @param string $ip
     * @return int
     */
    public function getIPLong($ip) {
        return bindec(decbin(ip2long($ip)));
    }

    /**
     * Check whether IP is valid
     *
     * @param string $ip
     * @return bool
     */
    public function checkIPValidity($ip) {
        if(filter_var($ip, FILTER_VALIDATE_IP)) return true;
        return false;
    }

    /**
     * Check whether CIDR is valid
     *
     * @param string $ip
     * @return bool
     */
    public function checkCIDRValidity($cidr)
    {
        $parts = explode('/', $cidr);
        if(count($parts) != 2) return false;

        $ip = $parts[0];
        if(!$this->checkIPValidity($ip)) return false;

        $netmask = $parts[1];
        if(!is_numeric($netmask)) return false;

        $netmask = intval($parts[1]);
        if($netmask < 0 || $netmask > 32) return false;

        return true;
    }

    /**
     * check whether IP in CIDR
     * @param string|number $ip
     * @param string $cidr
     * @return bool
     */
    public function checkIPInCIDR($ip, $cidr)
    {
        $cidr = explode('/', $cidr);
        if(is_string($ip)) $ip = $this->getIPLong($ip);
        $startIp = long2ip((ip2long($cidr[0])) & ((-1 << (32 - (int)$cidr[1]))));
        $endIp   = long2ip((ip2long($startIp)) + pow(2, (32 - (int)$cidr[1])) - 1);
        $startIp = $this->getIPLong($startIp);
        $endIp   = $this->getIPLong($endIp);
        return $ip >= $startIp && $ip <= $endIp;
    }

    /**
     * check whether IP in CIDRs
     * @param string $ip
     * @param string $startIp
     * @param string $endIp
     * @return bool
     */
    public function checkIPInCIDRs($ip, $cidrs)
    {
        $originCidrs = $cidrs;
        $cidrs = explode(',', $cidrs);
        if(count($cidrs) == 0)
        {
            if($this->checkCIDRValidity($originCidrs))
            {
                $cidrs = array($originCidrs);
            }
            else
            {
                return false;
            }
        }
        $ip = $this->getIPLong($ip);
        foreach($cidrs as $cidr) if($this->checkIPInCIDR($ip, $cidr)) return true;
        return false;
    }


    /**
     * __call functions defined in model.
     *
     * @param  string    $function
     * @param  array     $arguments
     * @access public
     * @return void
     */
    public function __call($function, $arguments)
    {
        foreach($this->models as $model)
        {
            if(strpos(strtolower($function), $model) === 0)
            {
                $trimedFunction = substr($function, strlen($model));
                if(is_callable(array($this->$model, $trimedFunction))) return call_user_func_array(array($this->$model, $trimedFunction), $arguments);
            }
        }

        $this->app->triggerError("Method im::$function not exists.", __FILE__, __LINE__, $exit = true);
    }
}
