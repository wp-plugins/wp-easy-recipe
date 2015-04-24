<?php
/*
 * Function for manage wp easy recipe slider
 * 
 * */
function wp_easy_recipe_gallery_function()
{

$werSlider ='
<div class="mini-slider-container clearfix">
                    <div class="mini-slider grid-center">              
        <div style="clear: both;"></div>
                 <div style="width: auto;postion:relative" id="divSliderMain">
           <div class="responsiveSlider" style="margin-top: 2px !important;">';
                       
         $gallery =new WP_Query(array('orderby' => 'post_title', 'order' => 'ASC','post_type'=>'wp_easy_recipe' ));
         if($gallery->have_posts()):
           while ( $gallery->have_posts() ) : $gallery->the_post();
                 $werSlider .='<div class="limargin"> '.get_the_post_thumbnail(get_the_ID(),array(155,108)).' <h2><a href="'.get_the_permalink().'" alt="'.get_the_title().'">'.get_the_title().'</a></h2></div>';
             endwhile;
             wp_reset_query();
          endif;

            $werSlider .='</div></div>';

     $werSlider .='</div></div>';
	
	 return $werSlider;
	}

//add inline js in footer 

add_filter('wp_head','wp_easy_recipe_inline_script');

function wp_easy_recipe_inline_script()
{
	$inlinescript='<script>
            var $n = jQuery.noConflict();  
            $n(document).ready(function(){
             var sliderMainHtml=$n(\'#divSliderMain\').html();   
             var slider= $n(\'.responsiveSlider\').werSlider({
                   slideWidth:113,
                    minSlides: 1,
                    maxSlides: 7,
                    moveSlides: 1,
                    slideMargin: 16,  
                    speed:3000,
                    pause:1000,
                    autoHover: true,
                    controls:true,
                    pager:false,
                    useCSS:false,
                    auto:true,       
                    infiniteLoop: false
                                    
              });
                
              
                            
                   var is_firefox=navigator.userAgent.toLowerCase().indexOf(\'firefox\') > -1;  
                  var is_android=navigator.userAgent.toLowerCase().indexOf(\'android\') > -1;
                  var is_iphone=navigator.userAgent.toLowerCase().indexOf(\'iphone\') > -1;
                  var width = $n(window).width();
                 if(is_firefox && (is_android || is_iphone)){
                     
                 }else{
                        var timer;
                        $n(window).bind(\'resize\', function(){
                           if($n(window).width() != width){
                               
                            width = $n(window).width(); 
                            timer && clearTimeout(timer);
                            timer = setTimeout(onResize, 600);
                            
                           }
                        });
                       
                  }    
                 
                   function onResize(){
                            
                                  $n(\'#divSliderMain\').html(\'\');   
                                  $n(\'#divSliderMain\').html(sliderMainHtml);
                                   var slider= $n(\'.responsiveSlider\').werSlider({
                                   slideWidth: 155,
                                    minSlides: 1,
                                    maxSlides: 7,
                                    moveSlides: 1,
                                    slideMargin: 16,  
                                    speed:3000,
                                    pause:1000,
                                    autoHover: true,
                                    controls:true,
                                    pager:false,
                                    useCSS:false,
                                    auto:true,       
                                    infiniteLoop: false
                                                                    
                              });
                             
                    
                      }
                      
      });
               
</script>';
      
     echo $inlinescript;
	}

/* ADD NEW SHORT CODE FOR PUBLISH ALL RECIPE ON LIST PAGE */
add_shortcode('wp_easy_recipe_slider','wp_easy_recipe_gallery_function'); // use [wp_recipe_list_page] shortcode

/* register style*/
add_action( 'wp_enqueue_scripts', 'wp_easy_recipe_slider_style' );

//register list page style files
function wp_easy_recipe_slider_style() {
wp_enqueue_script( 'jquery' );
wp_register_style( 'wer_slider_style', plugins_url( 'css/wp-easy-recipe-slider.css',__FILE__));
wp_enqueue_style( 'wer_slider_style');
wp_enqueue_script( 'wer_slider_js', plugins_url( 'js/wp-easy-recipe-slider.js',__FILE__),array('jquery'),'1',false);
}


?>
