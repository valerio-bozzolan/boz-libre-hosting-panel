<?php
# Copyright (C) 2019, 2020 Valerio Bozzolan
# Suckless Libre Hosting Panel
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
 * This is the template to see Domain Plan info
 *
 * Called from:
 * 	www/domain-plan.php
 *        template/domain-plan-page.php
 *
 * Available variables:
 * 	$domain Domain object
 *      $plan   Plan object
 */

// unuseful when load directly
defined( 'BOZ_PHP' ) or die;
?>

	<!-- start overview -->

	<h3><?= esc_html( __( "Information" ) ) ?></h3>

	<table class="table table-responsive table-bordered">
		<tbody>
			<tr>
				<th><?= esc_html( __( "Domain" ) ) ?></th>
				<td><code><?= esc_html( $domain->getDomainName() ) ?></code></td>
			</tr>
			<tr>
				<th><?= esc_html( __( "Plan" ) ) ?></th>
				<td><?= esc_html( $plan->getPlanName() ) ?></td>
			</tr>
			<tr>
				<th><?= esc_html( __( "Mailboxes" ) ) ?></th>
				<td><?= $plan->getPlanMailboxes() ?></td>
			</tr>
			<tr>
				<th><?= esc_html( __( "Mail Forwardings" ) ) ?></th>
				<td><?= $plan->getPlanMailforwardings() ?></td>
			</tr>
			<tr>
				<th><?= esc_html( __( "FTP Users" ) ) ?></th>
				<td><?= $plan->getPlanFTPUsers() ?></td>
			</tr>
			<tr>
				<th><?= esc_html( __( "Yearly Price" ) ) ?></th>
				<td><?= $plan->getPlanYearlyPrice() ?> <?= DEFAULT_CURRENCY_SYMBOL ?></td>
			</tr>
		</tbody>
	</table>

	<!-- end overview -->
