<?php
class demandZen extends demand
{
    /**
     * 构建创建需求池需求页面数据。
     * Build form fields for create demand.
     *
     * @param  int    $poolID
     * @access public
     * @return void
     */
    public function buildCreateForm($poolID)
    {
        $this->view->parents     = $this->demand->getParentDemandPairs($poolID);
        $this->view->reviewers   = $this->loadModel('demandpool')->getReviewers($poolID, $this->app->user->account);
        $this->view->assignTo    = $this->demandpool->getAssignedTo($poolID);
        $this->view->title       = $this->lang->demand->create;
        $this->view->users       = $this->loadModel('user')->getPairs('noclosed');
        $this->view->pool        = $this->loadModel('demandpool')->getByID($poolID);
        $this->view->needReview  = ($this->config->demand->needReview == 0 or !$this->demand->checkForceReview()) ? "checked='checked'" : "";
        $this->view->demandpools = $this->demandpool->getPairs('noclosed');
        $this->view->products    = $this->loadModel('product')->getProductByPool($poolID);
        $this->view->poolID      = $poolID;
    }

    /**
     * 解析extras，如果demand来源于某个对象 (demand, feedback) ，使用对象的一些属性对demand赋值。
     * Extract extras, if the demand comes from an object (demand, feedback), use the properties of the object to assign the demand.
     *
     * @param  int         $demandID
     * @param  array       $output
     * @access protected
     * @return object|bool
     */
    public function extractObjectFromExtras($demandID, $output)
    {
        $demand     = new stdclass();
        $copyDemand = $this->demand->getByID($demandID);
        $fromType   = isset($output['fromType']) ? $output['fromType'] : '';
        $fromID     = isset($output['fromID']) ? $output['fromID'] : '';

        /* 如果是复制需求，则初始化需求数据。*/
        /* If copying a demand, initialize the demand data.*/
        if($demandID)
        {
            $demand = $copyDemand;
            unset($copyDemand->files);
            unset($copyDemand->reviewer);
            unset($copyDemand->mailto);
            unset($copyDemand->feedbackedBy);
            unset($copyDemand->email);
        }

        /* 如果是反馈转需求池需求，则根据反馈初始化需求池需求数据。*/
        /* If the feedback is converted to demand pool demand, initialize the demand pool demand data according to the feedback.*/
        if($fromType == 'feedback')
        {
            $feedback = $this->loadModel('feedback')->getById($fromID);
            $demand->product    = $feedback->product;
            $demand->title      = $feedback->title;
            $demand->pri        = $feedback->pri;
            $demand->feedbackBy = $feedback->feedbackBy;
            $demand->mail       = $feedback->notifyEmail;
            $demand->spec       = $feedback->desc;
            $demand->keywords   = $feedback->keywords;

            $this->feedback->setMenu($feedback->product, 'demand');
        }

        return $demand;
    }

    /**
     * 处理创建需求池需求请求数据。
     * Processing request data for creating demand.
     *
     * @param  object       $demand
     * @access public
     * @return object|false
     */
    public function prepareCreateExtras($demand)
    {
        if($demand->assignedTo) $demand->assignedDate = helper::now();

        if(!$this->post->needNotReview && empty($demand->reviewer))
        {
            dao::$errors['reviewer'] = sprintf($this->lang->error->notempty, $this->lang->demand->reviewer);
            return false;
        }

        return $this->loadModel('file')->processImgURL($demand, $this->config->demand->editor->create['id'], $this->post->uid);
    }

    /**
     * 创建需求池需求后的返回结果。
     * respond after create.
     *
     * @param  int      $poolID
     * @param  int      $demandID
     * @param  string   $fromType
     * @param  int      $fromID
     * @access public
     * @return bool|int
     */
    public function responseAfterCreate($poolID = 0, $demandID = 0, $fromType = '', $fromID = 0)
    {
        if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $demandID));
        if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success', 'data' => $demandID));

        if(!empty($fromType))
        {
            $this->loadModel('action')->create('demand', $demandID, 'From' . ucfirst($fromType), '', $fromID);
            $locate = true;
            if(!isInModal()) $locate = $this->createLink('feedback', 'adminView', "feedbackID=$fromID");
        }
        else
        {
            $this->loadModel('action')->create('demand', $demandID, 'created');
            $locate = inlink('browse', "poolID=$poolID&browseType=all");
        }

        $message = $this->executeHooks($demandID);
        return $this->send(array('result' => 'success', 'message' => $message ? $message : $this->lang->saveSuccess, 'load' => $locate, 'closeModal' => true));
    }

    /**
     * 构建需求池需求编辑数据。
     * Build demand for edit.
     *
     * @param  object $oldDemand
     * @access public
     * @return object
     */
    public function buildDemandForEdit($oldDemand)
    {
        return form::data($this->config->demand->form->edit, $oldDemand->id)
            ->add('id', $oldDemand->id)
            ->add('lastEditedBy', $this->app->user->account)
            ->add('lastEditedDate', helper::now())
            ->get();
    }

    /**
     * 处理需求池需求编辑后的响应。
     * Respond after updating demand.
     *
     * @param  object $oldDemand
     * @param  object $demand
     * @access public
     * @return bool|int
     */
    public function responseAfterEdit($oldDemand, $demand)
    {
        $oldProducts  = $oldDemand->product;
        $oldProducts  = explode(',', trim($oldProducts, ','));
        $diffProducts = array_diff($oldProducts, explode(',', $demand->product));

        if(!empty($diffProducts))
        {
            unset($_POST);
            $retractStories = $this->demand->getDemandStories($demand->id, $diffProducts);
            $this->app->loadConfig('requirement');
            foreach($retractStories as $story) $this->demand->retract($story);
        }

        $message = $this->executeHooks($demand->id);

        if(isInModal()) $this->send(array('result' => 'success', 'message' => $message ? $message : $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        return $this->send(array('result' => 'success', 'message' => $message ? $message : $this->lang->saveSuccess, 'load' => inlink('view', "demandID={$demand->id}")));
    }

    /**
     * 根据上传图片，批量创建需求时，获取初始化需求数据。
     * Get data from upload images.
     *
     * @access protected
     * @return array
     */
    protected function getDataFromUploadImages()
    {
        if(empty($_SESSION['demandImagesFile'])) return array();

        $files   = $this->session->demandImagesFile;
        $demands = array();
        foreach($files as $fileName => $file)
        {
            $defaultDemand = array();
            $defaultDemand['title']       = $file['title'];
            $defaultDemand['uploadImage'] = $fileName;

            $demands[] = $defaultDemand;
        }

        return $demands;
    }

    /**
     * 检查是否已经分发过该产品。
     * Check if the product has been distributed.
     *
     * @param  int     $demandID
     * @param  array   $products
     * @access public
     * @return string
     */
    public function checkRedistribution($demandID, $products)
    {
        $distributedProducts = $this->demand->getDistributedProducts($demandID);
        $distributedProducts = array_keys($distributedProducts);

        $redistributedProducts = array_intersect($products, $distributedProducts);
        if(empty($redistributedProducts)) return '';

        $productNames             = '';
        $redistributedProductList = $this->loadModel('product')->getByIdList($redistributedProducts);
        foreach($redistributedProductList as $product) $productNames .= "{$product->name},";

        return sprintf($this->lang->demand->distributedTips, trim($productNames, ','));
    }
}
