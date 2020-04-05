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

// load dependend traits
class_exists( 'MailboxAPI' );

/**
 * Methods for a MailboxQuotaAPI class
 */
trait MailboxQuotaAPITrait {

	use MailboxAPITrait;

}

/**
 * MailboxQuota API
 */
class MailboxQuotaAPI extends Query {

	use MailboxQuotaAPITrait;

	/**
	 * Univoque column name to the Mailbox ID
	 */
	protected $MAILBOX_ID = 'mailboxquota.mailbox_ID';

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
