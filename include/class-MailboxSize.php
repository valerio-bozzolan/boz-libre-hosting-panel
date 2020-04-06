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

// load dependent traits
class_exists( 'Mailbox' );

/**
 * Methods for a MailboxSize class
 */
trait MailboxSizeTrait {

	/**
	 * Get the Mailbox quota date
	 *
	 * @return DateTime
	 */
	public function getMailboxSizeDate() {
		return $this->get( 'mailboxsize_date' );
	}

	/**
	 * Get the Mailbox quota bytes
	 *
	 * @return int
	 */
	public function getMailboxSizeBytes() {
		return $this->get( 'mailboxsize_bytes' );
	}

	/**
	 * Get the Mailbox quota size readable for humans
	 *
	 * @return string
	 */
	public function getMailboxSizeHumanSize() {
		$size = $this->getMailboxSizeBytes();
		return human_filesize( $size );
	}

	/**
	 * Normalize a MailboxSize object after being retrieved from database
	 */
	protected function normalizeMailboxSize() {
		$this->integers(  'mailboxsize_bytes' );
		$this->datetimes( 'mailboxsize_date' );
	}

}

/**
 * Rappresentation of a Mailbox quota size
 */
class MailboxSize extends Queried {

	use MailboxSizeTrait;

	/**
	 * Table name
	 */
	const T = 'mailboxsize';

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->normalizeMailboxSize();
	}

}
