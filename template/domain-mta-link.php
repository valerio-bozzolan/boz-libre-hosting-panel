<?php
# Copyright (C) 2018, 2019, 2020, 2021 Valerio Bozzolan
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
 * This is the template for the website Domain MTA link
 *
 * See:
 * 	https://gitpull.it/T340
 *
 * Called from:
 * 	domain.php
 *
 * Available variables:
 * 	$domain object    Domain
 */
?>

<h3><?= __( "MTA" ) ?></h3>

<?php if( !$domain->getMTAID() ): ?>

	<p><?= esc_html( __( "At the moment there is no MTA configured for this Domain." ) ) ?></p>

<?php endif ?>

<?php the_link( MTA::domainPermalink( $domain->getDomainName() ), __( "MTA" ) ) ?>
