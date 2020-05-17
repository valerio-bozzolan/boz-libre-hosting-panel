<?php
# Copyright (C) 2020 Valerio Bozzolan
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
 * This is the template for the Domain Activity
 *
 * Called from:
 * 	template/domain.php
 *
 * Available variables:
 * 	$domain         object|null
 */
?>

<?php if( $domain ): ?>

	<section>
		<h3><?= __( "Last Activity" ) ?></h3>

		<?php
			// print the last activities
			ActivityPanel::spawn( [
				'query' => [
					'domain' => $domain,
				],
			] )
		?>
	</section>

<?php endif ?>
