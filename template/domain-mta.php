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
 * This is the template for the website Domain MTA form
 *
 * See:
 * 	https://gitpull.it/T340
 *
 * Called from:
 * 	domain-mta.php
 *
 * Available variables:
 * 	$domain object    Domain
 * 	$mta    object    MTA
 * 	$mtas   generator MTAs
 */
?>

<?php if( !$mta || !$mta->getMTAID() ): ?>

	<p><?= esc_html( __( "At the moment there is no MTA configured for this Domain." ) ) ?></p>

<?php endif ?>

<form method="post">

	<?php form_action( 'save-domain-mta' ) ?>

	<p><?= esc_html( sprintf(
		__( "Assign an MTA for the Domain \"%s:\"" ),
		$domain->getDomainName()
	) ) ?></p>

	<p>
	<select name="mta_ID">

		<option value="-"><?= esc_html( __( "n.d. (external)" ) ) ?></option>

		<?php foreach( $mtas as $available_mta ): ?>

			<?php
				$option = ( new HTML( 'option' ) )
					->setText(  $available_mta->getMTAName() )
					->setValue( $available_mta->getMTAID() );

				// eventually select
				if( $mta && $available_mta->getMTAID() === $mta->getMTAID() ) {
					$option->setAttr( 'selected', 'selected' );
				}

				echo $option->render();
			?>

		<?php endforeach ?>

	</select>
	</p>

	<p><button type="submit" class="btn btn-default"><?= __( "Save" ) ?></button></p>

</form>
