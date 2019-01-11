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

/**
 * An e-mail forwarding
 */
class Mailforward extends Domain {

	const T = 'mailfoward';

	/**
	 * Get the mailforward address
	 *
	 * @return string E-mail
	 */
	public function getMailforwardAddress() {
		return sprintf( '%s@%s',
			$this->getMailforwardSource(),
			$this->getDomainName()
		);
	}

	/**
	 * Get the mailforward source (just username)
	 *
	 * @return string
	 */
	public function getMailforwardSource() {
		return $this->get( 'mailfoward_source' );
	}

	/**
	 * Get the mailforward destination
	 *
	 * @return string
	 */
	public function getMailforwardDestination() {
		return $this->get( 'mailfoward_destination' );
	}

	/**
	 * Get the mailforward permalink
	 *
	 * @param $absolute boolean
	 * @return string
	 */
	public function getMailforwardPermalink( $absolute = false ) {
		return Mailforward::permalink(
			$this->getDomainName(),
			$this->getMailforwardSource(),
			$absolute
		);
	}

	/**
	 * Update the related database row
	 */
	public function update( $columns ) {
		query_update( self::T, $columns, sprintf(
			"domain_ID = %d AND mailfoward_source = '%s'",
			$this->getDomainID(),
			esc_sql( $this->getMailforwardSource() )
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
}
