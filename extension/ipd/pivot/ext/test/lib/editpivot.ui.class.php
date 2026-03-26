<?php
include dirname(__FILE__, 7) . '/test/lib/ui.php';
class editPivotTester extends tester
{
    /**
     * 输入编辑表单字段内容。
     * Input fields.
     *
     * @param  array  $pivot
     * @access public
     */
    public function inputFields($pivot)
    {
        $form = $this->loadPage();
        $form->wait(1);
        $form->dom->group->multipicker($pivot['group']);
        if($this->config->uitest->langClient != 'zh-cn')
        {
            $form->dom->enPivotName->setValue($pivot['name']);
        }
        else
        {
            $form->dom->cnPivotName->setValue($pivot['name']);
        }
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);
    }

    /**
     * 编辑透视表。
     * Edit pivot table.
     *
     * @param  array  $pivot
     * @access public
     * @return object
     */
    public function edit($pivot)
    {
        $form = $this->initForm('pivot', 'browse', '', 'appIframe-bi');
        $form->dom->edit->click();
        $form->wait(1);
        $this->inputFields($pivot);
        $form->wait(1);

        $form = $this->initForm('pivot', 'browse', '', 'appIframe-bi');
        $form->wait(1);
        $name = $form->dom->firstName->getText();
        if($name == $pivot['name']) return $this->success('编辑透视表成功');
        return $this->failed('编辑透视表失败');
    }
}
