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
 * This is the template for FTP instructions
 *
 * Called from:
 * 	ftp.php
 *
 * Available variables:
 * 	$domain Domain object
 * 	$ftp FTP object
 */

// unuseful when load directly
defined( 'BOZ_PHP' ) or die;
?>

	<p><?php _e( "To enter in your website with this FTP user you can copy this address into your file manager (then it should ask for the related password):" ) ?></p>

	<blockquote>
		<code>ftp://<?php
			_esc_html( $ftp->getFTPLogin() );
			echo '@';
			_esc_html( $domain->getDomainName() );
		?></code>
	</blockquote>
