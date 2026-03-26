<?php
/**
 * The control file of ops of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jiangxiu Peng <pengjiangxiu@cnezsoft.com>
 * @package     ops
 * @version     $Id$
 * @link        http://www.zentao.net
 */

 helper::importControl('ops');
class myops extends ops
{
    /**
        * 管理主机CPU品牌信息。
        * Manger cpuBrand options of host. 
        * 
        * @param string $currentLang
        * @access public
        * @return void
        */
    public function stage($currentLang = '')
    {
        $this->setting('deploy', 'stage', 'stage', $currentLang);
    }
}
