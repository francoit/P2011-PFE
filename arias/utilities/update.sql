#Replacement:

UPDATE `menupage` SET `name` = 'arorderlist.php' WHERE `id` = '101';
#delete menupage (id, menufunctionid, name, orderflag, accessar) values (101, 11, 'arorderlst.php', 1, 1);
#insert into menupage (id, menufunctionid, name, orderflag, accessar) values (101, 11, 'arorderlist.php', 1, 1);

#delete menupage (id, menufunctionid, name, orderflag, accessinv) values (188,149, 'invlmarkuplst.php', 1, 1);
#insert into menupage (id, menufunctionid, name, orderflag, accessinv) values (188, 149, 'invmarkuplist.php', 1, 1);
UPDATE `menupage` SET `name` = 'invmarkuplist.php' WHERE `id` = '188';

#ADD:
insert into menupage (id, menufunctionid, name, orderflag, accessinv) values (210, 39, 'invitemlstval.php', 1, 1);
INSERT INTO `menupage` VALUES (211, 154, 'invitemtransfer.php', 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `menufunction` VALUES (154, 5, 'Item Transfer', 'images/menu/page.gif', NULL, 'invitemtransfer.php', 12, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

INSERT INTO `menupage` VALUES (212, 155, 'invitemcompfunc.php', 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `menufunction` VALUES (155, 5, 'Composit Item Control', 'images/menu/page.gif', NULL, 'invitemcompfunc.php', 12, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

ALTER TABLE `gencompany` CHANGE `name` `name` CHAR( 80 ) NOT NULL
ALTER TABLE `company` CHANGE `companyname` `companyname` CHAR( 80 ) NOT NULL

CREATE TABLE `invponotes` (`invpoid` double NOT NULL , `note` text, `lastchangedate` timestamp(14) NOT NULL, `lastchangeuserid` double NOT NULL default '0', PRIMARY KEY  (`invpoid`), UNIQUE KEY `orderid` (`invpoid`)) TYPE=MyISAM;  
