<?php
/** 
 * Create a new recipe post type
 * define the all options for new post type
 */

add_action('init','add_wp_easy_recipe_post_type');

function add_wp_easy_recipe_post_type()
{
	$labels = array(
	   'name'=>__('Recipes'),
	   'singular_name'=>__('Recipe'),
	   'edit_item'         => __( 'Edit Recipe' ),
	   'update_item'       => __( 'Update Recipe' ),
	   'add_new_item'      => __( 'Add New Recipe' ),);
	
	register_post_type('wp_easy_recipe',
	array(
	 'labels'=>$labels,
	 'public'=>true,
	 'has_archive'=>false,
	 'hierarchical'	  => true,
	 'taxonomies' => array('post_tag','wp_easy_recipe_tax'),
	 'supports'=>array('title','thumbnail','editor'),
	 'rewrite'         =>array('slug'=>'recipe-page'),
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
    'name' => 'Prepairation Time: ',
    'desc' => '',
    'id' => $prefix . 'prepairation-time',
    'type' => 'text',
    'std' => ''
    ),
    array(
    'name' => 'Cooking Time: ',
    'desc' => '',
    'id' => $prefix . 'cooking-time',
    'type' => 'text',
    'std' => ''
    ),
    array(
    'name' => 'Servings: ',
    'desc' => '',
    'id' => $prefix . 'servings',
    'type' => 'text',
    'std' => ''
    ),
    array(
    'name' => 'Category: ',
    'desc' => '',
    'id' => $prefix . 'category',
    'type' => 'text',
    'std' => ''
    ),
    array(
    'name' => 'Nutrition Facts: ',
    'id' => $prefix . 'nutrition-facts',
    'desc' => 'Please add all facts with comma seprated<br><h3 class="hndle"><span>Quick Look</span></h3>',
    'type' => 'textarea',
    'std' => ''
    ),
    array(
    'name' => 'Main Ingredients: ',
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
    'name' => 'Lavel Of Cooking: ',
    'desc' => '',
    'id' => $prefix . 'lavel-of-cooking',
    'type' => 'select',
    'std' => '',
    'options'=>'Easy,Medium,Hard',
    ),
    array(
    'name' => 'Show this post in 10 Best Recipes? :',
    'desc' => '',
    'id' => $prefix . 'ten_best_recipe',
    'type' => 'select',
    'std' => '',
    'options'=>'No,Yes',
    )
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
    case 'select':
    echo '<select name="', $field['id'], '" id="', $field['id'], '" >';
    $optionVal=explode(',',$field['options']);
    foreach($optionVal as $optVal):
    if($meta==$optVal){
    $valseleted =' selected="selected"';}else {
		 $valseleted ='';
		}
    echo '<option value="', $optVal, '" ',$valseleted,' id="', $field['id'], '">', $optVal, '</option>';
    endforeach;
    echo '</select>',$field['desc'];
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
		'rewrite'           => array( 'slug' => 'recipe', 'with_front' => true,'hierarchical' => true),
	);

	register_taxonomy( 'wp_easy_recipe_tax', array( 'wp_easy_recipe' ), $args );

}


/* End WP EASY RECIPE Taxonomies */


//remove 404 page error during get the custom post type on tag list page
function add_custom_types_to_tax( $query ) {
if( is_category() || is_tag() && empty( $query->query_vars['suppress_filters'] ) ) {
// Get all your post types
$post_types = array( 'post', 'wp_easy_recipe' );
$query->set( 'post_type', $post_types );
return $query;
}
}
add_filter( 'pre_get_posts', 'add_custom_types_to_tax' );

/*
 * Check for add .html in url
 * */
