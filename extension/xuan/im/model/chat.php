<?php
class imChat extends model
{
    /**
     * Get a chat by gid.
     *
     * @param  string $gid
     * @param  bool   $getMembers
     * @param  bool   $format          format the chat or return its data as is.
     * @param  bool   $getLastMessage
     * @access public
     * @return object
     */
    public function getByGid($gid = '', $getMembers = false, $format = true, $getLastMessage = false)
    {
        $chat = $this->dao->select('*')->from(TABLE_IM_CHAT)->where('gid')->eq($gid)->fetch();

        if($chat && $format)
        {
            $chat = $this->format($chat, $getLastMessage);
            if($getMembers) $chat->members = $this->getMembers($gid);
        }

        return $chat;
    }

    /**
     * Get public chat list that user not join.
     *
     * @param  int   $userID
     * @access public
     * @return array
     */
    public function getPublicList($userID)
    {
        $joinedChats = $this->dao->select('cgid')->from(TABLE_IM_CHATUSER)
            ->where('user')->eq($userID)
            ->andWhere('quit is null')
            ->fetchAll();

        $joinedChats = array_map(function($chat) { return $chat->cgid; }, $joinedChats);

        $chats = $this->dao->select('*')->from(TABLE_IM_CHAT)
            ->where('public')->eq(true)
            ->andWhere('dismissDate is null')
            ->andWhere('mergedDate is null')
            ->andWhere('archiveDate is null')
            ->andWhere('gid')->notin($joinedChats)
            ->fetchAll();

        return $this->format($chats);
    }

    /**
     * Get chat gid list by userID.
     *
     * @param  int    $userID
     * @param  bool   $includeMerged
     * @access public
     * @return array
     */
    public function getGidListByUserID($userID = 0, $includeMerged = false)
    {
        $systemChatGidList = $this->dao->select('gid')->from(TABLE_IM_CHAT)
            ->where('type')->eq('system')
            ->fetchPairs('gid');
        $gidList = $this->dao->select('t1.gid')->from(TABLE_IM_CHAT)->alias('t1')
            ->leftJoin(TABLE_IM_CHATUSER)->alias('t2')->on('t2.cgid=t1.gid')
            ->where('t2.user')->eq($userID)
            ->andWhere('t2.quit is null', $includeMerged)
            ->beginIF($includeMerged)->orWhere('t1.mergedDate')->ne(null)->markRight()->fi()
            ->fetchPairs('gid');
        return array_merge($systemChatGidList, $gidList);
    }

    /**
     * Get chat list by userID.
     *
     * @param  int    $userID
     * @access public
     * @return array
     */
    public function getListByUserID($userID = 0)
    {
        $limit = isset($this->config->dismissedGroupLife) ? $this->config->dismissedGroupLife : 90;
        $chats = $this->dao->select('chat.*, cu.star, cu.hide, cu.mute, cu.freeze, cu.category, cu.lastReadMessage, cu.lastReadMessageIndex')
            ->from(TABLE_IM_CHAT)->alias('chat')
            ->leftJoin(TABLE_IM_CHATUSER)->alias('cu')->on('chat.gid=cu.cgid')
            ->where('cu.user')->eq($userID)
            ->andWhere('cu.quit is null')
            ->andWhere('chat.dismissDate is null', true)
            ->orWhere('chat.dismissDate')->gt(date(DT_DATETIME1, strtotime("-{$limit} day")))
            ->markRight(1)
            ->fetchAll();

        if(!isset($this->config->xuanxuan->disableSystemGroupChat) || $this->config->xuanxuan->disableSystemGroupChat == 'off')
        {
            $this->loadModel('setting');
            $account = $this->dao->select('account')->from(TABLE_USER)->where('id')->eq($userID)->fetch('account');
            $lastReadMessage = $this->setting->getItem("owner=$account&module=chat&section=system&key=lastreadid");
            if(empty($lastReadMessage)) $lastReadMessage = 0;
            $lastReadMessageIndex = $this->setting->getItem("owner=$account&module=chat&section=system&key=lastreadindex");
            if(empty($lastReadMessageIndex)) $lastReadMessageIndex = 0;
            $systemChat = $this->dao->select("*, 1 as star, 0 as hide, 0 as mute, 0 as freeze, 0 as category, $lastReadMessage as lastReadMessage, $lastReadMessageIndex as lastReadMessageIndex")
                ->from(TABLE_IM_CHAT)
                ->where('type')->eq('system')
                ->fetch();
            if($systemChat->lastReadMessage == 0) $systemChat->lastReadMessage = $systemChat->lastMessage;
            if($systemChat->lastReadMessageIndex == 0) $systemChat->lastReadMessageIndex = $systemChat->lastMessageIndex;
            $chats[] = $systemChat;
        }
        return $this->format($chats, true);
    }

    /**
     * Get chat by gid and verify if user is in the chat.
     *
     * @param  string $gid
     * @param  int    $userID
     * @access public
     * @return object|bool
     */
    public function getByGidForUser($gid, $userID)
    {
        $chat = $this->getByGid($gid, true);
        if(empty($chat)) return false;
        if(!in_array($userID, $chat->members)) return false;

        return $chat;
    }

    /**
     * Get chats owned by given user.
     *
     * @param  int    $userID
     * @param  bool   $format
     * @access public
     * @return array
     */
    public function getOwnedListForUser($userID, $format = true)
    {
        $account = $this->dao->select('account')->from(TABLE_USER)->where('id')->eq($userID)->fetch('account');
        $chats = $this->dao->select('*')->from(TABLE_IM_CHAT)
            ->where('type')->eq('group')
            ->andWhere('dismissDate is null')
            ->andWhere('mergedDate is null')
            ->andWhere('ownedBy', true)->eq($account)
            ->orWhere('ownedBy')->eq('')
            ->andWhere('createdBy')->eq($account)
            ->markRight(1)
            ->fetchAll();
        return $format ? $this->format($chats) : $chats;
    }

