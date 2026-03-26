<?php
namespace zin;

$execution = data('execution');

if($execution->isTpl)
{
    query('#products')->remove();
}
