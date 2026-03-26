<?php
class story extends control
{
    public function browse()
    {
        $this->locate($this->createLink('product', 'browse'));
    }
}
