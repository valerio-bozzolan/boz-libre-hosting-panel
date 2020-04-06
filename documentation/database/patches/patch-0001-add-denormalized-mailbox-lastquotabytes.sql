RENAME TABLE `{$prefix}mailboxquota` TO  `{$prefix}mailboxsize`;
ALTER  TABLE `{$prefix}mailboxsize` CHANGE `mailboxquota_date`  `mailboxsize_date`  DATETIME NOT NULL;
ALTER  TABLE `{$prefix}mailboxsize` CHANGE `mailboxquota_bytes` `mailboxsize_bytes` INT(10) UNSIGNED NOT NULL;
ALTER  TABLE `{$prefix}mailboxsize` ADD    `mailboxsize_ID`                         INT(10) UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`mailboxsize_ID`);

