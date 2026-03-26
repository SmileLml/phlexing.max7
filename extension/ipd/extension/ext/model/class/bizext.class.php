<?php
class bizextExtension extends extensionModel
{
    public function expireCheck()
    {
        $this->app->user->extensionChecked = true;
        if($this->app->user->admin) return false;

        $extensions  = $this->dao->select('*')->from(TABLE_EXTENSION)->where('status')->eq('installed')->fetchAll();
        $licencePath = $this->app->getConfigRoot() . 'license/';
        $today       = date('Y-m-d');
        $noticeDays  = "7,3,2,1";
        foreach($extensions as $extension)
        {
            $licenceOrderFiles = glob($licencePath . 'order*.txt');
            foreach($licenceOrderFiles as $licenceOrderFile)
            {
                if(file_exists($licenceOrderFile))
                {
                    if(stripos($licenceOrderFile, "{$extension->code}{$extension->version}.txt") === false) continue;

                    $order = file_get_contents($licenceOrderFile);
                    $order = unserialize($order);
                    if($order->type != 'life')
                    {
                        $days = isset($order->days) ? $order->days : 0;
                        if($order->type == 'demo') $days = 31;
                        if($order->type == 'year') $days = 365;
                        if($days)
                        {
                            $startDate  = !helper::isZeroDate($order->paidDate) ? $order->paidDate : $order->createdDate;
                            $expireDate = date('Y-m-d', strtotime($startDate) + $days * 24 * 3600);
                            $diffDays   = helper::diffDate($expireDate, $today);
                            if(strpos(",{$noticeDays},", ",{$diffDays},") !== false)
                            {
                                $notice     = sprintf($this->lang->extension->expireNotice, "#{$extension->id}" . $extension->name, $diffDays);
                                $fullNotice = <<<EOT
<div id='noticeAttend' class='alert alert-warning with-icon alert-dismissable' style='width:280px; position:fixed; bottom:25px; right:15px; z-index: 9999;'>
   <i class='icon icon-warning-sign'>  </i>
   <div class='content'>{$notice}</div>
   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</buttont
 </div>
EOT;
                                echo $fullNotice;
                            }
                        }
                    }
                }
            }
        }
    }
}
