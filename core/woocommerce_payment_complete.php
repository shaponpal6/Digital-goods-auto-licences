<?php

global $wpdb;

add_action('woocommerce_payment_complete', 'dl_payment_complete', 10, 1);
add_filter('woocommerce_payment_complete_order_status_', 'dl_payment_complete_status');
add_action('woocommerce_thankyou', 'orderCompleted',  20, 1);
add_action('woocommerce_thankyou_payumbolt', 'orderCompleted',  20, 1);

function orderCompleted ($order_id) {
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
    // echo '<pre>';
    // print_r($order->get_customer_id());
    // print_r($order);
    // exit();
    $customer_id = $order->get_customer_id();
    $billingEmail = $order->get_billing_email();
    $products = $order->get_items();
    


    foreach ($products as $product) {
        $items[$product['product_id']] = $product['name'];
        if ($single_quantity && $product['quantity'] > 1) {
            $single_quantity = false;
        }
    }


    // Action here
    if ($single_quantity && count($items) > 0) {
        $allLicencesAvailable = all_licences_available_check(array_keys($items));
        if($allLicencesAvailable){

            // loop for every product
            foreach ($items as $pid => $title) {
                $meta_key =  'digital_goods_dl_type';
                $dl_type = get_post_meta( $pid, $meta_key );
                $dl_type = ($dl_type && count($dl_type)>0 )? $dl_type[0] : '';
                if($dl_type !== ''){
                    // TODO: check type empty
                    $licence_key_row = dl_check_licence_available($pid, $dl_type, $customer_id);
        //            print_r($licence_key_row);
                    if ($licence_key_row && count($licence_key_row) > 0) {
                        // Check Licence already send
                        $send = dl_check_already_send_licence($order_id, $pid);
        //                print_r($send);
                        if ($send && count($send) > 0) {
                            foreach ($send as $lc) {
                                $download_link = get_post_meta($pid, 'sp_dl_download_link', true);
                                dl_licence_template($download_link, $title, $lc, $dl_type);
                            }
                            $email_template = get_post_meta( $order_id, 'dl_email_template', true );
                            if (!!$email_template) {
                                echo $email_template;
                            }
                        }else {


                            // if(1 > 0){


                            // send to di licence log
                            // $wpdb->insert($wpdb->prefix . 'dl_order_log',
                            //     array(
                            //         'order_id' => $order_id,
                            //         'product_id' => $pid,
                            //         'licence' => $licence_key_row[0]->licence
                            //     ),
                            //     array('%s', '%d', '%s')
                            // );

                            save_order_log($order_id, $pid, $customer_id, $licence_key_row[0], $dl_type);

                            // Reduce Licence key
                            if ($wpdb->insert_id) {
                                $download_link = get_post_meta($pid, 'sp_dl_download_link', true);

                                if($dl_type === 'licence_key'){
                                    $order->add_order_note('Licence Key: ' . $licence_key_row[0]->licence);
                                }else if($dl_type === 'login_details'){
                                    $order->add_order_note('Login ID: ' . $licence_key_row[0]->login_id);
                                    $order->add_order_note('Login Password: ' . $licence_key_row[0]->login_password);
                                }else if($dl_type === 'download_link'){
                                    $order->add_order_note('Licensed Download link: ' . $licence_key_row[0]->download_link);
                                }

                                if($dl_type !== 'download_link'){
                                    $order->add_order_note('Download Link: ' . $download_link);
                                }
                                $billing_first_name = $order->get_billing_first_name();
                                $billing_last_name  = $order->get_billing_last_name();
                                $customer_name  = $billing_first_name.' '.$billing_last_name;
                                $order->add_order_note('Product Notes: ' . get_post_meta($pid, 'sp_dl_product_note', true));
                                $order->update_status('completed');
                                dl_reduce_licence($licence_key_row[0]->id, $licence_key_row[0]->sold, $dl_type);
                                dl_licence_template($download_link, $title, $licence_key_row[0], $dl_type);
                                $mail = array(
                                    'id' => $pid,
                                    'to' => $billingEmail,
                                    'name' => $customer_name,
                                    'order' => $order_id,
                                    'title' => $title,
                                    'type' => $dl_type,
                                    'licence' => $licence_key_row[0],
                                    'download_link' => $download_link,
                                );
                                dl_send_licence_email($mail, true);
                                $email_template = dl_send_licence_email($mail, false);
                                $response = get_post_meta( $order_id, 'dl_email_template', true );
                                if (!!$response) {
                                    $response = update_post_meta( $order_id, 'dl_email_template', $email_template );
                                } else {
                                    $response = add_post_meta( $order_id, 'dl_email_template', $email_template, true );
                                }
                                echo $email_template;
                                // $order->add_order_note(dl_send_licence_email($mail, false), true);
                            }

                        }
                    }else{
                        //TODO: Inform Admin if licence end
                        //print_r('No Licence ...............................');
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

};

function save_order_log($order_id, $pid, $uid, $data, $type)
{
    

    global $wpdb;
    $row = array(
        'row_id' => $data->id,
        'order_id' => $order_id,
        'product_id' => $pid,
        'user_id' => $uid,
        'type' => $type,
        'licence' => $data->licence,
        'login_id' => $data->login_id,
        'login_password' => $data->login_password,
        'download_link' => $data->download_link,
        // 'email_template' => $email_body,
    );
    // if($type === 'licence_key'){
    //     $row['licence'] = $data->licence;
    // }else if($type === 'login_details'){
    //      $row['login_id'] = $data->login_id;
    //      $row['login_password'] = $data->login_password;
    // }else if($type === 'download_link'){
    //     $row['download_link'] = $data->download_link;
    // }
    // echo '<pre> :::::::::::::::';
    // print_r($data);
    // print_r($row);
    // exit();

    try {
        $wpdb->insert(
            $wpdb->prefix . 'dl_order_log', 
            $row, 
            array('%d', '%d', '%d', '%d', '%s', '%s', '%s','%s', '%s', '%s'
        ));
    } catch (Exception $e) {

    }
}

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

function all_licences_available_check($productIds)
{
    // return true;
    global $wpdb;
    $row = true;
    if($productIds && count($productIds) > 0){
        foreach($productIds as $productId){
            if(!$row) return $row = false;
            $meta_key =  'digital_goods_dl_type';
            $dl_type = get_post_meta( $productId, $meta_key );
            $dl_type = ($dl_type && count($dl_type)>0 )? $dl_type[0] : '';
            if($dl_type === '') return $row = false;
            try {
                $sql = "SELECT COUNT(*) FROM  `{$wpdb->prefix}digital_licences` WHERE  `product_id` = {$productId} AND `type` = '{$dl_type}' AND `sold` < `total` ORDER BY id ASC LIMIT 1";
                $rowcount = $wpdb->get_var($sql);
                if($rowcount < 1) return $row = false;                
            } catch (Exception $e) {}
        }
    }
    return $row;
}

function dl_check_licence_available($product_id, $dl_type, $customer_id)
{
    global $wpdb;
    $row = array();
    if($dl_type === '') return $row;
    try {
        $order = "SELECT `row_id` FROM `{$wpdb->prefix}dl_order_log` WHERE `user_id` = ". $customer_id;
        $sql = "SELECT * FROM  `{$wpdb->prefix}digital_licences` WHERE `id` NOT IN ({$order}) AND `product_id` = {$product_id} AND `type` = '{$dl_type}' AND `sold` < `total` ORDER BY id ASC LIMIT 1";
        $results = $wpdb->get_results($sql, OBJECT);
        
        if ($results){ 
            $row = $results;
        }else{
            $results2 = $wpdb->get_results("SELECT * FROM  `{$wpdb->prefix}digital_licences` WHERE `product_id` = {$product_id} AND `type` = '{$dl_type}' AND `sold` < `total` ORDER BY id ASC LIMIT 1", OBJECT);
            if ($results2) $row = $results2;
        };

    } catch (Exception $e) {

    }
    return $row;
}

// dl_reduce_licence
function dl_reduce_licence($id, $current, $type)
{
    global $wpdb;
    try {
        $wpdb->update($wpdb->prefix . 'digital_licences',
            array('sold' => ($current + 1)),
            array('id' => $id, 'type' => $type),
            array('%d', '%s'),
            array('%d')
        );
    } catch (Exception $e) {
    }
    return 1;
}


function dl_licence_template($download_link, $title, $data, $type)
{

    // echo '<pre> ::::::::222:::::::';
    // print_r($data);
    // print_r($type);
    //exit();
    
    // TODO: Change template by type
    echo '<table class="dl_table sp_dl_table">';
    echo '<tr ><h4 class="sp_dl_title"><center><bold>' . $title . '</bold></center></h4></tr>';
    if($type === 'licence_key'){
        echo '<tr><td>Your Licence Key</td><td> <code>' . $data->licence . '</code></td></tr>';
        echo '<tr><td>Your Download Link</td><td> <code>' . $download_link . '</code></td></tr>';
    }else if($type === 'login_details'){
         echo '<tr><td>Your Login ID</td><td> <code>' . $data->login_id . '</code></td></tr>';
         echo '<tr><td>Your Login Password</td><td> <code>' . $data->login_password . '</code></td></tr>';
         echo '<tr><td>Your Download Link</td><td> <code>' . $download_link . '</code></td></tr>';
    }else if($type === 'download_link'){
        echo '<tr><td>Your Serial Key</td><td> <code>' . $data->licence . '</code></td></tr>';
       echo '<tr><td>Your Licensed Download link</td><td> <code>' . $data->download_link . '</code></td></tr>';
    }
    
    // echo '<tr><td>Download Link</td><td><a href="'.$download_link.'">'.$download_link.'</a> </td></tr>';
    echo '</table>';
}


function dl_send_licence_email($mail, $send = true)
{
    //  echo '<pre> ::::::::222:::::::';
    // print_r($mail);
    //exit();
    $id = $mail['id']; 
    $to = $mail['to'];
    $name = $mail['name'];
    $type = $mail['type']; // TODO: Change template by type
    $download = $mail['download_link'];
    $subject = get_post_meta($id, 'sp_dl_email_subject', true);
    $message_before = get_post_meta($id, 'sp_dl_email_body_before', true);
    $message_after = get_post_meta($id, 'sp_dl_email_body_after', true);
    $message = '<center><h2><bold>' . $mail['title'] . '</bold></bold></h2>';
    
    $headers = get_post_meta($id, 'sp_dl_email_header', true);
    // $headers = str_replace('{{name}}', $name, $headers);
    $message .= $headers;
    
    $receiver = get_post_meta($id, 'sp_dl_email_receiver', true);
    // $receiver = str_replace('{{name}}', $name, $receiver);
    $message .= $receiver;

    $attachments = array($download);
    if($type === 'licence_key'){
        $message .= '<h4>Your Licence Key: <code>' . $mail['licence']->licence . '</code></code></h4>';
        // $message .= '<h4>Your Download Link: <code>' . $download . '</code></code></h4>';
    }else if($type === 'login_details'){
        $message .= '<h4>Your Login ID: <code>' . $mail['licence']->login_id . '</code></code></h4>';
        $message .= '<h4>Your Login Password: <code>' . $mail['licence']->login_password . '</code></code></h4>';
        // $message .= '<h4>Your Download Link: <code>' . $download . '</code></code></h4>';
    }else if($type === 'download_link'){
        $message .= '<h4>Your Serial Key: <code>' . $mail['licence']->licence . '</code></code></h4>';
        $message .= '<h4>Your Licensed Download link: <code>' . $mail['licence']->download_link . '</code></code></h4>';
        $attachments = array($mail['licence']->download_link);
    }
    if($type !== 'download_link' && $download !== '')
        $message .= '<p>Your Download Link: <a href="' . $download . '">' . $download . '</a> </p>';
    $message .= '</center>';
    // $message = '<center><h2><bold>' . $mail['title'] . '</bold></bold></h2><h4>Your Licence Key: <code>' . $mail['licence']->licence . '</code></code></h4><p>Download Link: <a href="' . $download . '">' . $download . '</a> </p></center>';
    
    $headers = array('Content-Type: text/html; charset=UTF-8');
    
    $body = $message_before . $message . $message_after;
    $body = str_replace('{{name}}', $name, $body);;

    if($send){
        return wp_mail($to, $subject, $body, $headers, $attachments);
    }else{
        return $body;
    }


}



add_filter( 'woocommerce_my_account_my_orders_actions', 'change_my_account_my_orders_view_text_button', 10, 2 );
function change_my_account_my_orders_view_text_button( $actions, $order ) {

    $actions['view']['name'] = __( 'View Order Details', 'woocommerce' );

    return $actions;
}

// Rename My account "Orders" menu item
// add_filter( 'woocommerce_account_menu_items', 'rename_my_account_orders_menu_item', 22, 1 );
// function rename_my_account_orders_menu_item( $items ) {
//     $items['orders'] = __("Activation Details", "woocommerce");

//     return $items;
// }


add_action( 'woocommerce_view_order', 'my_custom_tracking' );
function my_custom_tracking( $order_id ){
    // Get an instance of the `WC_Order` Object
    $order = wc_get_order( $order_id );
    // $customer_id = $order->get_customer_id();

    $email_template = get_post_meta( $order_id, 'dl_email_template', true );
    if (!!$email_template) {
        echo $email_template;
    }
    
    // echo '<pre>';
    // print_r($order_id.'::::::::::::::::::'. $customer_id);
    // $items = $order->get_items();
    // foreach ( $items as $item ) {
    //     $product_name = $item->get_name();
    //     $product_id = $item->get_product_id();
    //     $send = dl_check_already_send_licence($order_id, $product_id);
    //     echo '<h2>'.$product_name.'</h2>';
    //     echo isset($send[0]->email_template) ? $send[0]->email_template : '';
    // }
   
}






/**
 * Adds a new column to the "My Orders" table in the account.
 *
 * @param string[] $columns the columns in the orders table
 * @return string[] updated columns
 */
function sv_wc_add_my_account_orders_column( $columns ) {

    $new_columns = array();

    foreach ( $columns as $key => $name ) {
        // echo '<pre>';
        // print_r($columns);

        $new_columns[ $key ] = $name;

        // add ship-to after order status column
        if ( 'order-actions' === $key ) {
            $new_columns['activation-details'] = __( 'Activation Details', 'textdomain' );
        }
    }

    return $new_columns;
}
add_filter( 'woocommerce_my_account_my_orders_columns', 'sv_wc_add_my_account_orders_column' );

/**
 * Adds data to the custom "ship to" column in "My Account > Orders".
 *
 * @param \WC_Order $order the order object for the row
 */
function sv_wc_my_orders_ship_to_column( $order ) {
    // echo '<pre>';
    // print_r($order->get_order_number());
    // print_r($order);
    $myAccount = get_permalink( get_option('woocommerce_myaccount_page_id'));
    $orderId = $order->get_order_number();


	// $formatted_shipping = $order->get_formatted_shipping_address();

	// echo ! empty( $formatted_shipping ) ? $formatted_shipping : '&ndash;';
    // echo '<button class="dl-activation-details" data-order-id="">Collect Activation Details</button>';
    echo '<a href="'.$myAccount.'premium-support?order_id='.$orderId.'"><button class="button dlCollectButton">Collect Activation Details</button></a>';
}
add_action( 'woocommerce_my_account_my_orders_column_activation-details', 'sv_wc_my_orders_ship_to_column' );




















// ------------------
// 1. Register new endpoint to use for My Account page
// Note: Resave Permalinks or it will give 404 error
  
function bbloomer_add_premium_support_endpoint() {
    add_rewrite_endpoint( 'premium-support', EP_ROOT | EP_PAGES );
}
  
add_action( 'init', 'bbloomer_add_premium_support_endpoint' );
  
  
// ------------------
// 2. Add new query var
  
function bbloomer_premium_support_query_vars( $vars ) {
    $vars[] = 'premium-support';
    return $vars;
}
  
add_filter( 'query_vars', 'bbloomer_premium_support_query_vars', 0 );
  
  
// ------------------
// 3. Insert the new endpoint into the My Account menu
  
function bbloomer_add_premium_support_link_my_account( $items ) {
    $items['premium-support'] = 'Premium Support';
    return $items;
}
  
//add_filter( 'woocommerce_account_menu_items', 'bbloomer_add_premium_support_link_my_account' );
  
  
// ------------------
// 4. Add content to the new endpoint
  
function bbloomer_premium_support_content() {
    $order_id = isset($_GET['order_id']) ? $_GET['order_id'] : false;
    if($order_id){
        $order = wc_get_order( $order_id );
        $email_template = get_post_meta( $order_id, 'dl_email_template', true );
        if (!!$email_template) {
            echo $email_template;
        }
    }
}
  
add_action( 'woocommerce_account_premium-support_endpoint', 'bbloomer_premium_support_content' );
// Note: add_action must follow 'woocommerce_account_{your-endpoint-slug}_endpoint' format