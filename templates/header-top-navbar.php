<?php 
// Get logo from admin options.
// Fetch ID and pull logo size.


// If this is the WooCommerce product archive page, grab the ID of the shop page.
// WOO Support
if(is_woocommerce_activated()) {
// Support for Woo Pages.
// Sometimes the page id isn't explicit so we have to go and look for it.
    if (is_shop()) {
        $shop_page_id = wc_get_page_id('shop');
    }
    if (is_product_category() || is_product_tag() || is_product_taxonomy()) {
        $woo_global_header_settings = true;
    }
}

if(isset($shop_page_id) && $shop_page_id > 0) {
    $postID = $shop_page_id;
}elseif(isset($woo_global_header_settings) && $woo_global_header_settings){
    $postID = "";
}elseif(isset($post->ID )&& $post->ID > ""){
	$postID = $post->ID;
}else{
	$postID = "";
}


if ( function_exists( 'ot_get_option' ) ) {
	
	/*-----------------------------------------------------
		Enable Transparent Header if:
		* Theme Options has it enabled and its not forced disabled on page settings.
		* There is flex slider or page header for the page loaded
	-----------------------------------------------------*/
	
	// Check for Slider
	$slider = false;
	$key = 'themo_slider';
	$show = get_post_meta($postID, $key.'_sortorder_show', true );
	$show_flex = get_post_meta($postID, $key.'_flex_show', true );
	$show_shortcode = get_post_meta($postID, $key.'_shortcode_show', true );

	if($show == 1 && $show_flex == 1) { // Slider Enabled
		$slider = true;
	}

	// Check for Master Slider
	$master_slider = false;
	$show = false;
	$shortcode = false;

	$key = 'themo_slider_master';
	$show = get_post_meta($postID, $key.'_show', true );
	$shortcode = get_post_meta($postID, $key.'_shortcode', true );

	if($show == 1 && $shortcode > "" ) { // Master Slider Enabled
		$master_slider = true;
	}

	// Check for Page Header
	$page_header = false;
	$key = 'themo_page_header_1';
	$show = get_post_meta($postID, $key.'_show', true );
	$show_header = get_post_meta($postID, $key.'_show_header', true );
	$show_map = get_post_meta($postID, 'themo_map_1_show', true ); // Returns Show Map (on / off)
	$map_in_header = get_post_meta($postID, 'themo_map_1_in_heder', true );

	if($show == 1 && $show_header == 1 && !($map_in_header == 1 && $show_map == 1)) { // Slider Enabled
		$page_header = true;
	}
	

    // check for light / dark style header.
    // Alternative logo for Transparent Header Enabled?
    $header_dark_style = ot_get_option( 'themo_header_dark_style', 'off' );
    if(isset($header_dark_style) && $header_dark_style == 'on' ){
        $header_dark_style_class = 'dark-header';
    }else{
        $header_dark_style_class = false;
    }

	// Check for Force Transparency Disable on Page
	$disable_nav_transparency = get_post_meta($postID, 'themo_page_disable_nav_transparency', true );
	
	// Check theme Transparency Option Setting
	$transparent_header = ot_get_option( 'themo_transparent_header', 'off' );

	// Check for text to replace logo
    $themo_logo_text = ot_get_option( 'themo_logo_text');


    if(is_archive() && is_woocommerce_activated() && is_shop()){
        $archive_pass = true;
    }elseif(is_archive()){
        $archive_pass = false;
    }else{
        $archive_pass = true;
    }

	if($archive_pass && isset($transparent_header) && !empty( $transparent_header ) && $transparent_header == 'on' && ($slider || $page_header || $master_slider) && $disable_nav_transparency !== '1')
	{
		$transparency = true;
		$transparent_header = 'data-transparent-header="true"';
	}else{
		$transparency = false;
		$transparent_header = '';
	}
	
	// Alternative logo for Transparent Header Enabled?
	$transparent_logo_enabled = ot_get_option( 'themo_logo_transparent_header_enable', 'off' );
	
	// To support for transparent header we want to keep a copy of the main logo, and use it when user scrolls (sticky header).
	$logo_main = ot_get_option( 'themo_logo');
	
	if(!$logo_main > ""){
        // If we are using the dark header, then default to white logo.
        if(isset($header_dark_style) && $header_dark_style == 'on' ){
            $logo_main = get_template_directory_uri() . '/assets/images/logo_white.png';
            $logo_main_retina = get_template_directory_uri() . '/assets/images/logo_white@2x.png';
        }else{
		$logo_main = get_template_directory_uri() . '/assets/images/logo.png' ;
		$logo_main_retina = get_template_directory_uri() . '/assets/images/logo@2x.png';
        }
	}else{
		$logo_main_retina = "";
	}
	
	// If transparent logo is enabled and transparency enabled, then replace logo.
	if($transparency && $transparent_logo_enabled == 'on'){
		$logo = ot_get_option( 'themo_logo_transparent_header' );
		if(!$logo > ""){
			$logo = get_template_directory_uri() . '/assets/images/logo_white.png';
			$logo_retina = get_template_directory_uri() . '/assets/images/logo_white@2x.png';
		}else{
			$logo_retina = "";
		}
	}else{
		$logo = $logo_main;
		$logo_retina = $logo_main_retina;
	}
	
	/*-----------------------------------------------------
		Logo & Retina Logo
	-----------------------------------------------------*/
	
	$id = themo_custom_get_attachment_id( $logo );
	
	// If this is a WordPress Attachment then get src, height, width and retina version too.
	if($id > 0){
		$image_attributes  = wp_get_attachment_image_src( $id, 'themo-logo' ); // ADD logo image size when ready. eg.  wp_get_attachment_image_src( $id, 'image-size' );
		list($logo_retina, $logo_retina_height, $logo_retina_width) = themo_return_retina_logo($id);
	}
	
	if(isset($image_attributes) && !empty( $image_attributes ) )
	{
		$logo_src = "src='".esc_url($image_attributes[0])."'";
		$logo_height = " height='".sanitize_text_field($image_attributes[2])."'";
		$logo_width =  " width='".sanitize_text_field($image_attributes[1])."'";
		
		$logo_retina_src = "src='".$logo_retina."'";
		$logo_retina_height = " height='".sanitize_text_field($logo_retina_height)."'";
		$logo_retina_width =  " width='".sanitize_text_field($logo_retina_width)."'";
		
	}else{
		$logo_src = "src='".esc_url($logo)."'";
		$logo_height = "";
		$logo_width =  "";
		
		if($logo_retina > ""){
			$logo_retina_src = "src='".esc_url($logo_retina)."'";
			$logo_retina_height = "";
			$logo_retina_width =  "";
		}
	}
	
	$id_main = themo_custom_get_attachment_id( $logo_main );
	
	if($id_main > 0){
		$image_attributes_main  = wp_get_attachment_image_src( $id_main, 'themo-logo' ); // ADD logo image size when ready. eg.  wp_get_attachment_image_src( $id, 'image-size' );
		list($logo_main_retina, $logo_main_retina_height, $logo_main_retina_width) = themo_return_retina_logo($id_main);
	}
	
	if(isset($image_attributes_main) && !empty( $image_attributes_main ) )
	{
		$logo_src_main = "src='".esc_url($image_attributes_main[0])."'";
		$logo_height_main = " height='".sanitize_text_field($image_attributes_main[2])."'";
		$logo_width_main =  "width='".sanitize_text_field($image_attributes_main[1])."'";
		
		$logo_main_retina_src = "src='".esc_url($logo_main_retina)."'";
		$logo_main_retina_height = " height='".sanitize_text_field($logo_main_retina_height)."'";
		$logo_main_retina_width =  " width='".sanitize_text_field($logo_main_retina_width)."'";
	}else{
		$logo_src_main = "src='".esc_url($logo_main)."'";
		$logo_height_main = "";
		$logo_width_main =  "";
		
		if($logo_main_retina > ""){
			$logo_main_retina_src = "src='".esc_url($logo_main_retina)."'";
			$logo_main_retina_height = "";
			$logo_main_retina_width =  "";
		}
	}	
}
?>

