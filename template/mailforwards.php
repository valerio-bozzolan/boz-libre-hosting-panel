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
 * This is the template for the mailboxes list
 *
 * Called from:
 * 	template/domain.php
 *
 * Available variables:
 * 	$domain object
 */

// unuseful when load directly
defined( 'BOZ_PHP' ) or die;

// domain mail forwardings
$mailforwardfroms = $domain->factoryMailforwardfrom()
	->joinDomain()
	->select( [
		'mailforwardfrom.mailforwardfrom_ID',
		'mailforwardfrom_username',
		'domain_name',
	] )
	->queryGenerator();
?>
	<!-- mail forwardings -->
	<h3><?php printf(
		__( "Your %s" ),
		__( "mail forwardings" )
	) ?></h3>
	<?php template( 'mailforward-description' ) ?>

	<?php if( $mailforwardfroms->valid() ): ?>
		<ul>
			<?php foreach( $mailforwardfroms as $mailforwardfrom ): ?>
				<li>
					<code><?= $mailforwardfrom->getMailforwardfromFirm() ?></code>
				</li>
			<?php endforeach ?>
		</ul>
	<?php else: ?>
		<p><?= __( "None yet.") ?></p>
	<?php endif ?>

	<p><?= esc_html( sprintf(
		__( "Your Plan \"%s\" allows %s %s." ),
		$plan->getPlanName(),
		$plan->getPlanMailForwardings(),
		__( "Forwarding" )
	) ) ?></p>

	<p><?php the_link(
		Mailforwardfrom::permalink( $domain->getDomainName() ),
		__( "Create" )
	) ?></p>
	<!-- end mail forwardings -->
