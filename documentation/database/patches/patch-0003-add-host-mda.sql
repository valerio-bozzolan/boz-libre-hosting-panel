CREATE TABLE `{$prefix}host` (
	`host_ID`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`host_ipv4`        INT UNSIGNED NOT NULL COMMENT 'main IPv4 stored with INET_ATON()',
	`host_hostname`    VARCHAR(128) NOT NULL COMMENT 'hostname',
	`host_description` TEXT,
	PRIMARY KEY (`host_ID`)
) ENGINE  = InnoDB
  COMMENT = 'a generic host';

INSERT INTO `{$prefix}host`
       (`host_ID`, `host_ipv4`,            `host_hostname`, `host_description`)
VALUES (NULL,      INET_ATON('127.0.0.1'), 'localhost',     'This is the default server.\r\n\r\nPlease set your absolute IP address and your hostname.');

CREATE TABLE `{$prefix}mta` (
	`mta_ID`  INT(10) unsigned NOT NULL AUTO_INCREMENT,
	`host_ID` INT(10) unsigned NOT NULL,
	PRIMARY KEY (`mta_ID`),
	KEY `host_ID` (`host_ID`),
	CONSTRAINT `mta_ibfk_1` FOREIGN KEY (`host_ID`) REFERENCES `{$prefix}host` (`host_ID`) ON DELETE CASCADE
) ENGINE  = InnoDB
  COMMENT = 'known mail delivery agents e.g. Postfix instances';

INSERT INTO `{$prefix}mta`
	( `mta_ID`, `host_ID` )
VALUES  ( NULL,     1         );

ALTER TABLE `{$prefix}domain`
	ADD        `mta_ID` INT UNSIGNED NULL AFTER `domain_parent`,
	ADD INDEX (`mta_ID`),
	ADD CONSTRAINT `domain_ibfk_mta` FOREIGN KEY (`mta_ID`) REFERENCES `{$prefix}mta`(`mta_ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;
