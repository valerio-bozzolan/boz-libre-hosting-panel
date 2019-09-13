<?php
# Copyright (C) 2019 Valerio Bozzolan
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
class_exists( 'Domain' );

/**
 * Methods for an FTP class
 */
trait FTPTrait {

	use DomainTrait;

	/**
	 * Get the FTP login username
	 *
	 * @return string E-mail
	 */
	public function getFTPLogin() {
		return $this->get( 'ftp_login' );
	}

	/**
	 * Check if this FTP account is active
	 *
	 * @return bool
	 */
	public function isFTPActive() {
		return $this->get( 'ftp_active' );
	}

	/**
	 * Get the mailbox permalink
	 *
	 * @return string
	 */
	public function getFTPPermalink( $absolute = false ) {
		return FTP::permalink(
			$this->getDomainName(),
			$this->getFTPLogin()
		);
	}

	protected function normalizeFTP() {
		$this->normalizeDomain();
		$this->booleans( 'ftp_active' );
		$this->integers( 'ftp_ulbandwidth',
		                 'ftp_dlbandwidth',
		                 'ftp_quotasize',
		                 'ftp_quotafiles'
		);
	}

}

/**
 * An FTP user
 */
class FTP extends Queried {

	use FTPTrait;

	/**
	 * Table name
	 */
	const T = 'ftp';

	/**
	 * Constructor
	 *
	 * Normalize the object obtained from the database
	 */
	public function __construct() {
		$this->normalizeFTP();
	}

	/**
	 * Get the FTP permalink from domain name and FTP login
	 *
	 * @param string $domain Domain name
	 * @param string $login FTP user login
	 * @return string
	 */
	public static function permalink( $domain, $login = null ) {
		$url = sprintf( '%s/%s', ROOT . '/ftp.php', $domain );
		if( $login ) {
			$url .= "/$login";
		}
		return $url;
	}

}
