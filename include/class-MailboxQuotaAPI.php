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
class_exists( 'MailboxAPI' );

/**
 * Methods for a MailboxQuotaAPI class
 */
trait MailboxQuotaAPITrait {

	use MailboxAPITrait;

	/**
	 * Limit to a specific mailbox
	 *
	 * @param  object $mailbox MailboxQuota
	 * @return self
	 */
	public function whereMailboxQuota( $mailbox ) {
		return $this->whereDomain( $mailbox )
		            ->whereMaiboxUsername( $mailbox->getMailboxQuotaUsername() );
	}

	/**
	 * Filter a specific MailboxQuota username
	 *
	 * @param  string $username MailboxQuota username (without domain name)
	 * @return self
	 */
	public function whereMailboxQuotaUsername( $username ) {
		return $this->whereStr( 'mailbox_username', $username );
	}

	/**
	 * Where the MailboxQuota is Active (or not)
	 *
	 * @param  boolean $active If you want the active, or the inactive
	 * @return self
	 */
	public function whereMailboxQuotaIsActive( $active = true ) {
		return $this->whereInt( 'mailbox_active', $active );
	}

	/**
	 * Join mailboxes and domain (once)
	 *
	 * @return self
	 */
	public function joinMailboxQuotaDomain() {
		if( empty( $this->joinedMailboxQuotaDomain ) ) {
			$this->from( 'domain' );
			$this->equals( 'domain.domain_ID', 'mailbox.domain_ID' );

			$this->joinedMailboxQuotaDomain = true;
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
	public function whereMailboxQuotaIsEditable() {
		return $this->whereDomainIsEditable();
	}

}

/**
 * MailboxQuota API
 */
class MailboxQuotaAPI extends Query {

	use MailboxQuotaAPITrait;

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
		parent::__construct( $db, MailboxQuota::class );

		// set database table
		$this->from( MailboxQuota::T );
	}

}
