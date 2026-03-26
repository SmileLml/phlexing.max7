<?php
if($this->post->module == 'ldap') return $this->loadModel('ldap')->buildQuery();
