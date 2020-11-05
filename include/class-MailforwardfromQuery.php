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

// load DomainAPITrait;
class_exists( 'DomainAPI', true );

trait MailforwardfromQueryTrait {

	/**
	 * Filter to a specific mailforward username
	 *
	 * @param $username string
	 * @return self
	 */
	public function whereMailforwardfromUsername( $username ) {
		return $this->whereStr( 'mailforwardfrom_username', $username );
	}

	public function whereMailforwardfromID( $id ) {
		return $this->whereInt( $this->MAILFORWARDFROM_ID, $id );
	}

	public function whereMailforwardfrom( $mailforwardfrom ) {
		return $this->whereMailforwardfromID( $mailforwardfrom->getMailforwardfromID() );
	}

}

/**
 * Execute query againsts a Mailforwardfrom
 */
class MailforwardfromQuery extends Query {

	use MailforwardfromQueryTrait;
	use DomainAPITrait;

	/**
	 * Univoque Domain ID column name
	 */
	const DOMAIN_ID = 'mailforwardfrom.domain_ID';

	/**
	 * Column name of the Mailforwardfrom ID
	 */
	protected $MAILFORWARDFROM_ID = 'mailforwardfrom.mailforwardfrom_ID';

	/**
	 * Construct
	 */
	public function __construct() {
		parent::__construct();

		$this->from( 'mailforwardfrom' );
		$this->defaultClass( 'Mailforwardfrom' );
	}

}
