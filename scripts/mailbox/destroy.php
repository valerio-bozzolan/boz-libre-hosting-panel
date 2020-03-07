#!/usr/bin/php
<?php
# Copyright (C) 2020 Valerio Bozzolan
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

/**
 * destroy-mailbox
 *
 * This is a command-line interface to destroy a mailbox.
 *
 * This script is designed to be run by a SYSTEM ADMINISTRATOR
 * with enough brain and with enough privileges to delete contents from
 * your MDA.
 *
 * YES, This script PERMANENTLY REMOVE ALL THE F*****G E-MAILS
 * of the specified mailbox and PERMANENTLY REMOVE the mailbox from
 * your database and YOU SHOULD HAVE A F*****G BACKUP.
 */

// require the framework
require dirname( __FILE__ ) . '/../../load.php';

// no arguments no party
if( !isset( $argv ) ) {
	exit( 1 );
}

// check the mailbox name
$mailbox_raw = $argv[1] ?? null;

// no mailbox no party
if( !$mailbox_raw ) {
	destroy_mailbox_help( "Please specify a mailbox" );
	exit( 2 );
}

// mailbox username and domain name
$mailbox_username = null;
$domain_name      = null;

// no valid mailbox no party
if( substr_count( $mailbox_raw, '@' ) === 1 ) {

	// extract the mailbox username and domain name
	list( $mailbox_username, $domain_name ) = explode( '@', $mailbox_raw, 2 );
}

// check if the user input has sense
if( !$mailbox_username || !$domain_name ) {
	destroy_mailbox_help( sprintf(
		"Invalid e-mail address '%s'",
		$mailbox_raw
	) );
	exit( 3 );
}

// request the mailbox
$mailbox = ( new MailboxAPI() )
	->joinDomain()
	->whereDomainName( $domain_name )
	->whereMailboxUsername( $mailbox_username )
	->queryRow();

// no mailbox no party
if( !$mailbox ) {
	echo "Cannot destroy an unexisting mailbox.\n";
	exit( 4 );
}

// mandatory question
printf(
	"Are you F*****G sure to DESTROY the mailbox '%s' FOREVER? [y/n]\n",
	$mailbox->getMailboxAddress()
);
$yes = readline();

// aborted
if( $yes !== 'y' ) {
	echo "Aborted\n";
	exit( 0 );
}

// destroy this F*****G mailbox NOW
MailboxDestroyer::destroy( $mailbox );

echo "Destroyed.\n";

/**
 * Show an help message, eventually with a custom message
 *
 * @param $message string
 */
function destroy_mailbox_help( $message = null ) {

	printf( "Usage:\n  %s foo@example.com\n", $GLOBALS['argv'][0] );

	// eventually spawn an error message
	if( $message ) {
		echo "\n";
		printf( "Error:\n  %s\n", $message );
	}
}
