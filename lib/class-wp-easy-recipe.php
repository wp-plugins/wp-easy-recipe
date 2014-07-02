<?php
/** 
 * Create a new recipe post type
 * define the all options for new post type
 */

add_action('init','add_wp_easy_recipe_post_type');

function add_wp_easy_recipe_post_type()
{
	$labels = array(
	   'name'=>__('WP-Easy Recipes'),
	   'singular_name'=>__('WP-Easy Recipe'),
	   'edit_item'         => __( 'Edit Recipe' ),
	   'update_item'       => __( 'Update Recipe' ),
	   'add_new_item'      => __( 'Add New Recipe' ),);
	
	register_post_type('wp_easy_recipe',
	array(
	 'labels'=>$labels,
	 'public'=>true,
	 'has_archive'=>false,
	 'taxonomies' => array('post_tag','wp_easy_recipe_tax'),
	 'supports'=>array('title','thumbnail','editor'),
	 'rewrite' =>array( 'slug' => 'recipe','with_front' => false)
	    )
	);
}


/*
 * Add New Meta Box Field For Recipe
 * define all meta boxes that will be publish on recipe pages 
 * */

//define action for create new meta boxes
add_action( 'add_meta_boxes', 'add_wp_easy_recipe_meta_box' );

/**
 * Adds the wp easy recipe meta box
 */
function add_wp_easy_recipe_meta_box()
{
 global $meta_box;
    add_meta_box($meta_box['id'], $meta_box['title'], 'show_wp_easy_recipe_meta_box','wp_easy_recipe', $meta_box['context'], $meta_box['priority']);
}

//Define meta box fields

  $prefix = 'wp_er_';
    $meta_box = array(
    'id' => 'wp-easy-recipe-meta-box',
    'title' => 'Recipe Extra Information',
    'page' => '',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
    array(
    'name' => 'Prepairation Time:',
    'desc' => '',
    'id' => $prefix . 'prepairation-time',
    'type' => 'text',
    'std' => ''
    ),
    array(
    'name' => 'Cooking Time:',
    'desc' => '',
    'id' => $prefix . 'cooking-time',
    'type' => 'text',
    'std' => ''
    ),
    array(
    'name' => 'Servings:',
    'desc' => '',
    'id' => $prefix . 'servings',
    'type' => 'text',
    'std' => ''
    ),
    array(
    'name' => 'Category:',
    'desc' => '',
    'id' => $prefix . 'category',
    'type' => 'text',
    'std' => ''
    ),
    array(
    'name' => 'Nutrition Facts:',
    'id' => $prefix . 'nutrition-facts',
    'desc' => 'Please add all facts with comma seprated<br><h3 class="hndle"><span>Quick Look</span></h3>',
    'type' => 'textarea',
    'std' => ''
    ),
    array(
    'name' => 'Main Ingredients:',
    'desc' => '',
    'id' => $prefix . 'main-ingredients',
    'type' => 'text',
    'std' => ''
    ),
    array(
    'name' => 'Cuisine:',
    'desc' => '',
    'id' => $prefix . 'cuisine',
    'type' => 'text',
    'std' => ''
    ),
    array(
    'name' => 'Course:',
    'desc' => '',
    'id' => $prefix . 'course',
    'type' => 'text',
    'std' => ''
    ),
    array(
    'name' => 'Lavel Of Cooking:',
    'desc' => '',
    'id' => $prefix . 'lavel-of-cooking',
    'type' => 'text',
    'std' => ''
    ),
    )
    );


//Display WP Easy Recipe Meta Box on recipe pages
function show_wp_easy_recipe_meta_box()
{
global $meta_box, $post;
     wp_nonce_field( 'wp_easy_recipe_meta_box_field', 'wp_easy_recipe_meta_box_once' );
    // Use nonce for verification
    //echo '<input type="hidden" name="wp_easy_recipe_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
    foreach ($meta_box['fields'] as $field) {
    // get current post meta data
   
    $meta = get_post_meta($post->ID, $field['id'], true);
    echo '<p>',
    '<label for="', $field['id'], '">', $field['name'], '</label>','';
    switch ($field['type']) {
    case 'text':
    echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" />', '<br />', $field['desc'];
    break;
    case 'textarea':
    echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>', '<br />', $field['desc'];
    break;
    '</p>';
    }

    }
}

//Define action for save "WP Easy Recipe" Meta Box fields Value
add_action( 'save_post', 'save_wp_easy_recipe_meta_box' );

function save_wp_easy_recipe_meta_box($post_id) {
	global $meta_box;
	// Check if our nonce is set.
	 if ( ! isset( $_POST['wp_easy_recipe_meta_box_once'] ) ) {
			return;
		}
		
	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
	return $post_id;
	}

	// check permissions
	if ('wp_easy_recipe' == $_POST['post_type']) 
	{
		if (!current_user_can('edit_page', $post_id))
		return $post_id;
	} 
	elseif(!current_user_can('edit_post', $post_id)){
	return $post_id;
	}
	
	foreach ($meta_box['fields'] as $field) 
	{
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		if ($new && $new != $old){
		 update_post_meta($post_id, $field['id'], $new);
		} 
		elseif ('' == $new && $old) {
		delete_post_meta($post_id, $field['id'], $old);
		}
	}
}



/* Register New WP EASY RECIPE Texonimoies*/

// define init action and call create_easy_recipe_taxonomies when it fires
add_action( 'init', 'create_wp_easy_recipe_taxonomies', 0 );

// create "wp_easy_recipe_tax" taxonomy for the post type "wp_easy_recipe"
function create_wp_easy_recipe_taxonomies() {
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'              => _x( 'Recipe Categories', 'taxonomy general name' ),
		'singular_name'     => _x( 'Recipe Category', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Recipe Category' ),
		'all_items'         => __( 'All Recipe Category' ),
		'parent_item'       => __( 'Parent Recipe Category' ),
		'parent_item_colon' => __( 'Parent Recipe Category:' ),
		'edit_item'         => __( 'Edit Recipe Category' ),
		'update_item'       => __( 'Update Recipe Category' ),
		'add_new_item'      => __( 'Add New Recipe Category' ),
		'new_item_name'     => __( 'New Recipe Category Name' ),
		'menu_name'         => __( 'Recipe Categories' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'recipe-category', 'with_front' => false),
	);

	register_taxonomy( 'wp_easy_recipe_tax', array( 'wp_easy_recipe' ), $args );

}

/* End WP EASY RECIPE Taxonomies */

//Flush the rules
add_action('init', 'custom_taxonomy_flush_rewrite');
function custom_taxonomy_flush_rewrite() {
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
}


/*
  * DEFINE "WP EASY RECIPE" POSTS IN FRONT SECTION
*/

//Include wp easy recipe files for manage from front-end 
include dirname( __FILE__ ) .'/front-pages.php';



add_action( 'wp_enqueue_scripts', 'str_testimonials_style' );

//register list page style files
function str_testimonials_style() {
wp_register_style( 'wp_easy_recipe_style', plugins_url( 'css/wer-style.css',__FILE__) );
wp_enqueue_style( 'wp_easy_recipe_style' );
}

?>