	/**
	 * Check if user is committer of a chat.
	 *
	 * @param  object    $message
	 * @param  int       $userID
	 * @param  object    $chat
	 * @access public
	 * @return object|bool  $output | true
	 */
	public function isCommitter($message, $userID, $chat)
	{
        $members = explode('&', $message->cgid);

		$output = new stdclass();
		$output->result = 'fail';
		$output->users   = $userID;

        if(!$chat)
		{
			$output->data = new stdclass();
			$output->data->gid      = $message->cgid;
			$output->data->messages = $this->lang->im->notExist;
			return $output;
		}

        if(count($members) == 2 and !in_array($userID, $members))
		{
            $output->message = $this->lang->im->notInChat;
			return $output;
		}

		if(!empty($chat->dismissDate))
		{
			$output->data = new stdclass();
			$output->data->gid      = $message->cgid;
			$output->data->messages = $this->lang->im->chatHasDismissed;
			return $output;
		}

		/* Check if user is in the group. */
		if($chat->type == 'group' and $message->type == 'normal')
		{
			$members = $this->getMembers($chat->gid);
			if(!in_array($message->user, $members))
			{
				$output->data = new stdclass();
				$output->data->gid      = $message->cgid;
				$output->data->messages = $this->lang->im->notInGroup;
				return $output;
			}
        }

		/* Check if user is in committers. */
		if(!empty($chat->committers))
		{
            if($chat->committers == '$ADMINS')
            {
                if(!$this->isAdmin($chat, $userID))
                {
                    $output->data = new stdclass();
                    $output->data->gid      = $message->cgid;
                    $output->data->messages = $this->lang->im->cantChat;
                    return $output;
                }
            }
            else
            {
                $committers = explode(',', $chat->committers);
                if(!in_array($userID, $committers))
                {
                    $output->data = new stdclass();
                    $output->data->gid      = $message->cgid;
                    $output->data->messages = $this->lang->im->cantChat;
                    return $output;
                }
            }

		}

		return true;
    }

    /**
     * Check if user is admin in a chat.
     *
     * @param  string|object $chat
     * @param  int           $userID
     * @access public
     * @return boolean
     */
    public function isAdmin($chat, $userID)
    {
        if(strpos(is_string($chat) ? $chat : $chat->gid, '&') !== false) return true;

        if(is_string($chat)) $chat = $this->getByGid($chat, false, false);
        if(isset($chat->admins) && (is_array($chat->admins) ? in_array($userID, $chat->admins) : strpos($chat->admins, ",$userID,")) !== false) return true;

        if($chat->createdBy === 'system')
        {
            $account = $this->loadModel('user')->getById($userID);
$admins = $this->dao->select('admins')->from(TABLE_COMPANY)->where('id')->eq($this->app->company->id)->fetch('admins');
$adminArray = explode(',', $admins);
return in_array($account, $adminArray);

            return in_array($userID, $sysAdmins);
        }

        $creatorID = $this->dao->select('id')->from(TABLE_USER)
            ->where('account')->eq(empty($chat->ownedBy) ? $chat->createdBy : $chat->ownedBy)
            ->fetch('id');
        return $userID == $creatorID;
    }

    /**
     * Get group pairs of all chat.
     *
     * @access public
     * @return array
     */
    public function getGroupPairs()
    {
        return $this->dao->select('gid, name')->from(TABLE_IM_CHAT)
            ->where('type')->eq('group')
            ->andWhere('dismissDate is null')
            ->fetchPairs();
    }

    /**
     * Get user pairs of one chat group.
     *
     * @param  string $gid
     * @access public
     * @return array
     */
    public function getUserPairs($gid = '')
    {
        $userIdList = $this->dao->select('user')->from(TABLE_IM_CHATUSER)
            ->where('quit is null')
            ->beginIF($gid)->andWhere('cgid')->eq($gid)->fi()
            ->fetchPairs();

        return $this->dao->select('id, realname')->from(TABLE_USER)->where('id')->in($userIdList)->fetchPairs();
    }

    /**
     * Create a chat.
     *
     * @param  string $gid
     * @param  string $name
     * @param  string $type
     * @param  array  $members
     * @param  int    $subjectID
     * @param  bool   $public
     * @param  int    $userID
     * @access public
     * @return object
     */
    public function create($gid = '', $name = '', $type = '', $members = array(), $subjectID = 0, $public = false, $userID = 0)
    {
        $user = $this->loadModel('im')->user->getByID($userID);

        $chat = new stdclass();
        $chat->gid         = $gid;
        $chat->name        = $name;
        if($type == 'bot' || ($type == 'one2one' && in_array('xuanbot', $members)))
        {
            $type = 'bot';

            $avatar = new stdclass();
            $avatar->type = 'image';
            $avatar->data = new stdclass();
            $avatar->data->imgUrl = $this->config->webRoot . 'data/image/xuanbot.png';

            $chat->avatar  = json_encode($avatar);
            $chat->name    = $this->lang->im->bot->commonName;
        }
        $chat->type        = $type;
        $chat->subject     = $subjectID;
        $chat->createdBy   = !empty($user->account) ? $user->account : '';
        $chat->ownedBy     = $chat->createdBy;
        $chat->createdDate = helper::now();

        if($public) $chat->public = 1;

        $this->dao->insert(TABLE_IM_CHAT)->data($chat)->exec();

        /* Add members to chat. */
        foreach($members as $member) $this->join($gid, $member);

        return $this->getByGid($gid, true);
    }

    /**
     * Update a chat.
     *
     * @param  object $chat
     * @param  int    $userID
     * @access public
     * @return object
     */
    public function update($chat = null, $userID = 0)
    {
        if(isset($chat))
        {
            $user = $this->loadModel('im')->user->getByID($userID);
            $chat->editedBy   = !empty($user->account) ? $user->account : '';
            $chat->editedDate = helper::now();
            if(is_array($chat->admins))         $chat->admins = implode(',', $chat->admins);
            if(is_array($chat->pinnedMessages)) $chat->pinnedMessages = implode(',', $chat->pinnedMessages);
            if(is_array($chat->mergedChats))    $chat->mergedChats = implode(',', $chat->mergedChats);

            if(is_bool($chat->public))
            {
                $chat->public = $chat->public ? '1' : '0';
            }
            if(is_bool($chat->adminInvite))
            {
                $chat->adminInvite = $chat->adminInvite ? '1' : '0';
            }

            foreach(array('createdDate', 'lastMessageInfo', 'avatar') as $dropProp) unset($chat->$dropProp);

foreach(array('dismissDate', 'mergedDate', 'archiveDate') as $nullable) if(isset($chat->$nullable) && empty($chat->$nullable)) $chat->$nullable = null;
            $this->dao->update(TABLE_IM_CHAT)->data($chat)->where('gid')->eq($chat->gid)->batchCheck($this->config->im->require->edit, 'notempty')->exec();
        }

        /* Return the changed chat. */
        return $this->getByGid($chat->gid, true);
    }

