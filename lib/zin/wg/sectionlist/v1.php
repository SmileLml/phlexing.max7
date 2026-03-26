<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'section' . DS . 'v1.php';

class sectionList extends wg
{
    /**
     * @param \zin\node $item
     */
    public function onBuildItem($item)
    {
        return new section(inherit($item));
    }

    protected function build()
    {
        return div
        (
            setClass('section-list', 'canvas', 'col', 'gap-6', 'pt-4', 'px-6', 'pb-6'),
            set($this->getRestProps()),
            $this->children()
        );
    }
}
