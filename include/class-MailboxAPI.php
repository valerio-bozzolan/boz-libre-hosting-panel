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

// load dependend traits
class_exists( 'DomainAPI' );

/**
 * Methods for a MailboxAPI class
 */
trait MailboxAPITrait {

	use DomainAPITrait;

	/**
	 * Limit to a specific mailbox
	 *
	 * @param  object $mailbox Mailbox
	 * @return self
	 */
	public function whereMailbox( $mailbox ) {
		return $this->whereDomain( $mailbox )
		            ->whereMaiboxUsername( $mailbox->getMailboxUsername() );
	}

	/**
	 * Filter a specific Mailbox username
	 *
	 * @param  string $username Mailbox username (without domain name)
	 * @return self
	 */
	public function whereMailboxUsername( $username ) {
		return $this->whereStr( 'mailbox_username', $username );
	}

	/**
	 * Where the Mailbox is Active (or not)
	 *
	 * @param  boolean $active If you want the active, or the inactive
	 * @return self
	 */
	public function whereMailboxIsActive( $active = true ) {
		return $this->wheerInt( 'mailbox_active', $active );
	}

	/**
	 * Join mailboxes and domain (once)
	 *
	 * @return self
	 */
	public function joinMailboxDomain() {
		if( empty( $this->joinedMailboxDomain ) ) {
			$this->from( 'domain' );
			$this->equals( 'domain.domain_ID', 'mailbox.domain_ID' );

			$this->joinedMailboxDomain = true;
		}
		return $this;
	}

	/**
	 * Check if I can edit this mailbox
	 *
	 * Actually it just checks if you can edit the whole domain.
	 *
	 * @return boolean
	 */
	public function whereMailboxIsEditable() {
		return $this->whereDomainIsEditable();
	}

}

/**
 * Mailbox API
 */
class MailboxAPI extends Query {

	use MailboxAPITrait;

	/**
	 * Univoque Domain ID column name
	 *
	 * Used by DomainAPITrait
	 */
	const DOMAIN_ID = 'mailbox.domain_ID';

	/**
	 * Univoque Plan ID column name
	 */
	const PLAN_ID = 'domain.plan_ID';

	/**
	 * Constructor
	 */
	public function __construct( $db = null ) {

		// set database and class name
		parent::__construct( $db, Mailbox::class );

		// set database table
		$this->from( Mailbox::T );
	}

}
