#!/usr/bin/php
<?php
# Copyright (C) 2019, 2020 Valerio Bozzolan
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

require __DIR__ . '/../load.php';

// parse some command line options
$options = getopt( 'h', [
	'help',
	'print',
	'no-save',
] );

// check if we have to show the help message
$SHOW_HELP = isset( $options['h'] ) || isset( $options['help'] );

// check if we have to alert the sysadmin (as deafult, no)
$PRINT = isset( $options['print'] );

// check if we have to save
$SAVE = !isset( $options['no-save'] );

// eventually print an help
if( $SHOW_HELP ) {
	echo "Usage: \n";
	echo "  {$argv[0]} [OPTIONS]\n\n";
	echo "OPTIONS:\n";
	echo "  --print                Print the overquota e-mail addresses\n\n";
	echo "  --no-save              Do not save any mailbox quota.\n";
	echo "                         Maybe because you just want to alert the sysadmin.\n";
	echo "                         As default the quotas will be saved in the database.\n\n";
	echo "  -h --help              Show this help and quit\n";
	exit( 0 );
}

// remember the overquota addresses
$overquota = [];

/**
 * Script to update Mailbox quotas
 *
 * See https://gitpull.it/T101
 */

// query every active Domain plus their Plan (if any)
$domains = ( new DomainAPI() )
	->select( [
		'domain.domain_ID',
		'domain_name',
		'plan_mailboxquota',
	] )
	->whereDomainIsActive()
	->joinPlan( 'LEFT' )
	->queryGenerator();

// for each active domain
foreach( $domains as $domain ) {

	$domain_name = $domain->getDomainName();

	// validate domain name
	if( strpos( $domain_name, __ ) !== false ) {
		error( "domain '$domain_name' is bad" );
		continue;
	}

	// get every active mailbox of this domain
	$mailboxes = ( new MailboxAPI() )
		->select( [
			'mailbox_ID',
			'mailbox_username',
		] )
		->whereDomain( $domain )
		->whereMailboxIsActive()
		->queryGenerator();

	// eventually start a transaction for the Domain quotas
	if( $SAVE ) {
		query( 'START TRANSACTION' );
	}

	// for each mailboxes
	foreach( $mailboxes as $mailbox ) {

		$mailbox_username = $mailbox->getMailboxUsername();

		// validate mailbox name
		if( strpos( $mailbox_username, __ ) !== false ) {
			error( "$domain_name.$mailbox_username is bad" );
			continue;
		}

		// calculate the quota size
		$bytes = 0;
		$expected_path = MAILBOX_BASE_PATH . __ . $domain_name . __ . $mailbox_username;
		if( file_exists( $expected_path ) ) {
			$bytes_raw = exec( sprintf(
				'du --summarize --bytes -- %s | cut -f1',
				escapeshellarg( $expected_path )
			) );

			$bytes = (int) $bytes_raw;
		}

		// check if the Mailbox is overquota
		if( $domain->getPlanMailboxQuota() && $bytes > $domain->getPlanMailboxQuota() ) {

			// eventually create a message for the sysadmin
			if( $PRINT ) {

				// allow to customize this message
				$msg = template_content( 'single-mailbox-overquota', [
					'mailbox' => $mailbox,
					'domain'  => $domain,
					'plan'    => $domain,
					'size'    => $bytes,
				] );

				// store these short and unique messages with their size to allow sort
				$overquota[ $msg ] = $bytes;
			}
		}

		// check if we have to save the current quota somewhere
		if( $SAVE ) {

			// store the value in the history
			( new MailboxSizeAPI() )
				->insertRow( [
					'mailbox_ID'        => $mailbox->getMailboxID(),
					'mailboxsize_bytes' => $bytes,
					new DBCol( 'mailboxsize_date', 'NOW()', '-' ),
				] );

			// update the denormalized latest Mailbox data
			( new MailboxAPI() )
				->whereMailbox( $mailbox )
				->update( [
					'mailbox_lastsizebytes' => $bytes,
				] );

		}
	}

	// eventually commit the Domain quotas
	if( $SAVE ) {
		query( 'COMMIT' );
	}
}

if( $PRINT ) {

	// sort the overquota messages by their size
	uasort( $overquota, function( $a, $b ) {
	    return $b - $a;
	} );

	// now just take the messages
	$overquota = array_keys( $overquota );

	// allow to customize the way the email is sent
	template( 'mailbox-overquotas', [
		'problematic_list' => $overquota,
	] );
}
