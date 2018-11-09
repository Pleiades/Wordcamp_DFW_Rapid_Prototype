<?php


// Only display if is admin page.
if ( is_admin() ) {
	new Agent_Focused_Pro_Dashboard_Widget;
}

class Agent_Focused_Pro_Dashboard_Widget {

	// Constructor for the Agent Focused dashboard widget and styles.
	public function __construct() {

		add_action( 'wp_dashboard_setup', array( $this, 'af_custom_dashboard_widget' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'af_load_dash_styles' ) );

	}

	// Adds widget styles to only the admin dashboard page.
	public function af_load_dash_styles( $hook ) {

		if ( 'index.php' !== $hook ) {
			return;
		}

		wp_enqueue_style( 'af-dash-widget-css', get_stylesheet_directory_uri(). '/lib/dashboard-widget/agentfocused-dash-css.css' );
	}

	// Setup the dashboard widget.
	public function af_custom_dashboard_widget() {

		wp_add_dashboard_widget(
			'agentfocused_support_widget',
			__( 'Agent Focused Pro Theme Support', 'agentfocused' ),
			array( $this, 'agentfocused_dashboard_theme_help' )
		);

	}

	// Display the content for the dashboard widget.
	public function agentfocused_dashboard_theme_help() {

		echo '<div class="logo"><img class="aligncenter" src="' .esc_url( get_stylesheet_directory_uri(). '/lib/dashboard-widget/winning-agent-logo.png' ) .'" width="222" height="60" alt="Winning Agent Logo"></div>';

		echo '<p>'. __( 'Welcome to the Agent Focused Pro Theme!', 'agentfocused' ) .'<br />'. __( 'Need help? Here are some helpful links.', 'agentfocused' ). '</p>
			<hr>';
		echo '<p><a href="'. esc_url( 'http://demo.winningagent.com/agent-focused/' ) .'" target="_blank">'. __( 'Agent Focused Pro Demo', 'agentfocused' ) .'</a></p>';
		echo '<p><a href="'. esc_url( 'https://my.winningagent.com/agent-focused-pro-theme-setup/' ) .'" target="_blank">'. __( 'Agent Focused Pro Set Up Instructions', 'agentfocused' ) .'</a></p>';
		echo '<p>'. __( 'Have questions? We have answers!', 'agentfocused' ) .'<br /><a href="'. esc_url( 'http://www.winningagent.com/support/' ) .'" target="_blank">'. __('Contact Support!', 'agentfocused' ) .'</a></p>
			<hr>';
		echo '<p>'. __( 'Do you need an IDX service?', 'agentfocused' ) .'<br /><a href="'. esc_url( 'http://www.winningagent.com/go/idxbrokeraf/' ) .'" target="_blank">'. __( 'Purchase IDX Broker', 'agentfocused' ) .'</a></p>';
		echo '<p><a href="'. esc_url( 'http://www.winningagent.com/go/gravity-forms/' ) .'" target="_blank">'. __( 'Purchase Gravity Forms', 'agentfocused' ) .'</a></p>';

	}
}


