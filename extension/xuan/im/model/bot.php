<?php
class imBot extends model
{
    /**
     * Bot code list.
     *
     * @var    array
     * @access public
     */
    public $bots = array();

    /**
     * Bot instance map.
     *
     * @var    object
     * @access public
     */
    public $botInstances;

    /**
     * Inited bot list.
     *
     * @var    array
     * @access public
     */
    public $initedBots = array();

    /**
     * Load and create bot instances.
     *
     * @param  string $appName
     * @param  object $im      imModel instance
     * @access public
     * @return void
     */
    public function __construct($appName, $im)
    {
        parent::__construct($appName);

        /* Save imModel for further uses. */
        $this->im = $im;

        /* Load bot classes and init. */
        $modulePaths = $im->app->getModuleExtPath('im', 'bot');
        $modulePath  = array_key_exists('xuan', $modulePaths) ? $modulePaths['xuan'] : current($modulePaths);

        helper::import($modulePath . 'xuanbot.php');
        $botFiles = helper::ls($modulePath, '*.bot.php');
        foreach($botFiles as $botFile) helper::import($botFile);

        $botClasses = array_filter(get_declared_classes(), function($className)
        {
            return is_subclass_of($className, xuanBot::className);
        });

        $this->botInstances = new stdclass();

        foreach($botClasses as $botClass)
        {
            $vars = get_class_vars($botClass);
            if(empty($vars['code']) || !preg_match('/^[a-zA-Z]\\w*$/', $vars['code'])) continue;
            $code = $vars['code'];

            $this->bots[] = $code;

            /* Init bots with im module. */
            $bot = new $botClass();
            $this->botInstances->$code     = $bot;
            $this->botInstances->$code->im = $im;
        }
    }

    /**
     * Init a bot.
     *
     * @param  string $botCode
     * @access public
     * @return bool
     */
    public function initBot($botCode)
    {
        if(!in_array($botCode, $this->bots)) return false;

        if(in_array($botCode, $this->initedBots)) return true;

        $this->botInstances->$botCode->init();
        $this->initedBots[] = $botCode;
        return true;
    }

    /**
     * Process message with bots.
     *
     * @param  object    $message
     * @param  int       $userID
     * @access public
     * @return array     messages from bots
     */
    public function processMessage($message, $userID = 0)
    {
        $replies = array();

        /* Convert characters from "zen-kaku"(full-width) to "han-kaku"(half-width). */
        $message->content = mb_convert_kana($message->content, "rnas", 'UTF-8');

        /* Check if message is command. */
        $isCommand = $message->type == 'botcommand' || ($message->type == 'normal' && in_array($message->contentType, array('text', 'plain')) && strpos($message->content, '/') === 0);

        if($isCommand)
        {
            /* Call bot commands. */
            $commandLine = ltrim($message->content, '/');
            $commandLine = explode(' ', $commandLine);

            $command = array_shift($commandLine);
            $args    = $commandLine;

            $result = $this->executeCommand($command, $args, $message, $userID);
            if(isset($result->executedBot))
            {
                $executedBot = $result->executedBot;
                $replies[]   = $result->result;
            }
            else
            {
                $replies[]   = $result;
            }
        }
        else
        {
            /* Call onMessage on bots. */
            foreach($this->bots as $bot)
            {
                if(!method_exists($this->botInstances->$bot, 'onMessage')) continue;
                $this->initBot($bot);
                $replies[] = $this->botInstances->$bot->onMessage($message->content, $userID);
            }
        }

        $messages  = array();
        $responses = array();

        $replies = array_filter($replies, function($reply)
        {
            return !is_null($reply) && $reply !== false;
        });
        foreach($replies as $reply)
        {
            /* Use reply as is if is properly set. E.g., (object)array('messages' => array(), 'responses' => array()) */
            if(is_object($reply) && isset($reply->messages) && isset($reply->responses))
            {
                $messages  = array_merge($messages,  $reply->messages);
                $responses = array_merge($responses, $reply->responses);
                continue;
            }

            /* Non-array / non-object values are treated as message contents. */
            if(!is_array($reply))
            {
                $messages[] = $reply;
                continue;
            }
            /* Flatten and extract array of message contents or responses. */
            if(is_array($reply) && array_keys($reply) === range(0, count($reply) - 1))
            {
                foreach($reply as $item)
                {
                    if(is_array($item) || (is_object($item) && isset($item->method) && isset($item->result)))
                    {
                        $responses[] = $item;
                    }
                    else
                    {
                        $messages[] = $item;
                    }
                }
                continue;
            }
            /* Otherwise it's a response. */
            $responses[] = $reply;
        }

        $sender = new stdclass();
        $sender->id            = 0;
        $sender->displayName   = isset($executedBot->code) && $executedBot->code != 'default' ? $this->lang->im->bot->commonName . '@' . $executedBot->name :  $this->lang->im->bot->commonName;
        $sender->avatar        = commonModel::getSysURL() . $this->config->webRoot . 'data/image/xuanbot.png';

        $messageData         = new stdclass();
        $messageData->sender = $sender;

        $messages = array_map(function($reply) use ($message, $messageData)
        {
            $botUserID = str_replace("$message->user&", '', $message->cgid);
            $replyMessage = new stdclass();
            $replyMessage->gid         = imModel::createGID();
            $replyMessage->cgid        = $message->cgid;
            $replyMessage->user        = $botUserID;
            $replyMessage->content     = is_object($reply) ? json_encode($reply) : $reply;
            $replyMessage->type        = 'normal';
            $replyMessage->contentType = is_object($reply) ? 'object' : 'text';
            $replyMessage->data        = json_encode($messageData);
            return $replyMessage;
        }, $messages);

        return (object)array('messages' => $messages, 'responses' => $responses);
    }

