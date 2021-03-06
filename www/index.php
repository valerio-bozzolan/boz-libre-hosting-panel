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
 * This is the homepage of your hosting panel
 */

// load framework
require '../load.php';

// this page is not public
require_permission( 'backend' );

// spawn header
Header::spawn( [
	'breadcrumb' => false,
] );

// user domains
$domains = ( new DomainAPI() )
	->select( [
		'domain.domain_ID',
		'domain_name',
		'domain_active',
	] )
	->whereDomainIsEditable()
	->orderBy( 'domain_name' )
	->queryGenerator();
?>

	<p class="lead"><?php printf(
		__( "Welcome in your %s dashboard!" ),
		SITE_NAME
	) ?></p>

	<?php if( $domains->valid() ): ?>
		<h3><?php printf(
			__( "Your %s" ),
			__( "domains" )
		) ?></h3>
		<ul>
			<?php foreach( $domains as $domain ): ?>
			<li>
				<code>
				<?php if( $domain->domain_active ): ?>
					<?= HTML::a(
						$domain->getDomainPermalink(),
						$domain->domain_name
					) ?>
				<?php else: ?>
					<del><?= esc_html( $domain->domain_name ) ?></del>
				<?php endif ?>
				</code>
			</li>
			<?php endforeach ?>
		</ul>

		<?php if( has_permission( 'edit-domain-all' ) ): ?>
			<p><a class="btn btn-default" href="<?= ROOT ?>/domain.php"><?php echo __( "Add" ) ?></a></p>
		<?php endif ?>
	<?php endif ?>

	<!-- link to users -->
	<?php if( has_permission( 'edit-user-all' ) ): ?>
		<h3><?= HTML::a(
			menu_entry( 'user-list' )->getURL(),
			__( "Users" )
		) ?></h3>
	<?php endif ?>
	<!-- end link to users -->

	<!-- link to users activity -->
	<?php if( has_permission( 'monitor' ) ): ?>
		<h3><?= HTML::a(
			menu_entry( 'activity' )->getURL(),
			__( "Last Activity" )
		) ?></h3>
	<?php endif ?>
	<!-- end link to users activity -->

<?php

// spawn footer
Footer::spawn();
