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
 * This is the single FTP account creation/edit page
 */

// load framework
require '../load.php';

// wanted informations
$domain = null;
$ftp    = null;

// URL paramenters (maximum both domain and FTP login, minimum just domain)
list( $domain_name, $ftp_login ) = url_parts( 2, 1 );

// eventually retrieve mailforward from database
if( $ftp_login ) {
	$ftp = ( new FTPAPI() )
		->select( [
			'domain.domain_ID',
			'domain_name',
			'ftp_login',
		] )
		->joinFTPDomain()
		->whereDomainName( $domain_name )
		->whereFTPLogin( $ftp_login )
		->whereDomainIsEditable()
		->queryRow();

	// 404
	$ftp or PageNotFound::spawn();

	// recycle the mailforward object that has domain informations
	$domain = $ftp;
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

if( ! $ftp ) {
	// to create an FTP user, must edit all FTP users
	require_permission( 'edit-ftp-all' );
}

// save destination action
if( is_action( 'ftp-save' ) ) {

	// save source only during creation
	if( ! $ftp ) {

		// sanitize
		if( ! isset( $_POST[ 'ftp_login' ] ) ) {
			BadRequest::spawn( __( "missing parameter" ) );
		}
		$username = luser_input( $_POST[ 'ftp_login' ], 128 );
		if( ! validate_mailbox_username( $username ) ) {
			BadRequest::spawn( __( "invalid mailbox name" ) );
		}

		// check existence
		$ftp_exists = ( new MailforwardfromAPI )
			->select( 1 )
			->whereDomain( $domain )
			->whereFTPLogin( $source )
			->queryRow();

		// die if exists
		if( $ftp_exists ) {
			BadRequest::spawn( __( "e-mail forwarding already existing" ) );
		}

		// insert as new row
		insert_row( 'ftp', [
			new DBCol( 'domain_ID',                $domain->getDomainID(), 'd' ),
			new DBCol( 'ftp_login', $username,              's' ),
		] );

		// POST/redirect/GET
		http_redirect( Mailforwardfrom::permalink(
			$domain->getDomainName(),
			$username,
			true
		), 303 );
	}
}

// delete action
if( $ftp ) {

	// action fired when deleting a whole mailforward
	if( is_action( 'ftp-delete' ) ) {

		// drop th
		query( sprintf(
			"DELETE FROM %s WHERE domain_ID = %d AND ftp_login = '%s'",
			T( 'ftp' ),
			$ftp->getDomainID(),
			$ftp->getFTPLogin()
		) );

		// POST/redirect/GET
		http_redirect( $domain->getDomainPermalink( true ), 303 );

	}

}

// spawn header
Header::spawn( [
	'title-prefix' => __( "FTP user" ),
	'title' => $ftp
		? $ftp->getFTPLogin()
		: __( "create" ),
	'breadcrumb' => [
		new MenuEntry( null, $domain->getDomainPermalink(), $domain->getDomainName() ),
	],
] );

// spawn the page content
template( 'ftp', [
	'domain' => $domain,
	'ftp'    => $ftp,
] );

// spawn the footer
Footer::spawn();
