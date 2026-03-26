<?php
/**
 * The common modal footer view file of XXB.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd., www.zentao.net)
 * @license     ZOSL (https://zpl.pub/page/zoslv1.html)
 * @author      Xiying Guan <guanxiying@xirangit.com>
 * @package     XXB
 * @version     $Id$
 * @link        https://xuanim.com
 */
?>
<?php if(helper::isAjaxRequest()):?>
    </div>
  </div>
</div>
<?php if(isset($pageJS)) js::execute($pageJS);?>
<?php else:?>
<?php include  $this->app->getModuleRoot() . 'common/view/footer.lite.html.php';?>
<?php endif;?>
