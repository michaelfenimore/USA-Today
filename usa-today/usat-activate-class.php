<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @link       www.usatoday.com
 * @since      1.0.0
 * @package    usa-today
 * @author     Michael Fenimore <mfenimore70@gmail.com>
 */
class Usat_Activator {

	/**
	 *
	 * Upon plugin activation this append a blogs most recent article to the bottom of every post.
	 *
	 * Adds two actions
	 * 	the_content: modifies a posts content by appending the most recent article to the end on the article
	 * 	wp_enqueue_scripts: lods the css for the most recent article container.
	 *
	 * @since    1.0.0
	 */
	public static function usat_activate() {
		add_action( 'the_content', array( Usa_Today, 'get_most_recent_article' ), 10, 1 );
		add_action( 'wp_enqueue_scripts', array( Usat_Activator, 'usat_style' ) );
	}
	
	public static function usat_style() {
		wp_enqueue_style( 'usa-today', plugins_url( '', basename( dirname(__FILE__) ) ) .'/usa-today/css/usat.css', array(), '1.0.0' );
	}

}