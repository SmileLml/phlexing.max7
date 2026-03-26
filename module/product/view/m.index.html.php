<?php
/**
 * The index mobile view file of product module of ZentTaoPMS.
 *
 * @copyright   Copyright 2009-2016 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     product 
 * @version     $Id$
 * @link        http://www.zentao.net
 */

include "../../common/view/m.header.html.php";
?>
<?php echo $this->fetch('block', 'dashboard', 'module=product');?>
<?php include "../../common/view/m.footer.html.php"; ?>
