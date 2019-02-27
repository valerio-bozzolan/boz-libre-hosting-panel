<?php
# Copyright (C) 2019 Valerio Bozzolan
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
 * 	template/mailforward.php
 *
 * Available variables:
 * 	$domain Domain object
 * 	$mailforwardfrom Mailforward object
 */

// unuseful when load directly
defined( 'BOZ_PHP' ) or die;

$mailforwardto =
	( new MailforwardtoAPI() )
		->whereMailforwardfrom( $mailforwardfrom )
		->orderBy( 'mailforwardto_address' )
		->queryGenerator();
?>

<ul>
	<?php if( $mailforwardto->valid() ): ?>
		<?php foreach( $mailforwardto as $address ): ?>
			<li>
				<form method="post">
					<input type="email" name="address"<?php _value( $address->getMailforwardtoAddress() ) ?> readonly />
					<button type="submit" class="btn btn-warning" name="action" value="mailforwardto-remove"><?php _e( "Remove" ) ?></button>
				</form>
			</li>
		<?php endforeach ?>
	<?php endif ?>
	<li>
		<form method="post">
			<input type="email" id="mailforwardto-address" name="address" />
			<button type="submit" class="btn btn-default" name="action" value="mailforwardto-add"><?php _e( "Add" ) ?></button>
		</form>
	</li>
</ul>
