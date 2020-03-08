<?php
# Copyright (C) 2019, 2020 Valerio Bozzolan
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
	 * Get the sanitized raw directory
	 *
	 * This value is sanitized before saving but it can be a malicious string
	 * if the database is overtaken so it's sanitized again.
	 *
	 * It always start with a filesystem directory separator.
	 *
	 * @return string
	 */
	public function getFTPRawDirectory() {

		// return a default '/' if empty
		$path = $this->get( 'ftp_directory' );
		if( !$path ) {
			return '/';
		}

		// even if it was sanitized during creation, double-sanitize
		// to protect against hacked databases
		validate_subdirectory( $path );

		return $path;
	}

	/**
	 * Get the sanitized absolute FTP directory pathname
	 *
	 * @return string
	 */
	public function getFTPAbsoluteDirectory() {

		// get the sanitized domain base path
		$absolute_base = $this->getDomainBasePath();

		// get the sanitized FTP raw directory
		$relative_path = $this->getFTPRawDirectory();

		// at this point the absolute realpath does not end with a slash
		// at this point the relative path always start with a slash
		return $absolute_base . __ . $relative_path;
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

	/**
	 * Encrypt an FTP user password
	 *
	 * @param $password string Clear text password
	 * @return          string One-way encrypted password
	 */
	public static function encryptPassword( $password ) {
		global $HOSTING_CONFIG;

		// the FTP password encryption mechanism can be customized
		if( isset( $HOSTING_CONFIG->FTP_ENCRYPT_PWD ) ) {
			return call_user_func( $HOSTING_CONFIG->FTP_ENCRYPT_PWD, $password );
		}

		// or then just a default behaviour

		/**
		 * The default behaviour is to adopt the crypt() encryption mechanism
		 * with SHA512 and some random salt. It's strong enough nowadays.
		 *
		 * Read your FTP server documentation, whatever you are using.
		 * We don't know how your infrastructure works, so we don't know
	 	 * how you want your password encrypted in the database and what kind
		 * of password encryption mechanisms your FTP server supports.
		 *
		 * In short if you are using PureFTPd this default configuration may work
		 * because you may have PureFTPd configured as follow:
		 *  ...
		 *  MYSQLCrypt crypt
		 *  ...
		 *
		 * You can read more here:
		 *   https://download.pureftpd.org/pub/pure-ftpd/doc/README.MySQL
		 *
		 * Anyway you can use whatever FTP server that talks with a MySQL database
		 * and so you should adopt the most stronger encryption mechanism available.
		 */

		$salt = bin2hex( openssl_random_pseudo_bytes( 3 ) );
		return '{SHA512-CRYPT}' . crypt( $password, "$6$$salt" );
	}
}
