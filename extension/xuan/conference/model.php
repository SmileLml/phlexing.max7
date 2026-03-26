<?php
/**
 * The model file of conference module of XXB.
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
class conferenceModel extends model
{
    /**
     * @var settingModel
     */
    public $setting;

    /**
     * Get current jitsi configuration.
     *
     * @access public
     * @return object
     */
    public function getConfiguration()
    {
        $this->loadModel('setting');
        $items = $this->setting->getItems("owner=system&module=jitsi&section=common&key=enabled,domain,detachedConference");
        $conferenceConfig = new stdClass();
        foreach($items as $item)
        {
            $conferenceConfig->{$item->key} = $item->value;
        }

        return $conferenceConfig;
    }

    /**
     * Check and set incoming jitsi configuration.
     *
     * @param  array $config
     * @access public
     * @return void
     */
    public function setConfiguration()
    {
        $post = fixer::input('post')
            ->setIF($this->post->domain  != '', 'domain',  trim($this->post->domain))
            ->get();

        $errors = array();
        if(isset($post->enabled) && $post->enabled === 'true')
        {
            foreach($this->config->conference->require->fields as $field)
            {
                if(empty($post->$field)) $errors[$field][] = $this->lang->conference->inputError->{$field};
            }
        }
        if(!empty($errors)) return array('result' => 'fail', 'message' => $errors);

        $this->loadModel('setting');
        $enabled = !isset($post->enabled) || empty($post->enabled)
            ? 'false'
            : 'true';
        $this->setting->setItem('system.jitsi.common.enabled', $enabled);
        $this->setting->setItem('system.jitsi.common.domain',  $post->domain);

        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('admin'));
    }

    /**
     * Check if jitsi functionality is enabled.
     *
     * @access public
     * @return boolean
     */
    public function isEnabled()
    {
        return filter_var($this->loadModel('setting')->getItem("owner=system&module=jitsi&section=common&key=enabled"), FILTER_VALIDATE_BOOLEAN)
            && extCommonModel::ilMethod('conference', 'detachedConference');
    }

    /**
     * Get conference permissions, set max number of participants.
     *
     * @access public
     * @return void
     */
    public function getConferencePermissions()
    {
        $conferenceLimitData = $this->loadModel('common')->getLicensePropertyValue('unlimitedParticipants');

        /* Fallback for older value. for older user, default give 10 participants limit. */
        if($conferenceLimitData === '1') $conferenceLimit = 10;

        /* '0' as unlimited participants in the new version */
        if($conferenceLimitData === '0') $conferenceLimit = null;

        /* not set value as 0 participants */
        if($conferenceLimitData === false) $conferenceLimit = 0;

        if(!array_key_exists('conferenceLimit', get_defined_vars())) $conferenceLimit = intval($conferenceLimitData);

        die(json_encode(array('status' => 'success', 'data' => array('limit' => $conferenceLimit))));
    }
}
