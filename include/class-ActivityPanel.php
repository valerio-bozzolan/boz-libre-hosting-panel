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
 * Help in printing the content of an Activity Panel
 */
class ActivityPanel {

	/**
	 * Spawn the activity panel
	 *
	 * Allowed arguments:
	 *
	 *    'query' => [ query arguments ]
	 *    The query arguments are the one available in APILog::query( $args )
	 *
	 * @param array $args Associative array of arguments.
	 */
	public static function spawn( $args ) {

		// available entities that can be used as filter
		$entities = [
			'actor',
			'marionette',
			'mailbox',
			'domain',
			'mailforwardfrom',
		];

		$query_args = $args['query'] ?? [];

		// build the query
		$query = APILog::query( $query_args );

		// if you filter by an Actor, the system will automatically NOT query Actor(s) in the log
		// so, we pass that Actor to Log#getLogMessage( $args ) in order to give it that object
		// to build the message
		$message_args = [];
		foreach( $entities as $entity ) {
			$query_arg = $query_args[ $entity ] ?? null;
			if( is_object( $query_arg ) ) {
				$message_args[ $entity ] = $query_arg;
			}
		}


		// spawn the activity panel
		template( 'activity-panel', [
			'message_args'  => $message_args,
			'query'         => $query,
		] );
	}
}
