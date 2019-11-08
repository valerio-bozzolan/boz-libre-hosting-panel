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
 * This is the template for the mailboxes list
 *
 * Called from:
 * 	template/domain.php
 */

// unuseful when load directly
defined( 'BOZ_PHP' ) or die;

// domain mailboxes
$mailboxes = $domain->factoryMailbox()
	->select( [
		'domain_name',
		'mailbox_username',
		'mailbox_receive',
	] )
	->queryGenerator();

// total number of mailboxes
$count = DB::instance()->affectedRows();
?>

<!-- mail boxes -->
<h3><?php printf(
	__( "Your %s" ),
	__( "mailboxes" )
) ?></h3>

<?php template( 'mailbox-description', [
	'mailbox' => null,
] ) ?>

<?php if( $mailboxes->valid() ): ?>
	<ul>
		<?php foreach( $mailboxes as $mailbox ): ?>
			<li>
				<code><?= HTML::a(
					$mailbox->getMailboxPermalink(),
					esc_html( $mailbox->getMailboxAddress() )
				) ?></code>
			</li>
		<?php endforeach ?>
	</ul>
<?php endif ?>

<p><?= esc_html( sprintf(
	__( "Your Plan \"%s\" allows %s %s." ),
	$plan->getPlanName(),
	$plan->getPlanMailboxes(),
	__( "Mailboxes" )
) ) ?></p>

<?php if( $plan->getPlanMailboxes() > $count || has_permission( 'edit-email-all' ) ): ?>
	<p><?php the_link( Mailbox::permalink( $domain->getDomainName() ), __( "Create" ) ) ?></p>
<?php endif ?>

<!-- end mail boxes -->