    /**
     * Touch a chat (as on Linux), changes its editedDate to now.
     *
     * This method is currently meant to track members' join/leave events, which will not get synced during login.
     *
     * @param  string  $gid
     * @return boolean
     */
    public function touch($gid)
    {
        $this->dao->update(TABLE_IM_CHAT)->set('editedDate')->eq(helper::now())->where('gid')->eq($gid)->exec();
        return !dao::isError();
    }

    /**
     * Init the system chat.
     *
     * @access public
     * @return bool
     */
    public function initSystemChat()
    {
        if(!isset($this->config->disableSystemGroupChat) || !$this->config->disableSystemGroupChat)
        {
            $chat = $this->dao->select('*')->from(TABLE_IM_CHAT)->where('type')->eq('system')->fetch();
            if(!$chat)
            {
                $chat = new stdclass();
                $chat->gid         = imModel::createGID();
                $chat->name        = $this->lang->im->systemGroup;
                $chat->type        = 'system';
                $chat->createdBy   = 'system';
                $chat->createdDate = helper::now();

                $this->dao->insert(TABLE_IM_CHAT)->data($chat)->exec();
            }
            return !dao::isError();
        }
        return true;
    }

    /**
     * Join a chat.
     *
     * @param  string   $gid
     * @param  int      $userID
     * @access public
     * @return bool|int return userID if already joined, else return result.
     */
    public function join($gid = '', $userID = 0)
    {
        $this->touch($gid);

        $lastMessageInfo = $this->dao->select('lastMessage, lastMessageIndex')->from(TABLE_IM_CHAT)->where('gid')->eq($gid)->fetch();
        $data = $this->dao->select('*')->from(TABLE_IM_CHATUSER)->where('cgid')->eq($gid)->andWhere('user')->eq($userID)->fetch();
        if($data)
        {
            /* If user hasn't quit the chat then return. */
            if($data->quit == null) return $userID;

            /* If user has quited the chat then update the record. */
            $data = new stdclass();
            $data->join = helper::now();
            $data->quit = null;
            $data->lastReadMessage      = $lastMessageInfo->lastMessage;
            $data->lastReadMessageIndex = $lastMessageInfo->lastMessageIndex;
            $this->dao->update(TABLE_IM_CHATUSER)->data($data)->where('cgid')->eq($gid)->andWhere('user')->eq($userID)->exec();

            return !dao::isError();
        }

        /* Create a new record of user's chat info. */
        $data = new stdclass();
        $data->cgid = $gid;
        $data->user = $userID;
        $data->join = helper::now();
        $data->lastReadMessage      = $lastMessageInfo->lastMessage;
        $data->lastReadMessageIndex = $lastMessageInfo->lastMessageIndex;
        $this->dao->insert(TABLE_IM_CHATUSER)->data($data)->exec();

        /* Update order field. */
        $id = $this->dao->lastInsertID();
        $this->dao->update(TABLE_IM_CHATUSER)->set('`order`')->eq($id)->where('id')->eq($id)->exec();

        return !dao::isError();
    }

    /**
     * leave a chat.
     *
     * @param  int    $gid
     * @param  int    $userID
     * @access public
     * @return bool
     */
    public function leave($gid, $userID)
    {
        $this->dao->update(TABLE_IM_CHATUSER)->set('quit')->eq(helper::now())->where('cgid')->eq($gid)->andWhere('user')->eq($userID)->exec();
        $this->removeAdmins($gid, array($userID));
        $this->loadModel('im')->conferenceRemoveUserFromChat($gid, $userID);
        $this->touch($gid);

        return !dao::isError();
    }

    /**
     * Format chats.
     *
     * @param  mixed  $chats  object | array
     * @access public
     * @return object | array
     */
    public function format($chats, $getLastMessage = false)
    {
        $isObject = false;
        if(is_object($chats))
        {
            $isObject = true;
            $chats    = array($chats);
        }

        $userID = $this->app->session->userID;

        foreach($chats as $chat)
        {
            if(!$chat) continue;
            $chat->id              = (int)$chat->id;
            $chat->subject         = (int)$chat->subject;
            $chat->createdDate     = $chat->createdDate == null ? 0 : strtotime($chat->createdDate);
            $chat->editedDate      = $chat->editedDate == null ? 0 : strtotime($chat->editedDate);
            $chat->lastActiveTime  = $chat->lastActiveTime == null ? 0 : strtotime($chat->lastActiveTime);
            $chat->dismissDate     = $chat->dismissDate == null ? 0 : strtotime($chat->dismissDate);
            $chat->mergedDate      = $chat->mergedDate == null ? 0 : strtotime($chat->mergedDate);
            $chat->archiveDate     = $chat->archiveDate == null ? 0 : strtotime($chat->archiveDate);
            $chat->lastMessage     = (int)$chat->lastMessage;
            $chat->admins          = array_values(array_map('intval', array_filter(explode(',', $chat->admins))));
            $chat->pinnedMessages  = array_values(array_map('intval', array_filter(explode(',', $chat->pinnedMessages))));
            $chat->mergedChats     = array_values(array_filter(explode(',', $chat->mergedChats)));
            $chat->avatar          = json_decode($chat->avatar);

            if(isset($chat->avatar) && $chat->avatar->type === 'image')
            {
                $chat->avatar->data->imgUrl = $this->loadModel('im')->getServer() . $chat->avatar->data->imgUrl;
            }

            if($getLastMessage && isset($chat->lastMessage)) $chat->lastMessageInfo = current($this->loadModel('im')->messageGetList($chat->gid, array($chat->lastMessage)));
            if(empty($chat->lastMessageInfo))  $chat->lastMessageInfo = null;
            if(!empty($chat->lastMessageInfo)) $chat->lastMessageInfo->senderId = intval($chat->lastMessageInfo->user);
            if(isset($chat->lastReadMessageIndex)) $chat->lastReadMessageIndex = (int)$chat->lastReadMessageIndex;

            if($chat->type == 'one2one' && $chat->gid != "$userID&$userID") $chat->name = '';

            if(isset($chat->star))            $chat->star   = (bool)$chat->star;
            if(isset($chat->hide))            $chat->hide   = (bool)$chat->hide;
            if(isset($chat->mute))            $chat->mute   = (bool)$chat->mute;
            if(isset($chat->public))          $chat->public = (bool)$chat->public;
            if(isset($chat->freeze))          $chat->freeze = (bool)$chat->freeze;
            if(isset($chat->lastReadMessage)) $chat->lastReadMessage = (int)$chat->lastReadMessage;
            if(isset($chat->adminInvite))     $chat->adminInvite = (bool)$chat->adminInvite;

            if($chat->archiveDate) {
                $chat->star = false;
                $chat->hide = false;
                $chat->mute = false;
                $chat->freeze = false;
            }
        }

        if($isObject) return reset($chats);

        return $chats;
    }

