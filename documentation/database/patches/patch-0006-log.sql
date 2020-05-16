CREATE TABLE `{$prefix}log` (
	`log_ID`             INT(10)     UNSIGNED NOT NULL AUTO_INCREMENT,
	`log_family`         VARCHAR(32)          NOT NULL COMMENT 'What was this action about: mailbox, mailforward, domain.privilege.kick ecc.',
	`log_action`         VARCHAR(32)          NOT NULL COMMENT 'What was the exact action: create, delete, change.password, ecc.',
	`log_timestamp`      TIMESTAMP            NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'When the action was registered',
	`actor_ID`           INT(10)     UNSIGNED NOT NULL COMMENT 'The user that has done the action',
	`marionette_ID`      INT(10)     UNSIGNED     NULL COMMENT 'The user that was touched by the actor',
	`mailforwardfrom_ID` INT(10)     UNSIGNED     NULL COMMENT 'The email forwarding touched by the actor',
	`mailbox_ID`         INT(10)     UNSIGNED     NULL COMMENT 'The mailbox touched by the actor',
	`domain_ID`          INT(10)     UNSIGNED     NULL COMMENT 'The domain touched by the actor',
	`plan_ID`            INT(10)     UNSIGNED     NULL COMMENT 'The plan touched by the actor',
	PRIMARY KEY ( `log_ID` ),
	KEY `idx_timestamp_actor_family` (`log_timestamp`,`actor_ID`,`log_family`),
	CONSTRAINT `fk-{$prefix}log-actor`           FOREIGN KEY ( `actor_ID`           ) REFERENCES `{$prefix}user`(`user_ID`),
	CONSTRAINT `fk-{$prefix}log-marionette`      FOREIGN KEY ( `marionette_ID`      ) REFERENCES `{$prefix}user`(`user_ID`),
	CONSTRAINT `fk-{$prefix}log-mailforwardfrom` FOREIGN KEY ( `mailforwardfrom_ID` ) REFERENCES `{$prefix}mailforwardfrom`(`mailforwardfrom_ID`),
	CONSTRAINT `fk-{$prefix}log-mailbox`         FOREIGN KEY ( `mailbox_ID`         ) REFERENCES `{$prefix}mailbox`(`mailbox_ID`),
	CONSTRAINT `fk-{$prefix}log-plan`            FOREIGN KEY ( `plan_ID`            ) REFERENCES `{$prefix}plan`(`plan_ID`)
);
