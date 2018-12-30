<?php
# Copyright (C) 2018 Valerio Bozzolan
# Reyboz hosting panel - another self-hosting panel project
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
 * This is the homepage of your hosting panel
 */

if( isset( $_POST[ 'user_password' ] ) ) {
	login();
}

if( ! is_logged() ) {
	http_response_code( 401 );
}

// spawn header
Header::spawn( [
	'title' => __( "Home" ),
] );
?>

	<?php if( is_logged() ): ?>
		<?php template( 'dashboard' ) ?>
	<?php else: ?>
		<?php template( 'login' ) ?>
	<?php endif ?>

<?php
Footer::spawn();
