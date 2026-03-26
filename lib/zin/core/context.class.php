<?php
/**
 * The context class file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

use function zin\utils\flat;

require_once dirname(__DIR__) . DS . 'utils' . DS . 'dataset.class.php';
require_once dirname(__DIR__) . DS . 'utils' . DS . 'flat.func.php';
require_once dirname(__DIR__) . DS . 'utils' . DS . 'deep.func.php';
require_once __DIR__ . DS . 'helper.func.php';
require_once __DIR__ . DS . 'render.class.php';
require_once __DIR__ . DS . 'command.class.php';
require_once __DIR__ . DS . 'js.class.php';

class context extends \zin\utils\dataset
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var mixed[]
     */
    public $globalRenderList = array();

    /**
     * @var int
     */
    public $globalRenderLevel = 0;

    /**
     * @var mixed[]
     */
    public $data = array();

    /**
     * @var mixed[]
     */
    public $debugData = array();

    /**
     * @var \control|null
     */
    public $control;

    /**
     * @var bool
     */
    public $rendered = false;

    /**
     * @var bool
     */
    public $rawContentCalled = false;

    /**
     * @var bool
     */
    public $hookContentCalled = false;

    /**
     * @var mixed[]
     */
    public $beforeBuildNodeCallbacks = array();

    /**
     * @var mixed[]
     */
    public $onBuildNodeCallbacks = array();

    /**
     * @var mixed[]
     */
    public $onRenderNodeCallbacks = array();

    /**
     * @var mixed[]
     */
    public $onRenderCallbacks = array();

    /**
     * @var mixed[]
     */
    public $queries = array();

    /**
     * @var \zin\node|null
     */
    public $rootNode;

    /**
     * @var \zin\render|null
     */
    public $renderer;

    /**
     * @var mixed[]
     */
    public $pageJS = array();

    /**
     * @var mixed[]
     */
    public $pageCSS = array();

    /**
     * @var mixed[]
     */
    public $jsVars = array();

    /**
     * @var mixed[]
     */
    public $jsCalls = array();

    /**
     * @var mixed[]
     */
    public $wgRes = array();

    /**
     * @var mixed[]
     */
    public $eventBindings = array();

    /**
     * @var mixed[]
     */
    public $renderWgMap = array('page' => 'page', 'modal' => 'modalDialog', 'fragment' => 'fragment');

    /**
     * @var mixed[]
     */
    public $rawContentNames = array();

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct();
        $this->name = $name;
    }

    public function __debugInfo()
    {
        return array_merge(array
        (
            'name'                => $this->name,
            'globalRenderListLen' => count($this->globalRenderList),
            'globalRenderList'    => $this->globalRenderList,
            'globalRenderLevel'   => $this->globalRenderLevel,
            'rendered'            => $this->rendered,
            'rawContentCalled'    => $this->rawContentCalled,
            'rawContentNames'     => $this->rawContentNames
        ), $this->storedData);
    }

    /**
     * @param mixed $defaultValue
     * @return mixed
     * @param string $namePath
     */
    public function getData($namePath, $defaultValue = null)
    {
        return \zin\utils\deepGet($this->data, $namePath, $defaultValue);
    }

    /**
     * @param mixed $value
     * @param string $namePath
     */
    public function setData($namePath, $value)
    {
        \zin\utils\deepSet($this->data, $namePath, $value);
    }

    public function enableGlobalRender()
    {
        $this->globalRenderLevel--;
    }

    public function disableGlobalRender()
    {
        $this->globalRenderLevel++;
    }

    public function enabledGlobalRender()
    {
        return $this->globalRenderLevel < 1;
    }

    /**
     * @param \zin\node|\zin\iDirective $item
     */
    public function renderInGlobal($item)
    {
        if($this->globalRenderLevel > 0)
        {
            return false;
        }

        if($item instanceof node)
        {
            if($item->parent) return false;

            $type = $item->type();
            if($type === 'wg' || $type === 'node') return false;

            if(!isset($this->globalRenderList[$item->gid])) $this->globalRenderList[$item->gid] = $item;
            return true;
        }

        if(in_array($item, $this->globalRenderList)) return false;

        $this->globalRenderList[] = $item;
        return true;
    }

    /**
     * @param mixed $data
     */
    function skipRenderInGlobal($data)
    {
        if(is_array($data))
        {
            foreach($data as $item) skipRenderInGlobal($item);
            return;
        }

        if($data instanceof node || $data instanceof iDirective)
        {
            if(isset($data->gid)) unset($this->globalRenderList[$data->gid]);
            $data->notRenderInGlobal = true;
        }
    }

    /**
     * @param bool $clear
     */
    public function getGlobalRenderList($clear = true)
    {
        $globalItems = array();

        foreach($this->globalRenderList as $item)
        {
            if(is_object($item) && ((isset($item->parent) && $item->parent) || (isset($item->notRenderInGlobal) && $item->notRenderInGlobal)))
            {
                continue;
            }
            $globalItems[] = $item;
        }

        /* Clear globalRenderList. */
        if($clear) $this->globalRenderList = array();

        return $globalItems;
    }

    /**
     * @param string|mixed[] ...$files
     */
    public function addHookFiles(...$files)
    {
        $files = flat($files);
        return $this->mergeToList('hookFiles', array_filter(array_values($files)));
    }

    public function getHookFiles()
    {
        return $this->getList('hookFiles');
    }

    /**
     * @param string ...$files
     */
    public function addImports(...$files)
    {
        return $this->mergeToList('import', $files);
    }

    public function getImports()
    {
        return $this->getList('import');
    }

    /**
     * @param string|mixed[] $css
     * @param string|null $name
     */
    public function addCSS($css, $name = null)
    {
        if(is_array($css)) $css = implode("\n", $css);

        if($name)
        {
            if(isset($this->pageCSS[$name]))
            {
                if(isDebug()) triggerError("Page CSS name \"$name\" already exists.");
                return;
            }
            $this->pageCSS[$name] = $css;
        }
        else
        {
            $this->pageCSS[] = $css;
        }
    }

    public function getCSS()
    {
        $css   = array();
        $wgRes = $this->wgRes;

        if($wgRes) foreach ($wgRes as $res) if($res['css']) $css[] = $res['css'];

        if($this->pageCSS) $css = array_merge($css, $this->pageCSS);
        return trim(implode("\n", $css));
    }

    /**
     * @param string|mixed[] $js
     * @param string|null $name
     */
    public function addJS($js, $name = null)
    {
        if(is_array($js)) $js = implode("\n", $js);

        if($name)
        {
            if(isset($this->pageJS[$name]))
            {
                if(isDebug()) triggerError("Page JS name \"$name\" already exists.");
                return;
            }
            $this->pageJS[$name] = $js;
        }
        else
        {
            $this->pageJS[] = $js;
        }
    }

    /**
     * @param mixed $value
     * @param string $name
     */
    public function addJSVar($name, $value)
    {
        $this->jsVars[$name] = $value;
    }

    public function addJSCall($func, $args)
    {
        $this->jsCalls[] = array($func, $args);
    }

    public function getJS()
    {
        $jsVars        = $this->jsVars;
        $pageJS        = $this->pageJS;
        $jsCalls       = $this->jsCalls;
        $wgRes         = $this->wgRes;
        $eventBindings = $this->eventBindings;
        $js            = array();

        if($wgRes)         foreach ($wgRes as $res) if($res['js']) $js[] = js::scope($res['js']);
        if($jsVars)        foreach($jsVars as $name => $value) $js[] = js::defineVar($name, $value);
        if($pageJS)        $js = array_merge($js, $pageJS);
        if($eventBindings) $js = array_merge($js, $eventBindings);
        if($jsCalls)       foreach($jsCalls as $call) $js[] = js::defineJSCall($call[0], $call[1]);

        if(empty($js)) return '';

        $js = trim(implode("\n", $js));
        if(strpos($js, 'setTimeout') !== false)  $js = 'function setTimeout(callback, time){return typeof window.registerTimer === "function" ? window.registerTimer(callback, time) : window.setTimeout(callback, time);}' . $js;
        if(strpos($js, 'setInterval') !== false) $js = 'function setInterval(callback, time){return typeof window.registerTimer === "function" ? window.registerTimer(callback, time, "interval") : window.setInterval(callback, time);}' . $js;

        $methods = array('onPageUnmount', 'beforePageUpdate', 'afterPageUpdate', 'onPageRender');
        foreach($methods as $method)
        {
            if(strpos($js, $method) === false) continue;
            $js .= "if(typeof $method === 'function') window.$method = $method;";
        }
        return $js;
    }

    /**
     * @param string $wgClass
     */
    public function addWgRes($wgClass)
    {
        if(isset($this->wgRes[$wgClass])) return;

        $res = array();
        $res['css'] = $wgClass::getPageCSS();
        $res['js']  = $wgClass::getPageJS();
        $this->wgRes[$wgClass] = $res;
    }

    /**
     * @param mixed ...$values
     * @param string $name
     */
    public function addDebugData($name, ...$values)
    {
        $e         = new \Exception();
        $trace     = $e->getTraceAsString();
        $trace     = str_replace($this->control->app->basePath, '', $trace);
        $stack     = explode("\n", $trace);
        if(str_contains($stack[0], 'lib/zin/core/context.func.php')) array_shift($stack);

        $finalName = $name;
        if(empty($finalName))
        {
            $statement = $stack[0];
            if(str_contains($stack[0], '): zin\d('))
            {
                $statement = explode('): zin\d(', $statement)[1];
                if(str_contains($statement, ',')) $finalName = explode(',', $statement)[0];
                else                              $finalName = explode(')', $statement)[0];
            }
            else
            {
                $finalName = 'dump';
            }
        }

        $isJson = !str_starts_with($name, '$');
        $data   = $values;
        if($isJson)
        {
            $data = json_encode($values);
            if($data === false) $isJson = false;
            else                $data = jsRaw($data);
        }
        if(!$isJson) $data = array_map(function($value) {return var_export($value, true);}, $values);
        $this->debugData[] = array('name' => $finalName, 'data' => $data, 'type' => $isJson ? 'json' : 'var', 'trace' => $stack);
    }

    public function getDebugData()
    {
        global $app;
        $zinDebug = null;
        if(isDebug() && (!isAjaxRequest() || isAjaxRequest('zin')))
        {
            $zinDebug = data('zinDebug');
            if(is_array($zinDebug))
            {
                $zinDebug['basePath'] = $app->getBasePath();
                $zinDebug['debug']    = $this->debugData;
                if(isset($app->zinErrors)) $zinDebug['errors'] = $app->zinErrors;
            }
        }
        return $zinDebug;
    }

    public function getRawContent()
    {
        $rawContent = ob_get_contents();
        if(!is_string($rawContent)) $rawContent = '';
        ob_end_clean();
        return $rawContent;
    }

    /**
     * @param \zin\query $query
     */
    public function addQuery($query)
    {
        $this->queries[] = $query;
    }

    /**
     * Include hooks files.
     */
    public function includeHooks()
    {
        $hookFiles = $this->getHookFiles();
        ob_start();
        foreach($hookFiles as $hookFile)
        {
            if(!empty($hookFile) && file_exists($hookFile)) include $hookFile;
        }
        $hookCode = ob_get_clean();
        ob_end_flush();
        if($hookCode) return $hookCode;
        return '';
    }

    /**
     * @param null|object|string|mixed[] $selectors
     * @return string|mixed[]|object
     * @param \zin\node $node
     * @param string $renderType
     * @param bool $renderInner
     */
    public function render($node, $selectors = null, $renderType = 'html', $renderInner = false)
    {
        $this->disableGlobalRender();

        $renderer = new render($node, $selectors, $renderType, $renderInner);
        $this->rendered = true;
        $this->renderer = $renderer;
        $this->rootNode = $node;

        $hookCode   = $this->includeHooks();
        $rawContent = $this->getRawContent();

        $node->prebuild(true);
        $this->applyQueries($node);

        $result = $renderer->render();
        if(is_object($result)) // renderType = json
        {
            $zinDebug = $this->getDebugData();
            if($zinDebug && isset($result->zinDebug)) $result->zinDebug = $zinDebug;
            $result = json_encode($result, JSON_PARTIAL_OUTPUT_ON_ERROR);
        }
        else
        {
            $js      = $this->getJS();
            $css     = $this->getCSS();
            $replace = array('<!-- {{HOOK_CONTENT}} -->' => $hookCode, '/*{{ZIN_PAGE_CSS}}*/' => $css, '/*{{ZIN_PAGE_JS}}*/' => $js);

            /* 如果存在 rawContentNames，则将 rawContent 中的内容块替换为对应名称的内容块。 */
            /* If rawContentNames exists, replace the content block in rawContent with the content block of the corresponding name. */
            if($this->rawContentNames)
            {
                $rawContentMap = parseRawContent($rawContent);
                $replace['<!-- {{RAW_CONTENT}} -->']     = $rawContentMap['GLOBAL'];
                $replace['<!-- {{RAW_CONTENT:ALL}} -->'] = $rawContent;
                foreach ($this->rawContentNames as $name => $_)
                {
                    if(!isset($rawContentMap[$name]))
                    {
                        if(isDebug()) triggerError("The content of rawContent(\"$name\") not found.");
                        continue;
                    }
                    $replace["<!-- {{RAW_CONTENT:{$name}}} -->"] = $rawContentMap[$name];
                }
            }
            else
            {
                $replace['<!-- {{RAW_CONTENT}} -->'] = $rawContent;
            }

            $zinDebug = $this->getDebugData();
            if(is_array($result)) // renderType = list
            {
                foreach($result as $index => $item)
                {
                    if($item->name === 'zinDebug' && $zinDebug)
                    {
                        $result[$index] = array('zinDebug:<BEGIN>', $zinDebug);
                        continue;
                    }
                    if($item->name === 'hookCode')
                    {
                        $item->data = $hookCode;
                        continue;
                    }
                    if(!isset($item->type) || $item->type !== 'html') continue;

                    $item->data = str_replace(array_keys($replace), array_values($replace), $item->data);
                }
                $result = json_encode($result, JSON_PARTIAL_OUTPUT_ON_ERROR);
            }
            else // renderType = html
            {
                if($zinDebug) $js .= js::defineVar('window.zinDebug', $zinDebug);
                $result  = str_replace(array_keys($replace), array_values($replace), $result);
            }
        }

        $data = new stdClass();
        $data->type   = $renderType;
        $data->output = $result;

        foreach($this->onRenderCallbacks as $callback)
        {
            if($callback instanceof \Closure) $callback($data, $node);
            else call_user_func($callback, $data, $node);
        }

        $this->enabledGlobalRender();
        return $data->output;
    }

    /**
     * @param \zin\node $rootNode
     */
    protected function applyQueries($rootNode)
    {
        if(!$this->queries) return;

        foreach($this->queries as $query)
        {
            $this->applyQuery($rootNode, $query);
        }
    }

    /**
     * @param \zin\node $rootNode
     * @param \zin\query $query
     */
    protected function applyQuery($rootNode, $query)
    {
        $nodes = $query->isRoot() ? array($rootNode) : findInNode($query->selectors, $rootNode, false, false, false);
        if(!$nodes) return;

        $queryNodes = $nodes;
        foreach($query->commands as $command)
        {
            list($method, $args) = $command;
            $result = call_user_func("\zin\command::{$method}", $queryNodes, ...$args);
            if(is_array($result))  $queryNodes = $result;
            if(empty($queryNodes)) break;
            prebuild($queryNodes);
        }
    }

    /**
     * @param \zin\node $node
     */
    public function handleBeforeBuildNode($node)
    {
        foreach($this->beforeBuildNodeCallbacks as $callback)
        {
            if($callback instanceof \Closure) $callback($node);
            else call_user_func($callback, $node);
        }
    }

    /**
     * @param \zin\stdClass $data
     * @param \zin\node $node
     */
    public function handleBuildNode(&$data, $node)
    {
        foreach($this->onBuildNodeCallbacks as $callback)
        {
            if($callback instanceof \Closure) $callback($data, $node);
            else call_user_func($callback, $data, $node);
        }

        if($node instanceof wg)
        {
            $class = get_class($node);
            if(!isset($this->wgRes[$class]))
            {
                $res = array();
                $res['css'] = $class::getPageCSS();
                $res['js']  = $class::getPageJS();
                $this->wgRes[$class] = $res;
            }
        }

        if($this->renderer) $this->renderer->handleBuildNode($data, $node);

        $eventBinding = $node->buildEvents();
        if($eventBinding) $this->eventBindings[] = $eventBinding;
    }

    /**
     * @param \zin\stdClass $data
     * @param \zin\node $node
     */
    public function handleRenderNode(&$data, $node)
    {
        foreach($this->onRenderNodeCallbacks as $callback)
        {
            if($callback instanceof \Closure) $callback($data, $node);
            else call_user_func($callback, $data, $node);
        }
    }

    /**
     * @param callable|\Closure $callback
     */
    public function onBuildNode($callback)
    {
        $this->onBuildNodeCallbacks[] = $callback;
    }

    /**
     * @param callable|\Closure $callback
     */
    public function onRenderNode($callback)
    {
        $this->onRenderNodeCallbacks[] = $callback;
    }

    /**
     * @param callable|\Closure $callback
     */
    public function onBeforeBuildNode($callback)
    {
        $this->beforeBuildNodeCallbacks[] = $callback;
    }

    /**
     * @param callable|\Closure $callback
     */
    public function onRender($callback)
    {
        $this->onRenderCallbacks[] = $callback;
    }

    /**
     * @param string|mixed[] $mapOrName
     * @param string|null $wgName
     */
    public function setRenderWgMap($mapOrName, $wgName = null)
    {
        if(is_array($mapOrName))
        {
            $this->renderWgMap = array_merge($this->renderWgMap, $mapOrName);
        }
        else
        {
            $this->renderWgMap[$mapOrName] = $wgName;
        }
    }

    public function getRenderWgName()
    {
        if(isset($this->renderWgMap['all']))         return $this->renderWgMap['all'];
        if(isAjaxRequest('modal'))                   return $this->renderWgMap['modal'];
        if(isAjaxRequest() && !isAjaxRequest('zin')) return $this->renderWgMap['fragment'];
        return 'page';
    }

    /**
     * @var mixed[]
     */
    public static $stack = array();

    /**
     * @param string ...$code
     */
    public static function js(...$code)
    {
        static::current()->addJS(flat($code));
    }

    /**
     * @param mixed ...$args
     * @param string $func
     */
    public static function jsCall($func, ...$args)
    {
        static::current()->addJSCall($func, $args);
    }

    public static function jsVar($name, $value)
    {
        static::current()->addJSVar($name, $value);
    }

    /**
     * @param string ...$code
     */
    public static function css(...$code)
    {
        static::current()->addCSS(flat($code));
    }

    /**
     * @param string ...$files
     */
    public static function import(...$files)
    {
        static::current()->addImports(...$files);
    }

    /**
     * Get current context.
     *
     * @access public
     * @return context
     */
    public static function current()
    {
        if(empty(static::$stack))
        {
            $context = new context('default');
            static::$stack['default'] = $context;
            return $context;
        }
        return end(static::$stack);
    }

    /**
     * Create context.
     *
     * @access public
     * @param string $name  Context name.
     * @return context
     */
    public static function create($name)
    {
        if(isset(static::$stack[$name]))
        {
            triggerError("Context name \"$name\" already exists.");
        }
        $context = new context($name);
        static::$stack[$name] = $context;
        return $context;
    }

    /**
     * Pop last context.
     *
     * @access public
     * @return ?context
     */
    public static function pop()
    {
        return array_pop(static::$stack);
    }
}
