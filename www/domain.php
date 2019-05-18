<?php
# Copyright (C) 2018, 2019 Valerio Bozzolan
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
 * This is the domain edit page
 */

// load framework
require '../load.php';

// wanted domain
list( $domain_name ) = url_parts( 1, 0 );

$domain = null;

if( $domain_name ) {
	// retrieve domain
	$domain = ( new DomainAPI() )
		->whereDomainName( $domain_name )
		->whereDomainIsEditable()
		->queryRow();

	// 404?
	$domain or PageNotFound::spawn();
} else {
	// try to create

	require_permission( 'edit-domain-all' );

	if( is_action( 'add-domain' ) && isset( $_POST[ 'domain_name' ] ) ) {
		$domain_name = luser_input( $_POST[ 'domain_name' ], 64 );

		// @TODO: check for duplicates

		insert_row( Domain::T, [
			new DBCol( 'domain_name',   $domain_name, 's' ),
			new DBCol( 'domain_active', 1,            'd' ),
			new DBCol( 'domain_born',  'NOW()',       '-' ),
		] );

		// POST -> redirect -> GET
		http_redirect( Domain::permalink( $domain_name, true ) );
	}
}

// spawn header
Header::spawn( [
	'title-prefix' => __( "Domain" ),
	'title' => $domain_name ? $domain_name : __( "Add" ),
] );

if( $domain ) {
	// spawn the domain template
	template( 'domain', [
		'domain' => $domain,
	] );
} else {
	// form to create the domain
	template( 'domain-create' );
}

// spawn the footer
Footer::spawn();