$getoption=get_wp_easy_recipe_options();
if($getoption['wpe_dothtml']=='yes'):


	//Define custom rewrite rule for add .html to recipe posts and their custom taxonomy
	add_action('init', 'add_html_ext_to_custom_post_types');
	function add_html_ext_to_custom_post_types() {
		add_rewrite_rule('^([^/]+)\.html', 'index.php?wp_easy_recipe=$matches[1]', 'top');
		add_rewrite_rule('^recipe/([^/]+)\.html', 'index.php?wp_easy_recipe_tax=$matches[1]', 'top');
		add_rewrite_rule('^recipe/([^/]+)/([^/]+)\.html', 'index.php?wp_easy_recipe_tax=$matches[2]', 'top');
		add_rewrite_rule('^recipe/([^/]+)/([^/]+)/([^/]+)\.html', 'index.php?wp_easy_recipe_tax=$matches[2]', 'top');
		add_rewrite_rule('^tag/([^/]+)\.html', 'index.php?tag=$matches[1]', 'top');
	/*
	 add_rewrite_rule( '^posttype_slug/(.+?)/(.+?)/(.+?)$', 'index.php?taxonomy=$matches[1]&taxonomy=$matches[2]&posttype=$matches[3]', 'top' );
        add_rewrite_rule( '^posttype_slug/(.+?)/(.+?)/$', 'index.php?posttype=$matches[2]', 'top' );
        add_rewrite_rule( '^posttype_slug/(.+?)/(.+?)/(.+?)$', 'index.php?posttype=$matches[3]', 'top' );
        add_rewrite_rule( '^posttype_slug/(.+?)/(.+?)/?$', 'index.php?taxonomy=$matches[2]', 'top' );
        add_rewrite_rule( '^posttype_slug/(.+?)$', 'index.php?taxonomy=$matches[1]', 'top' );
	 */
	}

	//return recipe page url with .html
	function my_post_type_link_filter_function( $post_link, $id = 0, $leavename = FALSE ) {
		$post = get_post($id);
		if ( !is_object($post) || $post->post_type != 'wp_easy_recipe' ) {
		  return $post_link;
		}else
		{
		$post_link=str_replace('/recipe-page/','/',$post_link.'.html');
			return $post_link;
			}
	   
	  }
	  
	add_filter('post_type_link', 'my_post_type_link_filter_function', 1, 3);

	/*
	 * Add .html for category and tags
	 * */

	add_filter('term_link', 'term_link_filter', 10);
	function term_link_filter($url) {
	 if($terms = wp_get_object_terms(get_the_ID(), 'wp_easy_recipe_tax'))
	  {
		$terntaxonomy=$terms[0]->taxonomy;
	  }
	  
		if(($terntaxonomy=='wp_easy_recipe_tax') and (!isset($request['tag']))){
           return $url. ".html";}
		else{
			return $url;
			}
	}
endif;

//Flush the rules
add_action('init', 'custom_taxonomy_flush_rewrite');
function custom_taxonomy_flush_rewrite() {
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
}




/* 
 * ADD IMAGE FIELD ON RECIPE CATEGORY PAGE 
 * 
 * */
define('CAT_IMAGE_PLACEHOLDER', plugins_url( '/images/placeholder.jpg' , __FILE__ ));

add_action('admin_init', 'wp_easy_recipe_catimg_init');
function wp_easy_recipe_catimg_init() {
	$wp_easy_recipe_taxonomies = get_taxonomies();
	
	if (is_array($wp_easy_recipe_taxonomies)) {
	    foreach ($wp_easy_recipe_taxonomies as $wp_easy_recipe_taxonomy) {
			
			if($wp_easy_recipe_taxonomy=='wp_easy_recipe_tax'):
	        add_action($wp_easy_recipe_taxonomy.'_add_form_fields', 'wp_easy_recipe_add_texonomy_field');
			add_action($wp_easy_recipe_taxonomy.'_edit_form_fields', 'wp_easy_recipe_edit_texonomy_field');
			add_filter( 'manage_edit-' . $wp_easy_recipe_taxonomy . '_columns', 'wp_easy_recipe_taxonomy_columns' );
			add_filter( 'manage_' . $wp_easy_recipe_taxonomy . '_custom_column', 'wp_easy_recipe_taxonomy_column', 10, 3 );
	       endif;
	    }
	}
}


