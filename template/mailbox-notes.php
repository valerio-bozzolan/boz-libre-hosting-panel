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
 * This is the template for the mailbox notes input
 *
 * Called from:
 * 	template/mailbox.php
 *
 * Available variables:
 * 	$mailbox object
 */
?>

	<h3><?= __( "Notes" ) ?></h3>

	<form method="post">

		<!-- protect against CSRF attacks -->
		<?php form_action( 'save-mailbox-notes' ) ?>

		<div class="form-group">
			<textarea name="mailbox_description" class="form-control"><?php

				// check if there is a mailbox description
				if( $mailbox && $mailbox->getMailboxDescription() ) {

					// print the escaped textarea value
					echo esc_html( $mailbox->getMailboxDescription() );
				}


			?></textarea>
		</div>

		<p><button type="submit" class="btn btn-default"><?= __( "Save" ) ?></button></p>
	</form>
