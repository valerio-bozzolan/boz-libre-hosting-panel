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
 * Mailbox API
 */
class MailboxAPI extends DomainAPI {

	public function __construct() {
		Query::__construct();
		$this->from( Mailbox::T );
		$this->defaultClass( 'Mailbox' );
	}

	/**
	 * Join mailboxes and domain (once)
	 *
	 * @return self
	 */
	public function joinMailboxDomain() {
		if( empty( $this->joinedMailboxDomain ) ) {
			$this->from( 'domain' );
			$this->equals( 'domain.domain_ID', 'mailbox.domain_ID' );

			$this->joinedMailboxDomain = true;
		}
		return $this;
	}

	/**
	 * Check if I can edit this mailbox
	 *
	 * Actually it just checks if you can edit the whole domain.
	 *
	 * @return boolean
	 */
	public function whereMailboxIsEditable() {
		return $this->whereDomainIsEditable();
	}
}
