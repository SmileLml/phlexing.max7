<?php
/**
 * The control file of market module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tang Hucheng <tanghucheng@easycorp.ltd>
 * @package     market
 * @link        https://www.zentao.net
 */
class marketModel extends Model
{
    /**
     * Get market info by id.
     *
     * @param  int $id
     * @access public
     * @return object
     */
    public function getByID($id)
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getMarket();

        return $this->dao->select('*')->from(TABLE_MARKET)->where('id')->eq($id)->fetch();
    }

    public function getReportGroupByID($marketID = 0, $group = 'researchID')
    {
        return $this->dao->select('t1.*,t2.name as researchName,t2.id as researchID')->from(TABLE_MARKETREPORT)->alias('t1')
            ->leftJoin(TABLE_MARKETRESEARCH)->alias('t2')->on('t1.research = t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.market')->eq($marketID)
            ->orderBy('t1.openedDate ASC')
            ->fetchGroup('researchName', 'id');
    }

    /**
     * Get market list.
     * @param  string  $browseType
     * @param  int     $queryID
     * @param  string  $orderBy
     * @param  object  $pager
     * @access public
     * @return array
     */
    public function getList($browseType = 'all', $queryID = 0, $orderBy = 'id_desc', $pager = null)
    {
        $account = $this->app->user->account;

        $marketQuery = '';
        if($browseType == 'bysearch')
        {
            $query = $queryID ? $this->loadModel('search')->getQuery($queryID) : '';
            if($query)
            {
                $this->session->set('marketQuery', $query->sql);
                $this->session->set('marketForm', $query->form);
            }

            if($this->session->marketQuery == false) $this->session->set('marketQuery', ' 1 = 1');
            $marketQuery = $this->session->marketQuery;
        }

        $markets = $this->dao->select('*')->from(TABLE_MARKET)
            ->where('deleted')->eq('0')
            ->beginIF($browseType == 'bysearch')->andWhere($marketQuery)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'market', $browseType != 'bysearch');
        return $markets;
    }

    /**
     * Add a market.
     *
     * @access public
     * @return false|int
     */
    public function create()
    {
        $market = fixer::input('post')
            ->add('openedBy', $this->app->user->account)
            ->add('openedDate', helper::now())
            ->remove('uid')
            ->stripTags($this->config->market->editor->create['id'], $this->config->allowedTags)
            ->get();

        $market->scale = (float) $market->scale;
        if($market->scale and !$this->checkScale($market->scale)) return false;

        $this->dao->insert(TABLE_MARKET)->data($market)
            ->autoCheck()
            ->batchCheck($this->config->market->create->requiredFields, 'notempty')
            ->exec();

        if(!dao::isError()) return $this->dao->lastInsertID();
        return false;
    }

    /**
     * Update a market.
     *
     * @param  int $marketID
     * @access public
     * @return false|object
     */
    public function update($marketID)
    {
        $oldMarket = $this->getByID($marketID);
        $market    = fixer::input('post')
            ->remove('uid')
            ->stripTags($this->config->market->editor->edit['id'], $this->config->allowedTags)
            ->get();
        $market->scale = (float) $market->scale;

        if($market->scale and !$this->checkScale($market->scale)) return false;

        $this->dao->update(TABLE_MARKET)->data($market)->autoCheck()
            ->batchCheck($this->config->market->edit->requiredFields, 'notempty')
            ->where('id')->eq($marketID)
            ->exec();

        if(!dao::isError()) return common::createChanges($oldMarket, $market);

        return false;
    }

    /**
     * Check scale.
     *
     * @param  string $scale
     * @access public
     * @return bool
     */
    public function checkScale($scale)
    {
        if(!is_numeric($scale))
        {
            dao::$errors['scale'] = sprintf($this->lang->market->scaleNumber);
            return false;
        }
        else if($scale < 0)
        {
            dao::$errors['scale'] = sprintf($this->lang->market->scaleGe0);
            return false;
        }

        return true;
    }

    /**
     * Set menu of market module.
     *
     * @param  int    $marketID
     * @access public
     * @return void
     */
    public function setMenu($marketID = 0)
    {
        if(!$marketID) $this->lang->market->menu = $this->lang->market->homeMenu;

        $this->loadModel('common');
        $moduleName = $this->app->rawModule;
        $methodName = $this->app->rawMethod;
        if(!$this->common->isOpenMethod($moduleName, $methodName) and !commonModel::hasPriv($moduleName, $methodName)) $this->common->deny($moduleName, $methodName, false);

        if($marketID)
        {
            $this->session->set('marketID', $marketID);
            $this->lang->switcherMenu = $this->getSwitcher($marketID, $moduleName, $methodName);
            commonModel::setMenuVars('market', $marketID);
        }
    }

    /**
     * Get market swapper.
     *
     * @param  int     $marketID
     * @param  string  $currentModule
     * @param  string  $currentMethod
     * @access public
     * @return string
     */
    public function getSwitcher($marketID, $currentModule, $currentMethod)
    {
        $currentMarketName = $this->lang->market->common;
        if($marketID)
        {
            $currentMarket     = $this->getByID($marketID);
            $currentMarketName = $currentMarket->name;
        }

        if($this->app->viewType == 'mhtml' and $marketID)
        {
            $output  = $this->lang->market->common . $this->lang->hyphen;
            $output .= "<a id='currentItem' href=\"javascript:showSearchMenu('market', '$marketID', '$currentModule', '$currentMethod', '')\">{$currentMarketName} <span class='icon-caret-down'></span></a><div id='currentItemDropMenu' class='hidden affix enter-from-bottom layer'></div>";
            return $output;
        }

        $dropMenuLink = helper::createLink('market', 'ajaxGetDropMenu', "marketID=$marketID&module=$currentModule&method=$currentMethod");
        $output  = "<div class='btn-group header-btn' id='swapper'><button data-toggle='dropdown' type='button' class='btn' id='currentItem' title='{$currentMarketName}'><span class='text'>{$currentMarketName}</span> <span class='caret' style='margin-bottom: -1px'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList' data-url='$dropMenuLink'>";
        $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>';
        $output .= "</div></div>";

        return $output;
    }

    /**
     * Create the link from module,method.
     *
     * @param  string $module
     * @param  string $method
     * @access public
     * @return string
     */
    public function getMarketLink($module, $method)
    {
        $link = helper::createLink($module, $method, "marketID=%s");

        if(strpos(',market,marketreport,marketresearch,researchtask,', ',' . $module . ',') !== false)
        {
            if(($module == 'marketreport' && ($method == 'edit' || $method == 'view')) || ($module == 'marketresearch' and $method == 'reports'))
            {
                $link = helper::createLink('marketreport', 'browse', "marketID=%s");
            }

            if(($module == 'marketresearch' && strpos(',createstage,task,view,edit,team,', ",$method,") !== false) || $module == 'researchtask')
            {
                $link = helper::createLink('marketresearch', 'browse', "marketID=%s");
            }
        }

        return $link;
    }

    /**
     * Get market pairs.
     *
     * @param  string $params nodeleted|all
     * @access public
     * @return void
     */
    public function getPairs($params = 'nodeleted')
    {
        return $this->dao->select('id,name')->from(TABLE_MARKET)
            ->beginIF(strpos($params, 'nodeleted') !== false)->where('deleted')->eq('0')->fi()
            ->orderBy('id_desc')
            ->fetchPairs();
    }

    /**
     * Get market id by research id.
     *
     * @param  int    $researchID
     * @access public
     * @return int
     */
    public function getIdByResearch($researchID)
    {
        return $this->dao->select('market')->from(TABLE_MARKETRESEARCH)->where('id')->eq($researchID)->fetch('market');
    }

    /**
     * Build market operate menu.
     *
     * @param  object $demand
     * @param  string $type
     * @access public
     * @return void
     */
    public function buildOperateBrowseMenu($market)
    {
        $menu   = '';
        $params = "id={$market->id}";

        $menu .= $this->buildMenu('marketreport', 'browse', $params, $market, 'browse', 'list-alt', '', '', false, '', $this->lang->market->report);
        $menu .= $this->buildMenu('market',       'edit',   $params, $market, 'browse');
        $menu .= $this->buildMenu('market',       'delete', $params, $market, 'browse', 'trash', 'hiddenwin');
        return $menu;
    }

    /**
     * Build market view action menu.
     *
     * @param  object $project
     * @access public
     * @return string
     */
    public function buildOperateViewMenu($market)
    {
        if($market->deleted) return '';

        $menu   = '';
        $params = "market=$market->id";

        $menu .= "<div class='divider'></div>";
        $menu .= $this->buildMenu('marketreport', 'browse', $params, $market, 'view', 'list-alt',  '', '', false, '', $this->lang->market->report);
        $menu .= "<div class='divider'></div>";
        $menu .= $this->buildFlowMenu('market', $market, 'view', 'direct');
        $menu .= "<div class='divider'></div>";

        $menu .= $this->buildMenu('market', 'edit',   $params, $market, 'button', 'edit', '',           '', '', '', $this->lang->edit);
        $menu .= $this->buildMenu('market', 'delete', $params, $market, 'button', 'trash', 'hiddenwin', '', '', '', $this->lang->delete);

        return $menu;
    }

    /**
     * Create market by name.
     *
     * @param  string $name
     * @access public
     * @return int|false
     */
    public function createMarketByName($name)
    {
        $market = new stdclass();
        $market->name       = $name;
        $market->openedBy   = $this->app->user->account;
        $market->openedDate = helper::now();
        $this->dao->insert(TABLE_MARKET)->data($market)->exec();

        if(!dao::isError()) return $this->dao->lastInsertID();
        return false;
    }
}
