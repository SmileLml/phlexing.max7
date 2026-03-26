<?php
/**
 * The model file of client module of XXB.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd., www.zentao.net)
 * @license     ZOSL (https://zpl.pub/page/zoslv1.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     client
 * @version     $Id$
 * @link        https://xuanim.com
 */
class clientModel extends model
{
    /**
     * Get a client by id.
     *
     * @param  int    $clientID
     * @access public
     * @return object | bool
     */
    public function getByID($clientID)
    {
        $client = $this->dao->select('*')->from(TABLE_IM_CLIENT)->where('id')->eq($clientID)->fetch();
        if(empty($client)) return false;
        $client->downloads = json_decode($client->downloads, true);
        return $client;
    }

    /**
     * Get a client by version.
     *
     * @param  string $version
     * @access public
     * @return object | bool
     */
    public function getByVersion($version)
    {
        $client = $this->dao->select('*')->from(TABLE_IM_CLIENT)->where('version')->eq($version)->fetch();
        if(empty($client)) return false;
        $client->downloads = json_decode($client->downloads, true);
        return $client;
    }

    /**
     * Get client list.
     *
     * @access public
     * @return array
     */
    public function getList()
    {
        return $this->dao->select('*')->from(TABLE_IM_CLIENT)->orderBy('id_desc')->fetchAll();
    }

    /**
     * Get xxc current version from xuan.im.
     *
     * @access public
     * @return array
     */
    public function getXxcVersionFromWebsite()
    {
        $this->loadModel('setting');
        $now           = helper::now();
        $xxcUpdateInfo = $this->setting->getItem('owner=system&module=common&section=client&key=xxcUpdateInfo');
        if(!empty($xxcUpdateInfo))
        {
            $xxcInfo     = json_decode($xxcUpdateInfo, false);
            $prevGetDate = $xxcInfo->updateDate;
            if(helper::diffDate($now, $prevGetDate) <= $this->config->client->expirationDays) return $xxcInfo->version;
        }

        $currentVersion = $this->getCurrentVersion();
        $apiUrl         = sprintf($this->config->client->upgradeApi, $currentVersion->version);
        $jsonData       = file_get_contents($apiUrl);
        $serverVersions = json_decode($jsonData);

        if(empty($serverVersions)) return false;
        $xxcVersion = $serverVersions[0]->xxcVersion;

        if(!empty($xxcVersion))
        {
            $xxcUpdateInfo = new stdclass();
            $xxcUpdateInfo->version    = $xxcVersion;
            $xxcUpdateInfo->updateDate = $now;

            $this->setting->setItem('system.common.client.xxcUpdateInfo', json_encode($xxcUpdateInfo));
        }

        return $xxcVersion;
    }

    /**
     * Create a client.
     *
     * @access public
     * @return bool
     */
    public function create()
    {
        $client = fixer::input('post')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->get();

        if(empty($client->version)) dao::$errors['version'][] = sprintf($this->lang->error->notempty, $this->lang->client->version);
        if($client->version && !preg_match("/([0-9]+)((?:\.[0-9]+)?)((?:\.[0-9]+)?)(?:[\s\-\+]?)((?:[a-z]+)?)((?:\.?[0-9]+)?)/i", $client->version)) dao::$errors['version'][] = $this->lang->client->wrongVersion;
        foreach($client->downloads as $os => $url)
        {
            if(!empty($url) && (!validater::checkURL($url) || !preg_match("/\.zip$/", $url))) dao::$errors[$os][] = sprintf($this->lang->client->urlError, zget($this->lang->client->zipList, $os) . $this->lang->client->downloadLink);
        }
        if(dao::isError()) return false;

        $client->downloads = helper::jsonEncode($client->downloads);
        $this->dao->insert(TABLE_IM_CLIENT)->data($client)->autoCheck()->exec();

        return !dao::isError();
    }

    /**
     * Create or edit a client by account.
     * @param $version
     * @param $link
     * @param $os
     * @return bool
     */
    public function edit($version, $link, $os)
    {
        $client = $this->getByVersion($version);
        if($client)
        {
            $client->downloads[$os] = $link;
            $client->editedBy       = $this->app->user->account;
            $client->editedDate     = helper::now();
            $client->downloads      = helper::jsonEncode( $client->downloads);
            $this->dao->update(TABLE_IM_CLIENT)->data($client)->where('id')->eq($client->id)->exec();
        }
        else
        {
            $client = new stdClass();
            $client->status      = 'wait';
            $client->version     = $version;
            $client->strategy    = 'optional';
            $client->downloads   = helper::jsonEncode(array($os => $link));
            $client->createdBy   = $this->app->user->account;
            $client->createdDate = helper::now();
            $this->dao->insert(TABLE_IM_CLIENT)->data($client)->autoCheck()->exec();

            $client->id = $this->dao->lastInsertID();
        }
        if(dao::isError()) return false;
        return $client;
    }

