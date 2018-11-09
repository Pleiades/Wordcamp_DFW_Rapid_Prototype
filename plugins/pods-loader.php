<?php

/**
 * Plugin Name: PODS Loader
 * Plugin URI: http://pleiadesservices.com	
 * Description: Find Plugins in the repo that haven't been updated in over 2 years
 * Author: Nicholas Batik
 * Author URI: http://pleiadesservices.com
 * Version: 0.1
 *
 * Copyright: (c) 2018
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



register_activation_hook( __FILE__, 'pods_cpt_and_taxonomy_loader' );

// _______________________________________________________________________________________

function pods_cpt_and_taxonomy_loader() {
    //
    global $wpdb;
    // require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	
    // load_pods_tables();
}

// _______________________________________________________________________________________



function load_pods_tables() {
	
include_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrade.php' );
include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
	
}

////

class PodsImporter {

	// require_once( ABSPATH . '/wp-content/plugins/pods/init.php' );
	protected $image_array = array(); 
	protected $image_index;
	protected $image_page = 1;
	
	protected $ingredient_array = array(); 
	protected $ingredient_index;
		
	function ParseCsvFile($filepath){
    
    	$array  = array();
    	$fields = array(); 
    	$i      = 0;
    	
		$csvfile = @fopen($filepath, "r");

		if ($csvfile) {
      
      		while (($row = fgetcsv($csvfile, 4096)) !== false) {
        	
        		if (empty($fields)) {
          			$fields = $row;
          			continue;
        		}
        		foreach ($row as $k=>$value) {
          
          			$array[$i][$k] = $value;
				}
				$i++;
			}
      		if (!feof($csvfile)) echo "Error: unexpected fgets() fail\n";

		fclose($csvfile);
		}
	return $array;
	}

  private function CleanText( $string ){
    
	if( ! empty($string) ){
		
		$string = trim($string);
		$string = ucwords(strtolower($string));
		
		return $string;
    }
  } //end CleanText

  private function add_check($listing){
    //looks at a listing, and decideds if it will be added or not
    if(get_page_by_title($listing[0], ARRAY_A, 'ingredient')){return false;}
    return strlen(trim($listing[0])); //check if there's a name desc
  }
  private function desc_masher($d){
    $d = array_filter($d); //rm blank elements
    if(!count($d)){return '';} //no desc at all
    return implode('<br/><br/>', $d);
  }
  
	private function AddCategories( $new_cat, $new_sub = NULL ){
		
		$output = array(); //hold cat id's
		
		$tax = 'menu_item';
		
		if( !$new_cat || !strlen(trim($new_cat)) ) return;
		
		// Main Category
		$new_cat = $this->CleanText($new_cat);
		$pid = wp_insert_term($new_cat, $tax);
		
		//interpret the return
		if(is_wp_error($pid)){
			$pid = $pid->error_data['term_exists'];
		} else {
			//term insert ok, deal with arr
			$pid = $pid['term_id'];
		}
		// echo 'Category '.$new_cat.' id: '.$pid."\n";
		$output[] = $pid;
		
		// Sub-category
		if( ! empty($new_sub) ){
			$new_cat[2] = $this->CleanText($new_cat[2]);
		
			//insert with a subcat
			$sub = wp_insert_term($new_cat[2], $tax, array('parent'=>$pid));
			delete_option($tax."_children"); // clear the cache
		
			if(is_wp_error($sub)){
				$sub = $sub->error_data['term_exists'];
			} else {
				$sub = $sub['term_id'];
			}
			$output[] = $sub;
		}
		
		return array_map('intval', $output);
	} //end AddCategories

  /**
   * The following functions matches the imported spreadsheet to the PODS fields
   */
	function AddProduce( $data ) {
	
		//given a set of data, generate the produce entries
		if( ! $data ) return; //no data, no dice
	
		// $image_type = 'cooking+food';
		$image_type = 'fruits+vegetables';
		
		foreach( $data as $item ){

			$fields = array(
				"name" => $item[0],
				"content" => file_get_contents('http://loripsum.net/api'),
				"status" => "publish"
			);
			
			$id = pods('ingredient')->add($fields);
			echo 'Adding ' . $fields["name"] . "\n";
		
			$this->PpscAddImage( $id, $this->RetrieveImage( $image_type ) );      	
		}
	} //end AddProduce
	
	/**
	 * The following functions matches the imported spreadsheet to the PODS fields
	 */
	function AddRecipe( $data ) {
	
		//given a set of data, generate the listings and cats
		if( ! $data ) return; //no data, no dice
	
		$image_type = 'cooking+food';
		
		foreach( $data as $item ){

			// if(!$this->add_check($x)){continue;} //check if it's worthy

			// $desc = $this->desc_masher(array($x[3], $x[4], $x[6]));
			$fields = array(
				"name" => $item[2],
				"content" => $item[3],
				"status" => "publish"
			);
		/*
	
				"content" => $desc,
				"website" => $x[5],
				"phone"   => $x[7],
				"email"   => $x[8],
				"street_address" => $x[9],
				"city"    => $x[10],
				"state"   => $x[11],
				"zipcode" => $x[12],
		*/
			$cids = $this->AddCategories( $item[1] ); //handle categories
			
			$id = pods('recipe')->add($fields);
			//echo 'Adding ' . $fields["name"] . "\n";
		
			$this->PpscAddImage( $id, $this->RetrieveImage( $image_type ) );
			/*
			if(count($cids) && $id){
				wp_set_object_terms($id, $cids, 'ingredient_category');
				delete_option("ingredient_category_children"); // clear the cache
			}
			*/
      	
		}
	} //end AddRecipe
	
	/**
	 * The following functions matches the imported spreadsheet to the PODS fields
	 */
	function AddGardening( $data ) {
	
		//given a set of data, generate the listings and cats
		if( ! $data ) return; //no data, no dice
	
		foreach( $data as $item ){

			$ingredient = $this->RetrieveIngredient();
			
			$fields = array(
				"name"            => 'Growing ' . get_the_title( $ingredient ),
				"common_name"     => $item[1],
				"scientific_name" => $item[2],
				"content"         => file_get_contents('https://loripsum.net/api/10/medium/headers/decorate/ul/dl'),
				"status"          => "publish"
			);
			
			$id = pods('gardening')->add($fields);
			echo 'Adding ' . $fields["name"] . "\n";
			
			$gardening_pod = pods("gardening", $id );
			$gardening_pod->add_to("cooking_ingredient", $ingredient );
			
			$gardening_pod = pods("ingredient", $ingredient );
			$gardening_pod->add_to("gardening", $gardening_pod );
		
			$image_type = 'vegetable+gardening';
			$this->PpscAddImage( $id, $this->RetrieveImage( $image_type ) );
			
		}
	} //end AddGardening
	
	/**
	 * The following functions matches the imported spreadsheet to the PODS fields
	 */
	function AddUsers( $data ) {
	
		//given a set of data, generate the listings and cats
		if( ! $data ) return; //no data, no dice
	
		$image_type = 'avatar';
		
		foreach( $data as $user ){

			$fields = array(
				"name"           => $user[2],
				"content"        => $user[3],
				"website"        => $user[5],
				"phone"          => $user[7],
				"email"          => $user[8],
				"street_address" => $user[9],
				"city"           => $user[10],
				"state"          => $user[11],
				"zipcode"        => $user[12],
			);
						
			$id = pods('menu')->add($fields);
			echo 'Adding User Item (' . $fields["name"] . "\n";
		
			$this->PpscAddImage( $id, $this->RetrieveImage( $image_type ) );
      	
		}
	} //end AddUser
	
	function AddMenus( $data ) {
	
		//given a set of data, generate the listings and cats
		if( ! $data ) return; //no data, no dice
	
		$image_type = 'meal';
		
		foreach( $data as $item ){

			// if(!$this->add_check($x)){continue;} //check if it's worthy

			// $desc = $this->desc_masher(array($x[3], $x[4], $x[6]));
			$fields = array(
				"name"    => $item[2],
				"content" => $item[3],
				"excerpt" => $item[3],
				"price"   => $item[4] ?? NULL,
				"status"  => "publish"
			);
			
			$cids = $this->AddCategories( $item[0], $item[1] ); //handle categories
			
			$id = pods('menu')->add($fields);
			echo 'Adding Menu Item (' . $item[0] .', '. $item[1] .') '. $fields["name"] . "\n";
		
			$this->PpscAddImage( $id, $this->RetrieveImage( $image_type ) );
      	
		}
	} //end AddMenus
  
	function RetrieveImage( $image_type ) {
		
		if ( empty($this->image_array) || $this->image_index >= count($this->image_array) ) {
			$images = file_get_contents("https://pixabay.com/api/?key={KEY}&q={$image_type}&image_type=photo&per_page=200&page={$this->image_page}");
			$json = json_decode($images, true);
			
			$this->image_array = $json['hits'];
			$this->image_index = 0;
			$this->image_page++;
		}
		$image = $this->image_array[$this->image_index]['largeImageURL'];
		
		$this->image_index++;
		return $image;
	}
	
	function RetrieveIngredient() {
		
		if ( empty($this->ingredient_array) || $this->ingredient_index >= count($this->ingredient_array) ) {
			$args = array(
				'orderby'        => 'rand',
				'post_type'      => 'ingredient',
				'posts_per_page' => -1,
			);
			
			$ingredients = new WP_Query( $args );	
			
			if ( $ingredients->have_posts() ) {
				while ( $ingredients->have_posts() ) {
					$ingredients->the_post();
					$this->ingredient_array[] = $ingredients->post->ID;
				}
			}
			$this->ingredient_index = 0;
		}
		
		$ingredient_id = $this->ingredient_array[$this->ingredient_index];
		$this->ingredient_index++;
		
		return $ingredient_id;
	}
  
	function PpscAddImage( $post_id, $image ) {
		
		$media = media_sideload_image($image, $post_id);

		// Find it and set it as featured ID
		if(!empty($media) && !is_wp_error($media)){
			$args = array(
				'post_type' => 'attachment',
				'posts_per_page' => -1,
				'post_status' => 'any',
				'post_parent' => $post_id
			);

			// reference new image to set as featured
			$attachments = get_posts($args);

			if(isset($attachments) && is_array($attachments)){
				
				foreach($attachments as $attachment){
					
					// grab source of full size images (so no 300x150 nonsense in path)
					$image = wp_get_attachment_image_src($attachment->ID, 'full');
					
					// determine if in the $media image we created, the string of the URL exists
					if(strpos($media, $image[0]) !== false){
						
						// if so, we found our image. set it as thumbnail
						set_post_thumbnail($post_id, $attachment->ID);
						
						// only want one image
						break;
					}
				}
			}
		}
	}
}

