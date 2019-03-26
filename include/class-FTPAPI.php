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
 * FTP users API
 */
class FTPAPI extends DomainAPI {

	public function __construct() {
		Query::__construct();
		$this->from( FTP::T );
		$this->defaultClass( 'FTP' );
	}

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
	 * Filter to a certain domain ID
	 *
	 * @param int $id int
	 * @return self
	 * @override
	 */
	public function whereDomainID( $id ) {
		return $this->whereInt( 'ftp.domain_ID', $id );
	}

}
