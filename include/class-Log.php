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
class_exists( Mailbox::class );
class_exists( Mailforwardfrom::class );

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
			case 'mailbox':
				return self::mailboxMessage( $action, $this, $args );
			case 'mailforward':
				return self::mailforwardMessage( $action, $this, $args );
		}

		return self::unknownAction( $family, $action );
	}

	/**
	 * Get the log message alongside the date and the actor name
	 *
	 * @param  array $args Arguments
	 * @return self
	 */
	public function getLogMessageWithDateAndUser( $args ) {

		$actor = $args['actor'] ?? $this;

		// create the Actor firm from the passed User object or from the Log
		$actor_firm = $actor instanceof User
			? $actor->getUserFirm()
		        : $actor->getLogActorFirm();

		return sprintf(
			"%s - %s %s",
			$this->getLogDate()->format( __( "Y-m-d H:i" ) ),
			$actor_firm,
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
	use MailboxTrait;
	use MailforwardfromTrait;

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
		$domain = $args['domain'] ?? $log;
		$plan   = $args['plan']   ?? $log;

		switch( $action ) {

			// an administrator has changed the Plan for a Domain
			case 'plan.change':
				return sprintf(
					__( "changed the Plan for %s to %s" ),
					$domain->getDomainFirm(),
					esc_html( $plan->getPlanName() )
				);
		}

		// default dummy message
		return self::unknownAction( 'domain', $action );
	}

	/**
	 * Generate a Mailbox-related message
	 *
	 * @param  string $action The related action name
	 * @param  object $log
	 * @param  array  $args Arguments
	 * @return string Message
	 */
	public static function mailboxMessage( $action, $log, $args ) {

		/**
		 * You can pass some objects to build the message:
		 *
		 * A complete 'actor'   User object
		 * A complete 'domain'  Domain object
		 * A complete 'mailbox' Mailbox object
		 */
		$actor   = $args['actor']   ?? $log;
		$domain  = $args['domain']  ?? $log;
		$mailbox = $args['mailbox'] ?? $log;

		$mailbox_firm = Mailbox::firm(
			$domain->getDomainName(),
			$mailbox->getMailboxUsername()
		);

		// trigger the right action message
		switch( $action ) {

			// the mailbox was created
			case 'create':
				return sprintf(
					__( "created the mailbox %s" ),
					$mailbox_firm
				);

			// the description was changed
			case 'description.change':
				return sprintf(
					__( "edited description of %s" ),
					$mailbox_firm
				);

			case 'newpassword':
				return sprintf(
					__( "reset password of %s" ),
					$mailbox_firm
				);
		}

		// default dummy message
		return self::unknownAction( 'mailbox', $action );
	}

	/**
	 * Generate a Mailforward-related message
	 *
	 * @param  string $action The related action name
	 * @param  object $log
	 * @param  array  $args Arguments
	 * @return string Message
	 */
	public static function mailforwardMessage( $action, $log, $args ) {

		/**
		 * You can pass some objects to build the message:
		 *
		 * A complete 'domain'  Domain object
		 * A complete 'mailbox' Mailbox object
		 */
		$domain      = $args['domain']      ?? $log;
		$mailforward = $args['mailforward'] ?? $log;

		if( $mailforward ) {
			$firm = Mailforwardfrom::firm(
				$domain->getDomainName(),
				$mailforward->getMailforwardfromUsername()
			);
		} else {
			$firm = $domain->getDomainFirm();
		}

		// trigger the right action message
		switch( $action ) {

			// a Mailforward destination was added
			case 'add.destination':
				return sprintf(
					__( "added a destination for %s" ),
					$firm
				);

			// a Mailforward destination was removed
			case 'remove.destination':
				return sprintf(
					__( "removed a destination for %s" ),
					$firm
				);

			// a Mailforward mailbox was created
			case 'create':
				return sprintf(
					__( "created %s" ),
					$firm
				);

			case 'delete':
				return sprintf(
					__( "deleted an %s mail forwarding" ),
					$firm
				);
		}

		// default dummy message
		return self::unknownAction( 'mailforward', $action );

	}

	private static function unknownAction( $family, $action ) {
		return esc_html( sprintf(
			__( "misterious action about %s (%s)" ),
			$family,
			$action
		) );
	}
}