// add image field in add form
function wp_easy_recipe_add_texonomy_field() {
	if (get_bloginfo('version') >= 3.5)
		wp_enqueue_media();
	else {
		wp_enqueue_style('thickbox');
		wp_enqueue_script('thickbox');
	}
	
	echo '<div class="form-field">
		<label for="taxonomy_image">' . __('Image', 'wer') . '</label>
		<input type="text" name="taxonomy_image" id="taxonomy_image" value="" />
		<br/>
		<button class="wp_easy_recipe_upload_image_button button">' . __('Upload/Add image', 'wer') . '</button>
	</div>'.wp_easy_recipe_script();
}

//Add the new image field in edit form
function wp_easy_recipe_edit_texonomy_field($taxonomy) {
	if (get_bloginfo('version') >= 3.5)
		wp_enqueue_media();
	else {
		wp_enqueue_style('thickbox');
		wp_enqueue_script('thickbox');
	}
	
	if (wp_easy_recipe_taxonomy_image_url( $taxonomy->term_id, NULL, TRUE ) == CAT_IMAGE_PLACEHOLDER) 
		$image_text = "";
	else
		$image_text = wp_easy_recipe_taxonomy_image_url( $taxonomy->term_id, NULL, TRUE );
	echo '<tr class="form-field">
		<th scope="row" valign="top"><label for="taxonomy_image">' . __('Image', 'wer') . '</label></th>
		<td><img class="taxonomy-image" src="' . wp_easy_recipe_taxonomy_image_url( $taxonomy->term_id, NULL, TRUE ) . '"/><br/><input type="text" name="taxonomy_image" id="taxonomy_image" value="'.$image_text.'" /><br />
		<button class="wp_easy_recipe_upload_image_button button">' . __('Upload/Add image', 'wer') . '</button>
		<button class="wp_easy_recipe_remove_image_button button">' . __('Remove image', 'wer') . '</button>
		</td>
	</tr>'.wp_easy_recipe_script();
}
//upload the files using wordpress default functionalty
function wp_easy_recipe_script() {
	return '<script type="text/javascript">
	    jQuery(document).ready(function($) {
			var wordpress_ver = "'.get_bloginfo("version").'", upload_button;
			$(".wp_easy_recipe_upload_image_button").click(function(event) {
				upload_button = $(this);
				var frame;
				if (wordpress_ver >= "3.5") {
					event.preventDefault();
					if (frame) {
						frame.open();
						return;
					}
					frame = wp.media();
					frame.on( "select", function() {
						// Grab the selected attachment.
						var attachment = frame.state().get("selection").first();
						frame.close();
						if (upload_button.parent().prev().children().hasClass("tax_list")) {
							upload_button.parent().prev().children().val(attachment.attributes.url);
							upload_button.parent().prev().prev().children().attr("src", attachment.attributes.url);
						}
						else
							$("#taxonomy_image").val(attachment.attributes.url);
					});
					frame.open();
				}
				else {
					tb_show("", "media-upload.php?type=image&amp;TB_iframe=true");
					return false;
				}
			});
			
			$(".wp_easy_recipe_remove_image_button").click(function() {
				$("#taxonomy_image").val("");
				$(this).parent().siblings(".title").children("img").attr("src","' . CAT_IMAGE_PLACEHOLDER . '");
				$(".inline-edit-col :input[name=\'taxonomy_image\']").val("");
				return false;
			});
			
			if (wordpress_ver < "3.5") {
				window.send_to_editor = function(html) {
					imgurl = $("img",html).attr("src");
					if (upload_button.parent().prev().children().hasClass("tax_list")) {
						upload_button.parent().prev().children().val(imgurl);
						upload_button.parent().prev().prev().children().attr("src", imgurl);
					}
					else
						$("#taxonomy_image").val(imgurl);
					tb_remove();
				}
			}
			
			$(".editinline").live("click", function(){  
			    var tax_id = $(this).parents("tr").attr("id").substr(4);
			    var thumb = $("#tag-"+tax_id+" .thumb img").attr("src");
				if (thumb != "' . CAT_IMAGE_PLACEHOLDER . '") {
					$(".inline-edit-col :input[name=\'taxonomy_image\']").val(thumb);
				} else {
					$(".inline-edit-col :input[name=\'taxonomy_image\']").val("");
				}
				$(".inline-edit-col .title img").attr("src",thumb);
			    return false;  
			});  
	    });
	</script>';
}

