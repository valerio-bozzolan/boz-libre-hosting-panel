<?php
# Copyright (C) 2020, 2025 Valerio Bozzolan
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

/*
 * This is the template for the Domain Activity
 *
 * Called from:
 * 	template/domain.php
 *
 * Available variables:
 * 	$domain         object|null
 */

$domain_users = (new DomainUserAPI())
	->whereDomain($domain)
	->joinUser()
	->orderByUserFirm()
	->queryResults();

$admins = (new UserAPI())
	->whereStr('user_role', 'admin')
	->orderByUserFirm()
	->queryResults();

?>

<?php if( $domain ): ?>

	<section>
		<h3><?= __( "Authorized Users" ) ?></h3>
		<p><?= __("These users can manage every aspect of your domain:") ?></p>

		<ul>
			<?php foreach ($domain_users as $domain_user): ?>
			<li>
				<?= $domain_user->getUserFirm() ?>
				<span class="badge badge-secondary">
					<?= __("since") ?>
					<?= $domain_user->getDomainUserCreationDate()->format('Y-m-d') ?>
				</span>
			</li>
			<?php endforeach ?>
		</ul>

		<p><?= __("Additionally, please note that this panel is assisted by the following blessed system administrators, who therefore have full access, to further assist you in case of need. Feel free to contact us:") ?></p>

		<ul>
			<?php foreach ($admins as $admin): ?>
			<li>
				<?= $admin->getUserFirm() ?><br />
				<a href="mailto:<?= esc_attr( $admin->getUserEmail() ) ?>"><?= esc_html( $admin->getUserEmail() ) ?></a>
			</li>
			<?php endforeach ?>
		</ul>

	</section>

<?php endif ?>
