<?php
/*
Plugin Name: Comic Rocket Ad Network Widget
Plugin URI: https://www.comic-rocket.com/
Description: Easily include Comic Rocket's network ad box on your webcomic!
Version: 0.5
Author: Philip M. Hofer (Frumph)
Author URI: http://frumph.net/

Copyright 2013 Philip M. Hofer (Frumph)  (email : philip@frumph.net)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

// load the comicrocket language translations
load_plugin_textdomain('comicrocket', false, basename( dirname( __FILE__ ) ) . '/lang');

/**
 * Get the path to the plugin folder.
 */
function comicrocket_get_plugin_path() {
	return PLUGINDIR . '/' . preg_replace('#^.*/([^\/]*)#', '\\1', dirname(plugin_basename(__FILE__)));
}
		
class comicrocket_widget extends WP_Widget {

	function comicrocket_widget() {
		$widget_ops = array('classname' => __CLASS__, 'description' => __('ComicRocket Widget Box', 'comicrocket') );
		$this->WP_Widget(__CLASS__, __('ComicRocket Widget Box', 'comicrocket'), $widget_ops);
	}
	
	function widget($args, $instance) {
		global $post, $wp_query;
		extract($args, EXTR_SKIP);
		$ID_num = preg_replace('/\D/', '', $args['widget_id']);
		echo $before_widget;
		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']); 
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
		echo "<script>(function(d,t,p){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.src='http'+(d.location.protocol=='https:'?'s':'')+'://www.comic-rocket.com/metrics.js#'+(p||\"\");s.parentNode.insertBefore(g,s);})(document,'script','key=".$instance['api_key']."');</script>\r\n";
		echo "<center>";
		echo '<div id="comic-rocket-'.$ID_num.'" data-comic-rocket-box="160x600"></div>'."\r\n";
		echo "</center>";
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['api_key'] = strip_tags($new_instance['api_key']);
		return $instance;
	}
	
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'api_key' => '') );
		$title = strip_tags($instance['title']);
		$api_key = strip_tags($instance['api_key']);
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title (Leave blank for no title):', 'comicrocket'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
		<br />
		<p><label for="<?php echo $this->get_field_id('api_key'); ?>"><?php _e('API Key:', 'comicrocket'); ?> <input class="widefat" id="<?php echo $this->get_field_id('api_key'); ?>" name="<?php echo $this->get_field_name('api_key'); ?>" type="text" value="<?php echo esc_attr($api_key); ?>" /></label></p>	
	<?php
	}
}

add_action( 'widgets_init', create_function('', 'return register_widget("comicrocket_widget");') );
