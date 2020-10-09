<?php

namespace RapidPrototype;

class PpscMockDataLoader {

	protected $image_array = array(); 
	protected $image_index;
	protected $image_page = 1;
	
	protected $ingredient_array = array(); 
	protected $ingredient_index;
		
	/**
	 * ParseCsvFile - Read a CSV file line by line and parse into data array
	 *
	 * @param   (string)  $filepath  Real path of CSV file
	 *
	 * @return  (array)              Array columns based on input CSV
	 */
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

	/**
	 * CleanText - Strip leading/trailing blanks, and conver to title case
	 *
	 * @param   (string)  $string  Name or title text
	 *
	 * @return  (string)           Converted text
	 */
	private function CleanText( $string ){
		
		if( ! empty($string) ){
			
			$string = trim($string);
			$string = ucwords(strtolower($string));
			
			return $string;
		}
	} 

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
  
	/**
	 * AddCategories - Check for existing primary/secondary categories and add if they don't exist
	 *
	 * @param   (string)	$new_cat  Primary Category
	 * @param   (string)	$new_sub  Sub-category
	 *
	 * @return  (array)          	  Category IDs
	 */
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
			$new_sub = $this->CleanText($new_sub);
			
			//insert with a subcat
			$sub = wp_insert_term( $new_sub, $tax, array('parent'=>$pid ));
			delete_option($tax."_children"); // clear the cache
		
