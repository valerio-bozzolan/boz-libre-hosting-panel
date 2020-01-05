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
 * This is the template for an User
 *
 * Called from:
 * 	user.php
 *
 * Available variables:
 * 	$user         object|null
 *	$new_password string|null
 *  $user_domains object|null (generator)
 */

// unuseful when load directly
defined( 'BOZ_PHP' ) or die;
?>

<!-- name, surname, ... -->
<form method="post" class="card">
	<?php form_action( 'save-user' ) ?>

	<div class="form-group">
		<label for="user-email"><?= esc_html( __( "E-mail" ) ) ?></label>
		<input type="email" name="email"<?= $user ? value( $user->getUserEmail() ) : '' ?> class="form-control" />
	</div>
	<div class="form-group">
		<label for="user-name"><?= esc_html( __( "Name" ) ) ?></label>
		<input type="text" name="name" id="user-name"<?= $user ? value( $user->getUserName() ) : '' ?> class="form-control" />
	</div>
	<div class="form-group">
		<label for="user-surname"><?= esc_html( __( "Surname" ) ) ?></label>
		<input type="text" name="surname" id="user-surname"<?= $user ? value( $user->getUserSurname() ) : '' ?> class="form-control" />
	</div>
	<div class="form-group">
		<label for="user-uid"><?= esc_html( __( "Login" ) ) ?></label>
		<input type="text" name="uid"<?= $user ? value( $user->getUserUID() ) : '' ?> class="form-control" />
	</div>
	<button type="submit" class="btn btn-primary"><?= esc_html( __( "Save" ) ) ?></button>
</form>
<!-- /name, surname -->

<!-- user domains -->
<?php if( $user_domains->valid() ): ?>
<section>
	<h3><?= esc_html( __( "Domains" ) ) ?></h3>
	<ul>
		<?php foreach( $user_domains as $domain ): ?>
			<li><?= HTML::a(
				$domain->getDomainPermalink(),
				esc_html( $domain->getDomainName() )
			) ?></li>
		<?php endforeach ?>
	</ul>
</section>
<?php endif ?>
<!-- /user domains -->

<!-- assign domain -->
<?php if( $user && has_permission( 'edit-user-all' ) ): ?>
<section>
	<form method="post">
		<h3><?= esc_html( __( "Add Domain" ) ) ?></h3>

		<?php form_action( 'add-domain' ) ?>

		<div class="form-group">
			<label for="domain-name-search"><?= esc_html( __( "Domain Name" ) ) ?></label>
			<input type="text" name="domain_name" id="domain-name-search" class="form-control" />
		</div>

		<button type="submit" class="btn btn-primary"><?= esc_html( __( "Add" ) ) ?></button>
	</form>
</section>
<?php endif ?>
<!-- /assign domain -->

<!-- password handler -->
<?php if( $user ): ?>
	<section>
		<form method="post">
			<h3><?= esc_html( __( "Password" ) ) ?></h3>
			<?php form_action( 'change-password' ) ?>

			<p>
				<?php if( $new_password ): ?>
					<?= esc_html( __( "The new password is:" ) ) ?><br />
					<input type="text" readonly<?= value( $new_password ) ?> />
				<?php endif ?>

				<button type="submit" class="btn btn-primary"><?= esc_html( __( "Password Reset" ) ) ?></button>
			</p>
		</form>
	</section>
<?php endif ?>
<!-- /password handler -->
