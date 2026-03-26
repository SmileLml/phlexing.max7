<?php
helper::importControl('convert');
class myConvert extends convert
{
    /**
     * Ajax获取禅道文档目录。
     * Ajax get zentao doc lib.
     *
     * @param  string $spaceType
     * @param  string $returnMode
     * @access public
     * @return void
     */
    public function ajaxGetZentaoDocLib($spaceType = 'mine', $returnMode = 'json')
    {
        if(!$spaceType) return $this->send(array('items' => array(), 'disabled' => false, 'defaultValue' => ''));

        $items = $this->convert->getZentaoDocLib($spaceType);
        $disabled = $spaceType == 'mine' || $spaceType == 'custom' ? true : false;
        $default  = $spaceType == 'mine' || $spaceType == 'custom' ? 'defaultSpace' : $items['defaultValue'];
        return $returnMode == 'json' ? $this->send(array('items' => $items['items'], 'disabled' => $disabled, 'defaultValue' => $default)) : $items['items'];
    }
}
