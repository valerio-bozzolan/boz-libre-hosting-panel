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
 */

// unuseful when load directly
defined( 'BOZ_PHP' ) or die;
?>

<?php template( 'mailfoward-description' ) ?>

<h3><?php _e( "Destination" ) ?></h3>
<form method="post">
	<p>
		<label for="mailfoward-destination"><?php printf(
			__( "Your incoming e-mails from %s will be fowarded to %s. Here you can change this destination:" ),
			esc_html( $mailfoward->getMailfowardAddress() ),
			esc_html( $mailfoward->getMailfowardDestination() )
		) ?></label>
		<br />
		<input type="email" name="mailfoward_destination" id="mailfoward-destination"<?php _value( $mailfoward->getMailfowardDestination() ) ?> />
	</p>
	<p><button type="submit" class="btn btn-default" name="action" value="mailfoward-save-destination"><?php _e( "Save" ) ?></button></p>
</form>
