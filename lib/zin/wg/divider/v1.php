<?php
namespace zin;

class divider extends wg
{
    protected function build()
    {
        return div
        (
            setClass("divider"),
            set($this->getRestProps()),
            $this->children()
        );
    }
}
