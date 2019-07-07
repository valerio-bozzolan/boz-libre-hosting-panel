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

	<?php if( $ftp ): ?>

		<?php template( 'ftp-access', [
			'domain' => $domain,
			'ftp'    => $ftp,
		] ) ?>

		<!-- delete form -->
		<form method="post">
			<?php form_action( 'ftp-delete' ) ?>
			<?= HTML::input( 'hidden', 'ftp_login', $ftp->getFTPLogin() ) ?>
			<button type="submit" class="btn btn-danger"><?= __( "Delete" ) ?></button>
		</form>
		<!-- /delete form -->

	<?php else: ?>

		<!-- create form -->
		<form method="post">
			<?php form_action( 'ftp-save' ) ?>
			<p>
				<label for="ftp-login"><?= __( "FTP login:") ?></label><br />
				<input type="text" id="ftp-login" name="ftp_login" />
				<button type="submit" class="btn btn-default"><?= __( "Create" ) ?></button>
			</p>
		</form>
		<!-- /create form -->

	<?php endif ?>
