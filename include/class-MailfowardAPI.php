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
 * E-mail fowarding API
 */
class MailfowardAPI extends DomainAPI {

	public function __construct() {
		Query::__construct();
		$this->from( Mailfoward::T );
		$this->defaultClass( 'Mailfoward' );
	}

	/**
	 * Join mailfowardes and domain (once)
	 *
	 * @return self
	 */
	public function joinMailfowardDomain() {
		if( empty( $this->joinedMailfowardDomain ) ) {
			$this->joinedMailfowardDomain = true;
			$this->from( 'domain' );
			$this->equals( 'domain.domain_ID', 'mailfoward.domain_ID' );
		}
		return $this;
	}

	/**
	 * Filter to a certain mail fowarding source
	 *
	 * @param mailfoward_source string
	 * @return self
	 */
	public function whereMailfowardSource( $mailfoward_source ) {
		return $this->whereStr( 'mailfoward_source', $mailfoward_source );
	}

	/**
	 * Filter to a certain domain ID
	 *
	 * @param $domain_ID int
	 * @return self
	 * @override
	 */
	public function whereDomainID( $domain_ID ) {
		return $this->whereInt( 'mailfoward.domain_ID', $domain_ID );
	}

}
