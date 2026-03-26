<?php
class zentaobizBi extends biModel
{
    /**
     *  获取扩展Js的内容
     *  @param  string $module    the module name, e.g: pivot
     *  @param  string $extraPath the extra file path, e.g: 'design/step2.ui.js'
     *  @access public
     *  @return
     */
    public function getExtJsContent($module, $extraPath)
    {
        $extPath  = $this->app->getModuleExtPath($module, 'js');
        $filePath = $extPath['common'] . $extraPath;

        if(!file_exists($filePath)) return;

        return file_get_contents($filePath);
    }
}