// save recipe taxonomy image while edit or save recipe category
add_action('edit_term','wp_easy_recipe_save_taxonomy_image');
add_action('create_term','wp_easy_recipe_save_taxonomy_image');
function wp_easy_recipe_save_taxonomy_image($term_id) {
    if(isset($_POST['taxonomy_image']))
        update_option('wp_easy_recipe_taxonomy_image'.$term_id, $_POST['taxonomy_image']);
}

// get attachment ID by image url
function wp_easy_recipe_get_attachment_id_by_url($image_src) {
    global $wpdb;
    $query = "SELECT ID FROM {$wpdb->posts} WHERE guid = '$image_src'";
    $id = $wpdb->get_var($query);
    return (!empty($id)) ? $id : NULL;
}

// get recipe taxonomy image url for the given term_id (Place holder image by default)
function wp_easy_recipe_taxonomy_image_url($term_id = NULL, $size = NULL, $return_placeholder = FALSE) {
	if (!$term_id) {
		if (is_category())
			$term_id = get_query_var('cat');
		elseif (is_tax()) {
			$current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
			$term_id = $current_term->term_id;
		}
	}
	
    $taxonomy_image_url = get_option('wp_easy_recipe_taxonomy_image'.$term_id);
    if(!empty($taxonomy_image_url)) {
	    $attachment_id = wp_easy_recipe_get_attachment_id_by_url($taxonomy_image_url);
	    if(!empty($attachment_id)) {
	    	if (empty($size))
	    		$size = 'full';
	    	$taxonomy_image_url = wp_get_attachment_image_src($attachment_id, $size);
		    $taxonomy_image_url = $taxonomy_image_url[0];
	    }
	}

    if ($return_placeholder)
		return ($taxonomy_image_url != '') ? $taxonomy_image_url : CAT_IMAGE_PLACEHOLDER;
	else
		return $taxonomy_image_url;
}

function wp_easy_recipe_quick_edit_custom_box($column_name, $screen, $name) {
	if ($column_name == 'thumb') 
		echo '<fieldset>
		<div class="thumb inline-edit-col">
			<label>
				<span class="title"><img src="" alt="Thumbnail" style="width:50px;"/></span>
				<span class="input-text-wrap"><input type="text" name="taxonomy_image" value="" class="tax_list" /></span>
				<span class="input-text-wrap">
					<button class="wp_easy_recipe_upload_image_button button">' . __('Upload/Add image', 'wer') . '</button>
					<button class="wp_easy_recipe_remove_image_button button">' . __('Remove image', 'wer') . '</button>
				</span>
			</label>
		</div>
	</fieldset>';
}

/**
 * Image column added to recipe category admin.
 *
 */
function wp_easy_recipe_taxonomy_columns( $columns ) {
	$new_columns = array();
	$new_columns['cb'] = $columns['cb'];
	$new_columns['thumb'] = __('Image', 'wer');
	unset( $columns['cb'] );
	return array_merge( $new_columns, $columns );
}

/**
 * Image column value added to recipe category admin.
 *
 */
function wp_easy_recipe_taxonomy_column( $columns, $column, $id ) {
	if ( $column == 'thumb' )
		$columns = '<span><img src="' . wp_easy_recipe_taxonomy_image_url($id, NULL, TRUE) . '" alt="' . __('Thumbnail', 'wer') . '" class="wp-post-image" style="width:60px;"/></span>';
	
	return $columns;
}


