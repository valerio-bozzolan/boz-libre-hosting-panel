<?php
# Copyright (C) 2018 Valerio Bozzolan
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
 * An e-mail fowarding
 */
class Mailfoward extends Domain {

	const T = 'mailfoward';

	/**
	 * Get the mailfoward address
	 *
	 * @return string E-mail
	 */
	public function getMailfowardAddress() {
		return sprintf( '%s@%s',
			$this->getMailfowardSource(),
			$this->getDomainName()
		);
	}

	/**
	 * Get the mailfoward source (just username)
	 *
	 * @return string
	 */
	public function getMailfowardSource() {
		return $this->get( 'mailfoward_source' );
	}

	/**
	 * Get the mailfoward destination
	 *
	 * @return string
	 */
	public function getMailfowardDestination() {
		return $this->get( 'mailfoward_destination' );
	}

	/**
	 * Get the mailfoward permalink
	 *
	 * @param $absolute boolean
	 * @return string
	 */
	public function getMailfowardPermalink( $absolute = false ) {
		return Mailfoward::permalink(
			$this->getDomainName(),
			$this->getMailfowardSource(),
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
			esc_sql( $this->getMailfowardSource() )
		) );
	}

	/**
	 * Get the mailfoward permalink
	 *
	 * @return string
	 */
	public static function permalink( $domain, $mailfoward = false, $absolute = false ) {
		$part = site_page( 'mailfoward.php', $absolute ) . _ . $domain;
		if( $mailfoward ) {
			$part .= _ . $mailfoward;
		}
		return $part;
	}
}
