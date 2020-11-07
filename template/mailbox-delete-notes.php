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
 * This is the template for a mailbox
 *
 * Called from:
 * 	www/mailbox.php
 *
 * Available variables:
 * 	$mailbox object
 * 	$domain  object
 *      $plan    object
 * 	$mailbox_password string|null
 */
?>

<h3><?= __( "Deletion" ) ?></h3>
<p><?= __( "For security reasons you cannot drop your mailbox from a web interface." ) ?></p>
<p><?= __( "Only system administrators with access to the command line can proceed, issuing this command:" ) ?>
<pre><?= sprintf(
	"./cli/destroy-mailbox %s",
	$mailbox->getMailboxAddress()
) ?></pre>