if ( strpos( $_SERVER['SCRIPT_NAME'], 'edit-tags.php' ) > 0 ) {
	add_action('quick_edit_custom_box', 'wp_easy_recipe_quick_edit_custom_box', 10, 3);
}

/*
 * Register new tips type post
 * */
add_action('init','create_tips_post_type');
function create_tips_post_type(){
	$lables=array(
	'name' =>__('Recipe Tips'),
	'singular_name' =>__('Recipe Tip'),
	'update_item' =>__('Update Tip'),
	'edit_item' =>__('Edit Tip'),
	'add_new_item' =>__('Add New Tip'),);
	
	register_post_type('wp_easy_recipe_tips',array(
	'labels' =>$lables,
	'public' =>true,
	'has_archive' =>false,
	'supports' =>array('title','editor'),
	'rewrite' =>false,
	
	));
	
	}
	
/* Define a sort code for get latest tips*/
add_shortcode('recent_tips','get_single_recent_tip'); //use [recent_tips list_page_id="TIPS_LIST_PAGE_ID"]
function get_single_recent_tip($attr){
	
if($attr['list_page_id']!=''){
	$viewall='<a href="'.get_the_permalink($attr['list_page_id']).'">View All Tips</a>';
	}else{	
		$viewall='';
		}		
		
global $wpdb;
//define query for get the 10 best category
$qry="SELECT * FROM $wpdb->posts WHERE $wpdb->posts.post_status = 'publish' AND $wpdb->posts.post_type = 'wp_easy_recipe_tips' ORDER BY $wpdb->posts.post_date DESC limit 1";

$tipAry=$wpdb->get_results($qry);

$tip_content='';
$tip_content .='<div class="wp_easy_recipe_tip"><span class="tip-left">Tips of the Day:</span>';
foreach ( $tipAry as $tipVal ) 
{
    $tip_content .='<span class="tip-center" id="ten'.$tipVal->ID.'">'.$tipVal->post_content.'</span><span class="tip-right">'.$viewall.'</span>';
}
$tip_content .="</div>";
wp_reset_query();	

return $tip_content;
}



/* Define a sort code for get latest tips*/
add_shortcode('all_tips','get_all_tips');
function get_all_tips(){
global $wpdb;
//define query for get the 10 best category
$qry="SELECT * FROM $wpdb->posts WHERE $wpdb->posts.post_status = 'publish' AND $wpdb->posts.post_type = 'wp_easy_recipe_tips' ORDER BY $wpdb->posts.post_date DESC";
$tipsAry=$wpdb->get_results($qry);
$tp=1;
$tips_content .='<ul class="wp_easy_recipe_tips tips runing">';
foreach ( $tipsAry as $tipsVal ) 
{
	if($tp!='1'){
    $tips_content .='<li>'.$tipsVal->post_content.'</li>';
    }
    $tp++;
}
$tips_content .="</ul>";
wp_reset_query();	

return $tips_content;
}


