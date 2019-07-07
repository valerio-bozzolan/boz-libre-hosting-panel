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
 * This is the login page
 */

// load framework
require '../load.php';

// spawn header
Header::spawn();

// go to the wanted page (or homepage)
if( login() ) {
	http_redirect( after_login_url(), 307 );
}
?>

	<?php if( isset( $_POST[ 'user_uid' ] ) ): ?>
		<p class="alert alert-warning"><?= __( "Authentication failed!" ) ?></p>
	<?php endif ?>

	<p><?php printf(
		__( "You need %s credentials to proceed." ),
		SITE_NAME
	) ?></p>

	<form method="post">
		<div class="form-group">
			<label for="user-uid"><?= __( "Username" ) ?></label>
			<input type="text" class="form-control" name="user_uid" id="user-uid" placeholder="<?= __( "foo.bar" ) ?>"<?php echo value( @ $_REQUEST[ 'user_uid' ] ) ?> />
		</div>
		<div class="form-group">
			<label for="user-password"><?= __( "Password" ) ?></label>
			<input type="password" class="form-control" name="user_password" id="user-password" />
		</div>

		<?php if( ! empty( $_POST[ 'user_uid' ] ) ): ?>
			<p><a href="<?= http_build_get_query(
				menu_entry( 'password-reset' )->getURL(), [
				'user_uid' => @ $_REQUEST[ 'user_uid' ],
			] ) ?>"><?= __( "Lost password?" ) ?></a></p>
		<?php endif ?>

		<button type="submit" class="btn btn-default"><?= __( "Login" ) ?></button>
	</form>

<?php
// spawn footer
Footer::spawn();

/**
 * Return the back URL to be redirected after the login action
 */
function after_login_URL() {
	if( isset( $_GET[ 'redirect' ] ) && 0 === strpos( $_GET[ 'redirect' ], '/' ) ) {
		return site_page( $_GET[ 'redirect' ], URL );
	}
	return menu_entry( 'index' )
		->getSitePage( URL );
}
