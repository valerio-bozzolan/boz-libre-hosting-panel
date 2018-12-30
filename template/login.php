<?php
# Copyright (C) 2018 Valerio Bozzolan
# Reyboz another self-hosting panel project
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
 * This is the template for the login form
 */

// unuseful when load directly
defined( 'BOZ_PHP' ) || die;

/**
 * Print the password reset URL
 */
$password_reset_url = function () {
	echo http_build_get_query(
		ROOT . '/password-reset.php', [
			'user_uid' => @ $_REQUEST[ 'user_uid' ],
		]
	);
};
?>

	<?php if( ! is_logged() ): ?>
		<?php if( isset( $_POST[ 'user_uid' ] ) ): ?>
			<p class="alert alert-warning"><?php _e( "Authentication failed!" ) ?></p>
		<?php endif ?>
	<?php endif ?>

	<p><?php printf(
		__( "You need %s credentials to proceed." ),
		SITE_NAME
	) ?></p>

	<form method="post">
		<div class="form-group">
			<label for="user-uid"><?php _e( "Username" ) ?></label>
			<input type="text" class="form-control" name="user_uid" id="user-uid" placeholder="<?php _e( "foo.bar" ) ?>"<?php _value( @ $_REQUEST[ 'user_uid' ] ) ?> />
		</div>
		<div class="form-group">
			<label for="user-password"><?php _e( "Password" ) ?></label>
			<input type="password" class="form-control" name="user_password" id="user-password" />
		</div>

		<?php if( ! empty( $_POST[ 'user_uid' ] ) ): ?>
			<p><a href="<?php $password_reset_url() ?>"><?php _e( "Lost password?" ) ?></a></p>
		<?php endif ?>

		<button type="submit" class="btn btn-default"><?php _e( "Login" ) ?></button>
	</form>
