<?php
abstract class xuanBot
{
    /**
     * 类名 (PHP 5.5 以下不能用 ::class 获取，所以需要提供)
     * Class name of this class, since ::class is introduced in PHP 5.5.
     */
    const className = __CLASS__;

    /**
     * 机器人的显示名称
     * Display name of this bot.
     *
     * @var    string
     * @access public
     */
    public $name = 'XuanBot';

    /**
     * 机器人的代号，与类名保持一致
     * Bot code. It should be the same as the class name.
     *
     * @var    string
     * @access public
     */
    public $code;

    /**
     * 机器人的头像图片 URL
     * Avatar image URL of this bot.
     *
     * @var    string
     * @access public
     */
    public $avatar;

    /**
     * 机器人的别名，可以有多个
     * Aliases of this bot.
     *
     * @var    array
     * @access public
     */
    public $alias = array();

    /**
     * 机器人的命令列表，需要与实际命令函数名称一一对应
     * Command list of this bot, should be the same as the function names.
     *
     * 可以使用函数名称或命令对象作为元素，如果使用命令对象，可以设置命令的别名、描述、参数验证器、参数是否必须等属性。
     * You can use function name or command object as the element. If you use command object, you can set alias, description, validator, require args and some other properties.
     *
     * 以下是一个命令对象示例：
     * Command object example:
     *   array(
     *       'command'      => 'sample',                // 命令名称
     *                                                  // Command name.
     *       'description'  => 'A sample command.',     // 命令描述
     *                                                  // Command description.
     *       'alias'        => array('example', 'cmd'), // 别名
     *                                                  // Alias.
     *       'validator'    => 'sampleValidator',       // 参数验证方法(在对应 bot 中定义)的名称
     *                                                  // Validator function name (defined in the bot).
     *       'argsRequired' => true,                    // 是否需要参数，如果为真，点击帮助信息中返回的链接不会发送命令，而是填写到输入框
     *                                                  // Whether the command requires arguments. If true, the command link in the help message will not send the command, but send command into input box.
     *       'adminOnly'    => true,                    // 是否只有管理员才能使用
     *                                                  // Whether the command can only be used by admin.
     *       'internal'     => true,                    // 是否切换到该应用才能使用
     *                                                  // Whether the command can only be used in this app.
     *   )
     *
     * @var    array
     * @access public
     */
    public $commands = array();

    /**
     * 机器人的帮助信息，是 Markdown 格式的文本
     * Help message of this bot.
     *
     * @var    string
     * @access public
     */
    public $help = '';

    /**
     * 构造函数，会随 im 模块初始化，可以在这里做一些简单的初始化，复杂的初始化请使用 init() 函数
     * Constructor, will be called when im module is initialized.
     *
     * @access public
     * @return void
     */
    abstract public function __construct();

    /**
     * 机器人初始化方法，可以在这里进行一些初始化，仅会在机器人初次被调用时执行，适合复杂的初始化步骤
     * Initialization method, can be used to initialize some variables, will be called when this bot is called.
     *
     * @access public
     * @return void
     */
    public function init()
    {
        // init.
    }

    /**
     * 获取机器人的帮助信息
     * Get help message of this bot.
     *
     * @access public
     * @return string
     */
    public function getHelp()
    {
        return $this->help;
    }

    /**
     * 打印 Markdown 表格的帮助方法
     * Convert items to markdown.
     *
     * @param  array  $header
     * @param  array  $items
     * @param  string $align
     * @access public
     * @return string
     */
    public static function printMarkdownTable($header, $items, $align = 'left')
    {
        $alignIdentifier = array('left' => ':---', 'center' => ':---:', 'right' => '---:');
        if(!isset($alignIdentifier[$align])) $align = 'left';

        $align  = "| " . str_repeat($alignIdentifier[$align] . ' | ', count($header)) . "\n";
        $header = "| " . join(" | ", $header) . " |\n";

        $content = '';
        foreach($items as $key => $item)
        {
            if(!is_array($item))
            {
                $content .= "| $key | $item |\n";
                continue;
            }
            $content .= "| " . join(" | ", $item) . " |\n";
        }

        return $header . $align . $content;
    }

    // /**
    //  * 收到普通消息(非命令消息)时的回调函数，可在机器人中实现该函数，会在收到普通消息时调用
    //  * Callback function when receive a normal message. If this method is implemented in the bot, it will be called on normal message.
    //  *
    //  * @param  string $message  message content
    //  * @param  int    $userID   sender user id
    //  * @access public
    //  * @return string|object
    //  */
    // public function onMessage() {}

    // /**
    //  * 命令函数，可在机器人中实现该函数并注册到命令中，会在收到对应命令时调用
    //  * Command function, if this method is implemented and registered in the bot, it will be called on command.
    //  *
    //  * @param  array  $args     arguments of the command
    //  * @param  int    $userID   sender user id
    //  * @param  object $user     sender user object
    //  * @access public
    //  * @return string|object
    //  */
    // public function command($args = array(), $userID = 0, $user = null) {}
}
