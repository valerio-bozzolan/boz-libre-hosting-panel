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

/*
 * This is the domain edit page
 */

// load framework
require 'load.php';

// wanted domain
list( $domain_name ) = url_parts( 1 );

// retrieve domain
$domain = ( new DomainAPI )
	->whereStr( 'domain_name', $domain_name )
	->whereDomainIsEditable()
	->queryRow();

// 404?
$domain or PageNotFound::spawn();

// domain mailboxes
$mailboxes = ( new MailboxFullAPI() )
	->select( [
		'domain_name',
		'mailbox_username',
		'mailbox_receive',
	] )
	->whereMailboxDomainID( $domain->get( 'domain_ID' ) )
	->queryGenerator();

// spawn header
Header::spawn( [
	'title' => sprintf(
		__( "Domain: %s" ),
		"<em>" . esc_html( $domain_name ) . "</em>"
	),
] );
?>

	<?php if( $mailboxes->valid() ): ?>
		<h3><?php printf(
			__( "Your %s" ),
			__( "mailboxes" )
		) ?></h3>
		<ul>
			<?php foreach( $mailboxes as $mailbox ): ?>
				<li>
					<code>
						<?php echo HTML::a(
						$mailbox->getMailboxPermalink(),
						$mailbox->getMailboxAddress()
					) ?>
					</code>
				</li>
			<?php endforeach ?>
		</ul>
	<?php endif ?>

<?php
// spawn the footer
Footer::spawn();