    /**
     * Execute command with bot.
     *
     * @param  string $command
     * @param  array  $args
     * @param  object $message
     * @param  int    $userID
     * @access public
     * @return object
     */
    public function executeCommand($command, $args, $message, $userID)
    {
        $user = $this->im->userGetByID($userID);
        $currentBot = $this->getUserCurrentBot($userID);

        /* Handle built-in command. */
        if(empty($args))
        {
            foreach($this->lang->im->bot->help->commands as $helpCommand)
            {
                if($helpCommand == mb_strtolower($command) || mb_strpos(mb_strtolower($command), "{$helpCommand}@") !== false)
                {
                    if($currentBot != 'default' && !strpos($command, '@')) $command .= '@' . $currentBot;
                    if($currentBot == 'default') return $this->genHelpContentReply();
                    return $this->helpCommand($command, $user);
                }
            }

            if(in_array(mb_strtolower($command), $this->lang->im->bot->show->commands)) return $this->showCommand($command, $user);

            $isSwitchBot = false;
            $botCode     = 'default';
            if(in_array(mb_strtolower($command), $this->lang->im->bot->exit->commands))
            {
                $isSwitchBot = true;
            }
            else
            {
                foreach($this->bots as $bot)
                {
                    $botClass = $this->botInstances->$bot;
                    if($botClass->code == $command || $botClass->name == $command || (isset($botClass->alias) && in_array($command, $botClass->alias)))
                    {
                        $isSwitchBot = true;
                        $botCode     = $botClass->code;
                        break;
                    }
                }
            }

            if($isSwitchBot)
            {
                $reply = new stdClass();
                $reply->messages  = array();
                $reply->responses = array();

                $this->setUserCurrentBot($botCode, $userID);
                if($botCode == 'default' && $currentBot != 'default')
                {
                    $reply->messages[] = sprintf($this->lang->im->bot->exit->tips, $this->botInstances->$currentBot->name);
                    return $reply;
                }

                $reply->messages[] = sprintf($this->lang->im->bot->switch->tips, $this->botInstances->$botCode->name);

                if($botCode == 'default' && $currentBot == 'default') return $reply;

                $help = $this->helpCommand($botCode, $user);
                if(isset($help->result)) $reply->messages[] = $help->result;

                return $reply;
            }
        }

        $botsToCall = $this->getListByCommand($command, $currentBot != 'default' ? $currentBot : '');
        if(empty($botsToCall)) return $this->lang->im->bot->error->noSuchCommand;

        $bot = current($botsToCall);

        $this->initBot($bot->code);

        /* Check permission. */
        $commandToCheck = array();
        foreach($this->botInstances->{$bot->bot}->commands as $cmd)
        {
            if(is_array($cmd) && $bot->command === $cmd['command']) $commandToCheck = $cmd;
            if(is_string($cmd) && $bot->command === $cmd) $commandToCheck = $cmd;
        }

        if(empty($commandToCheck)) return $this->lang->im->bot->error->noSuchCommand;

        $executedBot = (object)array('name' => $bot->name, 'code' => $bot->code);

        if(isset($commandToCheck['adminOnly']) && $commandToCheck['adminOnly'] && $user->admin == 'no') return $this->lang->im->bot->error->unauthorized;
        if(isset($commandToCheck['validator']))
        {
            $validationResult = $this->botInstances->{$bot->bot}->{$commandToCheck['validator']}($args);
            if(!$validationResult) return (object)array('executedBot' => $executedBot, 'result' => $this->lang->im->bot->error->badArguments);
        }

        if(!method_exists($this->botInstances->{$bot->bot}, $bot->command)) return $this->lang->im->bot->error->noSuchCommand;

        return (object)array('executedBot' => $executedBot, 'result' => $this->botInstances->{$bot->bot}->{$bot->command}($args, $userID, $user));
    }

