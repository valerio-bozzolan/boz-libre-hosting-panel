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
 * This is the template for some mailbox stats
 *
 * Called from:
 * 	template/mailbox.php
 *
 * Available variables:
 * 	$mailbox object
 *      $plan    object
 */

// avoid to be load directly
defined( 'BOZ_PHP' ) or die;

?>

	<h3><?= esc_html( __( "Stats" ) ) ?></h3>

	<?php if( $mailbox->getMailboxLastSizeBytes() !== null ): ?>

		<table class="table table-bordered table-responsive">
			<tr>
				<th><?= esc_html( __( "Size" ) ) ?></th>
				<td><?= human_filesize( $mailbox->getMailboxLastSizeBytes() ) ?></td>
			</tr>
		</table>

		<!--
			TODO: show Plan max size
			https://gitpull.it/T285
		-->

	<?php else: ?>

		<?= esc_html( __( "No stats available" ) ) ?>

	<?php endif ?>
