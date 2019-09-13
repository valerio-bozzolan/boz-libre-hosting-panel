<?php
# Copyright (C) 2018, 2019 Valerio Bozzolan
# Boz Libre Hosting Panel
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
 * This is the template for a single e-mail forwarding
 *
 * Called from:
 * 	template/mailbox.php
 *
 * Variables:
 *  $mailbox object|null Mailbox
 */

// you can override from your 'load.php' configuration file your own instructions. For now, I just set my own one :)
// just define this constant with the 'define' standard function.
define_default( 'MAILBOX_INSTRUCTIONS', 'https://mail.reyboz.it/?&email=%s' );

// unuseful when load directly
defined( 'BOZ_PHP' ) or die;
?>

	<p><?= esc_html( __( "A mailbox is a fully operative e-mail address that you can use to send and receive e-mails. As interface you can use a webmail, an e-mail client like Thunderbird, Evolution, or a mobile app like K-9 Mail, etc." ) ) ?></p>

	<!-- mailbox instructions -->
	<?php if( $mailbox ): ?>
		<!-- TODO: create a dedicated template for this, to allow easy override of just this -->
		<p><?= HTML::a(
			sprintf(
				MAILBOX_INSTRUCTIONS,
				urlencode( $mailbox->getMailboxPermalink( true ) )
			),
			esc_html( __( "How to configure your e-mail client" ) )
		) ?></p>
	<?php endif ?>
	<!-- /mailbox instructions -->
