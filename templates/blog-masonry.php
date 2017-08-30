<?php
/*
Template Name: Blog - Masonry
*/

$use_bittersweet_pagination = false;
if(is_front_page()) {
    $use_bittersweet_pagination=true;
}

?>
<?php global $post;  ?>
<?php include( locate_template( 'templates/page-layout.php' ) ); ?>
<div class="inner-container">

	<?php include( locate_template( 'templates/meta-slider-flex.php' ) ); ?>
	<?php include( locate_template( 'templates/page-header.php' ) ); // Page Header Template ?>
    
    <?php 
	//-----------------------------------------------------
	// OPEN | OUTER Container + Row
	//-----------------------------------------------------
    echo wp_kses_post($outer_container_open) . wp_kses_post($outer_row_open); // Outer Tag Open ?>

    <?php 
	//-----------------------------------------------------
	// OPEN | Wrapper Class - Support for sidebar
	//-----------------------------------------------------
    echo wp_kses_post($main_class_open);  ?>
    
    <?php
	//-----------------------------------------------------
	// OPEN | Section + INNER Container
	//----------------------------------------------------- ?>
    
    <?php
	// Set Image Size
	$image_size = 'themo_blog_masonry';
	
	$masonry_template_key = '-masonry';
	$masonry_section_class = 'masonry-blog';
	$masonry_row_class = 'mas-blog';
	if($has_sidebar){
		$masonry_div_class = 'mas-blog-post col-md-6';
	}else{
		$masonry_div_class = 'mas-blog-post col-lg-4 col-md-4 col-sm-6';
	}
	
	$automatic_post_excerpts = 'on';
	if ( function_exists( 'ot_get_option' ) ) {
		$automatic_post_excerpts = ot_get_option( 'themo_automatic_post_excerpts', 'on' );
	}
	
	?>

    <section id="<?php echo sanitize_html_class($key).'_content'; ?>" class="<?php echo sanitize_text_field($masonry_section_class); ?>">
	<?php echo wp_kses_post($inner_container_open);?>

	<?php
    //-----------------------------------------------------
    // LOOP
    //----------------------------------------------------- ?>
   <!--aqui-->
<div id="questions">
<section id="themo_conversion_form_1" class=" conversion-form ">
<div class="container"><div class="row">
    <div class="section-header col-xs-12 centered">
                		<h2>Subscribe to our stories</h2>		    		</div><!-- /.section-header -->        
</div><!-- /.row -->   
	<div class="row">
    	<div class="col-xs-12" style="margin-top: -30px;margin-bottom: 100px;">
    		<div class="simple-conversion">
			<div class="frm_forms  with_frm_style frm_style_formidable-style-2" id="frm_form_3_container">
<form enctype="multipart/form-data" method="post" class="frm-show-form " id="form_2ssykv">
<div class="frm_form_fields ">
<fieldset>

<input type="hidden" name="frm_action" value="create">
<input type="hidden" name="form_id" value="3">
<input type="hidden" name="frm_hide_fields_3" id="frm_hide_fields_3" value="">
<input type="hidden" name="form_key" value="2ssykv">
<input type="hidden" name="item_meta[0]" value="">
<input type="hidden" id="frm_submit_entry_3" name="frm_submit_entry_3" value="c1f921359d"><input type="hidden" name="_wp_http_referer" value="/home-1/"><input type="text" class="frm_hidden" id="frm_verify" name="frm_verify" value="">

<div id="frm_field_16_container" class="frm_form_field form-field  frm_none_container">
    <label for="field_qy05f8" class="frm_primary_label">Name
        <span class="frm_required"></span>
    </label>
    <input type="text" id="field_qy05f8" name="item_meta[16]" value="" placeholder="Name">

    
    
</div>
<div id="frm_field_17_container" class="frm_form_field form-field  frm_required_field frm_none_container">
    <label for="field_3asv29" class="frm_primary_label">Email Address
        <span class="frm_required">*</span>
    </label>
    <input type="text" id="field_3asv29" name="item_meta[17]" value="" placeholder="Email Address" data-reqmsg="This field cannot be blank.">

    
    
</div>
<input type="hidden" name="item_key" value="">
<div class="frm_submit">

<input type="submit" value="Send">


</div></fieldset>
</div>
</form>
</div>
    		</div>
		</div>
	</div>
