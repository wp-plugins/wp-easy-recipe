<?php
/*
Plugin Name: WP Easy Recipe
Plugin URI: http://raghunathgurjar.wordpress.com
Description: "WP Easy Recipe" is a very simple plugins for manage the recipe content on your site. 
Version: 1.1
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
	register_setting('wp_easy_recipe_options','wpe_dothtml');
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
				<td valign="top">Add Share Buttons Code:<br>
				<textarea type="textarea" id="wpe_shareBtns" name="wpe_shareBtns" rows="10" cols="35"  ><?php echo esc_attr(get_option('wpe_shareBtns')); ?></textarea>
				</td>
					<td>
		<strong>/* Sortcodes */</strong><br><br>[wp_recipe_list_page] : Use for display all recipe in a single page
	           <br><br>[cat_menu catid=""] : Use for get the child category link with thumbnail
	           <br><br>[wp_easy_recipe_slider] : Use for add recipe feature images slider
	           <br><br>[wer_recipe_of_the_day] :Use for display recipe of the day  
	           <br><br>[wer_ten_best_recipe] :Use for display 10 best recipe</td>
				<td rowspan="4" valign="top" style="padding-left: 20px;border-left:1px solid #ccc;">
					<h2>Plugin Author:</h2>
					<div style="font-size: 14px;">
	<img src="<?php echo  plugins_url( 'lib/images/raghu.jpg' , __FILE__ );?>" width="100" height="100"><br><a href="http://raghunathgurjar.wordpress.com" target="_blank">Raghunath Gurjar</a><br><br><a href="mailto:raghunath.0087@gmail.com" target="_blank">Contact Me!</a><br><br>Author Blog <a href="http://raghunathgurjar.wordpress.com" target="_blank">http://raghunathgurjar.wordpress.com</a>
	<br><br><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=WN785E5V492L4" target="_blank" style="font-size: 17px; font-weight: bold;">Donate for this plugin</a><br><br>
	My Other Plugins:<br>
	<ul>
		<li><a href="https://wordpress.org/plugins/simple-testimonial-rutator/" target="_blank">Simple Testimonial Rutator(Responsive)</a></li>
		<li><a href="https://wordpress.org/plugins/wp-easy-recipe/" target="_blank">WP Easy Recipe</a></li>
		</ul>

	</div></td>
			</tr>	
			<tr scope="row">
				<td valign="top"><label for="wpe_dothtml"><strong style="font-size:16px;">Add .html in url: </strong></label>
				<select type="textarea" id="wpe_dothtml" name="wpe_dothtml" >
					<option value="no" <?php if(get_option('wpe_dothtml')=='no'){echo 'selected="selected"';} ?>>No</option><option value="yes" <?php if(get_option('wpe_dothtml')=='yes'){echo 'selected="selected"';} ?>>Yes</option></select><br>(<strong>Example:</strong> http://your-domain/recipe/demo.html)
				</td>
				<td valign="top">&nbsp;</td>
				<td valign="top">&nbsp;</td>
			</tr>	
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>	
			<tr><td colspan="3">&nbsp;</td></tr>	
			<tr>
				<td ><?php echo get_submit_button('Save Settings','button-primary','submit','','');?></td><td>&nbsp;</td><td>&nbsp;</td>
			</tr>	
			<tr><td colspan="3">&nbsp;</td></tr>		
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
	delete_option('wpe_dothtml');
} 

?>