if(!is_admin()){

/*
 * 
 * Define function for get the recipe children category
 * 
 * */
function get_recipe_menu_categories ($atts)
{ 
	$term_id = $atts['catid'];
	$taxonomy_name = 'wp_easy_recipe_tax';
	$termchildren = get_term_children( $term_id, $taxonomy_name );

	$customChildCat='<ul class="submenu nav-submenu">';
	foreach ( $termchildren as $child ) 
	{
		$term = get_term_by( 'id', $child, $taxonomy_name );
		$cateImgSrc=get_option('wp_easy_recipe_taxonomy_image'.$term->term_id);
      
        if($cateImgSrc!=''){
        $catimg='<span class="menu-thumb"><img src="'.$cateImgSrc.'" alt="'.$term->name.'"></span>';
        }else{
			$catimg='<span class="menu-thumb"><img src="'.plugins_url( '/images/placeholder.jpg' , __FILE__ ).'" alt="'.$term->name.'"></span>';
			}
        
    
    
    
      if($terms = wp_get_object_terms(get_the_ID(), 'wp_easy_recipe_tax'))
	  {
		$terntaxonomy=$terms[0]->taxonomy;
	  }
	  
		if(($terntaxonomy=='wp_easy_recipe_tax') and (!isset($request['tag']))){
			$catLink=get_term_link( $child, $taxonomy_name );
			
			}else{
				$getoption=get_wp_easy_recipe_options();
if($getoption['wpe_dothtml']=='yes'){
	$catLink=get_term_link( $child, $taxonomy_name ).'.html';
	}else{
		$catLink=get_term_link( $child, $taxonomy_name );
		}
				
				
				}
        
		$customChildCat.='<li><a href="' . $catLink.'"><span class="menu-title">' . $term->name . '</span>'.$catimg.'</a></li>';
	}
	
	$customChildCat.='</ul>';
	
	return $customChildCat;

}

add_shortcode('cat_menu','get_recipe_menu_categories'); 
//use sortcode [cat_menu catid=INTER CATEGORY ID] for get the child category

/*
 * 
 * Define function for get the Quick Link Menu
 * 
 * */
function get_wp_easy_recipe_quick_links($atts)
{ 
	//echo $catID;
	$term_id = $atts['parentid'];
	$taxonomy_name = 'wp_easy_recipe_tax';
	$termchildren = get_term_children( $term_id, $taxonomy_name );
	$quicklinks='';
	foreach ( $termchildren as $child ) 
	{
		$term = get_term_by( 'id', $child, $taxonomy_name );
		$quicklinks.='<a href="' . get_term_link( $child, $taxonomy_name ) .'">' . $term->name . '</a>,';
	}

	return $quicklinks;

}

add_shortcode('quicklink_menu','get_wp_easy_recipe_quick_links'); 

/*
  * DEFINE "WP EASY RECIPE" LIST PAGE TEMPLATE
  * define [wp_recipe_list_page] for get all recipe on any specific page
  * 
*/

//Include wp easy recipe files for manage main recipe list page 
include dirname( __FILE__ ) .'/shortcode-list-page.php';

/*
 * DEFINE THE SINGLE PAGE TEMPLATE 
 * */

function get_wp_easy_recipe_post_type_template($single_template) {
     global $post;

     if ($post->post_type == 'wp_easy_recipe') {
          $single_template = dirname( __FILE__ ) . '/templates/single-page-template.php';
     }
     return $single_template;
}
add_filter( 'single_template', 'get_wp_easy_recipe_post_type_template' );

/*
 * DEFINE THE LIST PAGE TEMPLATE 
 * */

function get_wp_easy_recipe_archive_template($archive_template) {
     global $post;

     if ($post->post_type == 'wp_easy_recipe') {
          $archive_template = dirname( __FILE__ ) . '/templates/list-page-template.php';
     }
     return $archive_template;
}
add_filter( 'archive_template', 'get_wp_easy_recipe_archive_template' );

/*
 * DEFINE RECIPE FEATURE IMAGE SLIDER 
 * */
include dirname( __FILE__ ) .'/recipe-slider.php';


/*
 * DEFINE STYLE SHEET 
 * */
add_action( 'wp_enqueue_scripts', 'wp_easy_recipe_style' );

//register list page style files
function wp_easy_recipe_style() {
wp_register_style( 'wp_easy_recipe_style', plugins_url( 'css/wer-style.css',__FILE__) );
wp_enqueue_style( 'wp_easy_recipe_style' );
}


/*
 * 
 * Define function for get 10 Best Recipes
 * 
 * */
function get_wer_ten_best_recipe($atts)
{ 
//define query for get the 10 best category
query_posts( array('orderby' => 'post_publish', 'order' => 'ASC','post_type'=>'wp_easy_recipe','post_per_page'=>10,'meta_query'=> array(
    array(
      'key' => 'wp_er_ten_best_recipe',
      'compare' => '=',
      'value' =>'Yes',
      'type' => 'text',
    )
  )));
$wer_content='';
$wer_content .='<div class="ten_best_recipe"><h2 class="ten_best_heading">10 Best Recipes</h2><div class="ten_best_content clearfix">';
//define title length

while ( have_posts() ) : the_post();
if(get_post_meta(get_the_ID(),'wp_er_ten_best_recipe',true)=='Yes'):

if(strlen(get_the_title()) >25){
	$recipetitle=substr(get_the_title(),0,25).'...';
	}else{
		$recipetitle=get_the_title();
		}
		
	   $wer_content .='<div class="ten_best_block" id="ten'.get_the_ID().'">
                        	<div class="ten_best_thumb">
                            	<a href="'.get_the_permalink().'">'.get_the_post_thumbnail(get_the_ID(),array(150,150)).'</a>
                            </div>
                            <h3 class="ten_best_title"><a href="'.get_the_permalink().'">'.$recipetitle.'</a></h3>
                         </div>';
	endif;
	endwhile;
	$wer_content .="</div></div>";
	return $wer_content;
	
	wp_reset_query();

}
//add_action('init','get_wer_ten_best_recipe'); 
add_shortcode('wer_ten_best_recipe','get_wer_ten_best_recipe'); 

/*
 * 
 * Define function for "Recipe Of The Day"
 * 
 * */
function get_wer_recipe_of_the_day($atts)
{ 

global $wpdb;
//define query for get the 10 best category
$qry="SELECT * FROM $wpdb->posts
LEFT JOIN $wpdb->postmeta ON($wpdb->posts.ID = $wpdb->postmeta.post_id)
LEFT JOIN $wpdb->term_relationships ON($wpdb->posts.ID = $wpdb->term_relationships.object_id)
LEFT JOIN $wpdb->term_taxonomy ON($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
WHERE 
$wpdb->term_taxonomy.taxonomy = 'wp_easy_recipe_tax'
AND $wpdb->posts.post_status = 'publish'
AND $wpdb->posts.post_type = 'wp_easy_recipe' ORDER BY $wpdb->posts.post_date DESC limit 1";

$fivesdrafts=$wpdb->get_results($qry);

$wer_content='';
$wer_content .='<div class="recipe_of_the_day"><h2 class="rotd_heading">Recipe of The Day</h2>';
foreach ( $fivesdrafts as $fivesdraft ) 
{
    $wer_content .='<div class="rotd_content_block" id="ten'.$fivesdraft->ID.'">
                        	<div class="rotd_thumb">
                            	<a href="'.get_the_permalink($fivesdraft->ID).'">'.get_the_post_thumbnail($fivesdraft->ID,array(150,150)).'</a>
                            </div>
                            <div class="content"><h3 class="rotd_title"><a href="'.get_the_permalink($fivesdraft->ID).'">'.$fivesdraft->post_title.'</a></h3>
                         <ul class="dotr_cook">
                                    <li>Prepairation Time: '.get_post_meta($fivesdraft->ID,'wp_er_prepairation-time',true).'</li>
                                    <li>Cooking Time: '.get_post_meta($fivesdraft->ID,'wp_er_cooking-time',true).'</li>
                                    <li>Servings: '.get_post_meta($fivesdraft->ID,'wp_er_servings',true).'</li>
                                    <li>Category: '.get_post_meta($fivesdraft->ID,'wp_er_category',true).'</li>
                               </ul>
                               </div></div><a href="'.get_the_permalink($fivesdraft->ID).'" class="continue_reading">Continue reading →</a> ';
 
	
}
  $wer_content .="</div>";
  return $wer_content;
wp_reset_query();

}
add_shortcode('wer_recipe_of_the_day','get_wer_recipe_of_the_day'); 

}
?>
