<?php

add_action('rest_api_init', 'create_api_posts_meta_field');

function create_api_posts_meta_field()
{

    // register_rest_field ( 'name-of-post-type', 'name-of-field-to-return', array-of-callbacks-and-schema() )
    register_rest_field('post', 'subtitle', array(
        'get_callback' => 'get_post_meta_for_api',
        'update_callback' => 'update_post_meta_for_exp',
        'schema' => null,
    ));
}

function update_post_meta_for_exp($object, $meta_value)
{
    $havemetafield  = get_post_meta($object['id'], 'experience', false);
    if ($havemetafield) {
        $ret = update_post_meta($object['id'], 'subtitle', $meta_value );
    } else {
        $ret = add_post_meta( $object['id'], 'subtitle', $meta_value ,true );
    }
    return true;
}

function get_post_meta_for_api($object)
{
    //get the id of the post object array
    $post_id = $object['id'];

    $meta = get_post_meta( $post_id );

    if ( isset( $meta['subtitle' ] ) && isset( $meta['subtitle' ][0] ) ) {
        //return the post meta
        return $meta['subtitle' ][0];
    }

    // meta not found
    return false;
}


// Another soluation
    function prepare_rest($data, $post, $request){
    $_data = $data->data;

    $thumbnail_id = get_post_thumbnail_id( $post->ID );
    $featured_media_url = wp_get_attachment_image_src( $thumbnail_id, 'large' );

    $post_categories = wp_get_post_terms( $post->ID, 'ad_portfolios' , array("fields" => "all") );
    $cats = array();

    foreach($post_categories as $cat){
        $cats[] = ['slug' => $cat->slug, 'name' => $cat->name ];
    }

    $_data['featured_media_url'] = $featured_media_url[0];
    $_data['portfolio_cats'] = $cats;
    $data->data = $_data;

    return $data;
}
add_filter('rest_prepare_ad_portfolio', 'prepare_rest', 10, 3);
//post type is "ad_portfolio"



function load_form()
{
    ?>
<div class="wrap">
    <h1>REST API Crud</h1>
    <div id="dpls-crud">
        <table id="dl_rest_container" class="dl_form dl_table">
            <tr>
                <td>
                    <div class="sp_poll_sp_text"><strong>Title: </strong></div>
                </td>
                <td><input id="title" class="sp_poll_field regular-text" type="text" value=""></td>
            </tr>
            <tr>
                <td>
                    <div class="sp_poll_sp_text"><strong>Desc: </strong></div>
                </td>
                <td><input id="content" class="sp_poll_field regular-text" type="text" value=""></td>
            </tr>
            <tr>
                <td>
                    <div class="sp_poll_sp_text"><strong>company_name: </strong></div>
                </td>
                <td><input id="company_name" class="sp_poll_field regular-text" type="text" value=""></td>
            </tr>
            <tr>
                <td>
                    <div class="sp_poll_sp_text"><strong>time_period: </strong></div>
                </td>
                <td><input id="time_period" class="sp_poll_field regular-text" type="text" value=""></td>
            </tr>
        </table>
    </div>
    <button id="dl_rest_save">Save</button>
</div>
<?php
}

echo load_form();