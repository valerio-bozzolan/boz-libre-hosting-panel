<?php
# Copyright (C) 2019, 2020 Valerio Bozzolan
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
$domain       = null;
$ftp          = null;
$ftp_password = null;

// URL paramenters (maximum both domain and FTP login, minimum just domain)
list( $domain_name, $ftp_login ) = url_parts( 2, 1 );

// eventually retrieve mailforward from database
if( $ftp_login ) {
	$ftp = ( new FTPAPI() )
		->select( [
			'domain.domain_ID',
			'domain_name',
			'ftp_login',
			'ftp_directory',
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

	$data = [];

	// during creation require FTP login
	if( !$ftp ) {

		// require all the submitted data
		if( !isset( $_POST['ftp_login'] ) || !is_string( $_POST['ftp_login'] ) ) {
			BadRequest::spawn();
		}

		// generate the username - MUST start with domain name
		$username = generate_slug( $domain->getDomainName() ) . '_' . $_POST['ftp_login'];
		$username = FTP::normalizeUsername( $username, 128 );

		// validate username
		if( !validate_mailbox_username( $username ) ) {
			BadRequest::spawn( __( "invalid username" ) );
		}

		// check existence
		$ftp_exists = ( new FTPAPI() )
			->select( 1 )
			->whereDomain( $domain )
			->whereFTPLogin( $username )
			->queryRow();

		// die if exists
		if( $ftp_exists ) {
			BadRequest::spawn( __( "FTP account already existing" ) );
		}

		// save these fields
		$data['domain_ID'] = $domain->getDomainID();
		$data['ftp_login'] = $username;
	} else {
		$username = $ftp->getFTPLogin();
	}

	// no FTP directory no party
	$ftp_directory = $_POST['ftp_directory'] ?? '';

	// validate the FTP directory and save or die
	try {
		validate_subdirectory( $ftp_directory );
	} catch( Exception $e ) {
		error_log( $e->getMessage() );
		BadRequest::spawn( sprintf(
			__( "invalid Sub-Directory: %s" ),
			$e->getMessage()
		) );
	}

	// at this point the directory is safe
	$data['ftp_directory'] = $ftp_directory ?? null;

	// during creation generate a random password
	if( !$ftp ) {
		$ftp_password = generate_password();
		$data['ftp_password'] = FTP::encryptPassword( $ftp_password );
	}

	// insert or update
	if( $ftp ) {
		( new FTPAPI() )
			->whereFTP( $ftp )
			->update( $data );
	} else {
		( new FTPAPI() )
			->insertRow( $data );
	}

	// POST/redirect/GET
	http_redirect( FTP::permalink(
		$domain->getDomainName(),
		$username,
		true
	), 303 );
}

// change password action
if( $ftp && is_action( 'ftp-password-reset' ) ) {

	// generate a password and die
	$ftp_password      = generate_password();
	$ftp_password_safe = FTP::encryptPassword( $ftp_password );

	// update its password
	( new FTPAPI() )
		->whereFTP( $ftp )
		->update( [
			'ftp_password' => $ftp_password_safe,
		] );
}

// you can delete only if it exists
if( $ftp ) {

	// action fired when deleting a whole mailforward
	if( is_action( 'ftp-delete' ) ) {

		// delete the account
		( new FTPAPI() )
			->whereFTP( $ftp )
			->delete();

		// POST/redirect/GET
		http_redirect( $domain->getDomainPermalink( true ), 303 );
	}

}

// spawn header
Header::spawn( [
	'uid' => false,
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
	'domain'       => $domain,
	'ftp'          => $ftp,
	'ftp_password' => $ftp_password,
] );

// spawn the footer
Footer::spawn();
