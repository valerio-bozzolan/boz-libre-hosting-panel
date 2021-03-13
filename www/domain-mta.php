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
 * This is the page where you can assign an MTA
 * to a Domain.
 *
 * See:
 *   https://gitpull.it/T340
 */

// load framework
require '../load.php';

// this page is not public
require_permission( 'edit-mta-all' );

// require the Domain name
list( $domain_name ) = url_parts( 1 );

// retrieve the Domain
$domain = ( new DomainAPI() )
	->whereDomainName( $domain_name )
	->whereDomainIsEditable()
	->joinPlan( 'LEFT' )
	->queryRow();

// no Domain no party
if( !$domain ) {
	PageNotFound::spawn();
}

// this page contains a form
require_csrf();

// fetch Mail Transfer Agents
$mtas = ( new MTAAPI() )
	->joinHost()
	->queryGenerator();

// eventually save the MTA
if( is_action( 'save-domain-mta' ) ) {

	// receive the picked MTA
	$mta_ID = $_POST[ 'mta_ID'] ?? null;

	if( $mta_ID === '-' ) {
		$mta_ID = null;
	} else {
		$mta_ID = (int) $mta_ID;
	}

	// no MTA no party
	if( $mta_ID !== null && !$mta_ID ) {
		BadRequest::spawn();
	}

	query( 'START TRANSACTION' );

	if( $mta_ID !== null ) {

		// check if it exists
		$mta = ( new MTAAPI() )
			->whereMTAID( $mta_ID )
			->forUpdate()
			->queryRow();

		// no MTA no party
		if( !$mta ) {
			// rollback the change (no change)
			query( 'ROLLBACK' );

			BadRequest::spawn( "missing MTA" );
		}

		$mta_ID = $mta->getMTAID();
	}

	// update the Domain
	( new DomainAPI() )
		->whereDomain( $domain )
		->update( [
			// this can be numeric or NULL
			'mta_ID' => $mta_ID,
		] );

	// really save in the database
	query( 'COMMIT' );

	// POST -> redirect -> GET
	http_redirect( $_SERVER['REQUEST_URI'] );
}

// spawn header
Header::spawn( [
	'uid' => false,
	'title' => __( "MTA" ),
	'breadcrumb' => [
		new MenuEntry( null, $domain->getDomainPermalink(), $domain->getDomainName() ),
	],
] );

// spawn the Domain-MTA template
template( 'domain-mta', [
	'domain' => $domain,
	'plan'   => $domain,
	'mta'    => $domain,
	'mtas'   => $mtas,
] );

Footer::spawn();
