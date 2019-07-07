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
 * This is the template for the website breadcrumb navigation menu
 *
 * Called from:
 * 	template/header.php
 */

// unuseful when load directly
defined( 'BOZ_PHP' ) or die;

// add home to the breadcrumb
array_unshift( $args[ 'breadcrumb' ], menu_entry( 'index' ) );

// add "this page" to the breadcrumb
if( $args[ 'uid' ] !== 'index' ) {
	$args[ 'breadcrumb' ][] = new MenuEntry( null, null, $args[ 'title' ] );
}

Breadcrumb::spawn( $args[ 'breadcrumb' ] );