    /**
     * Add admins of a chat.
     *
     * @param  string $gid
     * @param  array  $admins
     * @param  int    $userID
     * @access public
     * @return object
     */
    public function addAdmins($gid = '', $admins = array())
    {
        $chat      = $this->getByGid($gid);
        $adminList = $chat->admins;
        $adminList = array_filter(array_unique(array_merge($adminList, $admins)));
        $adminList = implode(',', $adminList);
        $this->dao->update(TABLE_IM_CHAT)->set('admins')->eq(',' . $adminList . ',')->where('gid')->eq($gid)->exec();

        return $this->getByGid($gid, true);
    }

    /**
     * Remove admins of a chat.
     *
     * @param  string $gid
     * @param  array  $users
     * @access public
     * @return object
     */
    public function removeAdmins($gid = '', $users = array())
    {
        $chat      = $this->getByGid($gid);
        $adminList = $chat->admins;
        $adminList = array_filter(array_diff($adminList, $users));
        $adminList = implode(',', $adminList);
        $this->dao->update(TABLE_IM_CHAT)->set('admins')->eq(',' . $adminList . ',')->where('gid')->eq($gid)->exec();

        return $this->getByGid($gid, true);
    }

    /**
     * Pin messages of a chat.
     *
     * @param  string|object $chat
     * @param  array         $messageIds
     * @access public
     * @return object
     */
    public function pinMessages($chat, $messageIds)
    {
        if(is_string($chat)) $chat = $this->getByGid($chat);
        $pinnedMessages = $chat->pinnedMessages;

        if(!empty($pinnedMessages))
        {
            $pinnedMessagesData = $this->loadModel('im')->messageGetList('', $pinnedMessages);
            foreach($pinnedMessagesData as $msg) if($msg->deleted) $pinnedMessages = array_diff($pinnedMessages, array($msg->id));
        }

        $pinnedMessagesLimit = isset($this->config->pinnedMessagesLimit) ? $this->config->pinnedMessagesLimit : 10;
        if(count($pinnedMessages) >= $pinnedMessagesLimit) $pinnedMessages = array_slice($pinnedMessages, $pinnedMessagesLimit - count($pinnedMessages) + count($messageIds));

        $pinnedMessages = array_filter(array_unique(array_merge($pinnedMessages, $messageIds)));
        $pinnedMessages = implode(',', $pinnedMessages);
        $this->dao->update(TABLE_IM_CHAT)->set('pinnedMessages')->eq(',' . $pinnedMessages . ',')->where('gid')->eq($chat->gid)->exec();

        return $this->getByGid($chat->gid, true);
    }

     /**
     * Unpin messages of a chat.
     *
     * @param  string|object $chat
     * @param  array         $messageIDs
     * @access public
     * @return object
     */
    public function unpinMessages($chat, $messageIds)
    {
        if(is_string($chat)) $chat = $this->getByGid($chat);
        $pinnedMessages = $chat->pinnedMessages;
        $pinnedMessages = array_filter(array_diff($pinnedMessages, $messageIds));
        $pinnedMessages = implode(',', $pinnedMessages);
        $this->dao->update(TABLE_IM_CHAT)->set('pinnedMessages')->eq(',' . $pinnedMessages . ',')->where('gid')->eq($chat->gid)->exec();

        return $this->getByGid($chat->gid, true);
    }

    /**
     * Star or unstar a chat.
     *
     * @param  string $star
     * @param  string $gid
     * @param  int    $userID
     * @access public
     * @return object
     */
    public function star($star = '1', $gid = '', $userID = 0)
    {
        $this->dao->update(TABLE_IM_CHATUSER)
            ->set('star')->eq($star)
            ->where('cgid')->eq($gid)
            ->andWhere('user')->eq($userID)
            ->exec();

        return $this->getByGid($gid, true);
    }

    /**
     * Hide or display a chat.
     *
     * @param  string   $hide
     * @param  string   $gid
     * @param  int      $userID
     * @access public
     * @return bool
     */
    public function hide($hide = '1', $gid = '', $userID = 0)
    {
        $this->dao->update(TABLE_IM_CHATUSER)
            ->set('hide')->eq($hide)
            ->where('cgid')->eq($gid)
            ->andWhere('user')->eq($userID)
            ->exec();

        return !dao::isError();
    }

    /**
     * Mute or unmute a chat.
     *
     * @param  string   $mute
     * @param  string   $gid
     * @param  int      $userID
     * @access public
     * @return bool
     */
    public function mute($mute = '1', $gid = '', $userID = 0)
    {
        $this->dao->update(TABLE_IM_CHATUSER)
            ->set('mute')->eq($mute)
            ->where('cgid')->eq($gid)
            ->andWhere('user')->eq($userID)
            ->exec();

        return !dao::isError();
    }