<header class="banner navbar navbar-default navbar-static-top <?php echo sanitize_text_field($header_dark_style_class);?>" role="banner" <?php echo sanitize_text_field($transparent_header);?>>
    <?php
        if ( function_exists( 'ot_get_option' ) ) {
        /* Top Nav Enabled? */
        $top_nav_display = ot_get_option( 'themo_top_nav_switch','off');

            if ( ! empty( $top_nav_display ) && $top_nav_display == 'on') { ?>

                <!-- top navigation -->
                <div class="top-nav">
                    <div class="container">
                        <div class="row col-md-12">
                            <div class="top-nav-text">
                                <?php
                                if ( function_exists( 'ot_get_option' ) ) {
                                    /* Get top nav text. */
                                    // Check for top nav text from page, if not exists, than
                                    // get the theme options setting.
                                    $page_top_nav_text = false;
                                    $page_top_nav_text = get_post_meta($postID, "themo_top_nav_text", true);
                                    if(isset($page_top_nav_text) && $page_top_nav_text > ""){
                                        $top_nav_text = $page_top_nav_text;
                                    }else{
                                        $top_nav_text = ot_get_option( 'themo_top_nav_text');
                                    }
                                    if ( ! empty( $top_nav_text ) ) {
                                        echo '<p>'.wp_kses_post($top_nav_text).'</p>';
                                    }
                                }
                                ?>

                            </div>

                          <div class="top-nav-icon-blocks"><div class="icon-block"><p><a href="mailto:wishes@casalegadobogota.com"><i class="glyphicons glyphicons-envelope"></i><span>wishes@casalegadobogota.com</span></a>&nbsp; &nbsp; &nbsp; <a href="https://www.facebook.com/CasalegadoBogota/"><img style="width: 14px;" src="http://casalegadobogota.com/wp-content/uploads/2017/07/www.png" alt="" /></a>&nbsp; &nbsp; &nbsp;<a  href="https://www.instagram.com/casalegado/"><img style="width: 14px;" src="http://casalegadobogota.com/wp-content/uploads/2017/07/insta.png" alt="" /></a>&nbsp; &nbsp; &nbsp; <a href="https://twitter.com/"><img style="width: 14px;" src="http://casalegadobogota.com/wp-content/uploads/2017/08/TripAdvisor.png" alt="" /></a>    <img src="http://casalegadobogota.com/wp-content/uploads/2017/07/espacioidiomas.png"> <a href="http://casalegadobogota.com/es/inicio/">ES</a> / <a href="http://casalegadobogota.com/en/home-3/">EN</a></p></div></div>


                        </div>

                    </div>
                </div><!-- END top navigation -->
                <?php
            } // END Top Nav Enabled
        } // End Top Navigation
    ?>
	<div class="container">

        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div id="logo">
                <?php if(isset($themo_logo_text) && $themo_logo_text > "") {?>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="th-logo-text-link">
                    <div class="th-logo-text-main"><?php echo $themo_logo_text; ?></div>
                    <div class="th-logo-text-trans"><?php echo $themo_logo_text; ?></div>
                <?php } else {?>
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <?php if($transparency && $transparent_logo_enabled == 'on') { ?>
                    <img class="logo-trans logo-reg" <?php echo $logo_src . $logo_height . $logo_width ;?>  alt="<?php bloginfo("name" ); ?>" />
                    <?php }elseif($transparency && $transparent_logo_enabled == 'off'){ ?>
                    <img class="logo-trans logo-reg" <?php echo $logo_src_main . $logo_height_main ." ". $logo_width_main ;?>   alt="<?php bloginfo("name" ); ?>" />
                    <?php }?>
                    <img class="logo-main logo-reg" <?php echo $logo_src_main . $logo_height_main ." ". $logo_width_main ;?>   alt="<?php bloginfo("name" ); ?>" />
                <?php } ?>
				</a>
            </div>
        </div>

        <?php
        /*
        Shopping cart icon : show / hide
        Shopping cart item count
        */
        if(is_woocommerce_activated()) {
            $woo_cart_header_display = 'on'; // default
            $themo_cart_count = false;
            if (function_exists('ot_get_option')) {
                $woo_cart_header_display = ot_get_option('themo_woo_show_cart_icon', 'on');
                $woo_cart_header_icon = ot_get_option('themo_woo_cart_icon', 'th-i-cart');
            }
            if (isset($woo_cart_header_display) && $woo_cart_header_display == 'on') {

                global $woocommerce;
                $cart_count = $woocommerce->cart->cart_contents_count;

                $cart_url = $woocommerce->cart->get_cart_url();
                $ahref = false;
                $ahref_close = false;
                if(isset($cart_url)){
                    $ahref = "<a href='".esc_url($cart_url)."'>";
                    $ahref_close = "</a>";
                }

                if ($cart_count > 0) {
                    $themo_cart_count = "<span class='themo_cart_item_count'>" . $cart_count . "</span>";
                }
                echo "<div class='themo_cart_icon'>";
                echo $ahref;
                echo "<i class='th-icon ".esc_attr($woo_cart_header_icon)."'></i>";
                echo $themo_cart_count;
                echo $ahref_close;
                echo '</div>';
            }
        }
        ?>



    	<nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">

      	<?php
        if (has_nav_menu('primary_navigation')) :
          wp_nav_menu(array('theme_location' => 'primary_navigation', 'menu_class' => 'nav navbar-nav'));
        endif;
      	?>

    	</nav>


	</div>
<hr/>
</header>