<?php
/**
 * The control file of pivot module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     pivot
 * @version     $Id: model.php 5086 2013-07-10 02:25:22Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
class pivot extends control
{
    /**
     * Ajax get select control.
     *
     * @access public
     * @return string
     */
    public function ajaxGetOptions($type, $object = '', $field = '')
    {
        $options = $this->pivot->getSysOptions($type, $object, $field);

        $pickerOptions = array();
        foreach($options as $value => $text)
        {
            $pickerOptions[] = array('text' => $text, 'value' => $value);
        }

        return print(json_encode($pickerOptions));
    }
}
