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

// make sure that this class is loaded at startup
class_exists( User::class );
class_exists( Domain::class );

trait LogTrait {

	/**
	 * Get the log actor name
	 *
	 * @return string
	 */
	public function getLogActorFirm() {
		return User::firm( $this->get( 'actor_uid' ) );
	}

	/**
	 * Get the action family
	 *
	 * @return string
	 */
	public function getLogFamily() {
		return $this->get( 'log_family' );
	}

	/**
	 * Get the action
	 */
	public function getLogAction() {
		return $this->get( 'log_action' );
	}

	/**
	 * Get the action
	 */
	public function getLogDate() {
		return $this->get( 'log_timestamp' );
	}

	/**
	 * Get the log message
	 *
	 * @param  array $args Arguments
	 * @return self
	 */
	public function getLogMessage( $args ) {

		$family = $this->getLogFamily();
		$action = $this->getLogAction();

		// trigger the right message family
		switch( $family ) {
			case 'domain':
				return self::domainMessage( $action, $this, $args );
		}

		return 'misterious action';
	}

	/**
	 * Get the log message alongside the date
	 *
	 * @param  array $args Arguments
	 * @return self
	 */
	public function getLogMessageWithDate( $args ) {
		return sprintf(
			"%s - %s",
			$this->getLogDate()->format( __( "Y-m-d H:i" ) ),
			$this->getLogMessage( $args )
		);
	}

	protected function normalizeLog() {
		$this->datetimes( 'log_timestamp' );
	}
}

/**
 * A generic log of an action
 *
 * Something happened. Dunno what.
 */
class Log extends Queried {

	use LogTrait;
	use UserTrait;
	use DomainTrait;

	public function __construct() {
		$this->normalizeLog();
	}

	/**
	 * Database table name
	 */
	const T = 'log';

	/**
	 * Generate a Domain-related message
	 *
	 * @param  string $action The related action name
	 * @param  object $log
	 * @param  array  $args Arguments
	 * @return string Message
	 */
	public static function domainMessage( $action, $log, $args ) {

		/**
		 * You can pass some objects to build the message:
		 *
		 * A complete 'actor' User object
		 * A complete 'domain' Domain object
		 */
		$actor  = $args['actor']  ?? $log;
		$domain = $args['domain'] ?? $log;
		$plan   = $args['plan']   ?? $log;

		// create the Actor firm from the passed User object or from the Log
		$actor_firm = $actor instanceof User
			? $actor->getUserFirm()
		        : $log->getLogActorFirm();

		switch( $action ) {

			// an administrator has changed the Plan for a Domain
			case 'plan.change':
				return sprintf(
					__( "%s changed the Plan for %s to %s" ),
					$actor_firm,
					$domain->getDomainFirm(),
					esc_html( $plan->getPlanName() )
				);
		}

		return 'edited a domain (wtf?)';
	}
}
