<?php
# Copyright (C) 2019, 2020 Valerio Bozzolan
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

	<!-- access instructions -->
	<?php if( $ftp ): ?>

		<?php template( 'ftp-access', [
			'domain' => $domain,
			'ftp'    => $ftp,
		] ) ?>
	<?php endif ?>
	<!-- /access instructions -->

	<!-- create/edit form -->
	<form method="post">
		<?php form_action( 'ftp-save' ) ?>

		<p>
			<label for="ftp-login"><?= __( "FTP login:") ?></label><br />
			<input type="text" id="ftp-login" name="ftp_login"<?php
				if( $ftp ) {
					echo value( $ftp->getFTPLogin() );
					echo " readonly";
				}
			?> />
		</p>

		<p>
			<label for="ftp-directory"><?= __( "Sub-Directory:") ?></label><br />
			<input type="text" id="ftp-directory" name="ftp_directory" placeholder="/"<?php
				if( $ftp ) {
					echo value( $ftp->getFTPRawDirectory() );
				}
			?> />
		</p>
		<p class="tip"><?= __( "Tip:" ) ?>
			<em><?= __( "You may want to change the default Sub-Directory to restrict this FTP user to a specific pathname." ) ?>
			    <?= __( "Anyway, you may break the login of this FTP user if you don't know what you are doing." ) ?>
			</em>
		</p>
		<p>
			<button type="submit" class="btn btn-default"><?=
				$ftp ? __( "Save"   )
				     : __( "Create" )
			?></button>
		</p>
	</form>
	<!-- /create/edit form -->

	<!-- other actions -->
	<?php if( $ftp ): ?>
		<h3><?= __( "Password" ) ?></h3>

		<!-- change password form -->
		<form method="post">
			<?php form_action( 'ftp-password-reset' ) ?>
			<?php if( $ftp_password ): ?>
				<label for="password"><?= __( "Please copy your new password:" ) ?><br />
				<input type="text" id="password" readonly<?= value( $ftp_password ) ?> />
			<?php else: ?>
				<p><button type="submit" class="btn btn-default"><?= __( "Generate new password" ) ?></button></p>
			<?php endif ?>
		</form>
		<!-- /change password form -->

		<h3><?= __( "Actions" ) ?></h3>

		<!-- delete form -->
		<form method="post">
			<?php form_action( 'ftp-delete' ) ?>
			<?= HTML::input( 'hidden', 'ftp_login', $ftp->getFTPLogin() ) ?>
			<p><button type="submit" class="btn btn-danger"><?= __( "Delete" ) ?></button></p>
		</form>
		<!-- /delete form -->

	<?php endif ?>
	<!-- /other actions -->
