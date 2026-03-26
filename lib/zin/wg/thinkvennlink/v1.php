<?php
namespace zin;

class thinkVennLink extends wg
{
    /**
     * @var mixed[]
     */
    protected static $defineProps = array(
        'wizard: object', // 模型数据
    );

    public static function getPageCSS()
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    /**
     * @return \zin\node|mixed[]
     */
    protected function build()
    {
        $wizard = $this->prop('wizard');
        $model  = $wizard->model;
        $wgMap  = array('3c' => 'think3c', 'ansoff' => 'thinkAnsoff');
        if(!isset($wgMap[$model])) return array();

        return div(setClass('think-venn-link'), createWg($wgMap[$model], array(set::key('link'), set::mode('preview'), set::blocks($wizard->blocks), set::disabled(true))));
    }
}
