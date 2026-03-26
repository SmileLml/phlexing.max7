<?php
$config->group->subset->artifactrepo = new stdclass();
$config->group->subset->artifactrepo->order = 2760;
$config->group->subset->artifactrepo->nav   = 'devops';

$config->group->package->browseArtifactrepo = new stdclass();
$config->group->package->browseArtifactrepo->order  = 2780;
$config->group->package->browseArtifactrepo->subset = 'artifactrepo';
$config->group->package->browseArtifactrepo->privs  = array();
$config->group->package->browseArtifactrepo->privs['artifactrepo-browse']               = array('edition' => 'open,biz,max,ipd', 'vision' => 'rnd', 'order' => 5, 'depend' => array('repo-maintain'), 'recommend' => array());
$config->group->package->browseArtifactrepo->privs['artifactrepo-ajaxGetArtifactRepos'] = array('edition' => 'open,biz,max,ipd', 'vision' => 'rnd', 'order' => 10, 'depend' => array('repo-maintain'), 'recommend' => array());

$config->group->package->manageArtifactrepo = new stdclass();
$config->group->package->manageArtifactrepo->order  = 2800;
$config->group->package->manageArtifactrepo->subset = 'artifactrepo';
$config->group->package->manageArtifactrepo->privs  = array();
$config->group->package->manageArtifactrepo->privs['artifactrepo-create']                  = array('edition' => 'open,biz,max,ipd', 'vision' => 'rnd', 'order' => 5, 'depend' => array('repo-maintain'), 'recommend' => array());
$config->group->package->manageArtifactrepo->privs['artifactrepo-edit']                    = array('edition' => 'open,biz,max,ipd', 'vision' => 'rnd', 'order' => 10, 'depend' => array('repo-maintain'), 'recommend' => array());
$config->group->package->manageArtifactrepo->privs['artifactrepo-ajaxUpdateArtifactRepos'] = array('edition' => 'open,biz,max,ipd', 'vision' => 'rnd', 'order' => 15, 'depend' => array('repo-maintain'), 'recommend' => array());

$config->group->package->deleteArtifactrepo = new stdclass();
$config->group->package->deleteArtifactrepo->order  = 2820;
$config->group->package->deleteArtifactrepo->subset = 'artifactrepo';
$config->group->package->deleteArtifactrepo->privs  = array();
$config->group->package->deleteArtifactrepo->privs['artifactrepo-delete'] = array('edition' => 'open,biz,max,ipd', 'vision' => 'rnd', 'order' => 5, 'depend' => array('repo-maintain'), 'recommend' => array());
