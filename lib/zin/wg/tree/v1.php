<?php
namespace zin;

class tree extends wg
{
    protected function build()
    {
        $sortable = $this->prop('sortable');
        if(!empty($sortable))
        {
            return zui::sortableTree(set::_tag('menu'), inherit($this));
        }
        return zui::tree(set::_tag('menu'), inherit($this));
    }
}
