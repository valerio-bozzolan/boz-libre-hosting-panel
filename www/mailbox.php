<?php
# Copyright (C) 2018, 2019, 2020, 2021, 2022 Valerio Bozzolan
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
 * This is the mailbox edit page
 */

// load framework
require '../load.php';

// this page is not public
require_permission( 'backend' );

// wanted domain and mailbox username
list( $domain_name, $mailbox_username ) = url_parts( 2, 1 );

// some useful information
$domain   = null;
$mailbox  = null;
$plan     = null;
$mailbox_password = null;

// check if the page is about a specific Mailbox
if( $mailbox_username ) {

	// retrieve the mailbox and its domain and its Plan
	$mailbox = ( new MailboxFullAPI() )
		->joinPlan( 'LEFT' )
		->whereDomainName( $domain_name )
		->whereMailboxUsername( $mailbox_username )
		->whereMailboxIsEditable()
		->queryRow();

	// 404?
	$mailbox or PageNotFound::spawn();

	// the mailbox object has the domain stuff - recycle it
	$domain = $mailbox;

	// the mailbox object has the Plan stuff - recycle it
	$plan   = $mailbox;
} else {

	// retrieve just the domain and its Plan
	$domain = ( new DomainAPI() )
		->whereDomainName( $domain_name )
		->whereDomainIsEditable()
		->joinPlan( 'LEFT' )
		->queryRow();

	// 404?
	$domain or PageNotFound::spawn();

	// the domain object has the Plan stuff - recycle it
	$plan = $domain;
}

// does the user want to create a Mailbox?
if( !$mailbox ) {

	// check if I can add another Mailbox
	if( !$domain->canCreateMailboxInDomain() ) {
		BadRequest::spawn( __( "Your Plan does not allow this action" ), 401 );
	}

}

/*
 * Change the mailbox password
 */
if( $mailbox && is_action( 'mailbox-password-reset' ) ) {
	$mailbox_password = $mailbox->updateMailboxPassword();
}

/**
 * Eventually save the notes
 */
if( $mailbox && is_action( 'save-mailbox-notes' ) ) {

	// read the description
	$description = $_POST['mailbox_description'] ?? null;

	query( 'START TRANSACTION' );

	// save the description
	( new MailboxAPI() )
		->whereMailbox( $mailbox )
		->update( [
			'mailbox_description' => $description,
		] );

	// remember this action in the registry
	APILog::insert( [
		'family'  => 'mailbox',
		'action'  => 'description.change',
		'mailbox' => $mailbox,
		'domain'  => $domain,
	] );

	query( 'COMMIT' );

	// POST -> redirect -> GET
	http_redirect( $mailbox->getMailboxPermalink() );
}

/*
 * Create the mailbox
 */
if( !$mailbox && is_action( 'mailbox-create' ) && isset( $_POST['mailbox_username'] ) ) {

	// assure that the username is not too long
	$_POST['mailbox_username'] = luser_input( $_POST['mailbox_username'], 64 );

	// check if the mailbox already exist
	$mailbox_exists = ( new MailboxFullAPI() )
		->select( 1 )
		->whereDomainName( $domain_name )
		->whereMailboxUsername( $_POST['mailbox_username'] )
		->queryRow();

	// check if we can create the mailbox
	if( !$mailbox_exists ) {
		// assign a damn temporary password
		$mailbox_password = generate_password();
		$mailbox_password_safe = Mailbox::encryptPassword( $mailbox_password );

		query( 'START TRANSACTION' );

		// really create the mailbox
		insert_row( 'mailbox', [
			new DBCol( 'mailbox_username', $_POST['mailbox_username'], 's' ),
			new DBCol( 'domain_ID',        $domain->getDomainID(),       'd' ),
			new DBCol( 'mailbox_password', $mailbox_password_safe,       's' ),
		] );

		// register this event in the registry
		APILog::insert( [
			'family'  => 'mailbox',
			'action'  => 'create',
			'domain'  => $domain,
			'mailbox' => last_inserted_ID(),
		] );

		query( 'COMMIT' );
	}

	// POST -> redirect -> GET
	http_redirect( Mailbox::permalink(
		$domain->getDomainName(),
		$_POST['mailbox_username']
	) );
}

// spawn header
Header::spawn( [
	'uid' => false,
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
	'plan'             => $plan,
] );

// spawn the footer
Footer::spawn();
