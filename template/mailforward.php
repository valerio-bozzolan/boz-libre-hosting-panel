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
 * 	$mailforward Mailforward object
 */

// unuseful when load directly
defined( 'BOZ_PHP' ) or die;
?>

<?php template( 'mailforward-description' ) ?>

<form method="post">
	<p>
		<label for="mailforward-source"><?php _e( "E-mail address:") ?></label><br />
		<?php if( $mailforward ): ?>
			<input type="text" id="mailforward-source" readonly<?php _value( $mailforward->getMailforwardAddress() ) ?> />
		<?php else: ?>
			<input type="string" name="mailforward_source" id="mailforward-source"<?php
				if( $mailforward ) {
					_value( $mailforward->getMailforwardSource() );
				}
			?> /> <code>@<?php _esc_html( $domain->getDomainName() ) ?></code>
		<?php endif ?>
	</p>
	<p>
		<label for="mailforward-destination"><?php _e( "forward the incoming e-mails to this destination:" ) ?></label><br />
		<input type="email" name="mailforward_destination" id="mailforward-destination"<?php
			if( $mailforward ) {
				_value( $mailforward->getMailforwardDestination() );
			}
		?> />
	</p>
	<p>
		<button type="submit" class="btn btn-default" name="action" value="mailforward-save"><?php _e( "Save" ) ?></button>
		<?php if( $mailforward ): ?>
			<button type="submit" class="btn btn-warning" name="action" value="mailforward-delete"><?php _e( "Delete" ) ?></button>
		<?php endif ?>
	</p>
</form>

<!-- end change destination -->
