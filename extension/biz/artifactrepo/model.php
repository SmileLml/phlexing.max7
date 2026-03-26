<?php
/**
 * The model file of artifactrepo module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   artifactrepo
 * @version   $Id$
 * @link      https://www.zentao.net
 */
class artifactrepoModel extends model
{
    /**
     * 根据id获取制品库。
     * Get artifactrepo by id.
     *
     * @param  int    $artifactRepoID
     * @access public
     * @return object
     */
    public function getByID($artifactRepoID)
    {
        $artifactRepo =  $this->dao->select('t1.*, t2.url as serverUrl, t2.name as serverName')->from(TABLE_ARTIFACTREPO)->alias('t1')
            ->leftJoin(TABLE_PIPELINE)->alias('t2')->on('t1.serverID = t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.id')->eq($artifactRepoID)
            ->fetch();

        if($artifactRepo) $artifactRepo->url = "{$artifactRepo->serverUrl}/repository/{$artifactRepo->repoName}";
        return $artifactRepo;
    }

    /**
     * 获取制品库列表。
     * Get artifactrepo repo list.
     *
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($orderBy = 'id_desc', $pager = null)
    {
        if(substr($orderBy, 0, 5) == 'type_') $orderBy = 't2.' . $orderBy;
        $artifactRepos = $this->dao->select('t1.*, t2.id AS pipelineID, t2.url, t2.type')->from(TABLE_ARTIFACTREPO)->alias('t1')
            ->leftJoin(TABLE_PIPELINE)->alias('t2')->on('t1.serverID = t2.id')
            ->where('t1.deleted')->eq(0)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        foreach($artifactRepos as $repo) $repo->url .= '/#browse/browse:' . $repo->repoName;

        return $artifactRepos;
    }

    /**
     * 获取服务器上仓库列表。
     * Get server repos.
     *
     * @param  int    $serverID
     * @access public
     * @return array
     */
    public function getServerRepos($serverID)
    {
        $server = $this->loadModel('pipeline')->getByID($serverID);
        if(!$server) return array();

        $url  = $server->url . '/service/rest/v1/repositorySettings';
        $auth = "{$server->account}:{$server->password}";

        $response = common::http($url, '', array(CURLOPT_USERPWD => $auth), array(), 'data', 'POST', 10, true);
        return array('result' => $response[1] == 200, 'data' => (array)json_decode($response['body']));
    }

    /**
     * 创建一个制品库。
     * Create a artifact repo.
     *
     * @param  object $repo
     * @access public
     * @return false|int
     * @param object $artifactRepo
     */
    public function create($artifactRepo)
    {
        $this->dao->insert(TABLE_ARTIFACTREPO)->data($artifactRepo)
            ->check('name', 'unique', "name = '{$artifactRepo->name}'")
            ->check('repoName', 'unique', "serverID = {$artifactRepo->serverID} and repoName = '{$artifactRepo->repoName}'")
            ->autoCheck()
            ->exec();

        if(dao::isError()) return false;
        return $this->dao->lastInsertID();
    }

    /**
     * 更新一个制品库。
     * Update a artifact repo.
     *
     * @param  object      $artifactRepo
     * @param  int         $artifactRepoID
     * @access public
     * @return array|false
     */
    public function update($artifactRepo, $artifactRepoID)
    {
        $oldArtifactRepo = $this->getByID($artifactRepoID);
        if(!$oldArtifactRepo) return false;

        $this->dao->update(TABLE_ARTIFACTREPO)->data($artifactRepo)
            ->autoCheck()
            ->check('name', 'unique', "name = '{$artifactRepo->name}' and id != {$artifactRepoID}")
            ->where('id')->eq($artifactRepoID)
            ->exec();
        if(dao::isError()) return false;

        $changes = common::createChanges($oldArtifactRepo, $artifactRepo);
        if($changes)
        {
            $actionID = $this->loadModel('action')->create('artifactRepo', $artifactRepoID, 'edited');
            $this->action->logHistory($actionID, $changes);
        }

        return $changes;
    }

    /**
     * 通过产品ID获取制品库信息。
     * Get artifactrepo by product ID.
     *
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function getReposByProduct($productID)
    {
        $artifactRepos = $this->dao->select('t1.*, t2.id AS pipelineID, t2.url')->from(TABLE_ARTIFACTREPO)->alias('t1')
            ->leftJoin(TABLE_PIPELINE)->alias('t2')->on('t1.serverID = t2.id')
            ->where('products')->like("%,{$productID},%")
            ->andWhere('t1.deleted')->eq(0)
            ->fetchAll('id');

        foreach($artifactRepos as $repo) $repo->url .= '/repository/' . $repo->repoName;
        return $artifactRepos;
    }

    /**
     * 获取制品库关联的版本信息。
     * Get the build information linked with the artifactrepo.
     *
     * @param  int          $artifactRepoID
     * @access public
     * @return object|false
     */
    public function getLinkBuild($artifactRepoID)
    {
        return $this->dao->select('*')->from(TABLE_BUILD)->where('artifactRepoID')->eq($artifactRepoID)->fetch();
    }

    /**
     * 更新版本库状态。
     * Update artifact repo status.
     *
     * @param  int       $artifacts
     * @param  string    $status
     * @access protected
     * @return bool
     */
    public function updateStatus($artifacts, $status)
    {
        $this->dao->update(TABLE_ARTIFACTREPO)->set('status')->eq($status)->where('id')->eq($artifacts)->exec();

        return !dao::isError();
    }
}
