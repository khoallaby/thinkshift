<?php
namespace ThinkShift\Plugin;



class Shortcodes extends Base {
	protected $the_title = 'the_title';

	public function init() {
		add_shortcode( 'ts_show_tags', array( $this, 'showTags' ) );

		#add_filter( 'no_texturize_shortcodes', array( $this, 'no_wptexturize' ) );
		#add_action('wp_head', array( $this, 'shortcode_unautop' ) );
	}


	/*
	# example shortcode
	public function shortcode( $atts, $content = null ) {
		$a = shortcode_atts( array(
			'id'        => '',
			'class'     => '',
		), $atts );

		return $this->getView( 'fileName' );
	}
	*/

	public function showTags() {
		#if ( current_user_can( 'subscriber' ) )
			return $this->getView( 'show-tags' );
		#else
		#	return null;
	}



	public function getView( $file ) {
		$dir = dirname(__FILE__) . '/../views/';
		ob_start();
		include $dir . $file . '.php';
		return ob_get_clean();

	}


	/**
	 * Removes wpautop from happening inside shortcodes
	 * http://stackoverflow.com/questions/5940854/disable-automatic-formatting-inside-wordpress-shortcodes
	 */
	public function shortcode_unautop() {
		remove_filter( 'the_content', 'wpautop' );
		add_filter( 'the_content', 'wpautop', 99 );
		add_filter( 'the_content', 'shortcode_unautop', 100 );
	}


	public function no_wptexturize( $shortcodes ) {
		$shortcodes[] = 'row';
		return $shortcodes;
	}


}

add_action( 'plugins_loaded', array( \ThinkShift\Plugin\Shortcodes::get_instance(), 'init' ));