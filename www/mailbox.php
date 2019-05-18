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
 * This is the mailbox edit page
 */

// load framework
require '../load.php';

// wanted domain and mailbox username
list( $domain_name, $mailbox_username ) = url_parts( 2, 1 );

$domain   = null;
$mailbox  = null;
$mailbox_password = null;
if( $mailbox_username ) {
	// retrieve the mailbox and its domain
	$mailbox = ( new MailboxFullAPI() )
		->select( [
			'domain.domain_ID',
			'domain_name',
			'domain_active',
			'mailbox_username',
		] )
		->whereDomainName( $domain_name )
		->whereStr( 'mailbox_username', $mailbox_username )
		->whereMailboxIsEditable()
		->queryRow();

	// 404?
	$mailbox or PageNotFound::spawn();

	// the mailbox has the domain stuff
	$domain = $mailbox;
} else {
	// retrieve just the domain
	$domain = ( new DomainAPI() )
		->select( [
			'domain.domain_ID',
			'domain_name',
			'domain_active',
		] )
		->whereDomainName( $domain_name )
		->whereDomainIsEditable()
		->queryRow();

	// 404?
	$domain or PageNotFound::spawn();
}

/*
 * Change the mailbox password
 */
if( $mailbox && is_action( 'mailbox-password-reset' ) ) {
	$mailbox_password = $mailbox->updateMailboxPassword();
}

/*
 * Create the mailbox
 */
if( !$mailbox && is_action( 'mailbox-create' ) && isset( $_POST[ 'mailbox_username' ] ) ) {

	// TODO: check max. creation number in single domain props
	require_permission( 'edit-email-all' );

	$_POST[ 'mailbox_username' ] = luser_input( $_POST[ 'mailbox_username' ], 64 );

	$mailbox = ( new MailboxFullAPI() )
		->select( [
			'domain.domain_ID',
			'domain_name',
			'mailbox_username',
		] )
		->whereDomainName( $domain_name )
		->whereStr( 'mailbox_username', $_POST[ 'mailbox_username' ] )
		->queryRow();

	if( !$mailbox ) {
		insert_row( 'mailbox', [
			new DBCol( 'mailbox_username', $_POST[ 'mailbox_username' ], 's' ),
			new DBCol( 'domain_ID',        $domain->getDomainID(),       'd' ),
		] );
	}

	$mailbox = ( new MailboxFullAPI() )
		->select( [
			'domain.domain_ID',
			'domain_name',
			'mailbox_username',
		] )
		->whereDomainName( $domain_name )
		->whereStr( 'mailbox_username', $_POST[ 'mailbox_username' ] )
		->queryRow();

	if( $mailbox ) {
		http_redirect( $mailbox->getMailboxPermalink( true ) );
	}
}

// spawn header
Header::spawn( [
	'title-prefix' => __( "Mailbox" ),
	'title' => $mailbox ? $mailbox->getMailboxAddress() : __( "create" ),
	'breadcrumb' => [
		new MenuEntry( null, $domain->getDomainPermalink(), $domain->getDomainName() ),
	],
] );

// spawn the page content
template( 'mailbox', [
	'mailbox'          => $mailbox,
	'mailbox_password' => $mailbox_password,
	'domain'           => $domain,
] );

// spawn the footer
Footer::spawn();