    /**
     * Freeze or unfreeze a chat.
     *
     * @param  string   $freeze
     * @param  string   $gid
     * @param  int      $userID
     * @access public
     * @return bool
     */
    public function freeze($freeze = '1', $gid = '', $userID = 0)
    {
        $this->dao->update(TABLE_IM_CHATUSER)
            ->set('freeze')->eq($freeze)
            ->where('cgid')->eq($gid)
            ->andWhere('user')->eq($userID)
            ->exec();

        return !dao::isError();
    }

    /**
     * Set category for a chat
     *
     * @param  array  $gids
     * @param  string $category
     * @param  int    $userID
     * @access public
     * @return boolean
     */
    public function setCategory($gids = array(), $category = '', $userID = 0)
    {
        $this->dao->update(TABLE_IM_CHATUSER)
            ->set('category')->eq($category)
            ->where('cgid')->in($gids)
            ->andWhere('user')->eq($userID)
            ->exec();

        return !dao::isError();
    }

    /**
     * Get member list of one chat.
     *
     * @param  string|object $chat             chat gid or chat object.
     * @param  bool          $trueOnSystemChat return true if chat type is system.
     * @access public
     * @return array
     */
    public function getMembers($chat, $trueOnSystemChat = false)
    {
        if(is_string($chat)) $chat = $this->getByGid($chat);
        if(!$chat) return array();

        if($chat->type == 'system')
        {
            if($trueOnSystemChat) return true;
            $memberList = $this->dao->select('id')->from(TABLE_USER)->where('deleted')->eq('0')->fetchPairs();
        }
        else
        {
            $memberList = $this->dao->select('user')->from(TABLE_IM_CHATUSER)->alias('tcu')
                ->leftJoin(TABLE_USER)->alias('tu')->on('tcu.user = tu.id')
                ->where('tcu.quit is null')
                ->andWhere('tu.deleted')->eq('0')
                ->andWhere('cgid')->eq($chat->gid)
                ->fetchPairs();
        }

        $members = array();
        foreach($memberList as $member) $members[] = (int)$member;

        return $members;
    }

    /**
     * Get detailed member list of chat.
     *
     * @param  string $chat           chat gid
     * @param  object $pager
     * @param  string $orderBy        order by owner, admin, join date by default
     * @param  array  $memberIDs      only get details for members in memberIDs if provided
     * @access public
     * @return array
     */
    public function getMemberDetails($cgid, $pager = null, $orderBy = '', $memberIDs = array())
    {
        $chat = $this->getByGid($cgid);
        if(!$chat || $chat->type == 'system') return array();

        /* Get join and last seen date of members, with some values which will be filled in later. */
        $memberList = $this->dao->select('user as id, account, tcu.`join`, last as lastSeen, null as lastPost, 0 as isOwner, 0 as isAdmin')->from(TABLE_IM_CHATUSER)->alias('tcu')
            ->leftJoin(TABLE_USER)->alias('tu')->on('tcu.user = tu.id')
            ->where('tcu.quit is null')
            ->andWhere('tu.deleted')->eq('0')
            ->andWhere('cgid')->eq($cgid)
            ->fetchAll('id');
        if(empty($memberList)) return array();

        /* Filter members. */
        if(!empty($memberIDs))
        {
            $memberList = array_filter($memberList, function($member) use ($memberIDs)
            {
                return in_array($member->id, $memberIDs);
            });
        }

        /* Get date of members' last message in chat. */
        $members = array_keys($memberList);
        $lastMessageDates = $this->dao->select('user, MAX(date)')->from(TABLE_IM_MESSAGE)
            ->where('cgid')->eq($cgid)
            ->andWhere('user')->in($members)
            ->groupBy('user')
            ->fetchAll();
        foreach($lastMessageDates as $messageDate) $memberList[$messageDate->user]->lastPost = $messageDate->{'MAX(date)'};

        /* Set isAdmin for members. */
        foreach($chat->admins as $admin)
        {
            if(isset($memberList[$admin])) $memberList[$admin]->isAdmin = 1;
        }

        /* Set isOwner. */
        $ownerAccount = !empty($chat->ownedBy) ? $chat->ownedBy : $chat->createdBy;
        $ownerIndex = false; // For reorder later.
        foreach($memberList as $index => $member)
        {
            if($member->account == $ownerAccount)
            {
                $memberList[$index]->isOwner = 1;
                $ownerIndex = $index;
                break;
            }
        }

        /* Format data. */
        $memberList = array_map(function($member)
        {
            $member->id       = (int)$member->id;
            $member->isOwner  = (int)$member->isOwner;
            $member->isAdmin  = (int)$member->isAdmin;
            $member->join     = $member->join == null ? 0 : strtotime($member->join);
            $member->lastSeen = $member->lastSeen == null ? 0 : strtotime($member->lastSeen);
            $member->lastPost = $member->lastPost == null ? 0 : strtotime($member->lastPost);
            return $member;
        }, $memberList);

        /* Reorder data. */
        $orderedMemberList = array();
        if(empty($orderBy) || stripos($orderBy, 'member') === 0) // Default order: owner, admin, join date. Might reverse.
        {
            if($ownerIndex !== false) $orderedMemberList[] = $memberList[$ownerIndex];
            if(!empty($chat->admins))
            {
                $adminList = array_filter($memberList, function($member)
                {
                    return $member->isAdmin && !$member->isOwner;
                });
                usort($adminList, function($a, $b)
                {
                    return ($a->join < $b->join) ? -1 : 1;
                });
                $orderedMemberList = array_merge($orderedMemberList, $adminList);
            }
            $normalMemberList = array_filter($memberList, function($member)
            {
                return !$member->isOwner && !$member->isAdmin;
            });
            usort($normalMemberList, function($a, $b)
            {
                return ($a->join < $b->join) ? -1 : 1;
            });
            $orderedMemberList = array_merge($orderedMemberList, $normalMemberList);
            if(stripos($orderBy, 'desc') !== false) $orderedMemberList = array_reverse($orderedMemberList);
        }
        else
        {
            $orderByArgs = explode('_', $orderBy);
            $property  = $orderByArgs[0];
            $direction = $orderByArgs[1] == 'asc' ? 1 : -1;
            usort($memberList, function($a, $b) use ($property, $direction)
            {
                return ($a->$property < $b->$property) ? (-1 * $direction) : $direction;
            });
            $orderedMemberList = $memberList;
        }

        /* Slice data with pager. */
        $recTotal = count($memberList);
        if($pager->recPerPage * ($pager->pageID - 1) >= $recTotal)
        {
            $pager->pageID = ceil($recTotal / $pager->recPerPage);
        }
        $startIndex = $pager->recPerPage * ($pager->pageID - 1);
        if($startIndex >= $recTotal || $startIndex < 0) return array();

        $memberSlice = array_slice($orderedMemberList , $startIndex, $pager->recPerPage);

        /* Assemble data. */
        $details = new stdclass();
        $details->data  = $memberSlice;
        $details->pager = new stdclass();
        $details->pager->gid        = $cgid;
        $details->pager->recTotal   = $recTotal;
        $details->pager->recPerPage = $pager->recPerPage;
        $details->pager->pageID     = $pager->pageID;
        $details->pager->data       = array('orderBy' => $orderBy);

        return $details;
    }

