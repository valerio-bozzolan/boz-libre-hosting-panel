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
 * This is the single e-mail fowarding edit page
 */

// load framework
require 'load.php';

// wanted informations
$domain     = null;
$mailfoward = null;

// URL paramenters (maximum both domain and mailfoward source, minimum just domain)
list( $domain_name, $mailfoward_source ) = url_parts( 2, 1 );

// eventually retrieve mailfoward from database
if( $mailfoward_source ) {
	$mailfoward = ( new MailfowardFullAPI )
		->select( [
			'domain.domain_ID',
			'domain.domain_name',
			'mailfoward_source',
			'mailfoward_destination',
		] )
		->whereDomainName( $domain_name )
		->whereMailfowardSource( $mailfoward_source )
		->whereDomainIsEditable()
		->queryRow();

	// 404
	$mailfoward or PageNotFound::spawn();

	// recycle the mailfoward object that has domain informations
	$domain = $mailfoward;
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
if( is_action( 'mailfoward-save' ) ) {

	// columns to be saved
	$changes = [];

	// always require destination
	if( empty( $_POST[ 'mailfoward_destination' ] ) ) {
		BadRequest::spawn( __( "missing parameter" ) );
	}

	// validate destination
	$destination = luser_input( $_POST[ 'mailfoward_destination' ], 128 );
	if( filter_var( $destination, FILTER_VALIDATE_EMAIL ) ) {
		$changes[] = new DBCol( 'mailfoward_destination', $destination, 's' );
	} else {
		BadRequest::spawn( __( "fail e-mail validation" ) );
	}

	// save source only during creation
	if( ! $mailfoward ) {

		// require source (can be empty)
		if( ! isset( $_POST[ 'mailfoward_source' ] ) ) {
			BadRequest::spawn( __( "missing parameter" ) );
		}

		$source = luser_input( $_POST[ 'mailfoward_source' ], 128 );
		if( ! empty( $source ) && ! validate_mailbox_username( $source ) ) {
			BadRequest::spawn( __( "invalid mailbox name" ) );
		}
		$changes[] = new DBCol( 'mailfoward_source', $source, 's' );
	}

	if( $changes ) {
		if( $mailfoward ) {
			// update existing
			$mailfoward->update( $changes );

			// POST/redirect/GET
			http_redirect( $mailfoward->getMailfowardPermalink( true ), 303 );
		} else {
			// insert as new

			// check existence
			$mailfoward_exists = ( new MailfowardAPI )
				->select( 1 )
				->whereDomain( $domain )
				->whereMailfowardSource( $source )
				->queryRow();

			// die if exists
			if( $mailfoward_exists ) {
				BadRequest::spawn( __( "e-mail fowarding already existing" ) );
			}

			// insert as new row
			insert_row( 'mailfoward', array_merge( $changes, [
				new DBCol( 'domain_ID', $domain->getDomainID(), 'd' ),
			] ) );

			// POST/redirect/GET
			http_redirect( Mailfoward::permalink(
				$domain->getDomainName(),
				$source,
				true
			), 303 );
		}
	}
}

// delete action
if( $mailfoward && is_action( 'mailfoward-delete' ) ) {
	query( sprintf(
		"DELETE FROM %s WHERE domain_ID = %d AND mailfoward_source = '%s'",
		T( 'mailfoward' ),
		$mailfoward->getDomainID(),
		esc_html( $mailfoward->getMailfowardSource() )
	) );

	// POST/redirect/GET
	http_redirect( $domain->getDomainPermalink( true ), 303 );
}

// spawn header
Header::spawn( [
	'title-prefix' => __( "E-mail fowarding" ),
	'title' => $mailfoward
		? $mailfoward->getMailfowardAddress()
		: __( "create" ),
	'breadcrumb' => [
		new MenuEntry( null, $domain->getDomainPermalink(), $domain->getDomainName() ),
	],
] );

// spawn the page content
template( 'mailfoward', [
	'domain'     => $domain,
	'mailfoward' => $mailfoward,
] );

// spawn the footer
Footer::spawn();
