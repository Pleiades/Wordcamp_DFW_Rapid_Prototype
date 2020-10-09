<?php

/**
 * Plugin Name: Pleiades Rapid Prototype Data Loader
 * Plugin URI: http://pleiadesservices.com	
 * Description: Load customized mock data for demonstration and rapid prototyping
 * Author: Nicholas Batik
 * Author URI: http://pleiadesservices.com
 * Version: 1.3
 *
 * Copyright: (c) 2017
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @author    Nicholas Batik
 * @copyright Copyright (c) 2017, Nicholas Batik
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 *
 * Not unto us, O LORD, not unto us, but unto thy name give glory, for thy mercy, and for thy truth's sake. 
 * א לא לנו יהוה לא-לנו  כי-לשמך תן כבוד--על-חסדך על-אמתך
 * Psalm 115:1
 */

// include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
// require_once( ABSPATH . 'wp-content/plugins/pods/init.php' );

namespace RapidPrototype;


define ( 'BASE_PATH', WP_PLUGIN_DIR  . DIRECTORY_SEPARATOR . __NAMESPACE__ . DIRECTORY_SEPARATOR );

spl_autoload_register( function( $class ) {
	
	if ( false !== strpos( $class, __NAMESPACE__ ) ) {
	
		$classfile = str_replace( __NAMESPACE__, '', str_replace( '\\', DIRECTORY_SEPARATOR, $class ) );
		$filename  = BASE_PATH . 'inc' . $classfile . '.php';
		
		include $filename;
	}
});


register_activation_hook( __FILE__, 'ppsc_mock_data_loader' );

// _______________________________________________________________________________________

function ppsc_mock_data_loader() {
    
	global $wpdb;
	
	require_once 'vendor/autoload.php';
    // require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	
}

// _______________________________________________________________________________________