    /**
     * Get count of messages for a chat.
     *
     * @param  string $gid
     * @access public
     * @return int
     */
    public function getMessageCount($gid)
    {
        $masterTableCount = $this->dao->select('count(*)')->from(TABLE_IM_MESSAGE)->where('cgid')->eq($gid)->fetch('count(*)');
        $partitionsMessageCount = $this->dao->select('sum(`count`)')->from(TABLE_IM_CHAT_MESSAGE_INDEX)->where('gid')->eq($gid)->fetch('sum(`count`)');

        return $masterTableCount + ($partitionsMessageCount ?: 0);
    }

    /**
     * Add chat action.
     *
     * @param  int      $chatId
     * @param  string   $action
     * @param  int      $actionId
     * @param  string   $result
     * @param  string   $comment
     */
    public function addAction($chatId, $action, $actorId, $result, $comment = '')
    {
        if(!$this->loadModel('action')->checkLogLevel('chat', $action)) return;

        $account = $this->dao->select('account')
			->from(TABLE_USER)
			->where('id')->eq($actorId)
			->fetch('account');
        $actor   = !empty($account) ? $account : '';
        $extra   = json_encode(array('actorId' => $actorId));
        $this->loadModel('action')->create('chat', $chatId, $action, $result, $comment, $extra, $actor);
    }

    /**
     * Set last read message for a chat.
     *
     * @param  string   $gid
     * @param  int      $lastReadMessageID
     * @param  int      $userID
     * @access public
     * @return bool
     */
    public function setLastReadMessage($gid, $lastReadMessageID, $userID)
    {
        $messageIndex = $this->dao->select('`index`')->from(TABLE_IM_MESSAGE)
            ->where('id')->eq($lastReadMessageID)
            ->andWhere('cgid')->eq($gid)
            ->fetch('index');
        $this->dao->update(TABLE_IM_CHATUSER)
            ->set('lastReadMessage')->eq($lastReadMessageID)
            ->set('lastReadMessageIndex')->eq($messageIndex)
            ->where('cgid')->eq($gid)
            ->andWhere('lastReadMessage')->lt($lastReadMessageID)
            ->andWhere('user')->eq($userID)
            ->exec();

        return !dao::isError();
    }

    /**
     * Set last read message for a chat.
     *
     * @param  string   $gid
     * @param  int      $lastReadMessageIndex
     * @param  int      $userID
     * @access public
     * @return bool
     */
    public function setLastReadMessageByIndex($gid, $lastReadMessageIndex, $userID)
    {
        $messageID = $this->dao->select('id')->from(TABLE_IM_MESSAGE)
            ->where('`index`')->eq($lastReadMessageIndex)
            ->andWhere('cgid')->eq($gid)
            ->fetch('id');
        $affected = $this->dao->update(TABLE_IM_CHATUSER)
            ->set('lastReadMessageIndex')->eq($lastReadMessageIndex)
            ->set('lastReadMessage')->eq($messageID)
            ->where('cgid')->eq($gid)
            ->andWhere('user')->eq($userID)
            ->exec();

        if($affected === 0)
        {
            $systemChatGidList = $this->dao->select('gid')->from(TABLE_IM_CHAT)
                ->where('type')->eq('system')
                ->fetchPairs('gid');
            if(in_array($gid, array_keys($systemChatGidList)))
            {
                $this->loadModel('setting');
                $account = $this->dao->select('account')->from(TABLE_USER)->where('id')->eq($userID)->fetch('account');
if(!isset($this->app->user)) $this->app->user = new stdclass();
$this->app->user->account = $account;
$this->setting->setItem("$account.chat.system.lastreadid", $messageID);
                $this->setting->setItem("$account.chat.system.lastreadindex", $lastReadMessageIndex);
            }
        }

        return !dao::isError();
    }

    /**
     * Change ownership of chat.
     *
     * @param  object       $chat
     * @param  int          $ownerUserID  new owner id
     * @param  int          $userID
     * @param  bool         $byAdmin      true if is request by an admin, will bypass owner checking.
     * @access public
     * @return bool|object  returns chat on success, returns false on fail
     */
    public function changeOwnership($chat, $ownerUserID, $userID, $byAdmin = false)
    {
        if(!$byAdmin)
        {
            if($ownerUserID == $userID) return false;

            $currentUserAccount = $this->dao->select('account')->from(TABLE_USER)->where('id')->eq($userID)->fetch('account');
            if(empty($currentUserAccount) || (!empty($chat->ownedBy) && $chat->ownedBy != $currentUserAccount) || (empty($chat->ownedBy) && $chat->createdBy != $currentUserAccount)) return false;
        }

        $ownerAccount = $this->dao->select('account')->from(TABLE_USER)->where('id')->eq($ownerUserID)->fetch('account');
        if(empty($ownerAccount)) return false;

        $this->dao->update(TABLE_IM_CHAT)
            ->set('ownedBy')->eq($ownerAccount)
            ->where('gid')->eq($chat->gid)
            ->exec();

        return dao::isError() ? false : $this->getByGid($chat->gid, true);
    }

