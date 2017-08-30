<?php
/*
 * Rooms Format - IMAGE
 * Supports Featured Image, title and subtext.
 * */

global $key,$image_size,$more,$automatic_post_excerpts,$orderby_menu;

//-----------------------------------------------------
// Get Room Options
//-----------------------------------------------------

// Custom Icon
$custom_icon_set = get_post_meta( get_the_ID(), 'themo_room_single__glyphicons_icon_set', false);
$custom_icon = get_post_meta( get_the_ID(), 'themo_room_single__glyphicons-icon', false);

// Price
$price = get_post_meta( get_the_ID(), 'themo_room_price', false);
$price_per = get_post_meta( get_the_ID(), 'themo_room_per_price', false);

if(isset($price[0])){
    $price = $price[0];
}else{
    $price = false;
}
if(isset($price_per[0])){
    $price_per = $price_per[0];
}else{
    $price_per = false;
}

if(isset($custom_icon[0]) &&  !is_array($custom_icon[0]) && $custom_icon[0] > ""){
    $custom_icon = $custom_icon[0];
    if(isset($custom_icon_set[0]) &&  !is_array($custom_icon_set[0]) && $custom_icon_set[0] > "" && $custom_icon_set[0] != 'none'){
        $custom_icon_set = $custom_icon_set[0];
    }else{
        $custom_icon_set = '';
    }
}else{
    $custom_icon_set = false;
    $custom_icon = false;
}

$link_url = get_post_meta( $post->ID, '_format_link_url', true);
$link_title = get_post_meta( $post->ID, '_format_link_title', true);
$link_target = get_post_meta( $post->ID, '_format_link_target');
$link_direct = 1;

// Link Target
$link_target_markup = false;
if(isset($link_target) && is_array($link_target)){
    if(isset($link_target[0]) && $link_target[0] > "")
        $link_target_markup = "target='".$link_target[0]."'";
}
// Custom Title
if(!$link_title > "") {
    $link_title=get_the_title();
}
// href mark up
$href = "";
$href_close = "";

if ($link_url > ""){
    $href = "<a class='room-single-link' href='". $link_url . "'".$link_target_markup .">";
    $href_close = "</a>";
}

//-----------------------------------------------------
// Single Output
//-----------------------------------------------------
if(is_single()){

    echo "<div class='col-md-7'>";
        if ( has_post_thumbnail() ) {
            $featured_img_attr = array('class' => "img-responsive");
            echo wp_kses_post($href) . get_the_post_thumbnail($post->ID,$image_size,$featured_img_attr) . wp_kses_post($href_close);
        }
    echo "</div>";
    echo "<div class='col-md-5'>";
        get_template_part('templates/meta-social-addthis'); // AddThis Social Toolbox
        $content = apply_filters( 'the_content', get_the_content() );
        $content = str_replace( ']]>', ']]&gt;', $content );
        if($content != ""){
            echo "<div class='entry-content'>";
            echo $content;
            themo_do_shortocde_button('',$key.'_');
            echo "</div>";
        }
    echo "</div>";
} else {
//-----------------------------------------------------
// Index and Archive Output
//-----------------------------------------------------
    $more = 0;
    if ( has_post_thumbnail() ) {
        $featured_img_attr = array('class'	=> "img-responsive room-img");
    }
    echo '<div class="room-wrap">';
        echo get_the_post_thumbnail($post->ID,$image_size,$featured_img_attr);
        echo '<div class="room-overlay"></div>';
        echo '<div class="room-inner">';
            echo '<div class="room-center">';
                themo_print_room_icon(false,$custom_icon,$custom_icon_set);
                echo '<h3 class="room-title">'.$link_title.'</h3>';
                if($automatic_post_excerpts === 'off'){
                    $content = apply_filters( 'the_content', get_the_content() );
                    $content = str_replace( ']]>', ']]&gt;', $content );
                    if($content != ""){
                        echo '<p class="room-sub">'.$content.'</p>';
                    }
                }else{
                    $excerpt = apply_filters( 'the_excerpt', get_the_excerpt() );
                    $excerpt = str_replace( ']]>', ']]&gt;', $excerpt );
                    $excerpt = str_replace('<p', '<p class="room-sub"', $excerpt);
                    if($excerpt != ""){
                        echo $excerpt;
                    }
                }
            echo '<div class="pricing-cost">', $price,'<span>', $price_per, '</span></div>';
            echo '</div><!-- /.room-center -->';
            if(!$link_direct){
                if(isset($orderby_menu)){
                    echo '<a class="room-link" href="' . esc_url_raw(add_query_arg('portorder','menu',get_the_permalink())). '"></a>';
                }else{
                    echo '<a class="room-link" href="' . get_the_permalink(). '"></a>';
                }
            }else{
                echo '<a class="room-link" href="'. $link_url . '" ' . $link_target_markup . '></a>';
            }
    echo '</div><!-- /.room-inner -->';
    echo '</div><!-- /.room-wrap -->';
}