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
 * This is the template for an user profile
 *
 * Called from:
 * 	profile.php
 *
 * Available variables:
 * 	$email string
 */

// unuseful when load directly
defined( 'BOZ_PHP' ) or die;
?>

<h3><?php _e( "Change password" ) ?></h3>
<form method="post">
	<p><?php printf(
		__( "You can generate a new strong password that will be sent to your e-mail address (%s). Note that this action will also logout you immediately." ),
		esc_html( $email )
	) ?></p>
	<p><button type="submit" class="btn btn-default" name="action" value="send-user-password"><?php _e( "Generate new password" ) ?></button></p>
</form>

<h3><?php _e( "Logout" ) ?></h3>
<p><?php _e( "Have you done what you had to do? Then you can (and should) logout now." ) ?></p>
<p><?php the_menu_link( 'logout' ) ?></p>
