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

// calculate the remaining Mailbox quota percentage
$remaining_quota_percentage = null;
if( $plan ) {
	$remaining_quota_percentage = Plan::percentage(
		$mailbox->getMailboxLastSizeBytes(),
		$plan->getPlanMailboxQuota()
	);

	if( $remaining_quota_percentage ) {
		$remaining_quota_percentage = 100 - $remaining_quota_percentage;
	}
}
?>

	<h3><?= esc_html( __( "Stats" ) ) ?></h3>

	<?php if( $mailbox->getMailboxLastSizeBytes() !== null ): ?>

		<!-- start stats table -->
		<table class="table table-bordered table-responsive">

			<!-- start actual size -->
			<tr>
				<th><?= esc_html( __( "Current Size" ) ) ?></th>
				<td><?= human_filesize( $mailbox->getMailboxLastSizeBytes() ) ?></td>
			</tr>
			<!-- end actual size -->

			<!-- start max size allowed -->
			<?php if( $plan && $plan->getPlanMailboxQuota() ): ?>
			<tr>
				<th><?= esc_html( __( "Allowed Size" ) ) ?></th>
				<td><?= human_filesize( $mailbox->getPlanMailboxQuota() ) ?></td>
			</tr>
			<?php endif ?>
			<!-- endmax size allowed -->

			<!-- start quota ratio -->
			<?php if( $remaining_quota_percentage !== null ): ?>
			<tr>
				<th><?= esc_html( __( "Free" ) ) ?></th>
				<td><?= $remaining_quota_percentage ?> %</td>
			</tr>
			<?php endif ?>
			<!-- end quota ratio -->

		</table>
		<!-- end stats table -->

	<?php else: ?>

		<?= esc_html( __( "No stats available" ) ) ?>

	<?php endif ?>
