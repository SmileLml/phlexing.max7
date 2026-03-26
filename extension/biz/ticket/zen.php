<?php
/**
 * The zen file of ticket module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu
 * @package     ticket
 * @link        https://www.zentao.net
 */
class ticketZen extends control
{
    /**
     * 根据上传图片，批量创建工单时，获取初始化工单数据。
     * Get data from upload images.
     *
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function getDataFromUploadImages($productID)
    {
        /* Clear title when switching products and set the session for the current product. */
        if($this->cookie->preProductID && $productID != $this->cookie->preProductID) unset($_SESSION['ticketImagesFile']);
        helper::setcookie('preProductID', (string)$productID);

        if(empty($_SESSION['ticketImagesFile'])) return array();

        $files     = $this->session->ticketImagesFile;
        $tickets = array();
        foreach($files as $fileName => $file)
        {
            $defaultTicket['title']       = $file['title'];
            $defaultTicket['uploadImage'] = $fileName;

            $tickets[] = $defaultTicket;
        }
        return $tickets;
    }

    /**
     * 构造批量关闭工单数据。
     * Build batch close data.
     *
     * @param  array       $ticketData
     * @param  array       $oldTickets
     * @access public
     * @return false|array
     */
    public function buildBatchCloseData($ticketData = array(), $oldTickets = array())
    {
        $now = helper::now();
        foreach($ticketData as $ticketID => $ticket)
        {
            $oldTicket = zget($oldTickets, $ticketID);
            if($oldTicket->status == 'done') $ticket->closedReason = 'commented';

            if(empty($ticket->closedReason)) dao::$errors["closedReason[{$ticketID}]"] = sprintf($this->lang->error->notempty, $this->lang->ticket->closedReason);
            if($oldTicket->status != 'done' && $ticket->closedReason == 'commented' && empty($ticket->resolution)) dao::$errors["resolution[{$ticketID}]"] = sprintf($this->lang->error->notempty, $this->lang->ticket->resolution);

            if(dao::isError()) continue;

            if($oldTicket->status != 'done' && $ticket->closedReason == 'commented')
            {
                $ticket->resolvedBy   = $this->app->user->account;
                $ticket->resolvedDate = $now;
            }

            $ticket->status       = 'closed';
            $ticket->closedBy     = $this->app->user->account;
            $ticket->closedDate   = $now;
            $ticket->repeatTicket = $ticket->closedReason == 'repeat' ? $ticket->repeatTicket : $oldTicket->repeatTicket;
        }
        return dao::isError() ? false : $ticketData;
    }
}
