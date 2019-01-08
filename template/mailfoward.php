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
 * This is the template for a single e-mail fowarding
 *
 * Called from:
 * 	mailfoward.php
 *
 * Available variables:
 * 	$domain Domain object
 * 	$mailfoward Mailfoward object
 */

// unuseful when load directly
defined( 'BOZ_PHP' ) or die;
?>

<?php template( 'mailfoward-description' ) ?>

<form method="post">
	<p>
		<label for="mailfoward-source"><?php _e( "E-mail address:") ?></label><br />
		<?php if( $mailfoward ): ?>
			<input type="text" id="mailfoward-source" readonly<?php _value( $mailfoward->getMailfowardAddress() ) ?> />
		<?php else: ?>
			<input type="string" name="mailfoward_source" id="mailfoward-source"<?php
				if( $mailfoward ) {
					_value( $mailfoward->getMailfowardSource() );
				}
			?> /> <code>@<?php _esc_html( $domain->getDomainName() ) ?></code>
		<?php endif ?>
	</p>
	<p>
		<label for="mailfoward-destination"><?php _e( "Foward the incoming e-mails to this destination:" ) ?></label><br />
		<input type="email" name="mailfoward_destination" id="mailfoward-destination"<?php
			if( $mailfoward ) {
				_value( $mailfoward->getMailfowardDestination() );
			}
		?> />
	</p>
	<p><button type="submit" class="btn btn-default" name="action" value="mailfoward-save"><?php _e( "Save" ) ?></button></p>
</form>

<!-- end change destination -->
