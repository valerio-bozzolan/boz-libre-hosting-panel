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

/**
 * Methods related to an FTPAPI class
 */
trait FTPAPITrait {

	/**
	 * Join FTP and domain tables (once)
	 *
	 * @return self
	 */
	public function joinFTPDomain() {
		if( empty( $this->joinedFTPDomain ) ) {
			$this->joinedFTPDomain = true;

			$this->from( 'domain' );
			$this->equals( 'domain.domain_ID', 'ftp.domain_ID' );
		}
		return $this;
	}

	/**
	 * Filter to a certain FTP login
	 *
	 * @param string $login
	 * @return self
	 */
	public function whereFTPLogin( $login ) {
		return $this->whereStr( 'ftp_login', $login );
	}

	/**
	 * Limit to a specific FTP account
	 *
	 * @param object $ftp FTP account
	 * @return self
	 */
	public function whereFTP( $ftp ) {
		return $this->whereDomain(   $ftp )
		            ->whereFTPLogin( $ftp->getFTPLogin() );
	}

	/**
	 * Join whatever table with the FTP users table
	 *
	 * @param object
	 * @return self
	 */
	public function joinFTP() {
		return $this->joinOn( 'INNER', 'ftp', static::FTP_ID, 'ftp.ftp_ID' );
	}

}

// assure load of dependent traits
class_exists( 'DomainAPI', true );

/**
 * FTP users API
 */
class FTPAPI extends Query {

	use FTPAPITrait;
	use DomainAPITrait;

	/**
	 * Univoque domain ID column name
	 *
	 * @override
	 */
	const DOMAIN_ID = 'ftp.domain_ID';

	public function __construct( $db = null ) {
		// set database and class
		parent::__construct( $db, 'FTP' );

		// set table name
		$this->from( FTP::T );
	}

}
