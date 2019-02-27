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
 * This is the template for a single e-mail forwarding
 *
 * Called from:
 * 	mailforward.php
 *
 * Available variables:
 * 	$domain Domain object
 * 	$mailforwardfrom Mailforward object
 */

// unuseful when load directly
defined( 'BOZ_PHP' ) or die;
?>

	<?php template( 'mailforward-description' ) ?>

	<form method="post">
		<p>
			<label for="mailforward-source"><?php _e( "E-mail address:") ?></label><br />
			<?php if( $mailforwardfrom ): ?>
				<input type="text" id="mailforward-address" readonly<?php _value( $mailforwardfrom->getMailforwardfromAddress() ) ?> />
			<?php else: ?>
				<input type="string" name="mailforwardfrom_username" id="mailforwardfrom-username"<?php
					if( $mailforwardfrom ) {
						_value( $mailforwardfrom->getMailforwardfromUsername() );
					}
				?> /> <code>@<?php _esc_html( $domain->getDomainName() ) ?></code>
			<?php endif ?>

			<?php if( $mailforwardfrom ): ?>
				<button type="submit" class="btn btn-danger" name="action" value="mailforward-delete"><?php _e( "Delete" ) ?></button>
			<?php else: ?>
				<button type="submit" class="btn btn-default" name="action" value="mailforward-save"><?php _e( "Create" ) ?></button>
			<?php endif ?>
		</p>
	</form>

	<?php if( $mailforwardfrom ): ?>
		<p><?php _e( "Forward the incoming e-mails to these destinations:" ) ?></label></p>
		<?php template( 'mailforwardto', [
			'domain'          => $domain,
			'mailforwardfrom' => $mailforwardfrom,
		] ) ?>
	<?php endif ?>
