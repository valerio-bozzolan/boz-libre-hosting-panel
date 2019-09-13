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

// load dependent traits
class_exists( 'Domain' );

trait MailboxTrait {

	use DomainTrait;

	/**
	 * Get the mailbox username
	 *
	 * @return string
	 */
	public function getMailboxUsername() {
		return $this->get( 'mailbox_username' );
	}

	/**
	 * Get the mailbox address
	 *
	 * @return string E-mail
	 */
	public function getMailboxAddress() {
		return sprintf( "%s@%s",
			$this->get( 'mailbox_username' ),
			$this->get( 'domain_name' )
		);
	}

	/**
	 * Get the mailbox permalink
	 *
	 * @return string
	 */
	public function getMailboxPermalink( $absolute = false ) {
		return Mailbox::permalink(
			$this->get( 'domain_name' ),
			$this->get( 'mailbox_username' )
		);
	}

	/**
	 * Update this mailbox password
	 *
	 * @param  string $password
	 * @return string
	 */
	public function updateMailboxPassword( $password = null ) {
		if( ! $password ) {
			$password = generate_password();
		}

		$enc_password = Mailbox::encryptPassword( $password );

		// update
		( new MailboxAPI() )
			->whereMailbox( $this )
			->update( [
				new DBCol( 'mailbox_password', $enc_password, 's' ),
			] );

		return $password;
	}

	/**
	 * Normalize a Mailbox after being fetched from database
	 */
	protected function normalizeMailbox() {
		$this->normalizeDomain();
		$this->booleans( 'mailbox_receive' );
	}

}

/**
 * A mailbox
 */
class Mailbox extends Queried {

	use MailboxTrait;

	/**
	 * Database table
	 */
	const T = 'mailbox';

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->normalizeMailbox();
	}

	/**
	 * Get the mailbox permalink
	 *
	 * @param $domain string
	 * @param $mailbox string
	 * @param $absolute boolean
	 * @return string
	 */
	public static function permalink( $domain, $mailbox = null, $absolute = false ) {
		$part = site_page( 'mailbox.php', $absolute ) . _ . $domain;
		if( $mailbox ) {
			$part .= _ . $mailbox;
		}
		return $part;
	}

	/**
	 * Encrypt a password
	 *
	 * TODO: do not hardcode to my Dovecot configuration
	 */
	public static function encryptPassword( $password ) {
		$salt = bin2hex( openssl_random_pseudo_bytes( 3 ) );
		return '{SHA512-CRYPT}' . crypt( $password, "$6$$salt" );
	}

}