if( defined( 'WP_CLI' ) && WP_CLI ) {

	class Data_Loader_Commands {

		protected $podname, $produce, $file, $action, $uploads, $fields, $gardening, $recipe, $menus;
	
		/**
		 * produce - load data into produce posttype from a CSV file
		 *
		 * @param   (string)  $args  optional arguments. Not used. 
		 *
		 * @return  null         
		 */
		public function produce ( $args ) {
                	
			$this->podname  = 'ingredient';
			$this->file     = wp_upload_dir()['basedir'] . '/ingredients.csv';
			$this->produce	= TRUE;
			$this->process_args( 'Loading Produce PODS...' );
		}
		
		public function gardening ( $args ) {
                	
			$this->podname   = 'gardening';
			$this->file      = wp_upload_dir()['basedir'] . '/garden_plants.csv';
			$this->gardening = TRUE;
			$this->process_args( 'Loading Gardening PODS...' );
		}
		
		public function recipes ( $args, $assoc_args ) {
			
			$this->podname = 'recipe';
			$this->file      = wp_upload_dir()['basedir'] . '/recipes_pro_new.csv';
			$this->recipe = TRUE;
			$this->process_args( 'Loading Recipes PODS...' );
				
		}
		
		public function menus ( $args, $assoc_args ) {
			
			$this->podname = 'menu_item';
			$this->file      = wp_upload_dir()['basedir'] . '/menus.csv';
			$this->menus = TRUE;
			$this->process_args( 'Loading Menus PODS...' );
		}

		public function spanish ( $args, $assoc_args ) {
			
			$faker = \Faker\Factory::create( 'es_ES' );
			\WP_CLI::line( $faker->realText( $maxNbChars = 500 ) );
		}

		public function default_plugins( $args, $assoc_args ) {

			$defaults = [
				'codepress-admin-columns',
				'classic-editor',
				'pods',
				'woocommerce'
			];

			// Exerute `wp plugin install {plugin list} --activate` CLI command.
			\WP_CLI::runcommand( 'plugin install ' . implode( ' ', $defaults ) . ' --activate' );
		
		}

		public function admin_columns( $args, $assoc_args ) {

			$a = new PpscMockDataLoader;
			$a->presetAdminColumns();
		}

		public function default_pages( $args, $assoc_args ) {

			$a = new PpscMockDataLoader;
			$a->presetPages();
		}

		public function default_nav( $args, $assoc_args ) {

			$a = new PpscMockDataLoader;
			$a->prototypeMenu();
		}

		public function default_widgets( $args, $assoc_args ) {

			$a = new PpscMockDataLoader;
			$a->presetWidgets();
		}
		
		/**
		 * [gardenWeeds description]
		 *
		 * @param   [type]  $args        [$args description]
		 * @param   [type]  $assoc_args  [$assoc_args description]
		 *
		 * @return  [type]               [return description]
		 */
		public function gardenWeeds ( $args, $assoc_args ) {
			
			$faker = \Faker\Factory::create();
			$faker->addProvider( new PlantNames($faker) );
		}
		
		public function restaurant ( $args, $assoc_args ) {
			
			$num   = $assoc_args['num'] ?? 1;
			\WP_CLI::line();

			$faker = \Faker\Factory::create();
			$faker->addProvider( new Restaurant($faker) );
			$faker->addProvider( new PlantNames( $faker ) );

			for( $i = 0; $i < $num; $i++ ) {
				
				// Meat Entre
				$dish = array();

				$dish[] = $faker->optional($weight = 0.8)->meatPrep;
				$dish[] = $faker->meatName;
				$dish[] = 'with';
				$dish[] = $faker->optional($weight = 0.8)->spiceName;
				$dish[] = $faker->vegetableName;

				$meatEntre = implode( ' ', array_filter($dish) );

				$id = $this->addMenuItem( $faker, $meatEntre, 'Dinner', 'Meat Entre' );
				$id = $this->addMenuRecipe( $faker, $meatEntre, $id, array( $dish[1], $dish[3], $dish[4] ), array( 'Dinner', 'Meat Entre' ) );
				$veggie_id = $this->AddGardenVegetable( $faker, $dish[4], $recipe_id );
			}

			for( $i = 0; $i < $num; $i++ ) {
				
				// Vegetable Side Disk
				$dish = array();

				$dish[] = $faker->optional($weight = 0.5)->vegetablePrep;
				$dish[] = $faker->optional($weight = 0.8)->spiceName;
				$dish[] = $faker->vegetableName;

				$vegetableSideDish = implode( ' ', array_filter($dish) );

				$menu_id   = $this->addMenuItem( $faker, $vegetableSideDish, 'Dinner', 'Side Dishes' );
				$recipe_id = $this->addMenuRecipe( $faker, $vegetableSideDish, $menu_id, array( $dish[1], $dish[2] ), array( 'Dinner', 'Side Dishes' ) );
				
				$veggie_id = $this->AddGardenVegetable( $faker, $dish[2], $recipe_id );
			}
				
			for( $i = 0; $i < $num; $i++ ) {
				// Vegetarian Entre
				$dish = array();

				$dish[] = $faker->optional($weight = 0.5)->vegetablePrep;
				$dish[] = $faker->optional($weight = 0.2)->spiceName;
				$dish[] = $faker->vegetableName;
				$dish[] = 'in ' . $faker->vegetableSauce;

				$vegetableEntre = implode( ' ', array_filter($dish) );

				$id = $this->addMenuItem( $faker, $vegetableEntre, 'Dinner', 'Vegetarian Entre' );
				$id = $this->addMenuRecipe( $faker, $vegetableEntre, $id, array( $dish[1], $dish[2] ), array( 'Dinner', 'Vegetarian Entre' ) );
				$id = $this->AddGardenVegetable( $faker, $dish[2], $id );
			}
		}

		/**
		 * addMenuItem - Add a single record to the Menu custom post type
		 *
		 * @param   (object)  $faker  \Faker\Generator
		 * @param   (string)  $dish   Name of the menu item
		 * @param   (string)  $meal   Name of the meal - Brunch, Lunch, Dinner, etc.
		 * @param   (string)  $type   Sub-category of Meal - e.g. appetizer, entre, side dish
		 *
		 * @return  (int)             Record ID of newly created Menu Item
		 */
		protected function addMenuItem( $faker, $dish, $meal, $type ) {

			$a = new PpscMockDataLoader;

			$args = [
				$meal,
				$type,
				$dish,
				$a->RandomParagraph( $faker ),
				$faker->paragraph($nbSentences = 3, $variableNbSentences = true),
				$faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 50),
			];

			$id = $a->AddMenuItem( $args );
			return $id;  // returns the ID of the newly created Menu Item
		}

		protected function addMenuRecipe( $faker, $name, $menu_id = null, $ingredients = [], $meal = [] ) {

			$a = new PpscMockDataLoader;

			$args = [
				$name,
				$ingredients,
				$faker->paragraph($nbSentences = 3, $variableNbSentences = true),
				'00:' . $faker->time($format = 'i:s'),
				'00:' . $faker->time($format = 'i:s'),
				'00:' . $faker->time($format = 'i:s'),
				$faker->sentences($nb = 7, $asText = false),
				$menu_id,
				$meal,
			];

			$id = $a->AddMenuRecipe( $args );
			return $id;  // returns the ID of the newly created Recipe Item
		}

		protected function AddGardenVegetable( $faker, $name, $recipe_id ) {

			$a = new PpscMockDataLoader;

			$args = [
				$name,
				$faker->commonPlantName( $faker ),
				$faker->scientificPlantName( $faker ),
				$recipe_id,
			];

			$id = $a->AddGardenVegetable( $args );
			return $id;  // returns the ID of the newly created Recipe Item
		}

		/**
		 * erase - delete mock records from selected (or all) post types
		 *
		 * @param   (string)  $args        name of post type
		 * @param   (string)  $assoc_args  options
		 * @param   (string)  $all         --all - delete all records in custom post types
		 *
		 * @return  null[]                 completion message
		 */
		public function erase ( $args, $assoc_args ) {
			
			// $all = \WP_CLI\Utils\get_flag_value( $assoc_args, 'blank' );
			$type = isset( $args[0] ) ? $args[0] : null;
			$all  = $assoc_args['all'] ?? null;

			$a = new PpscMockDataLoader;
			$a->clearMockData( $args[0], $all );
		}
		
		/**
		 * process_args accepts the name of the post type and imports the appropriate test data CSV
		 *
		 * @param   (string)  $display_title  The name of the post type to process
		 *
		 * @return  (string)                  Success message
		 */
		private function process_args( $display_title ) {
				
			\WP_CLI::line();
			\WP_CLI::line( $display_title );
			
			$a = new PpscMockDataLoader;
			$test_data = $a->ParseCsvFile( $this->file );
			
			if ( $this->produce )   $a->AddProduce($test_data);
			if ( $this->gardening ) $a->AddGardening($test_data);
			if ( $this->recipe )    $a->AddRecipe($test_data);
			if ( $this->menus )     $a->AddMenus($test_data);
			
			\WP_CLI::success( "Processing {$display_title} completed" );
		}
	}
	\WP_CLI::add_command( 'prototype', 'RapidPrototype\Data_Loader_Commands' );
}


/*
error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(0);
ini_set('memory_limit', '512M');
*/

// $a = new PpscMockDataLoader;
// $produce = $a->ParseCsvFile( $uploads['basedir'] . '/ingredients.csv');

?>