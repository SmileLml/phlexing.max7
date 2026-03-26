<?php
/**
 * The control file of conference module of XXB.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd., www.zentao.net)
 * @license     ZOSL (https://zpl.pub/page/zoslv1.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     conference
 * @version     $Id$
 * @link        https://xuanim.com
 */
?>
<?php
class conference extends control
{
    /**
     * @var conferenceModel
     */
    public $conference;

    /**
     * View and set jitsi configuration.
     *
     * @param  string $type type could be 'server', 'video', 'edit'
     * @access public
     * @return void
     */
    public function admin($type = 'server')
    {
        if(!empty($_POST))
        {
            $domain = $_POST['domain'];
            /**
             * 匹配是否是域名+端口的形式
             * www.example.com:8080
             * www.example.com
             * 127.0.0.1:8080
             * 127.0.0.1
             * @var bool $isMatch
             */
            $re = '/^(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z0-9][a-z0-9-]{0,61}[a-z0-9](?:\:\d+)?$/';
            preg_match($re, $domain, $matches, PREG_OFFSET_CAPTURE, 0);

            if(empty($matches) && !empty($domain)) {
                $parsedUrl = parse_url($domain);
                if($parsedUrl !== false)
                {
                    $domain = $parsedUrl['host'] . ($parsedUrl['port'] ? ':' . $parsedUrl['port'] : '');
                }
            }
            $_POST['domain'] = $domain;
            $result = $this->conference->setConfiguration($_POST);
            $this->send($result);
        }

        $conferenceConfig = $this->conference->getConfiguration();

        $this->view->title              = $this->lang->conference->common;
        $this->view->type               = $type;
        $this->view->enabled            = isset($conferenceConfig->enabled) && $conferenceConfig->enabled == 'true';
        $this->view->domain             = isset($conferenceConfig->domain) ? $conferenceConfig->domain : '';

        $this->display();
    }

    /**
     * Get conference permissions.
     *
     * @access public
     * @return void
     */
    public function getConferencePermissions()
    {
        return $this->conference->getConferencePermissions();
    }
}
