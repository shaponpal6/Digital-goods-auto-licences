<?php


add_action( 'cmb2_admin_init', 'dl_licence_email_meta_boxes' );
/**
 * Define the metabox and field configurations.
 */
function dl_licence_email_meta_boxes() {

    /**
     * Initiate the metabox
     */
    $cmb = new_cmb2_box( array(
        'id'            => 'sp_dl_licence_manager',
        'title'         => __( 'Digital Licence Email Template', 'cmb2' ),
        'object_types'  => array( 'product' ), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
//        'show_names'    => true, // Show field names on the left
//         'cmb_styles' => false, // false to disable the CMB stylesheet
         'closed'     => true, // Keep the metabox closed by default
    ) );



    // Regular text field
    $cmb->add_field( array(
        'name'       => __( 'Download Link', 'cmb2' ),
        'desc'       => __( '', 'cmb2' ),
        'id'         => 'sp_dl_download_link',
        'type'       => 'textarea',
        'show_on_cb' => 'cmb2_hide_if_no_cats',
    ) );

    // Regular text field
    $cmb->add_field( array(
        'name'       => __( 'Add Product Note', 'cmb2' ),
        'desc'       => __( '', 'cmb2' ),
        'id'         => 'sp_dl_product_note',
        'type'       => 'textarea',
        'show_on_cb' => 'cmb2_hide_if_no_cats',
    ) );

    // Regular text field
    $cmb->add_field( array(
        'name'       => __( 'Email subject', 'cmb2' ),
        'desc'       => __( '', 'cmb2' ),
        'id'         => 'sp_dl_email_subject',
        'type'       => 'text',
        'show_on_cb' => 'cmb2_hide_if_no_cats',
    ) );

    // Regular text field
    $cmb->add_field( array(
        'name'       => __( 'Email header', 'cmb2' ),
        'desc'       => __( 'Add Email header here.', 'cmb2' ),
        'id'         => 'sp_dl_email_header',
        'type'       => 'textarea',
        'show_on_cb' => 'cmb2_hide_if_no_cats', // function should return a bool value
        // 'sanitization_cb' => 'my_custom_sanitization', // custom sanitization callback parameter
        // 'escape_cb'       => 'my_custom_escaping',  // custom escaping callback parameter
        // 'on_front'        => false, // Optionally designate a field to wp-admin only
//         'repeatable'      => true,
    ) );

    // Regular text field
    $cmb->add_field( array(
        'name'       => __( 'Email Body Before', 'cmb2' ),
        'desc'       => __( 'This will add before message in body.', 'cmb2' ),
        'id'         => 'sp_dl_email_body_before',
        'type'       => 'textarea',
        'show_on_cb' => 'cmb2_hide_if_no_cats', // function should return a bool value
        // 'sanitization_cb' => 'my_custom_sanitization', // custom sanitization callback parameter
        // 'escape_cb'       => 'my_custom_escaping',  // custom escaping callback parameter
        // 'on_front'        => false, // Optionally designate a field to wp-admin only
//         'repeatable'      => true,
    ) );

    // Regular text field
    $cmb->add_field( array(
        'name'       => __( 'Email Body After', 'cmb2' ),
        'desc'       => __( 'This will add after message in body.', 'cmb2' ),
        'id'         => 'sp_dl_email_body_after',
        'type'       => 'textarea',
        'show_on_cb' => 'cmb2_hide_if_no_cats', // function should return a bool value
        // 'sanitization_cb' => 'my_custom_sanitization', // custom sanitization callback parameter
        // 'escape_cb'       => 'my_custom_escaping',  // custom escaping callback parameter
        // 'on_front'        => false, // Optionally designate a field to wp-admin only
//         'repeatable'      => true,
    ) );






    // Add other metaboxes as needed

}