<?php
/**
 * The zen file of deliverable module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@chandao.com>
 * @package     deliverable
 * @link        https://www.zentao.net
 */
class deliverableZen extends deliverable
{
    /**
     * 构造编辑的交付物数据。
     * Build the deliverable data to edit.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function buildDeliverableForEdit($id)
    {
        $deliverable = form::data($this->config->deliverable->form->edit, $id)
            ->add('lastEditedBy', $this->app->user->account)
            ->add('lastEditedDate', helper::today())
            ->get();

        $oldFiles = explode(',', $this->deliverable->fetchById($id)->files);
        if(!empty($_POST['deleteFiles']))
        {
            foreach($oldFiles as $i => $fileID)
            {
                if(isset($_POST['deleteFiles'][$fileID])) unset($oldFiles[$i]);
            }

        }

        return $deliverable;
    }
}
