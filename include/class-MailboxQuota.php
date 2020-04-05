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

// load dependent traits
class_exists( 'Mailbox' );

/**
 * Methods for a MailboxQuota class
 */
trait MailboxQuotaTrait {

	/**
	 * Get the Mailbox quota date
	 *
	 * @return DateTime
	 */
	public function getMailboxQuotaDate() {
		return $this->get( 'mailboxquota_date' );
	}

	/**
	 * Get the Mailbox quota bytes
	 *
	 * @return int
	 */
	public function getMailboxQuotaBytes() {
		return $this->get( 'mailboxquota_bytes' );
	}

	/**
	 * Get the Mailbox quota size readable for humans
	 *
	 * @return string
	 */
	public function getMailboxQuotaHumanSize() {
		$size = $this->getMailboxQuotaBytes();
		return human_filesize( $size );
	}

	/**
	 * Normalize a MailboxQuota object after being retrieved from database
	 */
	protected function normalizeMailboxQuota() {
		$this->integers(  'mailboxquota_bytes' );
		$this->datetimes( 'mailboxquota_date' );
	}

}

/**
 * Rappresentation of a Mailbox quota size
 */
class MailboxQuota extends Queried {

	use MailboxQuotaTrait;

	/**
	 * Table name
	 */
	const T = 'mailboxquota';

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->normalizeMailboxQuota();
	}

}
