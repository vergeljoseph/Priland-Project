<?php 

function forge_element_wpwidget_calendar($atts){
	$output = '';
	$output .= '<div class="forge-widget">';
	ob_start();
	the_widget('WP_Widget_Calendar', $atts);
	$output .= ob_get_clean();
	$output .= '</div>';
	return $output;
}


add_filter('forge_elements', 'forge_element_wpwidget_calendar_metadata');
function forge_element_wpwidget_calendar_metadata($data){
	$data['widget_calendar'] = array(
	'title' => __('Widget: Calendar', 'forge'),
	'description' => __('Native WordPress calendar widget', 'forge'),
	'group' => 'layout',
	'callback' => 'forge_element_wpwidget_calendar',
	'fields' => array(
		array(
		'name' => 'title',
		'label' => __('Title', 'forge'),
		'type' => 'text',
		'default' => ''),
	));
	
	return $data;
}