#!/usr/bin/php
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

require __DIR__ . '/../load.php';

/**
 * Script to update Mailbox quotas
 *
 */

// get every active domain
$domains = ( new DomainAPI() )
	->select( [
		'domain.domain_ID',
		'domain_name',
	] )
	->whereDomainIsActive()
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

	query( 'START TRANSACTION' );

	// for each mailboxes
	foreach( $mailboxes as $mailbox ) {

		$mailbox_username = $mailbox->getMailboxUsername();

		// validate mailbox name
		if( strpos( $mailbox_username, __ ) !== false ) {
			error( "$domain_name.$mailbox_username is bad" );
			continue;
		}

		$bytes = 0;

		$expected_path = MAILBOX_BASE_PATH . __ . $domain_name . __ . $mailbox_username;
		if( file_exists( $expected_path ) ) {
			$bytes_raw = system( sprintf(
				"du --summarize -- %s | cut -f1",
				escapeshellarg( $expected_path )
			) );

			$bytes = (int) $bytes_raw;
		}

		( new MailboxQuotaAPI() )
			->insertRow( [
				'mailbox_ID'         => $mailbox->getMailboxID(),
				'mailboxquota_bytes' => $bytes,
				new DBCol( 'mailboxquota_date', 'NOW()', '-' ),
			] );
	}


	query( 'COMMIT' );
}