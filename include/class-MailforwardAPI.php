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
 * E-mail forwarding API
 */
class MailforwardfromAPI extends DomainAPI {

	/**
	 * Domain ID column name
	 *
	 * @override
	 */
	const DOMAIN_ID = Mailforwardfrom::T . DOT . 'domain_ID';

	/**
	 * @constructor
	 */
	public function __construct() {
		Query::__construct();
		$this->from( Mailforwardfrom::T );
		$this->defaultClass( 'Mailforwardfrom' );
	}

	/**
	 * Join mailforwardes and domain (once)
	 *
	 * @return self
	 */
	public function joinMailforwardfromDomain() {
		if( empty( $this->joinedMailforwardfromDomain ) ) {
			$this->joinedMailforwardfromDomain = true;
			$this->from( 'domain' );
			$this->equals( 'domain.domain_ID', 'mailforwardfrom.domain_ID' );
		}
		return $this;
	}

	/**
	 * Filter to a certain mail forwarding username
	 *
	 * @param mailforward_source string
	 * @return self
	 */
	public function whereMailforwardfromUsername( $username ) {
		return $this->whereStr( 'mailforwardfrom_username', $username );
	}

}
