<?php
namespace zin;

class hr extends wg
{
    protected function build()
    {
        return h::hr(setClass('my-5'));
    }
}
