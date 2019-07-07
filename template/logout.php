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
 * This is the template for the logout page
 *
 * Called from
 * 	logout.php
 *
 * Parameters:
 * 	$please (boolean) If true, will show the "Please confirm logout" warning.
 */

// unuseful when load directly
defined( 'BOZ_PHP' ) or die;
?>

	<?php if( $please ): ?>
		<p class="alert alert-warning"><?= __( "Please manually confirm your action." ) ?></p>
	<?php endif ?>
	<form method="post" action="<?= ROOT ?>/logout.php">
		<?php form_action( 'logout' ) ?>
		<button type="submit" class="btn btn-default"><?= __( "Logout" ) ?></button>
	</form>
