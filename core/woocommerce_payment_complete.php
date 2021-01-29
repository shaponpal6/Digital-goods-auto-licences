<?php

global $wpdb;

add_action('woocommerce_payment_complete', 'dl_payment_complete', 10, 1);
add_filter('woocommerce_payment_complete_order_status_', 'dl_payment_complete_status');

add_action('woocommerce_thankyou', function ($order_id) {
    global $wpdb;
    $items = array();
    $single_quantity = true;
    // check product item
    // product type
    // check licence key available
    // insert order and licence in new table
    // send email
    // display in order page
    $order = wc_get_order($order_id);
    $billingEmail = $order->billing_email;
    $products = $order->get_items();


    foreach ($products as $product) {
        $items[$product['product_id']] = $product['name'];
        if ($single_quantity && $product['quantity'] > 1) {
            $single_quantity = false;
        }
    }

    // Action here
    if ($single_quantity && count($items) > 0) {
        // loop for every product
        foreach ($items as $pid => $title) {
            $licence_key_row = dl_check_licence_available($pid);
//            print_r($licence_key_row);
            if ($licence_key_row && count($licence_key_row) > 0) {
                // Check Licence already send
                $send = dl_check_already_send_licence($order_id, $pid);
//                print_r($send);
                if ($send && count($send) > 0) {
                    foreach ($send as $lc) {
                        $download_link = get_post_meta($pid, 'sp_dl_download_link', true);
                        dl_licence_template($download_link, $title, $lc->licence);
                    }
                }else {
                    // send to di licence log
                    $wpdb->insert($wpdb->prefix . 'dl_order_log',
                        array(
                            'order_id' => $order_id,
                            'product_id' => $pid,
                            'licence' => $licence_key_row[0]->licence
                        ),
                        array('%s', '%d', '%s')
                    );

                    // Reduce Licence key
                    if ($wpdb->insert_id) {
                        $download_link = get_post_meta($pid, 'sp_dl_download_link', true);
                        $order->add_order_note('Licence Key: ' . $licence_key_row[0]->licence);
                        $order->add_order_note('Download Link: ' . $download_link);
                        $order->add_order_note('Product Notes: ' . get_post_meta($pid, 'sp_dl_product_note', true));
                        $order->update_status('completed');
                        dl_reduce_licence($licence_key_row[0]->id, $licence_key_row[0]->sold);
                        dl_licence_template($download_link, $title, $licence_key_row[0]->licence);
                        dl_send_licence_email(array(
                            'id' => $pid,
                            'to' => $billingEmail,
                            'order' => $order_id,
                            'title' => $title,
                            'licence' => $licence_key_row[0]->licence,
                            'download_link' => $download_link,
                        ));
                    }

                }
            }
        }
    }


//    echo '<pre>';
//    print_r($items);
//    echo($single_quantity);
//    print_r($single_quantity);
//    print_r($billingEmail);
//    print_r($products);
//    print_r($order);
//    echo '<pre>';

});

function dl_check_already_send_licence($order_id, $product_id)
{
    global $wpdb;
    $row = array();
    try {
        $results = $wpdb->get_results("SELECT * FROM  `{$wpdb->prefix}dl_order_log` WHERE `order_id` = {$order_id} AND `product_id` = {$product_id}", OBJECT);
        if ($results) $row = $results;
    } catch (Exception $e) {

    }
    return $row;
}

function dl_check_licence_available($product_id)
{
    global $wpdb;
//    $product_id = 430;
    $row = array();
    try {
        $results = $wpdb->get_results("SELECT * FROM  `{$wpdb->prefix}digital_licences` WHERE `product_id` = {$product_id} AND `sold` < `total` ORDER BY id ASC LIMIT 1", OBJECT);
        if ($results) $row = $results;
    } catch (Exception $e) {

    }
    return $row;
}

// dl_reduce_licence
function dl_reduce_licence($id, $current)
{
    global $wpdb;
    try {
        $wpdb->update($wpdb->prefix . 'digital_licences',
            array('sold' => ($current + 1)),
            array('id' => $id),
            array('%d'),
            array('%d')
        );
    } catch (Exception $e) {
    }
    return 1;
}


function dl_licence_template($download_link, $title, $licence)
{
    echo '<table class="dl_table sp_dl_table">';
    echo '<tr ><h4 class="sp_dl_title"><center><bold>' . $title . '</bold></center></h4></tr>';
    echo '<tr><td>Your Licence Key</td><td> <code>' . $licence . '</code></td></tr>';
    echo '<tr><td>Download Link</td><td><a href="'.$download_link.'">'.$download_link.'</a> </td></tr>';
    echo '</table>';
}


function dl_send_licence_email($mail)
{
    $id = $mail['id'];
    $to = $mail['to'];
    $download = $mail['download_link'];
    $subject = get_post_meta($id, 'sp_dl_email_subject', true);
    $message_before = get_post_meta($id, 'sp_dl_email_body_before', true);
    $message_after = get_post_meta($id, 'sp_dl_email_body_after', true);
    $message = '<center><h2><bold>' . $mail['title'] . '</bold></bold></h2><h4>Your Licence Key: <code>' . $mail['licence'] . '</code></code></h4><p>Download Link: <a href="' . $download . '">' . $download . '</a> </p></center>';
    $headers = get_post_meta($id, 'sp_dl_email_header', true);
    $attachments = array($download);
    $body = $message_before . $message . $message_after;

    return wp_mail($to, $subject, $body, $headers, $attachments);

}


