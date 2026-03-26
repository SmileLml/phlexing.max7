<?php
/**
 * The model watermark of watermark module of XXB.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd., www.zentao.net)
 * @license     ZOSL (https://zpl.pub/page/zoslv1.html)
 */
?>
<?php
class watermarkModel extends model
{
    /**
     * Set watermark config.
     */
    public function setConfig()
    {
        $data = fixer::input('post')
            ->setIF(!isset($_POST['enabled']), 'enabled', 0)
            ->setIF(!isset($_POST['content']) || empty($_POST['content']), 'content', '$displayName $account $date')
            ->get();
        $this->loadModel('setting')->setItems('system.watermark.client', $data);
    }

    /**
     * Get watermark config.
     *
     * @access public
     * @return object
     */
    public function getConfig()
    {
        $items = $this->loadModel('setting')->getItems("owner=system&module=watermark&section=client&key=enabled,content");
        $config = new stdClass();
        foreach($items as $item)
        {
            $config->{$item->key} = $item->value;
        }

        return $config;
    }
}
