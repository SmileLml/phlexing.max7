<?php
namespace zin;

class echarts extends wg
{
    /**
     * @var mixed[]
     */
    protected static $defineProps = array(
        'theme?: string|array',          // 主题
        'width?: number|string="100%"',  // 宽度
        'height?: number|string=100',    // 高度
        'responsive?: bool',             // 是否自适应
        'exts?: string|array'            // 插件
    );

    /**
     * 扩展定义。
     * Echart extensions map.
     *
     * @var array
     */
    public static $extMap = array
    (
        'timeline'   => 'timeline.min.js',
        'liquidfill' => 'echarts-liquidfill.min.js'
    );

    /**
     * @param string|int $width
     * @param string|int $height
     */
    public function size($width, $height)
    {
        if(isDebug())
        {
            $this->triggerError('echarts::size(' . json_encode($width) . ', ' . json_encode($height) . ') is deprecated, use echarts(set::width(' . json_encode($width) . '), set::height(' . json_encode($height) . ')) in instead.', E_USER_WARNING);
        }
        $this->setProp('width', $width);
        $this->setProp('height', $height);
        return $this;
    }

    /**
     * @param string|mixed[] $value
     */
    public function theme($value)
    {
        $this->setProp('theme', $value);
        return $this;
    }

    /**
     * @param bool $value
     */
    public function responsive($value = true)
    {
        $this->setProp('responsive', $value);
        return $this;
    }

    protected function build()
    {
        global $app;

        list($exts, $width, $height, $responsive, $theme) = $this->prop(array('exts', 'width', 'height', 'responsive', 'theme'));

        $root  = $app->getWebRoot() . 'js/echarts/';
        $files = array($root . 'echarts.common.min.js');
        if($exts)
        {
            $exts = is_array($exts) ? $exts : explode(',', $exts);
            foreach($exts as $ext)
            {
                if(isset(self::$extMap[$ext]))  $ext = self::$extMap[$ext];
                if(!str_contains($ext, '.'))    $ext = $ext . '.min.js';
                if(!str_starts_with($ext, '/')) $ext = $root . $ext;
                $files[] = $ext;
            }
        }

        return zui::echarts
        (
            set::_id('zin_echart_' . uniqid()),
            set::responsive($responsive),
            set::theme($theme),
            set::_style(array('width' => is_int($width) ? "{$width}px" : $width, 'height' => is_int($height) ? "{$height}px" : $height)),
            set('$lib', array('check' => 'echarts', 'src' => $files, 'root' => false)),
            set($this->getRestProps()),
            $this->children()
        );
    }
}
