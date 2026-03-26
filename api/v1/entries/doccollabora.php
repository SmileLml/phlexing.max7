<?php
/**
 * The docCollabora entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      qixinzhi <qixinzhi@chandao.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class docCollaboraEntry extends baseEntry
{
    /**
     * GET method.
     *
     * @param  int    $docID
     * @access public
     * @return string
     */
    public function get($docID)
    {
        $this->resetOpenApp($this->param('tab', 'doc'));

        $control = $this->loadController('doc', 'view');

        $doc = $this->loadModel('doc')->getByID($docID, 0, true);
        if(!$doc) return $this->send400('error');

        $isOpen          = $doc->acl == 'open';
        $currentAccount  = $this->app->user->account;
        $isAuthorOrAdmin = $doc->acl == 'private' && ($doc->addedBy == $currentAccount || $this->app->user->admin);
        $isInReadUsers   = strpos(",$doc->readUsers,", ",$currentAccount,") !== false;
        $isInEditUsers   = strpos(",$doc->users,", ",$currentAccount,") !== false;

        $priv = new stdClass();
        $priv->canEdit = $isOpen || $isAuthorOrAdmin || $isInEditUsers;
        $priv->canRead = $isOpen || $isAuthorOrAdmin || $isInReadUsers || $priv->canEdit;
        $priv->account = $currentAccount;
        $priv->userID  = $this->app->user->id;

        return $this->send(200, $priv);
    }
}
