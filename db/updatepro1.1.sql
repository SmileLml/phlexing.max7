ALTER TABLE  `zt_effort` ADD  `consumed` FLOAT NOT NULL AFTER  `date`;
REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`) VALUES
(1,'ldap','index'),
(1,'ldap','set'),
(1,'user','importldap');
