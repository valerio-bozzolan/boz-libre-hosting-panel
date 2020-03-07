<?php
# Copyright (C) 2018, 2019, 2020 Valerio Bozzolan
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
 * Class dedicated to the deletion of a Mailbox
 *
 * This class is called from command line scripts
 * and not from the web interface.
 */
class MailboxDestroyer {

	/**
	 * Really delete a mailbox with its contents
	 *
	 * This method is executed from command line scripts,
	 * with elevated privileges.
	 *
	 * This method is NOT designed to be called from the web interface
	 * for two reasons:
	 *
	 * - IT WILL NOT WORK because the web server user is low privileged
	 * - IT MAY WORK if you are an idiot and you configured a very dangerous
	 *   webserver and mailserver: in this case your configuration and this method
	 *   would be SO MUCH DANGEROUS. Your costumers should kill you instantly.
	 *
	 * This hosting panel is not designed to give high privileges to your users,
	 * but the SYSADMIN have all the rights to do whatever he wants. Here
	 * why this method exists and for what kind of people is designed: for SYSADMINS.
	 *
	 * @param $mailbox object Mailbox to be deleted
	 */
	public static function destroy( $mailbox ) {

		// valid and sanitized mailbox pathname
		$path = $mailbox->getMailboxPath();

		// delete the phisical e-mails
		if( file_exists( $path ) ) {

			/**
			 * Now it's the time to completely remove a mailbox
			 *
			 * I've invested some time in documenting this method,
			 * so don't be STUPID and don't RUN across your room
			 * screaming OH MY GOD OH MY GOD THIS IS SO INSECURE.
			 *
			 * If you remember the 2020 Coronavirus, we should arrest you
			 * if you create such panic.
			 *
			 * Again, this method is designed to be called by
			 * SYSADMINS from a command line interface, after so much
			 * security layers in the middle, and not by any other user
			 * from a stupid web interface.
			 *
			 * Just two FAQ:
			 * - NOPE, the webserver will not be able to execute this
			 *   command because it has not enough privileges.
			 * - NOPE, even a SYSTEM ADMINISTRATOR will NOT be able to run
			 *      rm -Rf /
			 *   because, first of all, look at how Mailbox#getMailboxPath()
			 *   is sanitized.
			 * - Holy shit stop thinking about rm -RF /. It's impossible. You should
			 *   at least specify also '--no-preserve-root'. It's not possible.
			 *   If you are in panic you are stupid. That's the end of the story.
			 *
			 * -- Valerio Bozzolan Sat 07 Mar 2020 05:03:32 PM CET
			 */
			$command = sprintf(
				'rm --force --recursive -- %s',
				escapeshellarg( $path )
			);

			// try to execute the command
			system( $command, $exit_code );

			// check if the command was not executed successfully
			if( $exit_code !== 0 ) {
				throw new Exception( sprintf(
					"unable to execute this command: %s",
					$command
				) );
			}
		}

		// last but not the least - delete from database
		( new MailboxAPI() )
			->whereMailbox( $mailbox )
			->delete();
	}

}
