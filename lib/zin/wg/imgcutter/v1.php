<?php
namespace zin;

class imgCutter extends wg
{
    /**
     * @var mixed[]
     */
    protected static $defineProps = array(
        'src: string',
        'btnText?: string',
        'tipText?: string',
        'coverColor?: string',
        'coverOpacity?: number',
        'defaultWidth?: number',
        'defaultHeight?: number',
        'minWidth?: number',
        'minHeight?: number',
        'fixedRatio?: boolean',
        'onSizeError?: callable',
        'ready: callable',
        'handleBtnClick: callable'
    );

    protected function build()
    {
        $btnText = $this->prop('btnText');
        $tipText = $this->prop('tipText');

        $imgCutter = div
        (
            set($this->getRestProps()),
            setClass('img-cutter'),
            div
            (
                setClass('canvas'),
                img(set::src($this->prop('src')))
            ),
            div
            (
                setClass('text-xl font-bold py-3'),
                $tipText
            ),
            btn
            (
                set::type('primary'),
                $btnText
            )
        );
        $imgCutter->setProp('data-zin-id', $imgCutter->gid);

        $props = array_merge($this->props->pick(array('coverColor', 'coverOpacity', 'defaultWidth', 'defaultHeight', 'minWidth', 'minHeight', 'fixedRatio', 'onSizeError', 'ready', 'handleBtnClick')), array('_to' => "[data-zin-id='{$imgCutter->gid}']"));

        return array(
            $imgCutter,
            zui::imgCutter(set($props))
        );
    }
}
