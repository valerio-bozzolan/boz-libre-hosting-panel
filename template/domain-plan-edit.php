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
 * This is the template for the Domain Plan edit form
 *
 * Called from:
 * 	www/domain-plan.php
 *        template/domain-plan-page.php
 *
 * Available variables:
 * 	$domain Domain object
 *      $plan   Plan object
 */

// all the Plans
$plans = ( new PlanApi() )
	->queryGenerator();

// unuseful when load directly
defined( 'BOZ_PHP' ) or die;
?>

	<?php if( has_permission( 'edit-plan-all' ) ): ?>

		<!-- view for administrators -->

		<h3><?= esc_html( __( "Change" ) ) ?></h3>

		<!-- select Domain Plan form -->
		<form method="post" action="<?= esc_html( $domain->getDomainPlanPermalink() ) ?>">

			<?php form_action( 'domain-plan-save' ) ?>

			<!-- available Plans -->
			<p>
				<select name="plan_uid">
					<?php foreach( $plans as $available ): ?>
						<option<?=
							value( $available->getPlanUID() )
							.
							selected( $available->getPlanID(), $plan->getPlanID() )
						?>><?=
							// displayed label
							esc_html( $available->getPlanName() )
						?></option>
					<?php endforeach ?>
				</select>
			</p>
			<!-- end available Plans -->

			<!-- save btn -->
			<p>
				<button type="submit" class="btn btn-default"><?= esc_html( __( "Save" ) ) ?></button>
			</p>
			<!-- end save btn -->
		</form>
		<!-- end select Domain Plan form -->

		<!-- end view for administrators -->

	<?php endif ?>
