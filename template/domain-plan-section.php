<?php
# Copyright (C) 2019, 2020 Valerio Bozzolan
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
 * This is the template to see/change the Domain Plan
 *
 * Called from:
 * 	domain.php
 *
 * Available variables:
 * 	$domain Domain object
 *      $plan   Plan object
 */

// unuseful when load directly
defined( 'BOZ_PHP' ) or die;
?>
	<h3><?php printf(
		__( "Your %s" ),
		__( "Plan" )
	) ?></h3>

	<?php if( $plan->getPlanID() ): ?>

		<!-- has plan -->

		<?= the_link(
			$domain->getDomainPlanPermalink(),
			$plan->getPlanName()
		) ?>

		<!-- end has plan -->

	<?php else: ?>

		<!-- does not have plan -->

		<?php the_link(
			$domain->getDomainPlanPermalink(),
			__( "Assign" )
		) ?>

		<!-- end does not have plan -->

	<?php endif ?>
