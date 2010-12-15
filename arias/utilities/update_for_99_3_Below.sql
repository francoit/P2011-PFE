#New Database

create table currency (
      id double NOT NULL auto_increment,
      countryname varchar(50) NOT NULL, 
      currencyname varchar(15) NOT NULL,
      currencysymbol char(3) NOT NULL,
      decimalplace double NOT NULL default 0,
      iso4217 char(3) NOT NULL,
      PRIMARY KEY(id));


#Currency

INSERT INTO `currency` VALUES ('1', 'United States of America', 'Dollars', '$', '2', 'USD');
INSERT INTO `currency` VALUES ('2', 'Malaysia', 'Ringgits', 'RM', '2', 'MYR');

#Invpodetail

ALTER TABLE `invpodetail` ADD `unitperpack` DOUBLE DEFAULT '1' NOT NULL AFTER `itemid` ;
ALTER TABLE `invpodetail` CHANGE `itemqty` `itemqty` DOUBLE NOT NULL ;
ALTER TABLE `invpodetail` CHANGE `itemprice` `itemprice` DOUBLE NOT NULL ;
 
ALTER TABLE `invpo` ADD `currencyid ` DOUBLE DEFAULT '0' NOT NULL AFTER `ordernumber` ;
ALTER TABLE `gencompany` ADD `currencyid` DOUBLE DEFAULT '0' NOT NULL AFTER `name` ;