    /**
     * Get the configured bot for the user.
     *
     * @param  int    $userID
     * @access public
     * @return string
     */
    public function getUserCurrentBot($userID)
    {
        $user = $this->im->userGetByID($userID);
        $botCode = $this->dao->select('value')->from(TABLE_CONFIG)
            ->where('owner')->eq($user->account)
            ->andWhere('module')->eq('xuanxuan')
            ->andWhere('section')->eq('bot')
            ->andWhere('`key`')->eq('context')
            ->fetch('value');

        if(empty($botCode))
        {
            $data = new stdclass();
            $data->owner   = $user->account;
            $data->module  = 'xuanxuan';
            $data->section = 'bot';
            $data->key     = 'context';
            $data->value   = 'default';
            $this->dao->insert(TABLE_CONFIG)->data($data)->exec();
            return 'default';
        }
        return $botCode;
    }

    /**
     * Set the configured bot for the user.
     * @param $botCode
     * @param $userID
     * @return void
     */
    public function setUserCurrentBot($botCode, $userID)
    {
        $user = $this->im->userGetByID($userID);
        $this->dao->update(TABLE_CONFIG)->set('value')->eq($botCode)->where('owner')->eq($user->account)->andWhere('module')->eq('xuanxuan')->andWhere('section')->eq('bot')->andWhere('`key`')->eq('context')->exec();
    }

    /**
     * Handle show command.
     *
     * @param  string $command
     * @param  object $user
     * @return object
     */
    public function showCommand($command, $user)
    {
        $bots   = array_values($this->bots);
        $result = array();

        $reply = new stdClass();
        $reply->messages  = array();
        $reply->responses = array();

        foreach($bots as $bot) $result[] = array($this->botInstances->$bot->name, sprintf($this->lang->im->bot->help->sendCommandLink, $bot, $bot));

        $reply->messages[] = xuanBot::printMarkdownTable(array($this->lang->im->bot->name, $this->lang->im->bot->code), $result);

        return $reply;
    }

    /**
     * Get all command with bot.
     *
     * @param  string $command
     * @param  object $user
     * @access public
     * @return string|object
     */
    public function helpCommand($command, $user)
    {
        $executedBot   = new stdClass();
        $helpContent   = array();
        $botCodeOffset = -1;

        if(isset($this->botInstances->{$command}))
        {
            $bot = $command;
            $this->initBot($bot);
            $executedBot = $this->getExecutedBot($bot);

            if(!empty($this->botInstances->$bot->help)) return (object)array('executedBot' => $executedBot, 'result' => $this->botInstances->$bot->getHelp());
        }

        if(!isset($this->botInstances->{$command})) $botCodeOffset = mb_strpos($command, "@");

        if($botCodeOffset === false) $bots = $this->bots;
        if($botCodeOffset !== false)
        {
            $bot = mb_substr($command, $botCodeOffset + 1);
            if(!isset($this->botInstances->$bot)) return $this->lang->im->bot->error->noSuchBot;

            $bots        = array($bot);
            $executedBot = $this->getExecutedBot($bot);
        }

        foreach($bots as $bot)
        {
            if(!isset($this->botInstances->$bot) || !isset($this->botInstances->$bot->commands)) continue;
            $this->initBot($bot);

            if(!empty($this->botInstances->$bot->help) && count($bots) == 1)
            {
                return (object)array('executedBot' => $executedBot, 'result' => $this->botInstances->$bot->getHelp());
            }

            foreach($this->botInstances->$bot->commands as $botCommand)
            {
                if(is_array($botCommand) && !empty($botCommand['adminOnly']) && $user->admin == 'no') continue;

                $botCode       = $this->botInstances->$bot->code;
                $command       = is_array($botCommand) ? $botCommand['command'] : $botCommand;
                $description   = is_array($botCommand) && isset($botCommand['description']) ? $botCommand['description'] : '';
                $isClickToSend = is_array($botCommand) && (!isset($botCommand['argsRequired']) || !$botCommand['argsRequired']);
                $commandLink   = sprintf($this->lang->im->bot->help->{$isClickToSend ? 'sendBotCommandLink' : 'botCommandLink'}, $command, $command, $botCode);

                $helpContent[] = array($commandLink, $description, $botCode);
            }
        }

        $result = xuanBot::printMarkdownTable($this->lang->im->bot->help->header, $helpContent);

        return (object)array('executedBot' => $executedBot, 'result' => $result);
    }

