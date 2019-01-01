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
		return sprintf( "%s@%s",
			$this->get( 'mailfoward_source' ),
			$this->get( 'domain_name' )
		);
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
	 * @return string
	 */
	public function getMailfowardPermalink( $absolute = false ) {
		return Mailfoward::permalink(
			$this->get( 'domain_name' ),
			$this->get( 'mailfoward_source' )
		);
	}

	/**
	 * Update the related database row
	 */
	public function update( $columns ) {
		query_update( self::T, $columns, sprintf(
			"domain_ID = %d AND mailfoward_source = '%s'",
			$this->getDomainID(),
			esc_sql( $this->get( 'mailfoward_source' ) )
		) );
	}

	/**
	 * Get the mailfoward permalink
	 *
	 * @return string
	 */
	public static function permalink( $domain, $mailfoward ) {
		return sprintf(
			'%s/%s/%s',
			ROOT . '/mailfoward.php',
			$domain,
			$mailfoward
		);
	}
}
