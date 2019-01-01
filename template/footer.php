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
 * This is the template for the website footer
 *
 * Called from:
 * 	include/class-Footer.php
 *
 * Available variables:
 * 	$args array
 */

// unuseful when load directly
defined( 'BOZ_PHP' ) or die;

load_module( 'footer' );
?>

		</div>
		<!-- /sidebar content -->

		<!-- sidebar -->
		<?php if( $args[ 'sidebar' ] ): ?>
			<div class="col-sm-4">
				<?php template( 'sidebar' ) ?>
			</div>
		<?php endif ?>
		<!-- /sidebar -->

	<!-- page row -->
	</div>

	<?php if( $args[ 'container' ] ): ?>
		</div>
		<!-- /page container -->
	<?php endif ?>

	<hr />
	<!-- footer -->
	<div class="container">
		<div class="row">

			<!-- help -->
			<?php if( CONTACT_EMAIL ): ?>
			<div class="col-sm-2">
				<?php _e( "Help:" ) ?><br />
				<?php email_blur( CONTACT_EMAIL ) ?>
			</div>
			<?php endif ?>
			<!-- end help -->

			<!-- fork -->
			<?php if( REPO_URL ): ?>
			<div class="col-sm-2">
				<?php _e( "Contribute:" ) ?><br />
				<a href="<?php echo REPO_URL ?>" target="_blank"><?php _e( "project repository" ) ?></a>
			</div>
			<?php endif ?>
			<!-- end for -->

		</div>
	</div>
	<!-- /footer -->
</body>
</html>
