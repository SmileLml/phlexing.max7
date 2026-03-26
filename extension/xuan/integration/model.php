<?php
/**
 * The model file of integration module of XXB.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd., www.zentao.net)
 * @license     ZOSL (https://zpl.pub/page/zoslv1.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     integration
 * @version     $Id$
 * @link        https://xuanim.com
 */
?>
<?php
class integrationModel extends model
{
    /**
     * Fetch hosting/discovery urls from Collabora.
     *
     * @param  string $collaboraPath
     * @access public
     * @return array
     */
    public function getCollaboraDiscovery($collaboraPath = '')
    {
        if(empty($collaboraPath) and !empty($this->config->integration->office->collaboraPath)) $collaboraPath = $this->config->integration->office->collaboraPath;
        if(empty($collaboraPath) and isset($this->config->file->collaboraPath))                 $collaboraPath = $this->config->file->collaboraPath;
        if(empty($collaboraPath)) return array();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, trim($collaboraPath, '/') . '/hosting/discovery');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $discovery = curl_exec($ch);
        curl_close($ch);

        preg_match_all('|<action(.+)/>|', $discovery, $results);

        $files = array();
        foreach($results[1] as $key => $action)
        {
            preg_match_all('|ext="([^"]*)"|', $action, $output);
            if($output[1]) $extension = $output[1][0];
            if(empty($extension)) continue;

            preg_match_all('|name="([^"]*)"|', $action, $output);
            if($output[1]) $name = $output[1][0];

            preg_match_all('|urlsrc="([^"]*)"|', $action, $output);
            if($output[1]) $urlsrc = $output[1][0];

            $files[$extension]['action'] = $name;
            $files[$extension]['urlsrc'] = $urlsrc;
        }

        return $files;
    }

    /**
     * Get identifiers for WOPI API on XXD.
     *
     * @param  object $file
     * @param  string $serverName
     * @param  bool   $enableWrite
     * @param  string $messageID
     * @param  string $sessionID
     * @param  string $userDisplayName
     * @param  int    $userID
     * @access public
     * @return array
     */
    public function getOfficeIdentifiers($file, $serverName, $enableWrite = false, $messageID = '', $sessionID = '', $userDisplayName = '', $userID = 0)
    {
        $fileName  = "$file->title.$file->extension";
        $fileTime  = isset($file->addedDate) ? strtotime($file->addedDate) : strtotime($file->createdDate);
        $fileOwner = $file->createdBy;

        if($enableWrite && !empty($messageID))
        {
            $enableWrite = false;
            $message = $this->loadModel('im')->messageGetList('', array($messageID));
            if(!empty($message)) $message = current($message);
            $chat = $this->im->chatGetByGid($message->cgid, false, false);
            if($chat->archiveDate == null && $message->contentType == 'file')
            {
                $content = json_decode($message->content);
                if($content->id == $file->id && isset($content->editable) && $content->editable) $enableWrite = true;
            }
        }

        $fileMode = $enableWrite ? 'rw' : 'ro';

        $fileSession = md5($sessionID.$fileName);

        $fileIdentifier = array($fileName, $fileTime, $file->id, $fileOwner, $serverName, $fileMode);
        $userIdentifier = array($fileSession, $userDisplayName, $userID);

        $identifiers = array('file' => $fileIdentifier, 'user' => $userIdentifier);
        return (object)array_map(function($params)
        {
            foreach($params as $key => $param) $params[$key] = str_replace(array('/', '+'), array('_', '-'), base64_encode($param));
            $params = implode(',', $params);
            return str_replace(array('/', '+'), array('_', '-'), base64_encode($params));
        }, $identifiers);
    }
}
