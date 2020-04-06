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
 * This is the template for some mailbox instructions
 *
 * Called from:
 * 	template/mailbox.php
 *
 * Available variables:
 * 	$mailbox object
 */

// do not load directly
defined( 'BOZ_PHP' ) or die;
?>
	<h3><?= __( "Documentation" ) ?></h3>
	<ul>
		<li>
			<a href="https://mail.reyboz.it?email=<?= urlencode( $mailbox->getMailboxAddress() ) ?>" target="_blank" />
				<?= __( "How to setup your IMAP/SMTP client" ) ?>
			</a>
		</li>
	</ul>
