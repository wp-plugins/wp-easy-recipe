<?php
/*
 * Function for get all recipe pages on list page
 * 
 * */
function wp_easy_recipe_list_page()
{
	
query_posts( array('orderby' => 'post_title', 'order' => 'ASC','post_type'=>'wp_easy_recipe' ));
$wer_content='';
$wer_content .="<div class='wp-recipe-list wp-easy-recipe'>";
while ( have_posts() ) : the_post();
	/* Get all recipe post tags */
	$recipetagsAry=wp_get_post_tags(get_the_ID());
	$reciepTags='';
	foreach($recipetagsAry as $recipetagsVal)
	{
		$reciepTags.='<a href="' . esc_attr(get_term_link($recipetagsVal->term_id, $recipetagsVal->taxonomy)) . '" title="' . sprintf( __( "View all recipe in %s" ), $recipetagsVal->name ) . '" ' . '>' . $recipetagsVal->name.'</a>, ';
		
		}
   
   /* Get all recipe post category */
	$recipecategoryAry=get_the_terms(get_the_ID(),'wp_easy_recipe_tax');
	$recipeCat='';
	foreach($recipecategoryAry as $recipecategoryVal)
	{
		$recipeCat.='<a href="' . esc_attr(get_term_link($recipecategoryVal->term_id, $recipecategoryVal->taxonomy)) . '" title="' . sprintf( __( "View all recipe in %s" ), $recipecategoryVal->name ) . '" ' . '>' . $recipecategoryVal->name.'</a>, ';
		}
		/* Get author inaformation*/ 
		$author_bio_avatar_size = apply_filters( 'twentytwelve_author_bio_avatar_size', 10 );
		$aurhorImg1 =get_avatar( get_the_author_meta( 'user_email' ), $author_bio_avatar_size );
		if($aurhorImg1!='')
		$aurhorImg='<a href="#">'.$aurhorImg1.'</a>';
	
	   $wer_content .='<ul class="dessert">
                    	<li>
                        	<div class="cook_list_left">
                            	<a href="'.get_the_permalink().'">'.get_the_post_thumbnail(get_the_ID(),array(240,200)).'</a>
                            	<div class="date">'.get_the_time('j').'<span>'.get_the_time('M').'</span></div>
                            </div>
                            <div class="cook_list_right">
                            		<h3><a href="'.get_the_permalink().'">'.get_the_title().'</a></h3>
                                    <div class="rating_wp clearfix">
                                    	<div class="rating">
                                        	<img alt="" src="'.get_template_directory_uri().'/images/rate.jpg">
											
                                        </div>
                                        <div class="admin_name">
                                       	    <div class="admin_pro"><img alt="" src="'.get_template_directory_uri().'/images/admin_pro.jpg"></div>
                                            <div class="admin_pro_name"><a href="'.esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ).'">'.get_the_author().'</a></div>
                                      </div>
                                    </div>
                                    <ul class="cook_cate">
                                    <li><i class="prepairation_time"></i> <strong>Prepairation Time:</strong> '.get_post_meta(get_the_ID(),'wp_er_prepairation-time',true).'</li>
                                    <li><i class="cooking_time"></i> <strong>Cooking Time:</strong> '.get_post_meta(get_the_ID(),'wp_er_cooking-time',true).'</li>
                                    <li><i class="servings"></i> <strong>Servings:</strong> '.get_post_meta(get_the_ID(),'wp_er_servings',true).'</li>
                                    <li><i class="category"></i> <strong>Category:</strong>  '.get_post_meta(get_the_ID(),'wp_er_category',true).'</li>
                               </ul>
                                    
                                    <ul class="cook_tag">
                                    	<li class="list_drop">'.$recipeCat.'</li>
                                        <li class="list_drop1">'.$reciepTags.'</li>
                                    </ul>
                            </div>
                        </li>
                        
                    </ul>';
	endwhile;
	$wer_content .="</div>";
	wp_reset_query();
	return $wer_content;
	
	}
	
/* ADD NEW SHORT CODE FOR PUBLISH ALL RECIPE ON LIST PAGE */
add_shortcode('wp_recipe_list_page','wp_easy_recipe_list_page'); // use [wp_recipe_list_page] shortcode
?>