    /**
     * Update a client.
     *
     * @param  int    $clientID
     * @access public
     * @return bool
     */
    public function update($clientID)
    {
        $client = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->get();

        if(empty($client->version)) dao::$errors['version'][] = sprintf($this->lang->error->notempty, $this->lang->client->version);
        if($client->version && !preg_match("/([0-9]+)((?:\.[0-9]+)?)((?:\.[0-9]+)?)(?:[\s\-\+]?)((?:[a-z]+)?)((?:\.?[0-9]+)?)/i", $client->version)) dao::$errors['version'][] = $this->lang->client->wrongVersion;
        foreach($client->downloads as $os => $url)
        {
            if(!empty($url) && (!validater::checkURL($url) || !preg_match("/\.zip$/", $url))) dao::$errors[$os][] = sprintf($this->lang->client->urlError, zget($this->lang->client->zipList, $os) . $this->lang->client->downloadLink);
        }
        if(dao::isError()) return false;

        $client->downloads = helper::jsonEncode($client->downloads);
        $this->dao->update(TABLE_IM_CLIENT)->data($client)->autoCheck()->where('id')->eq($clientID)->exec();

        return !dao::isError();
    }

    /**
     * Get current version
     * @access public
     * @return object | bool
     */
    public function getCurrentVersion()
    {
        $currentVersion = $this->dao->select('*')->from(TABLE_IM_CLIENT)->where('status')->eq('released')->orderBy('id_desc')->limit(1)->fetch();

        if(dao::isError()) return false;
        return $currentVersion ?: json_decode('{"version": "'.$this->config->xuanxuan->version.'"}');
    }

    /**
     * Check if a client need upgrade.
     *
     * @param  string $version
     * @access public
     * @return object | bool
     */
    public function getUpgrade($version)
    {
        $latestUpgradesIDQuery = $this->dao->select('MAX(id)')->from(TABLE_IM_CLIENT)
                                ->where('status')->eq('released')->groupBy('strategy')
                                ->get();
        $latestUpgradesObjectQuery = $this->dao->select('t1.downloads, t1.strategy, t1.version, t1.changeLog')->from(TABLE_IM_CLIENT)->alias('t1')
                                ->leftJoin("($latestUpgradesIDQuery)")->alias('t2')->on('t1.id = t2.`MAX(id)`')
                                ->get();
        $latestUpgradesObjectQuery = str_replace(' LEFT JOIN ', ' INNER JOIN ', $latestUpgradesObjectQuery);
        $latestUpgrades = $this->dao->query($latestUpgradesObjectQuery)->fetchAll();
        if(empty($latestUpgrades)) return false;

        foreach($latestUpgrades as $upgrade)
        {
            $upgrade->readme = $upgrade->changeLog;
            unset($upgrade->changeLog);

            $downloads = json_decode($upgrade->downloads);
            if(isset($downloads->linux32zip)) $downloads->linux32 = $downloads->linux32zip;
            if(isset($downloads->linux64zip)) $downloads->linux64 = $downloads->linux64zip;
            if(isset($downloads->macOSzip))   $downloads->mac64   = $downloads->macOSzip;
            if(isset($downloads->win32zip))   $downloads->win32   = $downloads->win32zip;
            if(isset($downloads->win64zip))   $downloads->win64   = $downloads->win64zip;
            unset($downloads->linux32zip);
            unset($downloads->linux64zip);
            unset($downloads->macOSzip);
            unset($downloads->win32zip);
            unset($downloads->win64zip);
            $upgrade->downloads = $downloads;
        }
        $optionalUpgrade = current(array_filter($latestUpgrades, function($upgrade) {return $upgrade->strategy == 'optional';}));
        $forcedUpgrade   = current(array_filter($latestUpgrades, function($upgrade) {return $upgrade->strategy == 'force';}));

        if($forcedUpgrade && version_compare(helper::formatVersion($version), helper::formatVersion($forcedUpgrade->version)) == -1)
        {
            if(version_compare(helper::formatVersion($version), '3.0.0-alpha.1') == -1)
            {
                $semver = helper::formatVersion($forcedUpgrade->version);
                if(strpos($semver, '-') !== false)
                {
                    $versions = explode('-', $semver);
                    $forcedUpgrade->version = $versions[0];
                }
                else
                {
                    $forcedUpgrade->version = $semver;
                }
            }
            return $forcedUpgrade;
        }
        elseif($optionalUpgrade && version_compare($version, $optionalUpgrade->version) == -1) return $optionalUpgrade;

        return false;
    }

    /**
     * Download zip package.
     * @param $version
     * @param $link
     * @return bool | string
     */
    public function downloadZipPackage($version, $link)
    {
        ignore_user_abort(true);
        set_time_limit(0);
        if(empty($version) || empty($link)) return false;
        $dir  = "data/client/" . $version . '/';
        $link = helper::safe64Decode($link);
        $file = basename($link);
        if(!is_dir($this->app->wwwRoot . $dir))
        {
            mkdir($this->app->wwwRoot . $dir, 0755, true);
        }
        if(!is_dir($this->app->wwwRoot . $dir)) return false;
        if(file_exists($this->app->wwwRoot . $dir . $file))
        {
            return commonModel::getSysURL() . $this->config->webRoot . $dir . $file;
        }
        ob_clean();
        ob_end_flush();

        $local  = fopen($this->app->wwwRoot . $dir . $file, 'w');
        $remote = fopen($link, 'rb');
        if($remote === false) return false;
        while(!feof($remote))
        {
            $buffer = fread($remote, 4096);
            fwrite($local, $buffer);
        }
        fclose($local);
        fclose($remote);
        return commonModel::getSysURL() . $this->config->webRoot . $dir . $file;
    }