    /**
     * Merge chat into targetChat.
     *
     * @param  object  $chat
     * @param  object  $targetChat
     * @param  int     $userID
     * @access public
     * @return bool
     */
    public function merge($chat, $targetChat, $userID)
    {
        /* Check if user is owner of both chats, and chat has not been merged. */
        $accountAdmin = $this->dao->select('account')->from(TABLE_USER)->where('id')->eq($userID)->fetch();
$sysAdmins = $this->dao->select('admins')->from(TABLE_COMPANY)->where('id')->eq($this->app->company->id)->fetch('admins');
$sysAdminArray = explode(',', $sysAdmins);
$accountAdmin->admin = in_array($accountAdmin->account, $sysAdminArray) ? 'super' : '';

        if((empty($accountAdmin) || (!empty($chat->ownedBy) && $chat->ownedBy != $accountAdmin->account) || (!empty($targetChat->ownedBy) && $targetChat->ownedBy != $accountAdmin->account) || (empty($chat->ownedBy) && $chat->createdBy != $accountAdmin->account) || (empty($targetChat->ownedBy) && $targetChat->createdBy != $accountAdmin->account)) && $accountAdmin->admin != 'super') return false;
        if($chat->mergedDate != null) return false;

        /* Mark chat as merged. */
        $this->dao->update(TABLE_IM_CHAT)
            ->set('mergedDate')->eq(helper::now())
            ->where('gid')->eq($chat->gid)
            ->exec();

        /* Merge previously merged chats. */
        $prevMergedChats = $chat->mergedChats;
        $currMergedChats = $targetChat->mergedChats;
        $mergedChats = array_merge(array($chat->gid), $prevMergedChats, $currMergedChats);
        $mergedChats = join(',', array_filter($mergedChats));
        $this->dao->update(TABLE_IM_CHAT)
            ->set('mergedChats')->eq($mergedChats)
            ->where('gid')->eq($targetChat->gid)
            ->exec();

        /* Merge members. */
        foreach($chat->members as $memberID)
        {
            $this->leave($chat->gid, $memberID);
            $this->join($targetChat->gid, $memberID);
        }

        return dao::isError() ? false : $this->getByGid($targetChat->gid, true);
    }

    /**
     * Get next owner candidate for chat. (Seniormost user other than current owner)
     *
     * @param  object     $chat
     * @param  int        $userID     current owner user id
     * @param  bool       $asAccount  will return id if set to false
     * @access public
     * @return int|string
     */
    public function getNextOwnerCandidate($chat, $userID, $asAccount = true)
    {
        if(!empty($chat->admins))
        {
            $seniormostAdmin = $this->dao->select($asAccount ? 'account' : 'user')->from(TABLE_IM_CHATUSER)->alias('tcu')
                ->leftJoin(TABLE_USER)->alias('tu')->on('tcu.user=tu.id')
                ->where('tcu.cgid')->eq($chat->gid)
                ->andWhere('tcu.quit is null')
                ->andWhere('tcu.user')->in($chat->admins)
                ->andWhere('tcu.user')->ne($userID)
                ->andWhere('tu.deleted')->eq('0')
                ->orderBy('tcu.join_asc')
                ->limit(1)
                ->fetch($asAccount ? 'account' : 'user');
            if(!empty($seniormostAdmin)) return $seniormostAdmin;
        }
        return $this->dao->select($asAccount ? 'account' : 'user')->from(TABLE_IM_CHATUSER)->alias('tcu')
            ->leftJoin(TABLE_USER)->alias('tu')->on('tcu.user=tu.id')
            ->where('tcu.cgid')->eq($chat->gid)
            ->andWhere('tcu.quit is null')
            ->andWhere('tcu.user')->ne($userID)
            ->andWhere('tu.deleted')->eq('0')
            ->orderBy('tcu.join_asc')
            ->limit(1)
            ->fetch($asAccount ? 'account' : 'user');
    }

    /**
     * Transfer all chats from user to next candidate if possible.
     *
     * @param  int    $userID
     * @access public
     * @return bool
     */
    public function transferAllFromUser($userID)
    {
        $userChats = $this->getOwnedListForUser($userID);

        $chatOwnerPairs = array();
        foreach($userChats as $chat) $chatOwnerPairs[$chat->gid] = $this->getNextOwnerCandidate($chat, $userID);
        $chatOwnerPairs = array_filter($chatOwnerPairs);
        if(empty($chatOwnerPairs)) return true;

        $queryData = array();
        foreach($chatOwnerPairs as $cgid => $owner) $queryData[] = "WHEN '$cgid' THEN '$owner'";
        $cgids = array_keys($chatOwnerPairs);

        $query = "UPDATE " . TABLE_IM_CHAT . " SET `ownedBy` = (CASE `gid` " . join(' ', $queryData) . " END) WHERE `gid` IN('" . join('\',\'', $cgids) . "');";
        $this->dao->query($query); $this->dao->setCache(trim(TABLE_IM_CHAT, "`"));

        return !!dao::isError();
    }

    /**
     * Prune group data of the expired, including group information, group messages, group files, etc.
     *
     * @access public
     * @return bool
     */
    public function pruneExpired()
    {
        $groupLife = isset($this->config->dismissedGroupLife) ? $this->config->dismissedGroupLife : 90;
        $expiredGroups = $this->dao->select('gid')
            ->from(TABLE_IM_CHAT)
            ->where('type')->eq('group')
            ->andWhere('dismissDate')->ne(null)
            ->andWhere('dismissDate')->lt(date(DT_DATETIME1, strtotime("-{$groupLife} day")))
            ->fetchPairs();
        $expiredGroups = array_keys($expiredGroups);
        if(empty($expiredGroups)) return true;

        $messageTables = $this->loadModel('im')->message->getAllTables();
        foreach($messageTables as $table) $this->dao->delete()->from($table->tableName)->where('cgid')->in($expiredGroups)->exec();

        $this->dao->delete()->from(TABLE_IM_CHAT_MESSAGE_INDEX)->where('gid')->in($expiredGroups)->exec();
        $this->dao->delete()->from(TABLE_IM_CHAT)->where('gid')->in($expiredGroups)->exec();
        $this->dao->delete()->from(TABLE_IM_CHATUSER)->where('cgid')->in($expiredGroups)->exec();
        $this->dao->delete()->from(TABLE_IM_CONFERENCE)->where('cgid')->in($expiredGroups)->exec();
        $this->dao->delete()->from(TABLE_IM_CONFERENCEACTION)->where('rid')->in($expiredGroups)->exec();

        return !dao::isError();
    }

