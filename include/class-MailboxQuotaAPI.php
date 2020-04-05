<?php
# Copyright (C) 2018, 2019, 2020 Valerio Bozzolan
# KISS Libre Hosting Panel
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

// load dependend traits
class_exists( 'MailboxAPI' );

/**
 * Methods for a MailboxQuotaAPI class
 */
trait MailboxQuotaAPITrait {

	use MailboxAPITrait;

	/**
	 * Select the MAX Mailbox quota date
	 *
	 * @return self
	 */
	public function selectMaxMailboxQuotaDate() {
		return $this->select( 'MAX( mailboxquota_date ) AS max_mailboxquota_date' )
		            ->groupBy(     'mailboxquota_date' );
	}

	/**
	 * Assure that this is only the more updated Mailbox quota
	 *
	 * @return self
	 */
	public function whereMailboxQuotaIsLast() {

		// subquery with a maximum constraint
		$max = ( new MailboxQuotaAPI( null, false ) )
			->fromCustom( DB::instance()->getTable( 'mailboxquota', 'mailboxquota_sub' ) )
			->equals( 'mailboxquota.mailbox_ID', 'mailboxquota_sub.mailbox_ID' )
			->selectMaxMailboxQuotaDate()
			->getQuery();

		return $this->where( sprintf( 'mailboxquota_date = (%s)', $max ) );
	}
}

/**
 * MailboxQuota API
 */
class MailboxQuotaAPI extends Query {

	use MailboxQuotaAPITrait;

	/**
	 * Univoque column name to the Mailbox ID
	 */
	protected $MAILBOX_ID = 'mailboxquota.mailbox_ID';

	/**
	 * Constructor
	 *
	 * @param object $db   Database
	 * @param mixed  $from Set to false to avoid to use the default FROM
	 */
	public function __construct( $db = null, $from = true ) {

		// set database and class name
		parent::__construct( $db, MailboxQuota::class );

		/**
		 * Set database table (sometime the standard alias it's not useful)
		 *
		 * See MailboxQuotaAPI class.
		 */
		if( $from ) {
			$this->from( MailboxQuota::T );
		}
	}

}
