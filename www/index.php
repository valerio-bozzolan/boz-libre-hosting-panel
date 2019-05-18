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
 * This is the homepage of your hosting panel
 */

// load framework
require '../load.php';

// require read permissions
require_permission( 'read' );

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
					<?php echo HTML::a(
						$domain->getDomainPermalink(),
						$domain->domain_name
					) ?>
				<?php else: ?>
					<del><?php _esc_html( $domain->domain_name ) ?></del>
				<?php endif ?>
				</code>
			</li>
			<?php endforeach ?>
		</ul>

		<?php if( has_permission( 'edit-domain-all' ) ): ?>
			<p><a class="btn btn-default" href="<?php echo ROOT ?>/domain.php"><?php _e( "Add" ) ?></a></p>
		<?php endif ?>
	<?php endif ?>

<?php

// spawn footer
Footer::spawn();