if( defined( 'WP_CLI' ) && WP_CLI ) {

	class PODS_Loader_Commands {

		protected $podname, $produce, $file, $action, $uploads, $fields, $gardening, $recipe, $menus;
	
		function produce ( $args ) {
                	
				$this->podname  = 'ingredient';
				$this->file     = content_url() . '/mock-data/ingredients.csv';
				$this->produce	= TRUE;
				$this->process_args( 'Loading Produce PODS...' );
		}
		
		function gardening ( $args ) {
                	
				$this->podname   = 'gardening';
				$this->file      = content_url() . '/mock-data/garden_plants.csv';
				$this->gardening = TRUE;
				$this->process_args( 'Loading Gardening PODS...' );
		}
		
		function recipes ( $args, $assoc_args ) {
			
				$this->podname = 'recipe';
				$this->file      = content_url() . '/mock-data/recipes_pro_new.csv';
				$this->recipe = TRUE;
				$this->process_args( 'Loading Recipes PODS...' );
				
		}
		
		function menus ( $args, $assoc_args ) {
			
				$this->podname = 'menu_item';
				$this->file      = content_url() . '/mock-data/menus.csv';
				$this->menus = TRUE;
				$this->process_args( 'Events - Call Center' );
		}
		
		private function process_args( $display_title ) {
				
				WP_CLI::line();
				WP_CLI::line( $display_title );
				
				$a = new PodsImporter;
				$test_data = $a->ParseCsvFile( $this->file );
				
				if ( $this->produce )   $a->AddProduce($test_data);
				if ( $this->gardening ) $a->AddGardening($test_data);
				if ( $this->recipe )    $a->AddRecipe($test_data);
				if ( $this->menus )     $a->AddMenus($test_data);
				
				WP_CLI::success( "Processing {$display_title} completed" );
		}
	}
	WP_CLI::add_command( 'podsloader', 'PODS_Loader_Commands' );
}

/*
error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(0);
ini_set('memory_limit', '512M');
*/

/*
// If you don't want to use WP-CLI: 
$a = new PodsImporter;
$produce = $a->ParseCsvFile( $uploads['basedir'] . '/ingredients.csv');
*/
?>