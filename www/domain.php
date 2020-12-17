<?php
# Copyright (C) 2018, 2019, 2020 Valerio Bozzolan
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

// this page is not public
require_permission( 'backend' );

// wanted domain
list( $domain_name ) = url_parts( 1, 0 );

$domain = null;

if( $domain_name ) {
	// retrieve domain
	$domain = ( new DomainAPI() )
		->whereDomainName( $domain_name )
		->whereDomainIsEditable()
		->joinPlan( 'LEFT' )
		->queryRow();

	// 404?
	$domain or PageNotFound::spawn();
} else {
	// try to create

	require_permission( 'edit-domain-all' );

	if( is_action( 'add-domain' ) && isset( $_POST[ 'domain_name' ] ) ) {

		// trim and normalize to max length
		$domain_name = luser_input( $_POST[ 'domain_name' ], 64 );

		// existing domain
		$existing = ( new DomainAPI() )
			->whereDomainName( $domain_name )
			->queryRow();

		// go to the existing one
		if( $existing ) {
			http_redirect( $existing->getDomainPermalink() );
		}

		query( 'START TRANSACTION' );

		// insert this new domain
		insert_row( Domain::T, [
			new DBCol( 'domain_name',   $domain_name, 's' ),
			new DBCol( 'domain_active', 1,            'd' ),
			new DBCol( 'domain_born',  'NOW()',       '-' ),
		] );

		// this Domain ID
		$domain_ID = last_inserted_ID();

		// add this event in the log
		APILog::insert( [
			'family' => 'domain',
			'action' => 'create',
			'domain' => $domain_ID,
		] );

		query( 'COMMIT' );

		// go to the new domain
		http_redirect( Domain::permalink( $domain_name, true ) );
	}
}

// spawn header
Header::spawn( [
	'uid' => false,
	'title-prefix' => __( "Domain" ),
	'title' => $domain_name ? $domain_name : __( "Add" ),
] );

if( $domain ) {
	// spawn the domain template
	template( 'domain', [
		'domain' => $domain,
		'plan'   => $domain,
	] );
} else {
	// form to create the domain
	template( 'domain-create' );
}

// spawn the footer
Footer::spawn();
