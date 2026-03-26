<?php
include dirname(__FILE__, 7) . '/test/lib/ui.php';
class createScreenTester extends tester
{
    /*
     * 创建大屏
     * Create screen
     *
     * @param object $screen
     * @return mixed
     */
    public function createScreen($screen)
    {
        $form = $this->initForm('screen', 'browse', array(), 'appIframe-bi');
        $form->dom->btn($this->lang->screen->create)->click();//点击创建大屏
        $form->wait(2);
        if(isset($screen->name)) $form->dom->name->setValue($screen->name);//填写大屏名称
        if(isset($screen->desc)) $form->dom->desc->setValue($screen->desc);//填写大屏描述
        $form->dom->btn($this->lang->screen->next)->click();//点击下一步
        $form->wait(2);
        if($this->response('method') != 'design')
        {
            if($this->checkFormTips('screen')) return $this->success('大屏必填提示信息正确');
            return $this->failed('大屏必填提示信息不正确');
        }
        return $this->success('创建大屏成功');
    }
}
