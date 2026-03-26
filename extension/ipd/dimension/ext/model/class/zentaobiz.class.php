<?php
/**
 * The model file of ddimension module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenxuan Song <1097180981@qq.com>
 * @package     dimension
 * @version     $Id: model.php 5086 2022-11-1 10:26:23Z $
 * @link        http://www.zentao.net
 */
class zentaobizDimension extends dimensionModel
{
    /**
     * Check whether a dimension have BI content.
     *
     * @param  int    $dimensionID
     * @access public
     * @return bool
     */
    public function checkBIContent($dimensionID)
    {
        $charts = $this->loadModel('chart')->getList($dimensionID);
        if(count($charts)) return true;

        $pivots = $this->loadModel('pivot')->getList($dimensionID);
        if(count($pivots)) return true;

        $screens = $this->loadModel('screen')->getList($dimensionID);
        return count($screens) > 0;
    }

    /**
     * Create a dimension.
     *
     * @access public
     * @return int
     */
    public function create()
    {
        $dimension = fixer::input('post')
            ->setDefault('createdBy', $this->app->user->account)
            ->setDefault('createdDate', helper::now())
            ->join('whitelist', ',')
            ->setIF($this->post->acl == 'open', 'whitelist', '')
            ->remove('contactList')
            ->get();

        $dimension->code = str_replace(' ', '', $dimension->code);

        if(!empty($dimension->code) && !validater::checkCode($dimension->code))
        {
            dao::$errors['code'] = sprintf($this->lang->dimension->errorCode, $this->lang->dimension->code);
            return false;
        }

        $this->dao->insert(TABLE_DIMENSION)->data($dimension)
            ->autoCheck()
            ->batchCheck($this->config->dimension->create->requiredFields, 'notempty')
            ->checkIF(!empty($dimension->name), 'name', 'unique')
            ->checkIF(!empty($dimension->code), 'code', 'unique')
            ->exec();

        if(dao::isError()) return false;

        $dimensionID = $this->dao->lastInsertID();

        $this->loadModel('upgrade')->addDefaultModules4BI('chart', $dimensionID);
        $this->loadModel('upgrade')->addDefaultModules4BI('pivot', $dimensionID);

        return $dimensionID;
    }

    /**
     * Update a dimension.
     *
     * @param  int    $dimensionID
     * @access public
     * @return bool|array
     */
    public function update($dimensionID)
    {
        $oldDimension = $this->getById($dimensionID);
        $dimension    = fixer::input('post')
            ->join('whitelist', ',')
            ->setIF($this->post->acl == 'open', 'whitelist', '')
            ->remove('contactList')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->get();
        $dimension->code = str_replace(' ', '', $dimension->code);

        if(!empty($dimension->code) && !validater::checkCode($dimension->code))
        {
            dao::$errors['code'] = sprintf($this->lang->dimension->errorCode, $this->lang->dimension->code);
            return false;
        }

        $this->dao->update(TABLE_DIMENSION)->data($dimension)
            ->autoCheck()
            ->batchcheck($this->config->dimension->edit->requiredFields, 'notempty')
            ->checkIF(!empty($dimension->name), 'name', 'unique', "id != {$dimensionID}")
            ->checkIF(!empty($dimension->code), 'code', 'unique', "id != {$dimensionID}")
            ->where('id')->eq($dimensionID)
            ->exec();

        if(dao::isError()) return false;

        return common::createChanges($oldDimension, $dimension);
    }

    /**
     * Set switcher menu and save last dimension.
     *
     * @param  int    $dimensionID
     * @param  string $type         screen | pivot | chart
     * @access public
     * @return void
     */
    public function setSwitcherMenu($dimensionID = 0, $type = '')
    {
        $dimensionID = $this->saveState($dimensionID);
        $this->loadModel('setting')->setItem($this->app->user->account . 'common.dimension.lastDimension', $dimensionID);

        $moduleName = $this->app->rawModule;
        $methodName = $this->app->rawMethod;
        $this->lang->switcherMenu = $this->getSwitcher($dimensionID, $moduleName, $methodName, $type);

        return $dimensionID;
    }

    /*
     * Get project swapper.
     *
     * @param  int    $dimensionID
     * @param  string $currentModule
     * @param  string $currentMethod
     * @param  string $type             screen | pivot | chart
     * @access public
     * @return string
     */
    public function getSwitcher($dimensionID, $currentModule, $currentMethod, $type)
    {
        $currentDimensionName = $this->lang->dimension->common;
        if($dimensionID)
        {
            $currentDimension     = $this->getByID($dimensionID);
            $currentDimensionName = $currentDimension->name;
        }

        if($this->app->viewType == 'mhtml' and $dimensionID)
        {
            $output  = $this->lang->dimension->common . $this->lang->hyphen;
            $output .= "<a id='currentItem' href=\"javascript:showSearchMenu('dimension', '$dimensionID', '$currentModule', '$currentMethod', '')\">{$currentDimensionName} <span class='icon-caret-down'></span></a><div id='currentItemDropMenu' class='hidden affix enter-from-bottom layer'></div>";
            return $output;
        }

        $dropMenuLink = helper::createLink('dimension', 'ajaxGetOldDropMenu', "currentModule=$currentModule&currentMethod=$currentMethod&dimensionID=$dimensionID&type=$type");
        $output  = "<div class='btn-group header-btn' id='swapper'><button data-toggle='dropdown' type='button' class='btn' id='currentItem' title='{$currentDimensionName}' style='padding-bottom:2px;'><span class='text'>{$currentDimensionName}</span> <span class='caret' style='margin-bottom: -1px'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList' data-url='$dropMenuLink'>";
        $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>';
        $output .= "</div></div>";

        return $output;
    }
}
