<?php
# Copyright (C) 2019 Valerio Bozzolan
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
require '../load.php';

// spawn header
Header::spawn( [
	'title' => __( "Users" ),
] );

$pager = new UserPager();
?>

<form method="get">
	<p>
		<label for="user-email"><?= esc_html( __( "E-mail" ) ) ?></label>
		<input id="user-email" type="email" name="email"<?= value( $pager->getArg( 'email' ) ) ?> />
	</p>
	<p>
		<label for="user-login"><?= esc_html( __( "Login" ) ) ?></label>
		<input id="user-login" type="text" name="uid"<?= value( $pager->getArg( 'uid' ) ) ?> />
	</p>
	<p>
		<button type="submit" class="btn btn-default"><?= esc_html( __( "Search" ) ) ?></button>
	</p>
</form>

<table class="table">
	<thead>
		<tr>
			<th><?= esc_html( __( "Login"  ) ) ?></th>
			<th><?= esc_html( __( "Surname" ) ) ?></th>
			<th><?= esc_html( __( "Name"    ) ) ?></th>
			<th><?= esc_html( __( "Role"    ) ) ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach( $pager->createPagedQuery()->queryGenerator() as $user ): ?>
			<tr>
				<td><?= HTML::a(
					$user->getUserPermalink(),
					$user->getUserUID()
				) ?></td>
				<td><?= esc_html( $user->getUserSurname()   ) ?></td>
				<td><?= esc_html( $user->getUserName()      ) ?></td>
				<td><?= esc_html( $user->getUserRoleLabel() ) ?></td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>

<?php
// spawn the footer
Footer::spawn();
