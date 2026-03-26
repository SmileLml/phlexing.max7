<?php
$mailTitle = strtoupper($objectType) . ' #' . $object->id . ' ' . $title;
$moduleName = $objectType;
$methodName = 'view';
$params = "objectId=$objectID";

if(isset($this->config->approval->objectLinks[$objectType]))
{
    list($moduleName, $methodName, $params) = explode('|', $this->config->approval->objectLinks[$objectType]);
}

$link = $domain . helper::createLink($moduleName, $methodName, $params, 'html');
?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
　<head>
　　<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
　　<title><?php echo $mailTitle ?></title>
　　<meta name='viewport' content='width=device-width, initial-scale=1.0'/>
    <style>span.pass{color:#60D978} span.fail{color:red}</style>
　</head>
  <body style='background-color: #e5e5e5;'>
  　<table border='0' cellpadding='0' cellspacing='0' width='100%' style='font-size: 13px; color: #333; line-height: 20px; font-family: "Helvetica Neue",Helvetica,"Microsoft Yahei","Hiragino Sans GB","WenQuanYi Micro Hei",Tahoma,Arial,sans-serif;'>
      <tr>
        <td>
          <table align='center' border='0' cellpadding='0' cellspacing='0' width='600' style='border: none; border-collapse: collapse;'>
            <tr>
              <td style='padding: 10px 0; border: none; vertical-align: middle;'><strong style='font-size: 16px'><?php echo $this->app->company->name ?></strong></td>
            </tr>
          </table>
          <table align='center' border='0' cellpadding='0' cellspacing='0' width='600' style='border-collapse: collapse; background-color: #fff; border: 1px solid #cfcfcf; box-shadow: 0 0px 6px rgba(0, 0, 0, 0.1); margin-bottom: 20px; font-size:13px;'>
            <tr>
              <td>
                <table cellpadding='0' cellspacing='0' width='600' style='border: none; border-collapse: collapse;'>
                  <tr>
                    <td style='padding: 10px; background-color: #F8FAFE; border: none; font-size: 14px; font-weight: 500; border-bottom: 1px solid #e5e5e5;'>
                      <?php
                      echo html::a($link, $subject, '', "text-decoration: underline;'");
                      ?>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>

            <tr>
              <td style='padding: 10px; border: none;'>
                <?php if($approval->result):?>
                <?php echo sprintf($this->lang->approval->mailContent['result'], $link, $title, $approval->result, zget($this->lang->approval->resultList, $approval->result));?>
                <?php else:?>
                    <?php if($isCC):?>
                    <?php echo sprintf($this->lang->approval->mailContent['mailto'], $link, $title);?>
                    <?php else:?>
                    <?php echo sprintf($this->lang->approval->mailContent['mail'], $link, $title);?>
                    <?php endif;?>
                <?php endif;?>
              </td>
            </tr>

            <?php if($node->type == 'review'):?>
            <tr>
              <td style='padding: 10px; border: none;'>
                <fieldset style='border: 1px solid #e5e5e5'>
                  <legend style='color: #114f8e'><?php echo $this->lang->approval->currentResult;?></legend>
                  <div style='padding:5px;'>
                    <?php echo zget($this->lang->approval->mailResultList, $node->result);?>
                  </div>
                </fieldset>
              </td>
            </tr>
            <?php endif;?>

            <?php if($actions):?>
            <?php foreach($actions as $action):?>
            <?php if(!empty($action->comment)):?>
            <tr>
              <td style="padding:0px 10px 10px 10px; border: none;">
                <fieldset style="border: 1px solid #e5e5e5">
                <legend style="color: #114f8e"><?php echo $this->lang->comment?></legend>
                <div style="padding:5px;"><?php echo $action->comment?></div>
                </fieldset>
              </td>
            </tr>
            <?php endif;?>
            <tr>
              <td style='padding: 10px; background-color: #FFF0D5'>
                <?php $action->actor = zget($users, $action->actor);?>
                <?php $action->extra = zget($users, $action->extra);?>
                <span style='font-size: 16px; color: #F1A325'>●</span> &nbsp;<span><?php $this->action->printAction($action);?></span>
              </td>
            </tr>
            <?php if(!empty($action->history)):?>
            <tr>
              <td style='padding: 10px;'>
                <div><?php $this->action->printChanges($action->objectType, $action->objectID, $action->history, false);?></div>
              </td>
            </tr>
            <?php endif;?>
            <?php endforeach;?>
            <?php endif;?>
          </table>
        </td>
      </tr>
  　</table>
  </body>
</html>
