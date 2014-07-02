<?php
/**
 * The template for displaying all recipe pages
 *
 * This is the template that displays all recipe pages by default.
 *
 */
get_header(); ?>
        <div class="slider-container">
    	<div class="grid-center">
            <h1 class="innerpage-heading"><?php the_title(); ?></h1>
        </div>
    </div>


<div class="bodypan clearfix">
	<div class="bodypaninner clearfix">
    
    	<div class="wp-easy-recipe-details wp-easy-recipe">
			<div class="leftpan">			
                 <?php 
                 while ( have_posts() ) : the_post(); 
                 
    $recipetagsAry=wp_get_post_tags(get_the_ID());
	$reciepTags='';
	foreach($recipetagsAry as $recipetagsVal)
	{
		$reciepTags.='<a href="' . esc_attr(get_term_link($recipetagsVal->term_id, $recipetagsVal->taxonomy)) . '" title="' . sprintf( __( "View all recipe in %s" ), $recipetagsVal->name ) . '" ' . '>' . $recipetagsVal->name.', </a>, ';
		
		}
		
	$recipecategoryAry=get_the_terms(get_the_ID(),'wp_easy_recipe_tax');
	$recipeCat='';
	foreach($recipecategoryAry as $recipecategoryVal)
	{
		$recipeCat.='<a href="' . esc_attr(get_term_link($recipecategoryVal->term_id, $recipecategoryVal->taxonomy)) . '" title="' . sprintf( __( "View all recipe in %s" ), $recipecategoryVal->name ) . '" ' . '>' . $recipecategoryVal->name.'</a>, ';
		}
		
		$author_bio_avatar_size = apply_filters( 'twentytwelve_author_bio_avatar_size', 10 );
		$author_bio_avatar_size1 = apply_filters( 'twentytwelve_author_bio_avatar_size', 'full' );
		$aurhorImg1 =get_avatar( get_the_author_meta( 'user_email' ), $author_bio_avatar_size );
		
		$aurhorFullImg1 =get_avatar( get_the_author_meta( 'user_email' ), $author_bio_avatar_size1 );
		
		$authorDesc =get_the_author_meta('description');
		
		if($aurhorImg1!=''){
		$aurhorImg='<a href="'.esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ).'">'.$aurhorImg1.'</a>';$aurhorFullImg='<a href="'.esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ).'">'.$aurhorFullImg1.'</a>';}
						
		$authorName =get_the_author();

	
                 ?>
                <div class="row">
					<div class="row">
                    	<?php //feature images
                    	the_post_thumbnail('full');
                    	?>
                     </div>
                
      
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			
                <div class="row">                
                        <div class="recipe_left">
                    	<div class="box_first">
                        	<h4>Quick Look</h4>
                            <ul class="quick_look_list">
                            	 <li><span>Main Ingredients</span><?php echo get_post_meta(get_the_ID(),'wp_er_main-ingredients',true);?></li>
                                <li><span>Cuisine</span><?php echo get_post_meta(get_the_ID(),'wp_er_cuisine',true);?></li>
                                <li><span>Course</span><?php echo get_post_meta(get_the_ID(),'wp_er_course',true);?></li>
                                <li><span>Lavel Of Cooking</span><?php echo get_post_meta(get_the_ID(),'wp_er_lavel-of-cooking',true);?></li>
                            </ul>
                            
                        </div>
                        
                        <div class="box_second">
                        	<h4>Nutrition Facts</h4>
                        	
                            <ul class="quick_look_list">
							<?php 
                        	$NutritionFactsAry =explode(',',get_post_meta(get_the_ID(),'wp_er_nutrition-facts',true));
                        	foreach ($NutritionFactsAry as $Nutritionvalue):
                        	echo '<li><span>'.$Nutritionvalue.'</span></li>';
                        	endforeach;
                        	?>
                            
                            </ul>
                            
                        </div>
                    </div>
                        
                        <div class="recipe_right">
                            <h3><?php the_title(); ?></h3>
                            
                               <div class="rating_wp clearfix">
                                            <div class="rating">
                                                <img alt="Rating" src="<?php echo plugins_url( 'images/rate.jpg' , __FILE__ ) ?>">
                                            </div>
                                            <div class="admin_name">
                                       	  <div class="admin_pro"><?php echo $aurhorImg; ?></div>
                                            <div class="admin_pro_name"><a href="#"><?php echo $authorName; ?></a></div>
                                      </div>
                                        </div>
                                        
                             
                            <div class="row_line">
                             <div class="one-half">
                               <ul class="cook_cate">
                                    <li><i class="prepairation_time"></i> <strong>Prepairation Time:</strong><?php echo get_post_meta(get_the_ID(),'wp_er_prepairation-time',true);?></li>
                                    <li><i class="cooking_time"></i> <strong>Cooking Time:</strong><?php echo get_post_meta(get_the_ID(),'wp_er_cooking-time',true);?></li>
                                    <li><i class="servings"></i> <strong>Servings:</strong><?php echo get_post_meta(get_the_ID(),'wp_er_servings',true);?></li>
                                    <li><i class="category"></i> <strong>Category:</strong> <?php echo get_post_meta(get_the_ID(),'wp_er_category',true);?></li>
                               </ul>
                             </div>
                              <div class="one-half last">
                                
                                <?php 
                                
                                if(function_exists('get_wp_easy_recipe_options'))
                                {
									$werOptions =get_wp_easy_recipe_options();
									
									
									 if($werOptions['wpe_shareBtns']!=''){
										 echo $werOptions['wpe_shareBtns'];
										 }else 
									{
										echo '<strong>Share Buttons Block</strong>';
										
										}
									}
                                ?>
                                
                             </div>
                           </div>
                           
                          <div class="recipe-content">  
                          <?php the_content();?>
                             </div> 
                         
                          <div class="row_line">
                                <ul class="cook_tag">
                                            <li class="list_drop">
                                               <?php echo $recipeCat;?>
                                            </li>
                                            <li class="list_drop1">
                                                <?php echo $reciepTags;?>
                                            </li>
                                   </ul>
                             </div>
                         <?php if($authorDesc!=''):?>
                             <div class="cook_profile">
                                <?php echo $aurhorFullImg; ?>
                                <h2><?php echo $authorName; ?></h2>
                                <p><?php echo $authorDesc; ?></p>
                                
                             </div>
                             <?php endif;?>
                             
                        </div>
                    </div>
                
                
           <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', '' ), 'after' => '</div>' ) ); ?>
		<footer class="entry-meta">
			<?php edit_post_link( __( 'Edit', '' ), '<span class="edit-link">', '</span>' ); ?>
		</footer><!-- .entry-meta -->
          
                	</div><!-- .entry-content --><!-- #post -->
		
			
	

	
		<?php endwhile; ?>
        
          	

            </div>        
                 
                
			</div>
            
			
		</div>
        
        <div class="row ">
         <?php
  $page = get_page_by_title( 'Ad footer' );
  if($page){
  $title = apply_filters('the_title', $page->post_title);
  $content = apply_filters('the_content', $page->post_content);
  echo $content;
}
  ?> 
		</div>       
		
		
	</div>
</div>


<?php get_footer(); ?>