</div><!-- /.container --></section>
</div>
<!--aqui fin-->
    <?php

    if ( get_query_var('paged') ) { $paged = get_query_var('paged'); }
    elseif ( get_query_var('page') ) { $paged = get_query_var('page'); }
    else { $paged = 1; }

    // Metabox options to filter by category.
    $themo_blog_cat_array = get_post_meta($post->ID, 'themo_category_checkbox', true );

    $themo_cat_arg = false;

    // Check if array is returned, if so implode, if not continue.
    if(isset($themo_blog_cat_array)){
        if(is_array($themo_blog_cat_array)) {
            $themo_blog_categories = implode(',', $themo_blog_cat_array);
        }else{
            $themo_blog_categories = $themo_blog_cat_array;
        }

        //are there any category ID's present? Continue, else do nothing.
        $themo_cat_arg = false;
        if($themo_blog_categories > ""){
            $themo_cat_arg = "cat=".$themo_blog_categories."&";
        }
    }

    query_posts($themo_cat_arg.'post_type=post&post_status=publish&paged='. $paged); ?>
    
    <div class="<?php echo sanitize_text_field($masonry_row_class); ?> row">
		<?php if (!have_posts()) : ?>
            <div class="alert">
            <?php esc_html_e('Sorry, no results were found.', 'BELLEVUE'); ?>
            </div>
            <?php get_search_form(); ?>
        <?php endif; ?>
            
		<?php while (have_posts()) : the_post(); ?>
		<?php
        $format = get_post_format();
        if ( false === $format ) {
        $format = 'standard';
        }
        ?>
            <div <?php post_class($masonry_div_class); ?> >
				<?php get_template_part('templates/content', $format); ?>
			</div><!-- /.col-md --> 
        <?php endwhile; ?>	
    </div><!-- /.row -->
    
    <div class="row">
		<?php if ($wp_query->max_num_pages > 1) : ?>
            <nav class="post-nav">
                <ul class="pager">
                    <?php if($use_bittersweet_pagination){
                        bittersweet_pagination();
                    }else{ ?>
                        <li class="previous"><?php next_posts_link(esc_html__('&larr; Older posts', 'BELLEVUE')); ?></li>
                        <li class="next"><?php previous_posts_link(esc_html__('Newer posts &rarr;', 'BELLEVUE')); ?></li>
                    <?php } ?>
                </ul>
            </nav>
        <?php endif; ?>
	</div>
    
    <?php wp_reset_postdata(); ?>
    
	<?php
	//-----------------------------------------------------
	// CLOSE | Section + INNER Container
	//----------------------------------------------------- ?>
	<?php echo wp_kses_post($inner_container_close);?>
	</section>


	<?php 
    //-----------------------------------------------------
	// CLOSE | Main Class
	//-----------------------------------------------------
    echo wp_kses_post($main_class_close); ?>
    
    <?php
    //-----------------------------------------------------
	// INCLUDE | Sidebar
	//-----------------------------------------------------
    include themo_sidebar_path(); ?>
    
    <?php
	//-----------------------------------------------------
	// CLOSE | OUTER Container + Row
	//----------------------------------------------------- 
    echo wp_kses_post($outer_container_close) . wp_kses_post($outer_row_close); // Outer Tag Close ?>
 <!--aqui-->
<div id="questions">
<section id="themo_conversion_form_1" class=" conversion-form ">
<div class="container"><div class="row">
    <div class="section-header col-xs-12 centered">
                		<h2>Subscribe to our stories</h2>		    		</div><!-- /.section-header -->        
</div><!-- /.row -->   
	<div class="row">
    	<div class="col-xs-12" style="margin-top: -30px;margin-bottom: 130px;">
    		<div class="simple-conversion">
			<div class="frm_forms  with_frm_style frm_style_formidable-style-2" id="frm_form_3_container">
<form enctype="multipart/form-data" method="post" class="frm-show-form " id="form_2ssykv">
<div class="frm_form_fields ">
<fieldset>

<input type="hidden" name="frm_action" value="create">
<input type="hidden" name="form_id" value="3">
<input type="hidden" name="frm_hide_fields_3" id="frm_hide_fields_3" value="">
<input type="hidden" name="form_key" value="2ssykv">
<input type="hidden" name="item_meta[0]" value="">
<input type="hidden" id="frm_submit_entry_3" name="frm_submit_entry_3" value="c1f921359d"><input type="hidden" name="_wp_http_referer" value="/home-1/"><input type="text" class="frm_hidden" id="frm_verify" name="frm_verify" value="">

<div id="frm_field_16_container" class="frm_form_field form-field  frm_none_container">
    <label for="field_qy05f8" class="frm_primary_label">Name
        <span class="frm_required"></span>
    </label>
    <input type="text" id="field_qy05f8" name="item_meta[16]" value="" placeholder="Name">

    
    
</div>
<div id="frm_field_17_container" class="frm_form_field form-field  frm_required_field frm_none_container">
    <label for="field_3asv29" class="frm_primary_label">Email Address
        <span class="frm_required">*</span>
    </label>
    <input type="text" id="field_3asv29" name="item_meta[17]" value="" placeholder="Email Address" data-reqmsg="This field cannot be blank.">

    
    
</div>
<input type="hidden" name="item_key" value="">
<div class="frm_submit">

<input type="submit" value="Send">


</div></fieldset>
</div>
</form>
</div>
    		</div>
		</div>
	</div>
</div><!-- /.container --></section>
</div>
<!--aqui fin-->


</div><!-- /.inner-container -->