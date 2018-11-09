<?php
/**
 * Register Featured Community and Dashboard Widgets
 *
 * @package Agent Focused Pro / Widgets
 * @author  Marcy Diaz for Winning Agent
 * @license GPL-2.0+
 * @link    http://demo.winningagent.com/agent-focused/
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'widgets_init', 'agentfocused_register_widget' );

// Register Featured Community Widget.
function agentfocused_register_widget() {

	register_widget( 'Agent_Focused_Pro_Featured_Community' );

}

require get_stylesheet_directory() . '/lib/widgets/featured-community-widget.php';


