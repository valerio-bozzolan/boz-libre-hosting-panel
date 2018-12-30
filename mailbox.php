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
 * This is the mailbox edit page
 */

// load framework
require 'load.php';

// wanted domain and mailbox username
list( $domain_name, $mailbox_username ) = url_parts( 2 );

// retrieve domain
$mailbox = ( new MailboxFullAPI )
	->select( [
		'domain.domain_ID',
		'domain.domain_name',
		'domain.domain_active',
		'mailbox_username',
	] )
	->whereStr( 'domain_name', $domain_name )
	->whereStr( 'mailbox_username', $mailbox_username )
	->whereDomainIsEditable()
	->queryRow();

// 404?
$mailbox or PageNotFound::spawn();

$password = null;
if( is_action( 'mailbox-password-reset' ) ) {
	$password = $mailbox->updateMailboxPassword();
}

// spawn header
Header::spawn( [
	'title' => sprintf(
		__( "Mailbox: %s" ),
		"<em>" . esc_html( $mailbox->getMailboxAddress() ) . "</em>"
	),
] );

// spawn the page content
template( 'mailbox', [
	'mailbox'  => $mailbox,
	'password' => $password,
] );

// spawn the footer
Footer::spawn();
