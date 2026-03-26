<?php
class defaultBot extends xuanBot
{
    /**
     * 机器人的显示名称
     * Display name of this bot.
     *
     * @var    string
     * @access public
     */
    public $name = '默认';

    /**
     * 机器人的代号
     * Bot code.
     *
     * @var    string
     * @access public
     */
    public $code = 'default';

    /**
     * 机器人的命令列表
     * Command list of this bot.
     *
     * @var    array
     * @access public
     */
    public $commands = array();

    /**
     * 机器人的帮助信息
     * Help message of this bot.
     *
     * @var    string
     * @access public
     */
    public $help = '';

    /**
     * 构造函数
     * Constructor.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->commands[] = array('command' => 'server', 'alias' => array('服务器', 'serverinfo'), 'description' => 'Get server information.', 'adminOnly' => true);
        $this->commands[] = array('command' => 'license', 'alias' => array('授权'), 'description' => 'Get license information.', 'adminOnly' => true);
        $this->commands[] = array('command' => 'searchUser', 'alias' => array('sch', 'schuser', 'searchuser', '搜索', '搜索用户', '查看用户'), 'description' => 'Search user with keyword.', 'argsRequired' => true);
    }


    /**
     * 获取服务器信息的命令，仅管理员可以使用
     * Get server information.
     *
     * @param array $args
     * @param integer $userID
     * @return void
     */
    public function server($args = array(), $userID = 0)
    {
        $users   = $this->im->userGetList();
        $online  = 0;
        $offline = 0;

        foreach($users as $user) $user->clientStatus == 'offline' ? $offline++ : $online++;

        $info = array();
        $info['PHP Version']    = PHP_VERSION;
        $info['PHP OS']         = PHP_OS;
        $info['64bit PHP']      = PHP_INT_SIZE === 8 ? 'Yes' : 'No';
        $info['PHP Binary dir'] = PHP_BINDIR;
        $info['Total Users']    = count($users);
        $info['Online Users']   = $online;
        $info['Offline Users']  = $offline;

        return self::printMarkdownTable(array('Item', 'Value'), $info);
    }

    /**
     * 获取授权信息的示例命令，仅管理员可以使用
     * Get license information.
     *
     * @access public
     * @return string
     */
    public function license()
    {
        if(!method_exists('commonModel', 'getLicenseProperties')) return $this->im->lang->im->bot->license->noLicense;

        $licenseData = commonModel::getLicenseProperties();
        if(empty($licenseData)) return $this->im->lang->im->bot->license->noLicense;

        $this->im->app->loadLang('license');

        $license = array();
        $license[$this->im->lang->license->licensedTo]           = isset($licenseData['company'])               ? $licenseData['company']['value'] : '';
        $license['IP']                                           = isset($licenseData['ip'])                    ? $licenseData['ip']['value'] : '';
        $license['MAC']                                          = isset($licenseData['mac'])                   ? $licenseData['mac']['value'] : '';
        $license[$this->im->lang->license->userLimit]            = isset($licenseData['user'])                  ? $licenseData['user']['value'] : '';
        $license[$this->im->lang->license->expireDate]           = isset($licenseData['expireDate'])            ? $licenseData['expireDate']['value'] : '';
        $license[$this->im->lang->license->startDate]            = isset($licenseData['startDate'])             ? $licenseData['startDate']['value'] : '';
        $license[$this->im->lang->license->permissions]          = isset($licenseData['permissions'])           ? $licenseData['permissions']['value'] : '';
        $license[$this->im->lang->license->conferencePermission] = isset($licenseData['unlimitedParticipants']) ? (empty($licenseData['unlimitedParticipants']['value']) ? sprintf($this->lang->license->conferenceLimited, $licenseData['unlimitedParticipants']['value']) : $this->im->lang->license->unlimited) : '';

        return self::printMarkdownTable($this->im->lang->im->bot->license->title, $license);
    }

    /**
     * 搜索用户的命令
     * Search for users.
     *
     * @param  array  $args
     * @access public
     * @return string
     */
    public function searchUser($args = array())
    {
        $keyword = current($args);
        if(empty($keyword)) return $this->im->lang->im->bot->searchUser->keywordRequired;

        $users = $this->im->userSearch($keyword);
        if(empty($users)) return $this->im->lang->im->bot->searchUser->notFound;

        $result = array();

        $imUser = $this->im->user;

$depts = $this->im->loadModel('dept')->getDeptPairs();
$deptList = array_map(function($k, $v) {return (object)array('id' => $k, 'name' => $v);}, array_keys($depts), $depts);
$roleList = $this->im->lang->user->roleList;

        $this->im->user = $imUser;

        foreach($users as $user)
        {
            $deptsObj = array_filter($deptList, function($dept) use ($user) {return $dept->id == $user->dept;});
            $dept     = current($deptsObj);
            $result[] = array("[{$user->realname}](xxc://showContextMenu/member.profile/{$user->id})", isset($dept->name) ? $dept->name : '', zget($roleList, $user->role, $user->role), empty($user->mobile) ? $user->phone : $user->mobile, $user->email, $this->im->lang->im->userStatus[$user->status]);
        }

        $this->im->app->loadLang('user');

        return self::printMarkdownTable(array($this->im->lang->user->account, $this->im->lang->user->dept, $this->im->lang->user->role, $this->im->lang->user->phone, $this->im->lang->user->email, $this->im->lang->user->clientStatus), $result);
    }

}
