<?php
namespace zin;

requireWg('thinkModel');

class thinkSwot extends thinkModel
{
    public static function getPageCSS()
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    /**
     * @param mixed[] $steps
     */
    protected function buildQuestion($steps)
    {
        $questionList = array();
        foreach($steps as &$step) $questionList[] = div(setClass('w-64 bg-canvas p-2 shadow', "card-{$step->options->questionType}"), $this->buildQuestionItem($step));
        return $questionList;
    }

    /**
     * @return \zin\node|mixed[]
     * @param int $order
     * @param object $block
     */
    protected function buildItem($order, $block)
    {
        global $app, $lang;
        $app->loadLang('thinkwizard');

        $mode         = $this->prop('mode');
        $defaultTitle = $mode == 'preview' ? $lang->thinkwizard->unAssociated : '';
        $blockTitle   = $block->text ?: $defaultTitle;
        $blockStyle   = $mode == 'preview' ? array('min-height' => '200px', 'width' => '50%') : array('min-height' => '200px', 'width' => '1078px');

        return div
        (
            setClass('relative py-1 px-2.5 bg-canvas border border-canvas border-2 model-block', "block-$order"),
            setStyle($blockStyle),
            div
            (
                setClass('h-full'),
                div(setClass('item-step-title text-center text-clip'), set::title($blockTitle), $blockTitle),
                !isset($block->steps) ? null : div(setClass('py-3 flex flex-wrap gap-2.5 relative z-10'), $this->buildQuestion($block->steps))
            )
        );
    }

    /**
     * @param int $key
     */
    protected function buildRow($key)
    {
        $blocks = $this->prop('blocks');
        return div
        (
            setClass('w-full flex items-stretch'),
            $this->buildItem($key, $blocks[$key]),
            $this->buildItem($key + 1, $blocks[$key + 1])
        );
    }

    protected function buildBody()
    {
        $blocks     = $this->prop('blocks');
        $modelItems = array();
        foreach($blocks as $key => $block)
        {
            if($key % 2 == 0) $modelItems[] = $this->buildRow($key);
        }
        return $modelItems;
    }

    protected function build()
    {
        global $app, $lang;
        $app->loadLang('thinkwizard');

        $mode  = $this->prop('mode');
        $style = $mode == 'preview' ? setStyle(array('min-height' => '254px')) : setStyle(array('min-height' => '254px', 'min-width' => '2156px'));
        $model = array(
            div
            (
                setClass('model-swot my-1 flex flex-wrap justify-between'),
                $style,
                $this->buildBody()
            )
        );
        if($mode == 'preview')
        {
            array_unshift($model, div(setClass('flex justify-between text-gray-400'), span($lang->thinkwizard->block . $lang->thinkwizard->blockList[0]), span($lang->thinkwizard->block . $lang->thinkwizard->blockList[1])));
            $model[] = div(setClass('flex justify-between text-gray-400'), span($lang->thinkwizard->block . $lang->thinkwizard->blockList[2]), span($lang->thinkwizard->block . $lang->thinkwizard->blockList[3]));
        }
        return $model;
    }
}
