<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo('charset'); ?>"/>
		<title>
			<?php wp_title(''); ?>
		</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<?php wp_head(); ?>
		<?php do_action('forge_builder_head'); ?>
	</head>
	<?php remove_all_filters('body_class'); ?>
	<body class="forge-builder-active">
		<?php do_action('forge_builder_wrapper'); ?>
		<div class="forge-builder-wrapper">
			<?php do_action('forge_builder_body'); ?>
			<div class="forge-builder-wrapper-body">
				<div class="forge-builder-wrapper-content">
					<iframe class="forge-builder-iframe" id="forge-builder-iframe" src="<?php echo add_query_arg('forge_layout', '', get_permalink(get_the_ID())); ?>"></iframe>
				</div>
			</div>
		</div>
		<?php wp_footer(); ?>
		<?php do_action('forge_builder_footer'); ?>
	</body>
</html>