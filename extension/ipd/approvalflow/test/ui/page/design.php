<?php
class designPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'nodeType'       => '//*[@id="editor"]/div[3]/div[1]/div[2]/div/div',
            'nodeName'       => '//*[@id="editor"]/div[3]/div[1]/div[1]/div',
            'addBtn'         => '//*[@id="editor"]/div[3]/div[2]/div[2]',
            'addReviewerBtn' => '//*[@id="editor"]/div[3]/div[2]/div[2]/div[2]/div[1]',
            'reviewerBody1'  => '//*[@id="editor"]/div[3]/div[1]/div[2]',
            'reviewerBody2'  => '//*[@id="editor"]/div[4]/div[1]/div[2]',
            'typePicker'     => '//*[@id="type_0"]',
            'saveReviewer'   => '//*[@id="zin_approvalflow_design_form_1"]/div[4]/button',
            'addCcBtn'       => '//*[@id="editor"]/div[3]/div[2]/div[2]/div[2]/div[2]',
            'submitBtn'      => '//*[@id="mainContent"]/div[1]/button'
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
