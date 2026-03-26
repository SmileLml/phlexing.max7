<?php
class myScreen extends control
{
    /**
     * Ajax get tree data.
     *
     * @param  int $screenID
     * @access public
     * @return void
     */
    public function ajaxGetTreeData($screenID)
    {
        $dimensions  = $this->screen->getDimensionPairs();
        $screen      = $this->screen->getByID($screenID);
        $dimensionID = $this->loadModel('dimension')->setSwitcherMenu($screen->dimension);

        foreach($dimensions as $dimension) $dimension->value = (string)$dimension->value;

        $data = new stdclass();
        $data->chartData   = $screen->chartData;
        $data->treeData    = $this->screen->getTreeData($dimensions);
        $data->dimensions  = $dimensions;
        $data->dimension   = (string)$screen->dimension;
        $data->scopeList   = $this->loadModel('metric')->getScopePairs(false);
        $data->scope       = 'project';
        $data->fieldConfig = $this->screen->getTreeSelectOptions();

        echo(json_encode($data));
    }
}
