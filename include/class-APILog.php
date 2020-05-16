<?php
# Copyright (C) 2018, 2019, 2020 Valerio Bozzolan
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

/**
 * Class to interact with the `log` database table
 */
class APILog {

	/**
	 * @param array $args Arguments
	 */
	public static function insert( $args ) {

		// data to be saved
		$data = [];

		// no family no party
		if( !isset( $args['family'] ) ) {
			throw new Exception( "missing family" );
		}

		// no action no party
		if( !isset( $args['action'] ) ) {
			throw new Exception( "missing action" );
		}

		// set the default Actor ID
		if( isset( $args['actor'] ) ) {
			$data['actor_ID'] = User::getID( $args['actor'] );
		} else {

			// otherwise please take the currently logged-in user as default
			$data['actor_ID'] = get_user()->getUserID();
		}

		// you cannot change the timestamp
		$data['log_timestamp'] = date( 'Y-m-d H:i:s' );

		// set the family
		$data['log_family'] = $args['family'];

		// set the action name
		$data['log_action'] = $args['action'];

		// eventually set the Domain ID
		if( isset( $args['domain'] ) ) {
			$data['domain_ID'] = Domain::getID( $args['domain'] );
		}

		// eventually set the Mailbox ID
		if( isset( $args['mailbox'] ) ) {
			$data['mailbox_ID'] = Mailbox::getID( $args['mailbox'] );
		}

		// eventually set the Mailforwardfrom ID
		if( isset( $args['mailforwardfrom'] ) ) {
			$data['mailforwardfrom_ID'] = Mailforwardfrom::getID( $args['mailforwardfrom'] );
		}

		// eventually set the Plan ID
		if( isset( $args['plan'] ) ) {
			$data['plan_ID'] = Plan::getID( $args['plan'] );
		}

		// eventually set the marionette ID (the touched User's ID)
		if( isset( $args['marionette'] ) ) {
			$data['marionette_ID'] = User::getID( $args['marionette'] );
		}

		// finally insert the row
		( new QueryLog() )
			->insertRow( $data );

	}

	/**
	 * Help in querying stuff
	 *
	 * @param array $args Arguments
	 */
	public static function query( $args ) {

		// expected arguments defaults to NULL
		$actor      = $args['actor']      ?? null;
		$marionette = $args['marionette'] ?? null;
		$mailbox    = $args['mailbox']    ?? null;
		$domain     = $args['domain']     ?? null;

		// create a fresh query builder
		$query = new QueryLog();

		// select the most important columns
		$query->select( [
			'log_timestamp',
			'log_family',
			'log_action',
		] );

		// eventually filter by Actor (the user who was doing the action)
		if( $actor ) {
			$query->whereLogActor( $actor );
		}

		// eventually filter by Marionette (the user who was receiving an edit)
		if( $marionette ) {
			$query->whereLogMarionette( $marionette );
		}

		// eventually filter by Mailbox
		if( $mailbox ) {
			$query->whereMailbox( $mailbox );
		}

		// eventually filter by Domain
		if( $domain ) {
			$query->whereDomain( $domain );
		}

		// eventually skip to join something
		$query->joinLogMessageTables( [
			'actor'      => is_object( $actor      ),
			'marionette' => is_object( $marionette ),
			'mailbox'    => is_object( $mailbox    ),
			'domain'     => is_object( $domain     ),
		] );

		// as default sort descending the timeline
		$query->orderByLogTimestamp( 'DESC' );

		// allow to change the limit
		$query->limit( $args['limit'] ?? 15 );

		return $query;

	}
}
