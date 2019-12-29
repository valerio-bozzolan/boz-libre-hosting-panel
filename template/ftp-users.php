<?php
# Copyright (C) 2019 Valerio Bozzolan
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
 * This is the template for the FTP user list
 *
 * Called from:
 * 	domain.php
 *
 * Available variables:
 * 	$domain Domain object
 */

// unuseful when load directly
defined( 'BOZ_PHP' ) or die;

// domain FTP users
$ftps = $domain->factoryFTP()
	->select( [
		'ftp_login',
	] )
	->queryGenerator();
?>
	<!-- FTP users -->
	<h3><?php printf(
		__( "Your %s" ),
		__( "FTP users" )
	) ?></h3>
	<?php template( 'ftp-description' ) ?>

	<?php if( $ftps->valid() ): ?>
		<ul>
			<?php foreach( $ftps as $ftp ): ?>
				<li>
					<code><?= HTML::a(
						FTP::permalink(
							$domain->getDomainName(),
							$ftp->getFTPLogin()
						),
						$ftp->getFTPLogin()
					) ?></code>
				</li>
			<?php endforeach ?>
		</ul>
	<?php else: ?>
		<p><?= __( "None yet.") ?></p>
	<?php endif ?>

	<?php if( $domain->canCreateFTPAccountForDomain() ): ?>
		<p><?php the_link(
			FTP::permalink( $domain->getDomainName() ),
			__( "Create" )
		) ?></p>
	<?php endif ?>

	<!-- end FTP users -->