    /**
     * Get Executed Bot.
     *
     * @param string $bot
     * @access public
     * @return object
     */
    public function getExecutedBot($bot)
    {
        if(!isset($this->botInstances->$bot)) return new stdClass();
        return (object)array('name' => $this->botInstances->$bot->name, 'code' => $this->botInstances->$bot->code, 'avatar' => $this->botInstances->$bot->avatar);
    }

    /**
     * Get bot list by command, returns list of bot with such command.
     *
     * @param  string $command
     * @access public
     * @return array
     */
    public function getListByCommand($command, $bot = '')
    {
        /* Get command from specified bot. */
        if(strpos($command, '@') !== false)
        {
            list($command, $bot) = explode('@', $command);
            if(!isset($this->botInstances->$bot) || !isset($this->botInstances->$bot->commands)) return array();
            return $this->getListByCommand($command, $bot);
        }

        $currentBot = $bot;
        $command    = mb_strtolower($command); // Ignore case of command.

        /* Get command bot pairs. */
        $bots = array();
        $botsToCheck = empty($bot) ? $this->bots : array($bot);
        foreach($botsToCheck as $bot)
        {
            if(!isset($this->botInstances->$bot) || !isset($this->botInstances->$bot->commands)) continue;

            $this->initBot($bot); // init bot on demand.

            /* Check command array of bot. */
            foreach($this->botInstances->$bot->commands as $botCommand)
            {
                $isThisCommand = false;

                /* Command is array, check for properties. */
                if(is_array($botCommand))
                {
                    /* Check if command equal (ignore case). */
                    if(!empty($botCommand['command']) && mb_strtolower($botCommand['command']) == $command)
                    {
                        $isThisCommand = true;
                    }
                    elseif(!empty($botCommand['alias'])) // Check for alias (ignore case).
                    {
                        foreach($botCommand['alias'] as $alias)
                        {
                            if(mb_strtolower($alias) == $command)
                            {
                                $isThisCommand = true;
                                break;
                            }
                        }
                    }
                    /* Check if command is internal (can only be called within the bot app). */
                    if($isThisCommand && (!isset($botCommand['internal']) || !$botCommand['internal'] || $bot == $currentBot))
                    {
                        $bots[] = (object)array('bot' => $bot, 'command' => $botCommand['command'], 'name' => $this->botInstances->$bot->name, 'code' => $this->botInstances->$bot->code, 'avatar' => $this->botInstances->$bot->avatar);
                        break;
                    }
                }
                elseif(mb_strtolower($botCommand) == $command) // Command is string, check if equal (ignore case).
                {
                    $bots[] = (object)array('bot' => $bot, 'command' => $command, 'name' => $this->botInstances->$bot->name, 'code' => $this->botInstances->$bot->code, 'avatar' => $this->botInstances->$bot->avatar);
                    break;
                }
            }
        }
        return $bots;
    }

    /**
     * Gen help content reply.
     *
     * @return object
     */
    public function genHelpContentReply()
    {
        $reply = new stdClass();
        $reply->messages  = array();
        $reply->responses = array();

        $reply->messages[] = $this->lang->im->bot->help->welcome;
        $reply->messages[] = $this->lang->im->bot->help->global . $this->lang->im->bot->help->system;

        return $reply;
    }

    /**
     * Create default bot sender.
     *
     * @param  string $extraName
     * @access public
     * @return object
     */
    public function createDefaultBotSender($extraName = '')
    {
        $sender = new stdclass();
        $sender->id = 0;
        $sender->displayName = $this->lang->im->bot->commonName;
        $sender->avatar = commonModel::getSysURL() . $this->config->webRoot . 'data/image/xuanbot.png';

        if(!empty($extraName)) $sender->displayName .= '@' . $extraName;

        return $sender;
    }
}
