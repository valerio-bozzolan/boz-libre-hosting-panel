<?php
# Copyright (C) 2018 Valerio Bozzolan
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
 * This is the template for a mailbox
 *
 * Called from:
 * 	mailbox.php
 */

// unuseful when load directly
defined( 'BOZ_PHP' ) or die;
?>

<?php template( 'mailbox-description' ) ?>

<h3><?php _e( "Resources" ) ?></h3>
<ul>
	<li>
		<a href="https://mail.reyboz.it?email=<?php echo urlencode( $mailbox->getMailboxAddress() ) ?>" target="_blank" />
			<?php _e( "How to setup your IMAP/SMTP client" ) ?>
		</a>
	</li>
</ul>

<h3><?php _e( "Actions" ) ?></h3>
<form method="post">
	<?php if( $password ): ?>
		<label for="password"><?php _e( "Please copy your new password:" ) ?><br />
		<input type="text" id="password" readonly<?php _value( $password ) ?> />
	<?php else: ?>
		<p><button type="submit" class="btn btn-default" name="action" value="mailbox-password-reset"><?php _e( "Generate new password" ) ?></button></p>
	<?php endif ?>
</form>
