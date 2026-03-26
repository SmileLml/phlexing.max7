<?php
class demoBot extends xuanBot
{
    /**
     * 机器人的显示名称
     * Display name of this bot.
     *
     * @var    string
     * @access public
     */
    public $name = '示例';

    /**
     * 机器人的代号
     * Bot code.
     *
     * @var    string
     * @access public
     */
    public $code = 'demo';

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
    public $help = 'Hello from demo bot!';

    /**
     * 构造函数
     * Constructor.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->commands[] = 'whoami';
        $this->commands[] = array('command' => 'ping', 'alias' => array('乒'), 'description' => 'Ping the bot.');
        $this->commands[] = array('command' => 'getUser', 'description' => 'Get current user.');
        $this->commands[] = array('command' => 'star', 'alias' => array('pin', '置顶', '收藏'), 'description' => 'Pin this chat.');
        $this->commands[] = array('command' => 'unstar', 'alias' => array('unpin', '取消置顶', '取消收藏'), 'description' => 'Unpin this chat.');
        $this->commands[] = array('command' => 'add', 'alias' => array('sum', '加'), 'description' => 'Calculate sum of given numbers.', 'validator' => 'numValidator', 'argsRequired' => true);
    }

    /**
     * 机器人初始化方法，仅会在机器人初次被调用时执行
     * Initialization method, will be called when this bot is called.
     *
     * @access public
     * @return void
     */
    public function init()
    {
        $this->setHelp();
    }

    /**
     * 设置帮助信息
     * Set help message.
     *
     * @access private
     * @return void
     */
    private function setHelp()
    {
        $this->help = <<<EOF
This is the help message of {$this->name} bot.
> This help message is updated with `init()` method.

| Command | Description | Bot Code |
| :--- | :--- | :--- |
| [ping](xxc://sendContentToServerBySendbox/ping@demo) | Ping bot | demo |
| [getUser](xxc://sendContentToServerBySendbox/getUser@demo) | Get current user | demo |
| [star](xxc://sendContentToServerBySendbox/star@demo) | Pin bot chat | demo |
| [unstar](xxc://sendContentToServerBySendbox/unstar@demo) | Unpin bot chat | demo |
| [whoami](xxc://sendContentToServerBySendbox/whoami@demo) | Who am I | demo |
| [add](xxc://sendContentToChat/add@demo) | Calculate sum of given numbers | demo |
EOF;
    }

    // /**
    //  * 收到普通消息(非命令消息)时的回调函数
    //  * Callback function when receive a normal message.
    //  *
    //  * @param  string $message  message content
    //  * @param  int    $userID   sender user id
    //  * @access public
    //  * @return string|object
    //  */
    // public function onMessage($message, $userID)
    // {
    //     return "示例机器人收到了消息：{$message}";
    // }

    /**
     * Who am I 示例命令，返回当前用户名称
     * Who am I, returns current user name.
     *
     * @param  array  $args
     * @param  int    $userID
     * @param  object $user
     * @access public
     * @return string
     */
    public function whoami($args = array(), $userID = 0, $user = null)
    {
        return empty($user) ? 'unknown' : (empty($user->realname) ? $user->account : $user->realname);
    }

    /**
     * Ping 示例命令，返回 pong
     * Ping, returns pong.
     *
     * @param  array  $args
     * @param  int    $userID
     * @access public
     * @return string
     */
    public function ping($args = array())
    {
        return empty($args) ? 'pong' : ('pong ' . implode(' ', $args));
    }

    /**
     * 获取当前用户信息示例命令
     * Get current user.
     *
     * @param  array  $args
     * @param  int    $userID
     * @access public
     * @return string
     */
    public function getUser($args = array(), $userID = 0)
    {
        return json_encode($this->im->userGetByID($userID));
    }

    /**
     * 置顶机器人会话示例命令
     * Pin bot chat.
     *
     * @param  array  $args
     * @param  int    $userID
     * @access public
     * @return void
     */
    public function star($args = array(), $userID = 0)
    {
        $cgid = "$userID&xuanbot";
        $this->im->chatStar('1', $cgid, $userID);
        $starResponse = array('method' => 'chatstar', 'result' => 'success', 'data' => array('gid' => $cgid, 'star' => 1), 'users' => array($userID));
        return array((object)$starResponse, 'Pinned.');
    }

    /**
     * 取消置顶机器人会话示例命令
     * Unpin bot chat.
     *
     * @param  array  $args
     * @param  int    $userID
     * @access public
     * @return void
     */
    public function unstar($args = array(), $userID = 0)
    {
        $cgid = "$userID&xuanbot";
        $this->im->chatStar('1', $cgid, $userID);
        $starResponse = array('method' => 'chatstar', 'result' => 'success', 'data' => array('gid' => $cgid, 'star' => 0), 'users' => array($userID));
        return array((object)$starResponse, 'Unpinned.');
    }

    /**
     * Add 示例命令，返回数字相加的结果
     *
     * @param  array  $args
     * @param  int    $userID
     * @access public
     * @return string
     */
    public function add($args = array(), $userID = 0)
    {
        $result = 0;
        foreach($args as $arg) $result += $arg;
        return "$result";
    }

    /**
     * Add 示例命令对应的参数验证函数
     * Add command args validator.
     *
     * @param  array $args
     * @access public
     * @return bool
     */
    public function numValidator($args = array())
    {
        foreach($args as $arg)
        {
            if(!is_numeric($arg)) return false;
        }
        return true;
    }
}
