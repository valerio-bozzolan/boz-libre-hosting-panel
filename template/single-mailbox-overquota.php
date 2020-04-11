<?php
# Copyright (C) 2018, 2019, 2020 Valerio Bozzolan
# KISS Libre Hosting Panel
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU Affero General Public License as
# published by the Free Software Foundation, either version 3 of the
# License, or (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU Affero General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program. If not, see <http://www.gnu.org/licenses/>.

/*
 * This is the template used for the sysadmin email, to say that a mailbox
 * is overquota.
 *
 * It's actually just one row about one single mailbox.
 *
 * Note that the email is considered in plaintext.
 *
 * Called from:
 * 	cli/update-mailbox-quotas.php
 *
 * Available variables:
 * 	$mailbox object
 * 	$domain  object
 *      $plan    object
 *      $size    int    Actual size of the mailbox in bytes
 */

$percentage = Plan::percentage( $size, $plan->getPlanMailboxQuota(), true );

$human_size = human_filesize( $size );

printf(
	// it should become something like:
	// "foo@example.com: 200MB (22%)"
	'%1$s@%2$s: %3$s (%4$s%%)',

	// %1$s
	$mailbox->getMailboxUsername(),

	// %2$s
	$domain->getDomainName(),

	// %3$s
	$human_size,

	// %4$s
	$percentage,
);
