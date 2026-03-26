<?php
/**
 * The vm entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class vmEntry extends baseEntry
{

    public function post()
    {
        $fields  = 'name,macAddress,status';
        $this->batchSetPost($fields);

        $vm     = $this->loadModel('vm');
        $result = $vm->updateVMByCallback();
        if($result === true)
        {
            $this->send(200, array('code' => 200));
        }
        else
        {
            $this->send(200, array('code' => 400, 'message' => $result));
        }
    }

}
