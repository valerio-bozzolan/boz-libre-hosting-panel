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
 * This is the single e-mail forwarding edit page
 */

// load framework
require 'load.php';

// wanted informations
$domain          = null;
$mailforwardfrom = null;

// URL paramenters (maximum both domain and mailforward source, minimum just domain)
list( $domain_name, $mailforwardfrom_username ) = url_parts( 2, 1 );

// eventually retrieve mailforward from database
if( $mailforwardfrom_username ) {
	$mailforwardfrom = ( new MailforwardfromAPI )
		->select( [
			'domain.domain_ID',
			'domain_name',
			'mailforwardfrom.mailforwardfrom_ID',
			'mailforwardfrom_username',
		] )
		->whereDomainName( $domain_name )
		->whereMailforwardfromUsername( $mailforwardfrom_username )
		->whereDomainIsEditable()
		->queryRow();

	// 404
	$mailforwardfrom or PageNotFound::spawn();

	// recycle the mailforward object that has domain informations
	$domain = $mailforwardfrom;
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
	$destination = require_email( $destination );
	$changes[] = new DBCol( 'mailforward_destination', $destination, 's' );

	// save source only during creation
	if( ! $mailforwardfrom ) {

		// require source (can be empty)
		if( ! isset( $_POST[ 'mailforward_source' ] ) ) {
			BadRequest::spawn( __( "missing parameter" ) );
		}

		$sources = luser_input( $_POST[ 'mailforward_source' ], 128 );
		$sources = explode( ',', $_POST[ 'mailforward_source' ] );
		foreach( $sources as $source ) {
			if( ! empty( $source ) && ! validate_mailbox_username( $source ) ) {
				BadRequest::spawn( __( "invalid mailbox name" ) );
			}
			$changes[] = new DBCol( 'mailforward_source', $source, 's' );
		}
	}

	if( $changes ) {
		if( $mailforwardfrom ) {
			// update existing
			$mailforwardfrom->update( $changes );

			// POST/redirect/GET
			http_redirect( $mailforwardfrom->getMailforwardPermalink( true ), 303 );
		} else {
			// insert as new

			// check existence
			$mailforwardfrom_exists = ( new MailforwardAPI )
				->select( 1 )
				->whereDomain( $domain )
				->whereMailforwardSource( $source )
				->queryRow();

			// die if exists
			if( $mailforwardfrom_exists ) {
				BadRequest::spawn( __( "e-mail forwarding already existing" ) );
			}

			// insert as new row
			insert_row( 'mailforward', array_merge( $changes, [
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
if( $mailforwardfrom ) {

	// action fired when deleting a whole mailforward
	if( is_action( 'mailforward-delete' ) ) {

		// drop th
		query( sprintf(
			"DELETE FROM %s WHERE domain_ID = %d AND mailforward_source = '%s'",
			T( 'mailforward' ),
			$mailforwardfrom->getDomainID(),
			esc_sql( $mailforwardfrom->getMailforwardSource() )
		) );

		// POST/redirect/GET
		http_redirect( $domain->getDomainPermalink( true ), 303 );

	}

	// action fired when adding/removing a mailforward
	if( ( is_action( 'mailforwardto-add' ) || is_action( 'mailforwardto-remove' ) ) && isset( $_POST[ 'address' ] ) ) {

		$address = require_email( $_POST[ 'address' ] );

		$existing_address =
			( new MailforwardtoAPI() )
				->whereMailforwardfrom( $mailforwardfrom )
				->whereMailforwardtoAddress( $address )
				->queryRow();

		// action fired when removing a mailforward
		if( is_action( 'mailforwardto-remove' ) && $existing_address ) {
			query( sprintf(
				"DELETE FROM %s WHERE mailforwardfrom_ID = %d and mailforwardto_address = '%s'",
				T( 'mailforwardto' ),
				$mailforwardfrom->getMailforwardfromID(),
				esc_sql( $address )
			) );
		}

		// action fired when adding a mailforward
		if( is_action( 'mailforwardto-add' ) && ! $existing_address ) {
			insert_row( 'mailforwardto', [
				new DBCol( 'mailforwardfrom_ID',    $mailforwardfrom->getMailforwardfromID(), 'd' ),
				new DBCol( 'mailforwardto_address', $address,                                 's' ),
			] );
		}
	}
}

// spawn header
Header::spawn( [
	'title-prefix' => __( "E-mail forwarding" ),
	'title' => $mailforwardfrom
		? $mailforwardfrom->getMailforwardfromAddress()
		: __( "create" ),
	'breadcrumb' => [
		new MenuEntry( null, $domain->getDomainPermalink(), $domain->getDomainName() ),
	],
] );

// spawn the page content
template( 'mailforward', [
	'domain'          => $domain,
	'mailforwardfrom' => $mailforwardfrom,
] );

// spawn the footer
Footer::spawn();
