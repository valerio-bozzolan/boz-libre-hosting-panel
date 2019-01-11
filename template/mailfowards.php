<?php
# Copyright (C) 2018 Valerio Bozzolan
# Boz Libre Hosting Panel
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
 */

// unuseful when load directly
defined( 'BOZ_PHP' ) or die;

// domain mail fowardings
$mailfowards = $domain->factoryMailfoward()
	->select( [
		'domain_name',
		'mailfoward_source',
		'mailfoward_destination',
	] )
	->queryGenerator();
?>
	<!-- mail fowardings -->
	<?php if( $mailfowards->valid() ): ?>
		<h3><?php printf(
			__( "Your %s" ),
			__( "mail fowardings" )
		) ?></h3>

		<?php template( 'mailfoward-description' ) ?>

		<ul>
			<?php foreach( $mailfowards as $mailfoward ): ?>
				<li>
					<code><?php echo HTML::a(
						$mailfoward->getMailfowardPermalink(),
						$mailfoward->getMailfowardAddress()
					) ?></code>
				</li>
			<?php endforeach ?>
		</ul>
	<?php endif ?>

	<p><?php the_link(
		Mailfoward::permalink( $domain->getDomainName() ),
		__( "Create" )
	) ?></p>
	<!-- end mail fowardings -->
