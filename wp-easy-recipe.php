<?php
/*
Plugin Name: WP Easy Recipe
Plugin URI: http://raghunathgurjar.wordpress.com
Description: "WP Easy Recipe" is a very simple plugins for manage the recipe content on your site. 
Version: 1.0
Text Domain: raghunath
Author: Raghunath Gurjar
Author URI: http://www.facebook.com/raghunathprasadgurjar
*/

/* 
* Setup Admin menu item 
*/

//"WP Easy Recipe" Admin Menu Item
add_action('admin_menu','wp_easy_recipe_menu');

function wp_easy_recipe_menu(){

	add_options_page('WP Easy Recipe','WP Easy Recipe','manage_options','wp-easy-recipe-plugin','wp_easy_recipe_admin_option_page');

}

//Define Action for register "WP Easy Recipe" Options
add_action('admin_init','wp_easy_recipe_init');


//Register "WP Easy Recipe" options
function wp_easy_recipe_init(){

	register_setting('wp_easy_recipe_options','wpe_shareBtns');
} 

/* Options Form */
function wp_easy_recipe_admin_option_page(){ ?>
	<div> 
	<h2>WP Easy Recipe Settings :</h2>
	<p>Please fill all options value.</p>
<!-- Start Options Form -->
	<form action="options.php" method="post" id="wp-easy-recipe-admin-form">
		<table class="wp-easy-recipe">
			<tr>
				<td >Add Share Buttons Code:<br>
				<textarea type="textarea" id="wpe_shareBtns" name="wpe_shareBtns" rows="10" cols="50"  ><?php echo esc_attr(get_option('wpe_shareBtns')); ?></textarea>
				</td>
				<td rowspan="10" valign="top" style="border-left: 1px solid rgb(204, 204, 204); padding-left: 20px;">
	           /* NOTE: Add [wp_recipe_list_page] this sortcode for get all the recipe pages */
	            </td>
			</tr>	
			
			<tr>
				<td><?php echo get_submit_button('Save Settings','button-primary','submit','','');?></td>
			</tr>	
			<tr><td >&nbsp;</td></tr>		
		</table>
    <?php settings_fields('wp_easy_recipe_options'); ?>
	</form>
<!-- End Options Form -->
	</div>
<?php 
} 

/*
 * Return all options value
 * */
function get_wp_easy_recipe_options() {
		global $wpdb;
		$ctOptions = $wpdb->get_results("SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE 'wpe_%'");
								
		foreach ($ctOptions as $option) {
			$ctOptions[$option->option_name] =  $option->option_value;
		}
	
		return $ctOptions;	
	}
	
	
/*
  * DEFINE "WP EASY RECIPE" POSTS
*/

//Include wp easy recipe files for manage recipe pages
include dirname( __FILE__ ) .'/lib/class-wp-easy-recipe.php';


/* 
*Delete the options during disable the plugins 
*/
if( function_exists('register_uninstall_hook') )

	register_uninstall_hook(__FILE__,'wp_easy_recipe_uninstall');   

//Delete all Custom Tweets options after delete the plugin from admin
function wp_easy_recipe_uninstall(){
	delete_option('wpe_shareBtns');
} 

?>
