<?php
/**
 * The control file of integration module of XXB.
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
class integration extends control
{
    /**
     * Configure office integrations, currently supports Collabora Office.
     *
     * @access public
     * @return void
     */
    public function office()
    {
        if($_POST)
        {
            $data = fixer::input('post')->get();
            if($data->officeEnabled)
            {
                $collaboraDiscovery = $this->integration->getCollaboraDiscovery($data->collaboraPath);
                if(empty($collaboraDiscovery)) $this->send(array('result' => 'fail', 'message' => $this->lang->integration->error->cannotConnectToCollabora));
            }
            $this->loadModel('setting')->setItems('system.integration.office', $data);
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('office')));
        }

        $this->view->title = $this->lang->integration->office;
        $this->view->position[] = $this->lang->integration->office;

        $this->display();
    }

    /**
     * Generate and redirect to collabora view url of file for user.
     *
     * @param  int    $fileID
     * @param  string $serverName     serverName set on XXD server
     * @param  string $protocol
     * @param  string $hostname
     * @param  string $port
     * @param  string $sessionID      user's sessionID on XXD server
     * @param  string $mode           ro | rw, rw enables collabora editing if file is marked as editable
     * @param  int    $messageID      required if mode is set to rw
     * @param  int    $userID
     * @access public
     * @return void   redirects user to collabora view url
     */
    public function wopi($fileID, $serverName, $protocol, $hostname, $port, $sessionID, $mode = 'ro', $messageID = 0, $userID = 0)
    {
        /* Fix params for older clients. */
        if(is_numeric($mode))
        {
            $userID = $mode;
            $mode = 'ro';
        }

        if(method_exists($this->app, 'loadConfig'))
        {
            $this->app->loadConfig('file');
            if(!zget($this->config->integration->office, 'officeEnabled') && empty($this->config->file->collaboraPath)) die($this->lang->integration->error->officeNotEnabled);
        }
        elseif(!zget($this->config->integration->office, 'officeEnabled')) die($this->lang->integration->error->officeNotEnabled);

        $user = $this->dao->select('account,realname')->from(TABLE_USER)->where('id')->eq($userID)->fetch();
        if(!$user) die($this->lang->integration->error->userNotFoundForRequest);

        $file = $this->loadModel('file')->getByID($fileID);
        if(!$file) die($this->lang->integration->error->fileNotFoundForRequest);

        $discovery = isset($this->file->getCollaboraDiscovery) ? $this->file->getCollaboraDiscovery() : $this->integration->getCollaboraDiscovery();
        if(!$discovery) die($this->lang->integration->error->cannotConnectToCollabora);
        if(!isset($discovery[$file->extension])) die($this->lang->integration->error->filePreviewNotSupported);

        $identifiers = $this->integration->getOfficeIdentifiers($file, $serverName, $mode == 'rw', $messageID, $sessionID, $user->realname, $userID);
        if(empty($identifiers)) die($this->lang->integration->error->buildIdentifierFail);

        $serverAddress = $protocol . '://' . $hostname . ':' . $port;
        $xxdWopiUrl = $serverAddress . '/wopi/files/' . $identifiers->file;
        $collaboraUrl = $discovery[$file->extension]['urlsrc'] . 'WOPISrc=' . $xxdWopiUrl . '&access_token=' . $identifiers->user;

        /* Change theme with css variables */
        $cssVariables = '';
        $docTheme = $this->config->collaboraThemes[$file->extension];
        if(isset($docTheme))
        {
            foreach($docTheme as $varName => $varValue)
            {
                $cssVariables .= "--$varName=$varValue;";
            }
        }
        if(!empty($cssVariables)) $collaboraUrl .= '&css_variables=' . urlencode($cssVariables);

        header("Location: $collaboraUrl", true, 302);
    }
}
