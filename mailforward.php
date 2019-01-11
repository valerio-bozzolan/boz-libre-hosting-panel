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
 * This is the single e-mail forwarding edit page
 */

// load framework
require 'load.php';

// wanted informations
$domain     = null;
$mailforward = null;

// URL paramenters (maximum both domain and mailforward source, minimum just domain)
list( $domain_name, $mailforward_source ) = url_parts( 2, 1 );

// eventually retrieve mailforward from database
if( $mailforward_source ) {
	$mailforward = ( new MailforwardFullAPI )
		->select( [
			'domain.domain_ID',
			'domain.domain_name',
			'mailfoward_source',
			'mailfoward_destination',
		] )
		->whereDomainName( $domain_name )
		->whereMailforwardSource( $mailforward_source )
		->whereDomainIsEditable()
		->queryRow();

	// 404
	$mailforward or PageNotFound::spawn();

	// recycle the mailforward object that has domain informations
	$domain = $mailforward;
}

// eventually retrieve domain from database
if( ! $domain ) {
	$domain = ( new DomainAPI() )
		->select( [
			'domain.domain_ID',
			'domain.domain_name',
		] )
		->whereDomainName( $domain_name )
		->whereDomainIsEditable()
		->queryRow();

	// 404
	$domain or PageNotFound::spawn();
}

// save destination action
if( is_action( 'mailforward-save' ) ) {

	// columns to be saved
	$changes = [];

	// always require destination
	if( empty( $_POST[ 'mailforward_destination' ] ) ) {
		BadRequest::spawn( __( "missing parameter" ) );
	}

	// validate destination
	$destination = luser_input( $_POST[ 'mailforward_destination' ], 128 );
	if( filter_var( $destination, FILTER_VALIDATE_EMAIL ) ) {
		$changes[] = new DBCol( 'mailfoward_destination', $destination, 's' );
	} else {
		BadRequest::spawn( __( "fail e-mail validation" ) );
	}

	// save source only during creation
	if( ! $mailforward ) {

		// require source (can be empty)
		if( ! isset( $_POST[ 'mailforward_source' ] ) ) {
			BadRequest::spawn( __( "missing parameter" ) );
		}

		$source = luser_input( $_POST[ 'mailforward_source' ], 128 );
		if( ! empty( $source ) && ! validate_mailbox_username( $source ) ) {
			BadRequest::spawn( __( "invalid mailbox name" ) );
		}
		$changes[] = new DBCol( 'mailfoward_source', $source, 's' );
	}

	if( $changes ) {
		if( $mailforward ) {
			// update existing
			$mailforward->update( $changes );

			// POST/redirect/GET
			http_redirect( $mailforward->getMailforwardPermalink( true ), 303 );
		} else {
			// insert as new

			// check existence
			$mailforward_exists = ( new MailforwardAPI )
				->select( 1 )
				->whereDomain( $domain )
				->whereMailforwardSource( $source )
				->queryRow();

			// die if exists
			if( $mailforward_exists ) {
				BadRequest::spawn( __( "e-mail forwarding already existing" ) );
			}

			// insert as new row
			insert_row( 'mailfoward', array_merge( $changes, [
				new DBCol( 'domain_ID', $domain->getDomainID(), 'd' ),
			] ) );

			// POST/redirect/GET
			http_redirect( Mailforward::permalink(
				$domain->getDomainName(),
				$source,
				true
			), 303 );
		}
	}
}

// delete action
if( $mailforward && is_action( 'mailforward-delete' ) ) {
	query( sprintf(
		"DELETE FROM %s WHERE domain_ID = %d AND mailfoward_source = '%s'",
		T( 'mailfoward' ),
		$mailforward->getDomainID(),
		esc_html( $mailforward->getMailforwardSource() )
	) );

	// POST/redirect/GET
	http_redirect( $domain->getDomainPermalink( true ), 303 );
}

// spawn header
Header::spawn( [
	'title-prefix' => __( "E-mail forwarding" ),
	'title' => $mailforward
		? $mailforward->getMailforwardAddress()
		: __( "create" ),
	'breadcrumb' => [
		new MenuEntry( null, $domain->getDomainPermalink(), $domain->getDomainName() ),
	],
] );

// spawn the page content
template( 'mailforward', [
	'domain'     => $domain,
	'mailforward' => $mailforward,
] );

// spawn the footer
Footer::spawn();
