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
 * This is the template for the website footer
 */

// unuseful when load directly
defined( 'BOZ_PHP' ) or die;

load_module( 'footer' );
?>

	<?php if( $args[ 'container' ] ): ?>
		</div>
	<?php endif ?>

	<hr />
	<div class="container">
		<p>
			<?php _e( "Help:" ) ?><br />
			<?php email_blur( CONTACT_EMAIL ) ?>
		</p>
	</div>
</body>
</html>
