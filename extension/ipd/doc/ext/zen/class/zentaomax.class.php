<?php
class zentaomaxDocZen extends docZen
{
    /**
     * 检查区块的权限。
     * Check block priviledge.
     *
     * @param  string $type
     * @access public
     * @return bool
     */
    public function checkBlockPriv($type)
    {
        extract($this->config->docTemplate->zentaoListPrivs[$type]);

        return common::hasPriv($module, $method);
    }
}
