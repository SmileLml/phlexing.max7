<?php
public function getCustomCalcFile($code)
{
    return $this->loadExtension('zentaobiz')->getCustomCalcFile($code);
}

public function create($metric)
{
    return $this->loadExtension('zentaobiz')->create($metric);
}

public function update($id, $metric)
{
    return $this->loadExtension('zentaobiz')->update($id, $metric);
}

public function getMetricPHPTemplate($metricID)
{
    return $this->loadExtension('zentaobiz')->getMetricPHPTemplate($metricID);
}

public function updateMetric($metric)
{
    return $this->loadExtension('zentaobiz')->updateMetric($metric);
}

public function checkCustomCalcExists($code)
{
    return $this->loadExtension('zentaobiz')->checkCustomCalcExists($code);
}

public function checkCustomCalcSyntax($code)
{
    return $this->loadExtension('zentaobiz')->checkCustomCalcSyntax($code);
}

public function checkCustomCalcClassName($code)
{
    return $this->loadExtension('zentaobiz')->checkCustomCalcClassName($code);
}

public function checkCustomCalcClassMethod($code)
{
    return $this->loadExtension('zentaobiz')->checkCustomCalcClassMethod($code);
}

public function checkCustomCalcRuntime($code)
{
    return $this->loadExtension('zentaobiz')->checkCustomCalcRuntime($code);
}

public function runCustomCalc($code)
{
    return $this->loadExtension('zentaobiz')->runCustomCalc($code);
}

public function getCalcDir($metric)
{
    return $this->loadExtension('zentaobiz')->getCalcDir($metric);
}

public function moveCalcFile($calcDir, $code)
{
    return $this->loadExtension('zentaobiz')->moveCalcFile($calcDir, $code);
}
