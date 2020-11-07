<?php
# Copyright (C) 2018, 2019, 2020 Valerio Bozzolan
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
 * This is the template for a mailbox
 *
 * Called from:
 * 	www/mailbox.php
 *
 * Available variables:
 * 	$mailbox object
 * 	$domain  object
 *      $plan    object
 * 	$mailbox_password string|null
 */

// unuseful when load directly
defined( 'BOZ_PHP' ) or die;
?>

	<!-- start description -->
	<?php template( 'mailbox-description', [
		'mailbox' => $mailbox,
	] ) ?>
	<!-- end description -->

	<!-- start stats -->
	<?php if( $mailbox ): ?>
		<?php template( 'mailbox-stats', [
			'mailbox' => $mailbox,
			'plan'    => $plan,
		] ) ?>
	<?php endif ?>
	<!-- end stats -->

	<!-- start documentation -->
	<?php if( $mailbox ): ?>

		<?php template( 'mailbox-documentation', [
			'mailbox' => $mailbox,
		] ) ?>

	<?php endif ?>
	<!-- end documentation -->

	<!-- notes -->
	<?php if( $mailbox ): ?>
		<?php template( 'mailbox-notes', [
			'mailbox' => $mailbox,
		] ) ?>
	<?php endif ?>
	<!-- /notes -->

	<?php if( $mailbox ): ?>
		<h3><?= __( "Actions" ) ?></h3>
		<form method="post">
			<?php form_action( 'mailbox-password-reset' ) ?>
			<?php if( $mailbox_password ): ?>
				<label for="password"><?= __( "Please copy your new password:" ) ?><br />
				<input type="text" id="password" readonly<?= value( $mailbox_password ) ?> />
			<?php else: ?>
				<p><button type="submit" class="btn btn-default"><?= __( "Generate new password" ) ?></button></p>
			<?php endif ?>
		</form>
	<?php else: ?>
		<form method="post">
			<?php form_action( 'mailbox-create' ) ?>
			<p><label for="mailbox-username"><?= __( "Mailbox name:" ) ?></label><br />
				<input type="text" name="mailbox_username" id="mailbox-username" length="64" /> @ <?= esc_html( $domain->getDomainName() ) ?>
			</p>
			<p><button type="submit" class="btn btn-default"><?= __( "Create" ) ?></button></p>
		</form>
	<?php endif ?>
	<!-- /actions -->

	<?php template( 'mailbox-delete-notes', [
		'mailbox' => $mailbox,
	] ) ?>

	<?php if( $mailbox ): ?>
	<section>
		<h3><?= __( "Last Activity" ) ?></h3>

		<?php
			// print the last activities
			ActivityPanel::spawn( [
				'query' => [
					'mailbox' => $mailbox,
				],
			] )
		?>
	</section>
	<?php endif ?>
