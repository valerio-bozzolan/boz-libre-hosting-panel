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
 * This is the template for a single FTP account
 *
 * Called from:
 * 	ftp.php
 *
 * Available variables:
 * 	$domain Domain object
 * 	$ftp FTP object
 */

// unuseful when load directly
defined( 'BOZ_PHP' ) or die;
?>

	<?php template( 'ftp-description' ) ?>

	<?php if( $ftp ) {
		template( 'ftp-access', [
			'domain' => $domain,
			'ftp'    => $ftp,
		] );
	} ?>

	<form method="post">
		<p>
			<?php if( $ftp ): ?>
				<input type="hidden" readonly<?php _value( $ftp->getFTPLogin() ) ?> />
			<?php else: ?>
				<label for="ftp-login"><?php _e( "FTP login:") ?></label><br />
				<input type="text" id="ftp-login" name="ftp_login" />
			<?php endif ?>

			<?php if( $ftp ): ?>
				<button type="submit" class="btn btn-danger" name="action" value="ftp-delete"><?php _e( "Delete" ) ?></button>
			<?php else: ?>
				<button type="submit" class="btn btn-default" name="action" value="ftp-save"><?php _e( "Create" ) ?></button>
			<?php endif ?>
		</p>
	</form>
