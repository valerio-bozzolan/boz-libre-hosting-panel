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
 * Methods for a MailboxSizeAPI class
 */
trait MailboxSizeAPITrait {

	use MailboxAPITrait;

	/**
	 * Select the MAX Mailbox quota date
	 *
	 * @return self
	 */
	public function selectMaxMailboxSizeDate() {
		return $this->select( 'MAX( mailboxsize_date ) AS max_mailboxsize_date' )
		            ->groupBy( 'mailbox_ID' );
	}

	/**
	 * Assure that this is only the more updated Mailbox quota
	 *
	 * @return self
	 */
	public function whereMailboxSizeIsLast() {

		// subquery with a maximum constraint
		$max = ( new MailboxSizeAPI( null, false ) )
			->fromCustom( DB::instance()->getTable( 'mailboxsize', 'mailboxsize_sub' ) )
			->equals( 'mailboxsize.mailbox_ID', 'mailboxsize_sub.mailbox_ID' )
			->selectMaxMailboxSizeDate()
			->getQuery();

		return $this->where( sprintf( 'mailboxsize_date = (%s)', $max ) );
	}
}

/**
 * MailboxSize API
 */
class MailboxSizeAPI extends Query {

	use MailboxSizeAPITrait;

	/**
	 * Univoque column name to the Mailbox ID
	 */
	protected $MAILBOX_ID = 'mailboxsize.mailbox_ID';

	/**
	 * Constructor
	 *
	 * @param object $db   Database
	 * @param mixed  $from Set to false to avoid to use the default FROM
	 */
	public function __construct( $db = null, $from = true ) {

		// set database and class name
		parent::__construct( $db, MailboxSize::class );

		/**
		 * Set database table (sometime the standard alias it's not useful)
		 *
		 * See MailboxSizeAPI class.
		 */
		if( $from ) {
			$this->from( MailboxSize::T );
		}
	}

}
