<?php
/**
 * The html helper methods file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'h.class.php';

/**
 * @param mixed ...$args
 */
function h(...$args)          {return h::create(...$args);}
/**
 * @param mixed ...$args
 */
function div(...$args)        {return h::div(...$args);}
/**
 * @param mixed ...$args
 */
function span(...$args)       {return h::span(...$args);}
/**
 * @param mixed ...$args
 */
function strong(...$args)     {return h::strong(...$args);}
/**
 * @param mixed ...$args
 */
function small(...$args)      {return h::small(...$args);}
/**
 * @param mixed ...$args
 */
function code(...$args)       {return h::code(...$args);}
/**
 * @param mixed ...$args
 */
function canvas(...$args)     {return h::canvas(...$args);}
/**
 * @param mixed ...$args
 */
function br(...$args)         {return h::br(...$args);}
/**
 * @param mixed ...$args
 */
function a(...$args)          {return h::a(...$args);}
/**
 * @param mixed ...$args
 */
function p(...$args)          {return h::p(...$args);}
/**
 * @param mixed ...$args
 */
function img(...$args)        {return h::img(...$args);}
/**
 * @param mixed ...$args
 */
function button(...$args)     {return h::button(...$args);}
/**
 * @param mixed ...$args
 */
function h1(...$args)         {return h::h1(...$args);}
/**
 * @param mixed ...$args
 */
function h2(...$args)         {return h::h2(...$args);}
/**
 * @param mixed ...$args
 */
function h3(...$args)         {return h::h3(...$args);}
/**
 * @param mixed ...$args
 */
function h4(...$args)         {return h::h4(...$args);}
/**
 * @param mixed ...$args
 */
function h5(...$args)         {return h::h5(...$args);}
/**
 * @param mixed ...$args
 */
function h6(...$args)         {return h::h6(...$args);}
/**
 * @param mixed ...$args
 */
function ol(...$args)         {return h::ol(...$args);}
/**
 * @param mixed ...$args
 */
function ul(...$args)         {return h::ul(...$args);}
/**
 * @param mixed ...$args
 */
function li(...$args)         {return h::li(...$args);}
/**
 * @param mixed ...$args
 */
function template(...$args)   {return h::template(...$args);}
/**
 * @param mixed ...$args
 */
function formHidden(...$args) {return h::formHidden(...$args);}
/**
 * @param mixed ...$args
 */
function fieldset(...$args)   {return h::fieldset(...$args);}
/**
 * @param mixed ...$args
 */
function legend(...$args)     {return h::legend(...$args);}
/**
 * @param mixed ...$args
 */
function iframe(...$args)     {return h::iframe(...$args);}
/**
 * @param mixed ...$args
 */
function css(...$args)        {return h::css(...$args);}
