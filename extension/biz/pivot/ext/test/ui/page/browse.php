<?php
class browsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            /* 创建、编辑弹窗元素 */
            'group'       => "//*[@name='group[]']",
            'cnPivotName' => "//*[@name='name[zh-cn]']",
            'enPivotName' => "//*[@name='name[en]']",
            'firstName'   => "//*[@id='table-pivot-browse']/div[2]/div/div/div[2]/div/a",
            'modalTitle'  => "//div[@class='whitespace-nowrap']",
            /* 设计列表元素 */
            'edit' => "//*[@id='table-pivot-browse']/div[2]/div[3]/div/div[1]/div/nav/a[1]",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
