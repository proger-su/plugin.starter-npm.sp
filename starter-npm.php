<?php
/*
  Plugin Name: Starter NPM
  Description: Starter NPM
  Author: Sergey P.
  Author URI: http://proger.su
  Version: 1.0
 */

defined( 'ABSPATH' ) or die( 'No direct access' );

define( 'STARTER_NPM_PATH', plugin_dir_path( __FILE__ ) );
define( 'STARTER_NPM_URL', plugin_dir_url( __FILE__ ) );

require_once 'inc/helpers.php';

class starterNPM {

	protected static $_instance;

	public static function getInstance() {
		if ( self::$_instance === null ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	function __construct() {
		add_action( 'activate_' . basename( __DIR__ ) . '/' . basename( __FILE__ ), array( __CLASS__, '_action_activate' ) );

		add_action( 'init', array( __CLASS__, '_action_register_gutenberg_blocks' ) );
		add_action( 'wp_footer', array( __CLASS__, '_action_wp_footer' ) );

		add_action( 'wp_enqueue_scripts', array( __CLASS__, '_action_enqueue_scripts' ) );

		add_action( 'admin_enqueue_scripts', array( __CLASS__, '_action_admin_enqueue_scripts' ) );
	}

	static function _action_enqueue_scripts() {
		$plugin_style  = 'dist/css/styles.css';
		$plugin_script = 'dist/js/scripts.js';

		wp_enqueue_style( 'starter-npm', STARTER_NPM_URL . $plugin_style, array( 'dashicons' ), filemtime( STARTER_NPM_PATH . $plugin_style ) );
		wp_enqueue_script( 'starter-npm', STARTER_NPM_URL . $plugin_script, array( 'jquery', ), filemtime( STARTER_NPM_PATH . $plugin_script ), true );

		wp_localize_script(
			'starter-npm',
			'starterNPMParams',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			)
		);
	}

	static function _action_admin_enqueue_scripts() {
		$plugin_style  = 'dist/css/admin.css';
		$plugin_script = 'src/js/admin.js';

		wp_enqueue_style( 'starter-npm', STARTER_NPM_URL . $plugin_style, array(), filemtime( STARTER_NPM_PATH . $plugin_style ) );
		wp_enqueue_script( 'starter-npm', STARTER_NPM_URL . $plugin_script, array( 'jquery', ), filemtime( STARTER_NPM_PATH . $plugin_script ), true );

		wp_localize_script(
			'starter-npm',
			'starterNPMParams',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			)
		);
	}

	static function _action_activate() {
	}

	static function _action_wp_footer() {
		include_once( STARTER_NPM_PATH . 'src/img/icons.svg' );
	}

	static function _action_register_gutenberg_blocks() {
		//!!!! Update webpack.mix.js after add new blocks
		$blocks = array(
			'block-name' => array(
				'attr' => '',
			)
		);

		$localize = array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		);

		foreach ( $blocks as $block => $attrs ) {
			wp_register_script(
				"starter-npm-{$block}-editor",
				STARTER_NPM_URL . "dist/blocks/{$block}/editor.js",
				array( 'wp-blocks', 'wp-element' ),
				filemtime( STARTER_NPM_PATH . "dist/blocks/{$block}/editor.js" ),
				true
			);

			wp_register_script(
				"starter-npm-{$block}",
				STARTER_NPM_URL . "dist/blocks/{$block}/view.js",
				array( 'wp-blocks', 'wp-element' ),
				filemtime( STARTER_NPM_PATH . "dist/blocks/{$block}/view.js" ),
				true
			);

			wp_localize_script( "advents-{$block}-editor", preg_replace( '/\W+/', '_', "{$block}BlockParams" ), $localize );
			wp_localize_script( "advents-{$block}", preg_replace( '/\W+/', '_', "{$block}BlockParams" ), $localize );

			wp_register_style(
				"starter-npm-{$block}-editor",
				STARTER_NPM_URL . "dist/blocks/{$block}/editor.css",
				array(),
				filemtime( STARTER_NPM_PATH . "dist/blocks/{$block}/editor.css" )
			);

			wp_register_style(
				"starter-npm-{$block}",
				STARTER_NPM_URL . "dist/blocks/{$block}/view.css",
				array(),
				filemtime( STARTER_NPM_PATH . "dist/blocks/{$block}/view.css" )
			);

			register_block_type(
				"starter-npm/{$block}",
				array(
					'render_callback' => function ( $attributes, $content ) use ( $block ) {
						ob_start();
						require STARTER_NPM_PATH . "blocks/{$block}/{$block}.php";

						return trim( ob_get_clean() );
					},
					'editor_script'   => "starter-npm-{$block}-editor",
					'editor_style'    => "starter-npm-{$block}-editor",
					'style'           => "starter-npm-{$block}",
					'script'          => "starter-npm-{$block}",
					'attributes'      => $attrs,
				)
			);


		}
	}

}

starterNPM::getInstance();
