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
$plan     = null;
$mailbox_password = null;

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

	// the mailbox has the domain stuff
	$domain = $mailbox;

	// the mailbox has the Plan stuff
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

	$plan = $domain;
}

// does the user want to create a Mailbox?
if( !$mailbox ) {

	// count the actual number of Domain Mailbox(es)
	$mailbox_count = (int)
		( new MailboxAPI() )
			->select( 'COUNT(*) count' )
			->whereDomain( $domain )
			->queryValue( 'count' );

	// check if I can add another Mailbox
	if( $mailbox_count >= $plan->getPlanMailboxes() && !has_permission( 'edit-email-all' ) ) {
		BadRequest::spawn( __( "Your Plan does not allow this action" ), 401 );
	}

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
		// assign a damn temporary password
		$mailbox_password = generate_password();
		$mailbox_password_safe = Mailbox::encryptPassword( $mailbox_password );

		insert_row( 'mailbox', [
			new DBCol( 'mailbox_username', $_POST[ 'mailbox_username' ], 's' ),
			new DBCol( 'domain_ID',        $domain->getDomainID(),       'd' ),
			new DBCol( 'mailbox_password', $mailbox_password_safe,       's' ),
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
] );

// spawn the footer
Footer::spawn();
