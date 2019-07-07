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
 * This is the template for a bad request
 *
 * Called from:
 * 	include/class-BadRequest.php
 *
 * Available variables:
 * 	$message string
 */

// unuseful when load directly
defined( 'BOZ_PHP' ) or die;
?>

	<p class="alert alert-danger">
		<?= __( "Your request cannot be processed." ) ?>
		<?php if( $message ): ?>
			<br />
			<?php printf( __( "Error: %s. "), $message ) ?>
		<?php endif ?>
	</p>
	<p><?= __( "If you think that it's not your fault, contact us. Thanks!" ) ?></p>
