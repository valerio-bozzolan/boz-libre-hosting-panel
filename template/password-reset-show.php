<?php
# Copyright (C) 2020-2023 Valerio Bozzolan
# KISS Libre Hosting Panel
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
 * This is the template for an User
 *
 * Called from:
 * 	user.php
 *
 * Available variables:
 *	$new_password string|null
 */

// unuseful when load directly
defined( 'BOZ_PHP' ) or die;
?>

	<p class="alert alert-info">
		<!-- show the generated password -->
		<?= esc_html( __( "This Password was auto-magically generated for you:" ) ) ?><br />
		<input type="text" readonly<?= value( $new_password ) ?> />
	</p>

	<p><?= __( "Copy the above password, then Login Again." ) ?></p>

	<?php template( 'link', [
		'title' =>
			__( "Login Again" ),
		'url'  =>
			http_build_get_query(
				menu_entry( 'login' )->getURL(), [
					'user_uid' => $user->getUserUID(),
				]
			),
	] ) ?>