    /**
     * Search chats with chat name or groupOwner's realname/account/pinyin.
     *
     * @param string    $searchField
     * @param object    $pager
     * @param string    $orderBy
     * @access public
     * @return array
     */
    public function search($searchField, $pager, $orderBy)
    {
        $accounts = array();
        if(isset($searchField) && !empty($searchField))
        {
            $accounts = $this->dao->select('account')->from(TABLE_USER)
                ->beginIF($searchField)
                ->where('account')->like("%$searchField%")
                ->andWhere('deleted')->eq(0)
                ->orWhere('pinyin')->like("%$searchField%")
                ->orWhere('realname')->like("%$searchField%")
                ->fi()
                ->fetchPairs('account');
            $accounts = array_keys($accounts);
        }

        $chatMemberCounts = $this->dao->select('tc.gid, COUNT(*) AS memberCount')
            ->from(TABLE_IM_CHATUSER)->alias('tcu')
            ->leftJoin(TABLE_IM_CHAT)->alias('tc')
            ->on('tcu.cgid=tc.gid')
            ->where('tc.type')->eq('group')
            ->andWhere('tc.dismissDate is null')
            ->andWhere('tc.mergedDate is null')
            ->beginIF($searchField)->andWhere('tc.name', true)->like("%$searchField%")
            ->orWhere('tc.ownedBy')->in($accounts)->markRight(1)
            ->fi()
            ->groupBy('tcu.cgid')
            ->fetchPairs();
        $pagerGids = array();
        if($orderBy == 'userCount_asc' || $orderBy == 'userCount_desc')
        {
            if($orderBy == 'userCount_asc')
            {
                asort($chatMemberCounts);
            }
            else
            {
                arsort($chatMemberCounts);
            }
            $pagerGids = array_slice(array_keys($chatMemberCounts), ($pager->pageID-1)*$pager->recPerPage, $pager->recPerPage);
        }

        $chats = $this->dao->select('tc.gid, tc.id, tc.name, tc.public, tu.id as groupOwner, tc.createdDate, tc.lastActiveTime, tc.archiveDate')
            ->from(TABLE_IM_CHAT)->alias('tc')
            ->leftJoin(TABLE_USER)->alias('tu')
            ->on('tc.ownedBy=tu.account')
            ->where('tc.type')->eq('group')
            ->andWhere('dismissDate is null')
            ->andWhere('mergedDate is null')
            ->beginIF($searchField)->andWhere('tc.name', true)->like("%$searchField%")
            ->orWhere('tc.ownedBy')->in($accounts)->markRight(1)
            ->fi()
            ->beginIF(!empty($pagerGids))->andWhere('tc.gid')->in($pagerGids)
            ->fi()
            ->beginIF(empty($pagerGids))
            ->orderBy($orderBy)
            ->fi()
            ->beginIF($pager)->page($pager, 'tc.gid')->fi()
            ->fetchAll();
        foreach($chats as $chat) $chat->userCount = isset($chatMemberCounts[$chat->gid]) ? $chatMemberCounts[$chat->gid] : 0;

        $sortedChats = array();
        if($orderBy == 'userCount_asc' || $orderBy == 'userCount_desc')
        {
            foreach($pagerGids as $key => $gid)
            {
                foreach($chats as $key => $chat)
                {
                    if($chat->gid == $gid)
                    {
                        $sortedChats[] = $chat;
                        break;
                    }
                }
            }
        }
        $chats = $sortedChats ? $sortedChats : $chats;

        /* Format data. */
        $chats = array_map(function($chat)
        {
            $chat->id             = (int)$chat->id;
            $chat->groupOwner     = (int)$chat->groupOwner;
            $chat->userCount      = (int)$chat->userCount;
            $chat->createdDate    = $chat->createdDate    == null ? 0 : strtotime($chat->createdDate);
            $chat->lastActiveTime = $chat->lastActiveTime == null ? 0 : strtotime($chat->lastActiveTime);
            $chat->public         = empty($chat->public) ? 0 : 1;
            $chat->archiveDate    = $chat->archiveDate    == null ? 0 : strtotime($chat->archiveDate);
            return $chat;
        }, $chats);

        return dao::isError() ? array() : $chats;
    }

    /**
     * Update chat avatar.
     *
     * @param string    $gid
     * @param object    $avatarData
     * @access public
     * @return object
     */
    public function updateAvatar($gid, $avatarData)
    {
        $this->dao->update(TABLE_IM_CHAT)->set('avatar')->eq($avatarData)->where('gid')->eq($gid)->exec();
        return $this->getByGid($gid, true);
    }

    /**
     * Super admin get all chatGroups.
     *
     * @access public
     * @return array
     */
    public function adminGetChatGroups()
    {
        $chats = $this->dao->select('*')->from(TABLE_IM_CHAT)
                      ->where('type')->eq('group')
                      ->andWhere('mergedDate is null')
                      ->andWhere('dismissDate is null')
                      ->fetchAll();
        return dao::isError() ? array() : $chats;
    }

    /**
     * Filter out users not in chat.
     *
     * @param  string $chatID
     * @param  array  $userIDs
     * @access public
     * @return array
     */
    public function filterUsers($chatID, $userIDs)
    {
        $chat = $this->getByGid($chatID);
        if($chat->type == 'group')
        {
            $members = $this->getMembers($chatID);
            return array_intersect($members, $userIDs);
        }
        elseif($chat->type == 'one2one')
        {
            $members = explode('&', $chatID);
            return array_intersect($members, $userIDs);
        }
        return $userIDs;
    }
}
