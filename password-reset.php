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

require 'load.php';

/*
 * This is the password reset page
 */

// do something
if( is_action( 'password-reset' ) ) {
	if( is_logged() ) {
		send_email( __( "Password reset" ), $message, $to = false );
	} else {
		send_email( __( "Password reset" ), $message, $to = false );
	}
}

// spawn header
Header::spawn();
?>

	<form method="post">
		<?php if( ! is_logged() ): ?>
			<div class="form-group">
				<label for="user-uid"><?php _e( "Username" ) ?></label>
				<input type="text" class="form-control" name="user_uid" id="user-uid" placeholder="<?php _e( "foo.bar" ) ?>"<?php _value( @ $_REQUEST[ 'user_uid' ] ) ?> />
			</div>
		<?php endif ?>
		<button type="submit" class="btn btn-default" name="action" value="password-reset"><?php _e( "Proceed" ) ?></button>
	</form>

<?php
// spawn footer
Footer::spawn();
