<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class designTester extends tester
{
    public function getNodeType($expect)
    {
        $form = $this->initForm('approvalflow', 'design', array('id' => 1), 'appIframe-admin', 'max');

        $nodeType = $form->dom->nodeType->getText();
        return $nodeType == $expect ? $this->success('评审节点类型正确') : $this->fail('评审节点类型错误');
    }

    public function designFlow()
    {
        $page = $this->loadPage('approvalflow', 'design', 'max');

        $page->dom->reviewerBody1->click();
        $page->dom->title->setValue('审批节点1');
        $page->dom->saveReviewer->click();

        $page->wait(1);

        $page->dom->addBtn->click();
        $page->dom->addReviewerBtn->click();

        $page->wait(1);

        $page->dom->reviewerBody2->click();

        $page->wait(1);

        $page->dom->title->setValue('审批节点2');
        $page->dom->saveReviewer->click();

        $page->wait(1);

        $page->dom->addBtn->click();
        $page->dom->addReviewerBtn->click();

        $page->wait(1);

        $page->dom->reviewerBody2->click();

        $page->wait(1);

        $page->dom->title->setValue('审批节点3');
        $page->dom->saveReviewer->click();

        $page->wait(1);

        $page->dom->addBtn->click();
        $page->dom->addCcBtn->click();

        $page->wait(1);

        $page->dom->submitBtn->click();

        $page->wait(1);

        if($this->response('method') != 'browse') return $this->failed('设计审批流失败');

        return $this->success('设计审批流成功');
    }

    public function getNodeName($expect)
    {
        $form = $this->initForm('approvalflow', 'design', array('id' => 1), 'appIframe-admin', 'max');

        $nodeName = $form->dom->nodeName->getText();
        return $nodeName == $expect ? $this->success('评审节点名称正确') : $this->fail('评审节点名称错误');
    }
}