    /**
     * Fetch client zip packages for current version.
     *
     * @param  string $osList  comma separated os list
     * @access public
     * @return void
     */
    public function fetchCurrentClient($osList = 'win64')
    {
        ignore_user_abort(true);
        set_time_limit(0);

        $osList  = explode(',', $osList);
        $version = $this->config->xuanxuan->version;
        $downloadDir = "{$this->app->wwwRoot}data/client/$version/";
        if(!is_dir($downloadDir)) mkdir($downloadDir, 0755, true);

        /* Lock for single download process. */
        if(file_exists($downloadDir . 'downloading')) return 'downloading';
        touch($downloadDir . 'downloading');

        $errors = array();
        foreach($osList as $os)
        {
            $fileName  = "xuanxuan.$version.$os.zip";
            $localPath = $downloadDir . $fileName;
            if(file_exists($localPath)) continue;

            $downloadLink = "{$this->config->client->downloadLinkPrefix}$version/$fileName";
            $downloadHandle  = fopen($downloadLink, 'rb');
            $localFileHandle = fopen($localPath, 'w');
            if($downloadHandle === false)
            {
                $errors[] = $downloadLink;
                continue;
            }
            while(!feof($downloadHandle))
            {
                $buffer = fread($downloadHandle, 4096);
                fwrite($localFileHandle, $buffer);
            }
            fclose($localFileHandle);
            fclose($downloadHandle);
        }

        /* Unlock. */
        unlink($downloadDir . 'downloading');

        return empty($errors) ? 'error' : 'done';
    }

    /**
     * Pack current server config into packages.
     *
     * @param  string $osList  comma separated os list
     * @param  bool   $repack
     * @access public
     * @return void
     */
    public function packServerConfig($osList = 'win64', $repack = false)
    {
        ignore_user_abort(true);
        set_time_limit(0);

        $osList  = explode(',', $osList);
        $version = $this->config->xuanxuan->version;
        $downloadDir = "{$this->app->wwwRoot}data/client/$version/";
        $packageDir  = "{$this->app->wwwRoot}data/client/$version/_common/";
        if(!is_dir($packageDir)) mkdir($packageDir, 0755, true);

        /* Lock for single packing process. */
        if(file_exists($packageDir . 'packing')) return 'packing';
        touch($packageDir . 'packing');

        /* Write server address into config file. */
        $loginInfo = new stdclass();
        $loginInfo->ui = new stdclass();
        $loginInfo->ui->defaultUser = new stdclass();
        $loginInfo->ui->defaultUser->server = $this->config->xuanxuan->server;
        $loginInfo = json_encode($loginInfo);

        $loginFile = $packageDir . 'config.json';
        file_put_contents($loginFile, $loginInfo);

        define('PCLZIP_TEMPORARY_DIR', $packageDir);
        $this->app->loadClass('pclzip', true);

        $errors = array();
        foreach($osList as $os)
        {
            $fileName = "xuanxuan.$version.$os.zip";
            $packagePath = $packageDir . $fileName;

            if(file_exists($packagePath) && !$repack) continue;

            $localPath = $downloadDir . $fileName;
            if(!file_exists($localPath))
            {
                $errors[] = "$localPath does not exist.";
                continue;
            }

            $copyResult = copy($localPath, $packagePath);
            if($copyResult === false)
            {
                $errors[] = "cannot copy $localPath to $packagePath.";
                continue;
            }

            $archive = new pclzip($packagePath);
            if($os == 'mac') $result = $archive->add($loginFile, PCLZIP_OPT_REMOVE_ALL_PATH, PCLZIP_OPT_ADD_PATH, 'xuanxuan.app/Contents/Resources/build-in');
            if($os != 'mac') $result = $archive->add($loginFile, PCLZIP_OPT_REMOVE_ALL_PATH, PCLZIP_OPT_ADD_PATH, 'xuanxuan/resources/build-in');

            if($result != 0) $errors[] = $archive->errorInfo(true);
        }
        unlink($loginFile);

        /* Unlock. */
        unlink($packageDir . 'packing');

        return empty($errors) ? 'error' : 'done';
    }

    /**
     * Get download link texts.
     *
     * @param  string $osList
     * @access public
     * @return void
     */
    public function getLinks($osList)
    {
        $osList  = explode(',', $osList);
        $version = $this->config->xuanxuan->version;

        $linksText = '';
        foreach($osList as $os) $linksText .= $this->lang->client->osList[$os] . ': ' . commonModel::getSysURL() . $this->config->webRoot . "data/client/$version/_common/xuanxuan.$version.$os.zip\n";
        return $linksText;
    }
}
