<?php
/**
 * The control file of feedback module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@chandao.com>
 * @package     feedback
 * @link        https://www.zentao.net
 */
class feedbackZen extends control
{
    /**
     * 根据上传图片，批量创建反馈时，获取初始化反馈数据。
     * Get data from upload images.
     *
     * @access public
     * @return array
     */
    public function getDataFromUploadImages($productID)
    {
        /* Clear title when switching products and set the session for the current product. */
        if($this->cookie->preProductID && $productID != $this->cookie->preProductID) unset($_SESSION['feedbackImagesFile']);
        helper::setcookie('preProductID', (string)$productID);

        if(empty($_SESSION['feedbackImagesFile'])) return array();

        $files     = $this->session->feedbackImagesFile;
        $feedbacks = array();
        foreach($files as $fileName => $file)
        {
            $defaultFeedback['title']       = $file['title'];
            $defaultFeedback['uploadImage'] = $fileName;

            $feedbacks[] = $defaultFeedback;
        }
        return $feedbacks;
    }

    /**
     * 回复反馈不存在提示消息。
     * Response feedback not found message.
     *
     * @access public
     * @return array
     */
    public function responseNotFound($browseURL)
    {
        if(defined('RUN_MODE') && RUN_MODE == 'api')
        {
            return $this->send(array('status' => 'fail', 'code' => 404, 'message' => '404 Not found'));
        }
        return $this->send(array('result' => 'success', 'load' => array('alert' => $this->lang->notFound, 'locate' => $browseURL)));
    }
}
