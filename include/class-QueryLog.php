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

// make sure that this class is loaded
class_exists( MailboxAPI::class );

/**
 * Methods of a QueryLog object
 */
trait QueryLogTrait {

	/**
	 * Filter to a specific log family
	 *
	 * @param  string $family
	 * @return self
	 */
	public function whereLogFamily( $family ) {
		return $this->whereStr( 'log_family', $family );
	}

	/**
	 * Filter to a specific actor
	 *
	 * The actor is the user who performed the action.
	 *
	 * @param object $user Actor
	 * @return self
	 */
	public function whereLogActor( $user ) {
		return $this->whereLogActorID( $user->getUserID() );
	}

	/**
	 * Filter to a specific marionette User ID
	 *
	 * The marionette is the user touched by the actor.
	 *
	 * @param string $id User ID
	 * @return self
	 */
	public function whereLogMarionette( $id ) {
		return $this->whereLogMarionetteID( $user->getUserID() );
	}

	/**
	 * Filter to a certain actor ID
	 *
	 * The actor is the user who performed the action.
	 *
	 * @param string $id User ID
	 * @return self
	 */
	public function whereLogActorID( $id ) {
		return $this->whereInt( 'actor_ID', $id );
	}

	/**
	 * Filter to a specific marionette User ID
	 *
	 * The marionette is the user touched by the actor.
	 *
	 * @param string $id User ID
	 * @return self
	 */
	public function whereLogMarionetteID( $id ) {
		return $this->whereInt( 'marionette_ID', $id );
	}

	/**
	 * Order by the log timestamp
	 *
	 * @param string $dir Direction
	 * @return self
	 */
	public function orderByLogTimestamp( $dir = 'DESC' ) {
		return $this->orderBy( 'log_timestamp', $dir );
	}

	/**
	 * Join with the tables that are necessary to build the log message
	 *
	 * @param  array $skip_join Array of entities to do not join
	 * @return self
	 */
	public function joinLogMessageTables( $skip_join = [] ) {

		// as default nothing is skipped
		$skip_actor           = $skip_join['actor']           ?? false;
		$skip_marionette      = $skip_join['marionette']      ?? false;
		$skip_domain          = $skip_join['domain']          ?? false;
		$skip_mailbox         = $skip_join['mailbox']         ?? false;
		$skip_plan            = $skip_join['plan']            ?? false;
		$skip_mailforwardfrom = $skip_join['mailforwardfrom'] ?? false;

		// inner join with the User table to retrieve the actor (the user who has done the action)
		if( !$skip_actor ) {
			//             type,     table,  first column,  second column,   table alias
			$this->joinOn( 'INNER', 'user', 'actor_ID',    'actor.user_ID', 'actor' );
			$this->select( [
				'actor_ID',
				'actor.user_uid AS actor_uid',
			] );
		}

		// left join with the User table to retrieve the marionette (the user affected by this action)
		if( !$skip_marionette ) {
			//             type,   table,   first column,   second column,       table alias
			$this->joinOn( 'LEFT', 'user', 'marionette_ID', 'marionette.user_ID', 'marionette' );
			$this->select( [
				'marionette_ID',
				'marionette.user_uid AS marionette_uid',
			] );
		}

		// left join with the Domain table
		if( !$skip_domain ) {
			//             type,    table,    first column,       second column
			$this->joinOn( 'LEFT', 'domain', 'domain.domain_ID', 'log.domain_ID' );
			$this->select( [
				'domain.domain_ID',
				'domain_name',
			] );
		}

		// left join with the Mailbox table
		if( !$skip_mailbox ) {
			//              type,   table,     first column,         second column
			$this->joinOn( 'LEFT', 'mailbox', 'mailbox.mailbox_ID', 'log.mailbox_ID' );
			$this->select( [
				'mailbox_username',
			] );
		}

		// left join with the Mailforwardfrom table
		if( !$skip_mailforwardfrom ) {
			//              type,   table,             first column,                         second column
			$this->joinOn( 'LEFT', 'mailforwardfrom', 'mailforwardfrom.mailforwardfrom_ID', 'log.mailforwardfrom_ID' );
			$this->select( [
				'mailforwardfrom_username',
			] );
		}

		// left join with the Plan table
		if( !$skip_plan ) {
			//              type,   table,  first column,   second column
			$this->joinOn( 'LEFT', 'plan', 'plan.plan_ID', 'log.plan_ID' );
			$this->select( [
				'plan_name',
			] );
		}

		return $this;
	}

}

/**
 * Query the `log` database table
 */
class QueryLog extends Query {

	use QueryLogTrait;
	use MailboxAPITrait;

	/**
	 * Univoque Domain ID column name
	 */
	const DOMAIN_ID = 'log.domain_ID';

	/**
	 * Univoque column name to the Mailbox ID
	 *
	 * @var string
	 */
	protected $MAILBOX_ID = 'log.mailbox_ID';

	/**
	 * Constructor
	 *
	 * @param object $db Database
	 */
	public function __construct( $db = null ) {
		parent::__construct( $db, Log::class );

		// default table
		$this->from( Log::T );
	}
}
