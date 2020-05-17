<?php
# Copyright (C) 2018, 2019, 2020 Valerio Bozzolan
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

/**
 * Methods for a Mailforwardfrom class
 */
trait MailforwardfromTrait {
	/**
	 * Get the mailforward from ID
	 *
	 * @return int
	 */
	public function getMailforwardfromID() {
		return $this->get( 'mailforwardfrom_ID' );
	}

	/**
	 * Get the mailforward from address
	 *
	 * @return string E-mail
	 */
	public function getMailforwardfromAddress() {
		return Mailbox::address(
			$this->getDomainName(),
			$this->getMailforwardfromUsername()
		);
	}

	/**
	 * Get the mailforward from username
	 *
	 * @return string
	 */
	public function getMailforwardfromUsername() {
		return $this->get( 'mailforwardfrom_username' );
	}

	/**
	 * Get the mailforward permalink
	 *
	 * @param $absolute boolean
	 * @return string
	 */
	public function getMailforwardfromPermalink( $absolute = false ) {
		return Mailforwardfrom::permalink(
			$this->getDomainName(),
			$this->getMailforwardfromUsername(),
			$absolute
		);
	}

	/**
	 * Normalize a Mailforwardfrom object after being retrieved from database
	 */
	protected function normalizeMailforwardfrom() {
		$this->integers( 'mailforwardfrom_ID' );
	}
}

/**
 * An e-mail forwarding
 */
class Mailforwardfrom extends Queried {

	use MailforwardfromTrait;
	use DomainTrait;

	/**
	 * Constructor
	 */
	public function __constructor() {
		$this->normalizeMailforwardfrom();
		$this->normalizeDomain();
	}

	/**
	 * Database table name
	 */
	const T = 'mailforwardfrom';

	/**
	 * Update the related database row
	 */
	public function update( $columns ) {
		query_update( self::T, $columns, sprintf(
			"mailforwardfrom_ID = %d",
			$this->getMailforwardfromID()
		) );
	}

	/**
	 * Get the mailforward permalink
	 *
	 * @return string
	 */
	public static function permalink( $domain, $mailforward = false, $absolute = false ) {
		$part = site_page( 'mailforward.php', $absolute ) . _ . $domain;
		if( $mailforward ) {
			$part .= _ . $mailforward;
		}
		return $part;
	}

	/**
	 * Force to get a Mailforwardfrom ID, whatever is passed
	 *
	 * @param  mixed $mailforward Mailforwardfrom object or its ID
	 * @return int
	 */
	public static function getID( $mailforward ) {
		return is_object( $mailforward ) ? $mailforward->getMailforwardfromID() : (int)$mailforward;
	}
}
