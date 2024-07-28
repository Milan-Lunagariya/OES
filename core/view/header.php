<?php

global $nav_logo_help, $menu_top_help, $frontheader, $menu_header_help;
?>

<header class="header_container">
	<div class="menu_tablets">
		<div class="menu_tablets_container">
			<div class="login_and_register_container">
				<div class="register">
					<a href="">Register</a>
				</div>
				<div class="login">
					<a href="">Login</a>
				</div>
			</div>
			<div class="close_button_container">
				<div class="close_button">X</div>
			</div>
			
			<?php echo $frontheader->nav_tablets_view_sigle_box('#','All Category', 'text-center mb-2 main_container', '' ); ?>
			
			<div class="main_container">
				<div class="menu_header">
					<ul>
						<?php
						foreach ($menu_header_help as $name => $link) {
							echo '<li>' . $link . '</li>';
						}
						?>
					</ul>
				</div>
			</div>
			<?php echo $frontheader->nav_tablets_view_sigle_box('#','Settings', 'text-center mt-2 main_container', '' ); ?>
			<?php echo $frontheader->nav_tablets_view_sigle_box('#','Term & Conditions', 'text-center mt-2 main_container', '' ); ?>
		</div>
	</div>
	<div class="fixed_nav">
		<?php
		echo $frontheader->nav_logo($nav_logo_help);
		echo $frontheader->nav_menu_top($menu_top_help);
		echo $frontheader->nav_searchbar();
		?>
	</div>
	<div class="menu_header desktop_view">
		<ul>
			<?php
			foreach ($menu_header_help as $name => $link) {
				echo '<li>' . $link . '</li>';
			}
			?>
		</ul>
	</div>
</header>