			if(is_wp_error($sub)){
				$sub = $sub->error_data['term_exists'];
			} else {
				$sub = $sub['term_id'];
			}
			$output[] = $sub;
		}
		
		return array_map('intval', $output);
	} 

	/**
	* Gets a random set of paragraphs
	*
	* @param Faker\Generator
	* @return string
	*/
	public function RandomParagraph( $faker )
	{
		$paragraphs = rand(1, 5);
		$i = 0;
		$ret = "";
		while ($i < $paragraphs) {
			$ret .= "<p>" . $faker->paragraph(rand(2, 6)) . "</p>";
			$i++;
		}
		return $ret;
	}  
	
	/**
   * The following functions matches the imported spreadsheet to the PODS fields
   */
	public function AddProduce( $data ) {
	
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
		
			$this->PpscAddImage( $id, $this->RetrieveImage( $image_type ) );      	
		}
	} //end AddProduce

	public function AddIngredient( $item ) {
	
		if( ! $item ) return; //no data, no dice
	
		// $image_type = 'cooking+food';
		$image_type = 'fruits+vegetables';
		
		$fields = array(
			"name" => $item[0],
			"content" => file_get_contents('http://loripsum.net/api'),
			"status" => "publish"
		);
		
		$id = pods('ingredient')->add($fields);
		echo 'Adding ' . $fields["name"] . "\n";
	
		$this->PpscAddImage( $id, $this->RetrieveImage( $image_type ) );      	
		
	} //end AddProduce
	
	/**
	 * The following functions matches the imported spreadsheet to the PODS fields
	 */
	public function AddRecipe( $data ) {
	
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
	} 

	/**
	 * AddMenuRecipe - Create a Recipe and relate it to a Menu item
	 *
	 * @param   (array)  $item  Array containing the necessary parameters. Unused fields require a null place holder.
	 *    $item [
	 *          [0] "name"         => (string) Name of the Menu/Recipe item. Required.
	 *          [1] "ingredients"  => (array)  A list of one or more ingredients. Used to create Ingredients relationships. Required.
	 *          [2] "notes"        => (string) Content for the Notes field. Optional.
	 *          [3] "prep_time"    => (string) Long description of the item. Required. 
	 *          [4] "cook_time"    => (string) Short description of the item. Required.
	 *          [5] "total_time"   => (float)  Item price. Required.
	 *          [6] "instructions" => (array)  A list of one or more instructions. Used to create Instructions relationships. Required.
	 *          [7] "menu_id"      => (int)    The ID of the related Menu item. Used to create Menu relationship. Required.
	 *          [8] "meal"         => (array)  The category and optional sub-category of Menu Items taxonomy
	 *	        ]
	 *
	 * @return  [type]         [return description]
	 */
	public function AddMenuRecipe( $item ) {
	
		if( ! $item ) return; //no data, no dice
	
		$image_type = 'cooking+food+meal';
				
		$fields = array(
			"name"       => $item[0],
			"content"    => $item[3],
			"prep_time"  => $item[3],
			"cook_time"  => $item[4],
			"total_time" => $item[5],
			"notes"      => $item[2],
			"status"     => "publish"
		);
		
		// Create a Recipe with the fields provided
		$id = pods('recipe')->add( $fields );

		// Set the Menu Item categories for this Recipe
		if(count( $item[8] ) && $id) {
			wp_set_object_terms($id, $item[8], 'menu_item');
		}
	
		$this->PpscAddImage( $id, $this->RetrieveImage( $image_type ) );
		
		$recipe_pod = pods('recipe', $id );

		// Create relationship between Recipe and Menu
		$recipe_pod->add_to("menu_entry", $item[7]);

		// Create relationship between Recipe and Ingredients
		foreach( $item[1] as $ingredient ) {
			/*
			$ingredient_id = pods( 'ingredient', $ingredient );
			if( ! $ingredient_id->exists() ) { 

				$params = [
					"name"       => $ingredient,
					"status"     => "publish"
				];
				$ingredient_id = pods('ingredient')->add( $params );
			}

			$recipe_pod->add_to("ingredients", $ingredient_id );
			*/
			$ingredient_id = $this->AddRecipeIngredient( $id, $ingredient );
		}

		// Create relationship between Recipe and Instructions
		foreach( $item[6] as $instruction ) {
			
			$instruction_id = pods( 'instruction', $instruction );
			if( ! $instruction_id->exists() ) { 

				$params = [
					"name"       => $instruction,
					"status"     => "publish"
				];
				$instruction_id = pods('instruction')->add( $params );
			}

			$recipe_pod->add_to("instructions", $instruction_id );
		}
		return $recipe_pod->id();
	} //end AddRecipe
	
	/**
	 * The following functions matches the imported spreadsheet to the PODS fields
	 */
	public function AddGardening( $data ) {
	
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
			
			$gardening_pod = pods("gardening", $id );
			$gardening_pod->add_to("cooking_ingredient", $ingredient );
			
			$gardening_pod = pods("ingredient", $ingredient );
			$gardening_pod->add_to("gardening", $gardening_pod );
		
			$image_type = 'vegetable+gardening';
			$this->PpscAddImage( $id, $this->RetrieveImage( $image_type ) );
			
		}
	} //end AddGardening

	public function AddGardenVegetable( $item ) {
	
		$ingredient = $this->RetrieveIngredient();
		
		$fields = array(
			"name"            => 'Growing ' . $item[0],
			"common_name"     => $item[1],
			"scientific_name" => $item[2],
			"content"         => file_get_contents('https://loripsum.net/api/10/medium/headers/decorate/ul/dl'),
			"status"          => "publish"
		);
		
		$id = pods('gardening')->add($fields);

		$image_type = 'garden+vegetable';
		$this->PpscAddImage( $id, $this->RetrieveImage( $image_type ) );

		$gardening_pod = pods("gardening", $id );
		$gardening_pod->add_to("recipe", $item[3] );

		$ingredient = $this->AddGardenIngredient( $gardening_pod->id(), $item[0] );
		$this->AddProduct( $item[0] );
	} //end AddGardenVegetable

	
	public function AddGardenIngredient( $garden_id, $ingredient ) {

		$gardening_pod = pods("gardening", $garden_id );
		
		$ingredient_pod = pods( 'ingredient', $ingredient );
		if( ! $ingredient_pod->exists() ) { 
			
			$params = [
				"name"       => $ingredient,
				"status"     => "publish"
			];
			$ingredient_pod = pods('ingredient')->add( $params );
			
		} 
		$gardening_pod->add_to("cooking_ingredient", $ingredient_pod->id() );
		return $ingredient_pod->id();
	}

	public function AddRecipeIngredient( $recipe_id, $ingredient ) {

		$recipe_pod = pods("recipe", $recipe_id );
		
		$ingredient_pod = pods( 'ingredient', $ingredient );
		if( ! $ingredient_pod->exists() ) { 
			
			$params = [
				"name"       => $ingredient,
				"status"     => "publish"
			];
			$ingredient_pod = pods('ingredient')->add( $params );
			
			$ingredient_pod = pods( 'ingredient', $ingredient );
		} 
		$recipe_pod->add_to("ingredients", $ingredient_pod->id() );
		return $ingredient_pod->id();
	}

	public function AddProduct( $produce ) {
		
		$gen_product = \Faker\Factory::create();

		$product = array(
			'post_title'   => $produce,
			'post_content' => file_get_contents('https://loripsum.net/api/1/short/'),
			'post_status'  => 'publish',
			'post_type'    => 'product',
		);
		$post_id = wp_insert_post( $product );
		wp_set_object_terms( $post_id, 'simple', 'product_type' );

		update_post_meta( $post_id, '_price', $gen_product->randomFloat($nbMaxDecimals = 2, $min = 1, $max = 20) );
		update_post_meta( $post_id, '_featured', 'no' );
		update_post_meta( $post_id, '_stock',  $gen_product->randomNumber($nbDigits = 3, $strict = true));
		update_post_meta( $post_id, '__stock_status', 'instock' );
		update_post_meta( $post_id, '_sku', $gen_product->randomNumber($nbDigits = 7, $strict = true) );

		// No image is assigned to Ingredient. Using PODS traversals, retrieve the image from Gardening
		$ingredient_pod = pods( 'ingredient', $produce );
		if( $ingredient_pod->exists() ) { 
			
			$garden_items = $ingredient_pod->field( 'gardening' );
			if ( ! empty( $garden_items )  ) {
				
				foreach( $garden_items as $garden_item ) {
					
					$id = $garden_item[ 'ID' ];
					$image_id = get_post_meta( $id, '_thumbnail_id', true );
					update_post_meta( $post_id, '_thumbnail_id', $image_id );
				}
			}			
		} 
		
		// Create or retrieve WooCommerce product categories. Expand as necessary for your project
		$term = wp_insert_term( 'Fresh Produce', 'product_cat', [
			'description' => 'Garden fresh produce',
			'slug'        => 'fresh-produce',
			]
		);
		if ( is_wp_error( $term ) ) {
			$term_id = $term->error_data['term_exists'] ?? null;
			
		} else {
			$term_id = $term['term_id'];
		}

		// Attach the category term to the product
		wp_set_object_terms( $post_id, $term_id, 'product_cat' );
	}
	
	/**
	 * The following functions matches the imported spreadsheet to the PODS fields
	 */
	public function AddUsers( $data ) {
	
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
		
			$this->PpscAddImage( $id, $this->RetrieveImage( $image_type ) );
      	
		}
	} //end AddUser
	
	/**
	 * AddMenus - Takes an array and loops through each entry to add records to the Menu post type
	 *
	 * @param   (array)  $data  [$data description]
	 *
	 * @return  (string)        completion message
	 */
	public function AddMenus( $data ) {
	
		//given a set of data, generate the listings and cats
		if( ! $data ) return; //no data, no dice
	
		foreach( $data as $item ){
			
			// $item[2] = $this->CleanText( $item[2] );
			$this->AddMenuItem( $item );
      	
		}
	} //end AddMenus

	/**
	 * AddMenuItem - Create a Menu CPT record and insert the fields from the passed array into the metadata
	 *
	 * @param   (array)  $item  Array containing the necessary parameters.
	 *    $item [
	 *          "category"    => (string) Category of the meal - Brunch, Lunch, Diner. Required.
	 *          "subcategory" => (string) Subcategory of the meal - side dish, entre, etc. Required.
	 *          "name"        => (string) Name of the menu item. Required.
	 *          "content"     => (string) Long description of the item. Required. 
	 *          "excerpt"     => (string) Short description of the item. Required.
	 *          "price"       => (float)  Item price. Required.
	 *	        ]
	 *
	 * @return  null         
	 */
	public function AddMenuItem( $item ) {
	
		//given a set of data, generate the listings and cats
		if( ! $item ) return; //no data, no dice

		$image_type = 'meal';

		switch ( $item[1] ) {
			case 'Meat Entre': 
				$image_type = $image_type . ' meat';
				break;

			case 'Side Dishes': 
			case 'Vegetarian Entre': 
				$image_type = $image_type . ' vegetable';
				break;
		}
	
		$this->image_index = rand( 0, 199 );
		
		// $desc = $this->desc_masher(array($x[3], $x[4], $x[6]));
		$fields = array(
			"name"    => $this->CleanText( $item[2] ),
			"content" => sanitize_textarea_field( $item[3] ),
			"excerpt" => sanitize_textarea_field( $item[4] ),
			"price"   => $item[5] ?? NULL,
			"status"  => "publish"
		);
		
		$cids = $this->AddCategories( $item[0], $item[1] ); //handle categories
		
		$id = pods('menu')->add($fields);
		echo 'Adding Menu Item (' . $item[0] .', '. $item[1] .') '. $fields["name"] . "\n";
	
		$this->PpscAddImage( $id, $this->RetrieveImage( $image_type ) );

		if( count($cids) && $id ){
			wp_set_object_terms($id, $cids, 'menu_item');
		}
		return $id;
	} 
  
	/**
	 * RetrieveImage - Fetch a collection of subject related images from Pixabay
	 *
	 * @param   (string)  $image_type  A keyword used as an image category for Pixabay selection
	 *
	 * @return  (string)               URL of selected image
	 */
	protected function RetrieveImage( $image_type ) {
		
		if ( empty($this->image_array) || $this->image_index >= count($this->image_array) ) {
			$images = file_get_contents("https://pixabay.com/api/?key=2506626-ab647979ac2e2344eaadd6367&q={$image_type}&image_type=photo&per_page=200&page={$this->image_page}");
			$json = json_decode($images, true);
			
			$this->image_array = $json['hits'];
			$this->image_index = 0;
			$this->image_page++;
		}
		$this->image_index = rand( 0, 199 );
		$image = $this->image_array[$this->image_index]['largeImageURL'];
		
		// $image = 'http://wcdfw.test/wp-content/uploads/2020/10/55e0d34a4253aa14f6da8c7dda79357b1539d8e7554c704f752d7ad4904bc258_1280.jpg';

		return $image;
	}
	
	protected function RetrieveIngredient() {
		
		if ( empty($this->ingredient_array) || $this->ingredient_index >= count($this->ingredient_array) ) {
			$args = array(
				'orderby'        => 'rand',
				'post_type'      => 'ingredient',
				'posts_per_page' => -1,
			);
			
			$ingredients = new \WP_Query( $args );	
			
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
  
	protected function PpscAddImage( $post_id, $image ) {
		
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

	public function clearMockData( $type, $option = null ) {

		$mock_types = [
			'menu',
			'gardening',
			'ingredient',
			'instruction',
			'recipe',
			'product'
		];

		$clear_types[] = ( in_array( $type, $mock_types ) ) ? $type : null;
		if ( $option ) { 
			\WP_CLI::line( print_r( $option, true) );
			$clear_types = $mock_types;

			$attachments = get_posts(
				array(
					'post_type' => 'attachment',
					'posts_per_page' => -1,
					'post_status' => 'any',
				)
			);
			\WP_CLI::line( 'Erasing ' . count( $attachments ) . ' media images' );

			foreach ( $attachments as $attachment ) {
				wp_delete_attachment( $attachment->ID );
			}
		}
		
		foreach( $clear_types as $each_type ) {

			$args = array( 'post_type' => $each_type, 'numberposts' => -1 );

			$all_posts = get_posts( $args );

			\WP_CLI::line( print_r( 'Erasing ' . count( $all_posts ) . ' ' . $each_type . ' records', true) );

			foreach( $all_posts as $each_post ) {
			
				$this->clearAttachments( $each_post->ID);
				wp_delete_post( $each_post->ID, true );
			}
		}		
	}

	protected function clearAttachments( $post_id ) {

		$attachments = get_posts( array(
			'post_type' => 'attachment',
			'posts_per_page' => -1,
			'post_parent' => $post_id,
		));

		foreach( $attachments as $attachment ) {

			wp_delete_attachment( $post_id, true );
		}
	}

	public function presetAdminColumns() {
		
		global $wpdb;

		$wpdb->insert(
			'wp_admin_columns',
			array(
				'id'            => NULL,
				'list_id'       => '5f6e6941d3ea9',
				'list_key'      => 'menu',
				'title'         => 'Menus',
				'columns'       => 'a:5:{s:13:"5be29baabfa1b";a:7:{s:4:"type";s:21:"column-featured_image";s:5:"label";s:14:"Featured Image";s:5:"width";s:2:"75";s:10:"width_unit";s:2:"px";s:10:"image_size";s:11:"cpac-custom";s:12:"image_size_w";s:2:"60";s:12:"image_size_h";s:2:"60";}s:5:"title";a:4:{s:4:"type";s:5:"title";s:5:"label";s:5:"Title";s:5:"width";s:0:"";s:10:"width_unit";s:1:"%";}s:13:"5f77f71fc0d64";a:7:{s:4:"type";s:15:"column-taxonomy";s:5:"label";s:4:"Meal";s:5:"width";s:0:"";s:10:"width_unit";s:1:"%";s:8:"taxonomy";s:9:"menu_item";s:12:"term_link_to";s:6:"filter";s:15:"number_of_items";s:2:"10";}s:13:"5f7f76a755829";a:13:{s:4:"type";s:11:"column-meta";s:5:"label";s:5:"Price";s:5:"width";s:0:"";s:10:"width_unit";s:1:"%";s:5:"field";s:5:"price";s:10:"field_type";s:0:"";s:6:"before";s:0:"";s:5:"after";s:0:"";s:4:"sort";s:3:"off";s:11:"inline-edit";s:3:"off";s:9:"bulk-edit";s:3:"off";s:15:"smart-filtering";s:3:"off";s:6:"export";s:3:"off";}s:4:"date";a:4:{s:4:"type";s:4:"date";s:5:"label";s:4:"Date";s:5:"width";s:0:"";s:10:"width_unit";s:1:"%";}}',
				'settings'      => NULL,
				'date_created'  => '2020-09-25 22:03:45',
				'date_modified' => '2020-10-08 20:29:27',
			)
		);	
		$wpdb->insert(
			'wp_admin_columns',
			array(
				'id'            => NULL,
				'list_id'       => '5f6e6941d3eee',
				'list_key'      => 'ingredient',
				'title'         => 'Ingredients',
				'columns'       => 'a:2:{s:5:"title";a:4:{s:4:"type";s:5:"title";s:5:"label";s:5:"Title";s:5:"width";s:0:"";s:10:"width_unit";s:1:"%";}s:4:"date";a:4:{s:4:"type";s:4:"date";s:5:"label";s:4:"Date";s:5:"width";s:0:"";s:10:"width_unit";s:1:"%";}}',
				'settings'      => NULL,
				'date_created'  => '2020-09-25 22:03:45',
				'date_modified' => '2020-10-08 20:26:43',
			)
		);
		$wpdb->insert(
			'wp_admin_columns',
			array(
				'id'            => NULL,
				'list_id'       => '5f781e91f031e',
				'list_key'      => 'recipe',
				'title'         => 'Recipes',
				'columns'       => 'a:4:{s:13:"5f781ecf3bbea";a:7:{s:4:"type";s:21:"column-featured_image";s:5:"label";s:14:"Featured Image";s:5:"width";s:2:"72";s:10:"width_unit";s:2:"px";s:10:"image_size";s:11:"cpac-custom";s:12:"image_size_w";s:2:"60";s:12:"image_size_h";s:2:"60";}s:5:"title";a:4:{s:4:"type";s:5:"title";s:5:"label";s:5:"Title";s:5:"width";s:0:"";s:10:"width_unit";s:1:"%";}s:18:"taxonomy-menu_item";a:4:{s:4:"type";s:18:"taxonomy-menu_item";s:5:"label";s:10:"Menu Items";s:5:"width";s:0:"";s:10:"width_unit";s:1:"%";}s:4:"date";a:4:{s:4:"type";s:4:"date";s:5:"label";s:4:"Date";s:5:"width";s:0:"";s:10:"width_unit";s:1:"%";}}',
				'settings'      => NULL,
				'date_created'  => '2020-10-03 06:48:33',
				'date_modified' => '2020-10-03 06:48:47',
			)
		);
		$wpdb->insert(
			'wp_admin_columns',
			array(
				'id'            => NULL,
				'list_id'       => '5f7d3e4cdf3b2',
				'list_key'      => 'gardening',
				'title'         => 'Gardening',
				'columns'       => 'a:4:{s:13:"5f7d3e7ba308f";a:7:{s:4:"type";s:21:"column-featured_image";s:5:"label";s:14:"Featured Image";s:5:"width";s:2:"75";s:10:"width_unit";s:2:"px";s:10:"image_size";s:11:"cpac-custom";s:12:"image_size_w";s:2:"60";s:12:"image_size_h";s:2:"60";}s:5:"title";a:4:{s:4:"type";s:5:"title";s:5:"label";s:5:"Title";s:5:"width";s:0:"";s:10:"width_unit";s:1:"%";}s:13:"5f7f75c0a54b6";a:13:{s:4:"type";s:11:"column-meta";s:5:"label";s:15:"Scientific Name";s:5:"width";s:0:"";s:10:"width_unit";s:1:"%";s:5:"field";s:15:"scientific_name";s:10:"field_type";s:0:"";s:6:"before";s:0:"";s:5:"after";s:0:"";s:4:"sort";s:3:"off";s:11:"inline-edit";s:3:"off";s:9:"bulk-edit";s:3:"off";s:15:"smart-filtering";s:3:"off";s:6:"export";s:3:"off";}s:4:"date";a:4:{s:4:"type";s:4:"date";s:5:"label";s:4:"Date";s:5:"width";s:0:"";s:10:"width_unit";s:1:"%";}}',
				'settings'      => NULL,
				'date_created'  => '2020-10-07 04:05:04',
				'date_modified' => '2020-10-08 20:25:36',
			)
		);
	}

	public function presetPages() {

		$pages = [
			'Cooking Ingredients' => '[pods name="ingredient" limit="-1" template="Ingredients"]',
			'Fresh Produce'       => '[product_category category="fresh-produce"]',
			'Restaurant'          => '[pods name="menu" limit="-1" template="menu"]',
		];

		foreach ( $pages as $key => $value ) {
			
			$found = post_exists( $key, '', '', 'page' );
			if ( $found ) {
				\WP_CLI::line( 'Page ' . $key . ' already exists.' );
				continue;
			}

			\WP_CLI::line( 'Page added: ' . $key );

			$insert_page = [
				'post_title'   => $key,
				'post_content' => $value,
				'post_status'  => 'publish',
				'post_author'  => 1,
				'post_type'    => 'page',
			];
			wp_insert_post( $insert_page );
		}
	}

	public function presetWidgets() {

		$active_widgets = get_option( 'sidebars_widgets' );
		// \WP_CLI::line( 'Sidebars: ' . print_r( $active_widgets, true ) );

		// $widget_options = get_option( 'front-page-1' );
		// \WP_CLI::line( 'Sidebars: ' . print_r( $widget_options, true ) );

		// $active_widgets[ 'front-page-1' ][] = 'custom_html';
		// update_option( 'sidebar_widgets', $active_widgets );

		/*
		this->add_default_widget( 
			'rss'
			,array( 
				'title' => 'Test Widget', 
				'items' => 5, 
				'url'   => 'https://codesymphony.co/feed/'
			)
			,'search-bar'
		);
		*/
		$this->t5_default_widget_demo();

		// \WP_CLI::line( 'Sidebars: ' . print_r( $active_widgets, true ) );
	}

	public function prototypeMenu() {

		// Check if menu exists
		$menu_name   = 'Prototype Menu';
		$menu_exists = wp_get_nav_menu_object( $menu_name );

		// Create menu if it doesn't exist
		if ( ! $menu_exists ) {

			$menu_id = wp_create_nav_menu( $menu_name );

			// Set up default entries
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'   => __( 'Home', 'rapidprototype' ),
				'menu-item-classes' => 'home',
				'menu-item-url'     => home_url( '/' ),
				'menu-item-status'  => 'publish'
			) );

			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'   => __( 'Gardening', 'rapidprototype' ),
				'menu-item-classes' => 'home',
				'menu-item-url'     => home_url( '/gardening/' ),
				'menu-item-status'  => 'publish'
			) );

			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'   => __( 'Recipes', 'rapidprototype' ),
				'menu-item-classes' => 'home',
				'menu-item-url'     => home_url( '/recipe/' ),
				'menu-item-status'  => 'publish'
			) );

			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'   => __( 'Ingredients', 'rapidprototype' ),
				'menu-item-classes' => 'home',
				'menu-item-url'     => home_url( '/cooking-ingredients/' ),
				'menu-item-status'  => 'publish'
			) );

			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'   => __( 'Fresh Produce', 'rapidprototype' ),
				'menu-item-classes' => 'home',
				'menu-item-url'     => home_url( '/fresh-produce/' ),
				'menu-item-status'  => 'publish'
			) );

			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'   => __( 'Menu of the Day', 'rapidprototype' ),
				'menu-item-classes' => 'home',
				'menu-item-url'     => home_url( '/restaurant/' ),
				'menu-item-status'  => 'publish'
			) );
		}
	}

	public function defaultWidgets() {

		add_default_widget( 
			'rss'
			,array( 
				'title' => 'Test Widget', 
				'items' => 5, 
				'url'   => 'https://codesymphony.co/feed/'
			)
			,'front-page-1'
		);
	}

	/**
	 * Programmatically save a new widget instance.
	 *
	 * Based on wp_ajax_save_widget().
	 *
	 * @param string $id_base    The base ID for instances of this widget.
	 * @param array  $settings   The settings for this widget instance. Optional.
	 * @param string $sidebar_id The ID of the sidebar to add the widget to. Optional.
	 *
	 * @return bool Whether the widget was saved successfully.
	 */
	protected function t5_default_widget_demo() {
		
		$active_widgets = get_option( 'sidebars_widgets' );
		
		$sidebars = [
			'1' => 'front-page-1',
			'2' => 'front-page-3',
			'3' => 'front-page-4',
			'4' => 'front-page-5',
			'5' => 'search-bar'
		];

		// Note that widgets are numbered. We need a counter:
		$counter = 1;
		/*
		$active_widgets[ $sidebars['5'] ][] = 'rss-' . $counter;
		$rss_content[ $counter ] = array (
			'title'        => 'WordPress Stack Exchange',
			'url'          => 'http://wordpress.stackexchange.com/feeds',
			'link'         => 'http://wordpress.stackexchange.com/questions',
			'items'        => 15,
			'show_summary' => 0,
			'show_author'  => 1,
			'show_date'    => 1,
		);
		update_option( 'widget_rss', $rss_content );
		*/

		$counter++;
		$active_widgets[ $sidebars['1'] ][] = 'custom_html-' . $counter;
		$html_content[ $counter ] = array (
			'title'        => '',
			'content'      => '<h2>Welcome to Farm-to-Table<br>Restaurant</h2>',
		);
		update_option( 'widget_custom_html', $html_content );

		$counter++;
		$active_widgets[ $sidebars['2'] ][] = 'custom_html-' . $counter;
		$html_content[ $counter ] = array (
			'title'        => '',
			'content'      => '<h2 style="text-align:center;font-weight:bold;color:#fff;margin:2em">Today\'s Featured Menu</h2>',
		);
		update_option( 'widget_custom_html', $html_content );

		$counter++;
		$active_widgets[ $sidebars['3'] ][] = 'custom_html-' . $counter;
		$html_content[ $counter ] = array (
			'title'        => '',
			'content'      => '<h2 style="text-align: center;">Become part of our community</h2>',
		);
		update_option( 'widget_custom_html', $html_content );

		$counter++;
		$active_widgets[ $sidebars['4'] ][] = 'pods_widget_single-' . $counter;
		$pods_single_content[ $counter ] = array (
			'title'           => '',
			'pod_type'        => 'gardening',
			'slug'            => 'growing-carrots',
			'use_current'     => '',
			'template'        => 'Home Page Gardening',
			'template_custom' => '',
		);
		update_option( 'widget_pods_widget_single', $pods_single_content );
		
		$counter++;
		$active_widgets[ $sidebars['4'] ][] = 'pods_widget_single-' . $counter;
		$pods_single_content[ $counter ] = array (
			'title'           => '',
			'pod_type'        => 'ingredient',
			'slug'            => 'watercress',
			'use_current'     => '',
			'template'        => 'Home Page Ingredients',
			'template_custom' => '',
		);
		update_option( 'widget_pods_widget_single', $pods_single_content );
		
		$counter++;
		$active_widgets[ $sidebars['4'] ][] = 'pods_widget_single-' . $counter;
		$pods_single_content[ $counter ] = array (
			'title'           => '',
			'pod_type'        => 'recipe',
			'slug'            => 'aussie-roseville-tea-sandwiches',
			'use_current'     => '',
			'template'        => 'Home Page Recipes',
			'template_custom' => '',
		);
		update_option( 'widget_pods_widget_single', $pods_single_content );

		$counter++;
		$active_widgets[ $sidebars['5'] ][] = 'pods_widget_single-' . $counter;
		$pods_single_content[ $counter ] = array (
			'title'           => '',
			'pod_type'        => 'menu',
			'slug'            => 'faroe-island-salmon',
			'use_current'     => '',
			'template'        => 'Home Featured Menu Item',
			'template_custom' => '',
		);
		update_option( 'widget_pods_widget_single', $pods_single_content );

		$counter++;
		$active_widgets[ $sidebars['5'] ][] = 'pods_widget_single-' . $counter;
		$pods_single_content[ $counter ] = array (
			'title'           => '',
			'pod_type'        => 'menu',
			'slug'            => 'fish-tacos',
			'use_current'     => '',
			'template'        => 'Home Featured Menu Item',
			'template_custom' => '',
		);
		update_option( 'widget_pods_widget_single', $pods_single_content );

		$counter++;
		$active_widgets[ $sidebars['5'] ][] = 'pods_widget_single-' . $counter;
		$pods_single_content[ $counter ] = array (
			'title'           => '',
			'pod_type'        => 'menu',
			'slug'            => 'the-farm-table-burger',
			'use_current'     => '',
			'template'        => 'Home Featured Menu Item',
			'template_custom' => '',
		);
		update_option( 'widget_pods_widget_single', $pods_single_content );

		$counter++;
		$active_widgets[ $sidebars['2'] ][] = 'pods_widget_list-' . $counter;
		$pods_list_content[ $counter ] = array (
			'title'           => '',
			'pod_type'        => 'recipe',
			'template'        => 'Home Featured Menu Item',
			'template_custom' => '',
			'limit'           => 3,
            'orderby'         => '',
            'where'           => '',
            'expires'         => 300,
            'cache_mode'      => 'none',
            'before_content'  => '',
            'after_content'   => '',
		);
		update_option( 'widget_pods_widget_list', $pods_list_content );
		
		// Save the $active_widgets array.
		update_option( 'sidebars_widgets', $active_widgets );
	}
}