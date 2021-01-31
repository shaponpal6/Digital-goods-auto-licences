<?php

class metabox_executor
{

    public function __construct()
    {

        add_action('add_meta_boxes', array($this, 'wporg_add_custom_box'));
        add_action('wp_ajax_dl_licence_admin_ajax', array($this, 'dl_licence_admin_ajax'));
        add_action('wp_ajax_save_dl_type_ajax', array($this, 'save_dl_type_ajax'));
    }

    /**
     * Define the metabox and field configurations.
     */
    function wporg_add_custom_box()
    {
        add_meta_box(
            'wporg_box_id',           // Unique ID
            esc_html__('Digital Goods Licence', 'text-domain'),  // Box title
            array($this, 'wporg_custom_box_html'),  // Content callback, must be of type callable
            'product',                  // Post type
            'normal',
            'high'
        );
    }

    

    function wporg_custom_box_html()
    {
        global $post;
        $product_id =  $post->ID;
        $meta_key =  'digital_goods_dl_type';
        $dl_type = get_post_meta( $product_id, $meta_key );
        $dl_type = ($dl_type && count($dl_type)>0 )? $dl_type[0] : 'licence_key';
        $licences = $this->get_licence($dl_type);
        // print_r($product_id);
        // echo '<br/>';
        // print_r($dl_type);
        // echo '<br/>';
        // print_r(get_post_meta( $product_id, '_price' ));
        // echo '<br/>';
        // echo '<br/>';

        ob_start();
        ?>
        <div class="dl_licence_class">
        <div id="dlresult">rrrrrrrrrr</div>
        <hr/>
        <div id="dlresult2">tyyyyyyyyyyyy</div>
            <table class="dl_form dl_table">
                <tr></tr>
                <tr>
                    <td>
                    <div class="sp_poll_sp_text"><strong>Choose Type </strong></div>
                        <select name="digital_goods_dl_type" id="digital_goods_dl_type" data-pid="<?php echo $product_id;?>">
                            <option value="" ><?php echo $dl_type;?></option>
                            <option value="licence_key" >Licence Key</option>
                            <option value="login_details" >Login Details</option>
                            <option value="download_link" >Download Link</option>
                        </select>
                    </td>
                    <td><h4 class="button dl_add_licence">Add Licence</h4></td>
                    <td><h4 class="button dl_view_licence">View Licence</h4></td>
                    <td>
                    <form class="form-horizontal" action="" method="post"
                        name="frmCSVImport" id="frmCSVImport"
                        enctype="multipart/form-data">
                        <div class="input-row">
                        <div class="sp_poll_sp_text"><strong>Choose CSV File </strong></div>
                            <input type="file" name="file" id="file" accept=".csv">
                            <button type="submit" id="submit" name="import"
                                class="btn-submit">Import</button>
                            <br />

                        </div>

                    </form>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>

            <table id="dl_licence_container" style="display: none" class="dl_form dl_table">
                <tr></tr>
                <tr>
                    <td >
                    <?php if($dl_type === 'licence_key'){?>
                        <div class="sp_poll_sp_text"><strong>Add Licence Key: </strong></div>
                        <input id="dl_licence_key" class="sp_poll_field regular-text" type="text" value="">
                        <div class="sp_poll_sp_text"><strong>Licence Items: </strong></div>
                        <input id="dl_licence_item" class="sp_poll_field regular-text" type="number" value="1">
                        <br/><br/>
                    <?php } else if($dl_type === 'login_details'){?>
                        <div class="sp_poll_sp_text"><strong>Add Login ID: </strong></div>
                        <input id="dl_login_id" class="sp_poll_field regular-text" type="text" value="">
                        <div class="sp_poll_sp_text"><strong>Add Login Password: </strong></div>
                        <input id="dl_login_password" class="sp_poll_field regular-text" type="text" value="">
                        <br/><br/>
                    <?php } else if($dl_type === 'download_link'){?>
                        <div class="sp_poll_sp_text"><strong>Add Download Link: </strong></div>
                        <input id="dl_download_link" class="sp_poll_field regular-text" type="text" value="">
                        <br/><br/>
                    <?php }?>
                        <div id="dl_licence_add" data-id="<?php echo  get_the_ID();?>" class="button button-primary">Add new</div>
                    </td>
                </tr>
            </table>

            <table id="dl_licence_view_container" class="dl_form dl_table">
                <?php if($dl_type === 'licence_key'){?>
                    <tr>
                        <td>#SI</td>
                        <td>Licence Key</td>
                        <td>Sold</td>
                        <td>Total</td>
                    </tr>
                    <?php if(count($licences) > 0){
                     for ($i =0; count($licences) > $i; $i++){ ?>
                    <tr>
                        <?php
                        echo '<td>'.($i+1).'</td>';
                        echo '<td>'.$licences[$i]->licence.'</td>';
                        echo '<td>'.$licences[$i]->sold.'</td>';
                        echo '<td>'.$licences[$i]->total.'</td>';
                        ?>
                    </tr>
                <?php }
                } else{ 
                    echo '<tr><td></td><td>No Data </td><td></td><td></td></tr>';
                } ?>

                <?php } else if($dl_type === 'login_details'){?>
                    
                    <tr>
                        <td>#SI</td>
                        <td>Login ID</td>
                        <td>Login Password</td>
                        <td>Sold</td>
                    </tr>
                    <?php if(count($licences) > 0){
                     for ($i =0; count($licences) > $i; $i++){ ?>
                    <tr>
                        <?php
                        echo '<td>'.($i+1).'</td>';
                        echo '<td>'.$licences[$i]->login_id.'</td>';
                        echo '<td>'.$licences[$i]->login_password.'</td>';
                        echo '<td>'.$licences[$i]->sold.'</td>';
                        ?>
                    </tr>
                <?php }
                } else{ 
                    echo '<tr><td></td><td>No Data </td><td></td><td></td></tr>';
                } ?>

                <?php } else if($dl_type === 'download_link'){?>
                    

                     <tr>
                        <td>#SI</td>
                        <td>Download Link</td>
                        <td>Sold</td>
                    </tr>
                    <?php if(count($licences) > 0){
                     for ($i =0; count($licences) > $i; $i++){ ?>
                    <tr>
                        <?php
                        echo '<td>'.($i+1).'</td>';
                        echo '<td>'.$licences[$i]->download_link.'</td>';
                        echo '<td>'.$licences[$i]->sold.'</td>';
                        ?>
                    </tr>
                    <?php }
                    } else{ 
                        echo '<tr><td></td><td>No Data </td><td></td><td></td></tr>';
                    } ?>

                <?php }?>

                

            </table>


        </div>
        <?php
        $html = ob_get_clean();
        echo $html;
    }

    function get_licence($type = ''){
        global $wpdb;
        $id = get_the_ID();
        $filter = $type !=='' ? " AND type LIKE '{$type}'" : '';
        try{
            $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}digital_licences WHERE product_id = {$id} {$filter}", OBJECT );
            return $results && count($results) > 0 ? $results : array();
        }catch (Exception $e){

        }
//        echo '<pre>';
//        print_r($results);
        return array();

    }


    // save licence type
    function save_dl_type_ajax(){
        global $post;
        $dl_type =  isset($_POST['dl_type']) ? $_POST['dl_type']: 'licence_key' ;
        $product_id =  isset($_POST['product_id']) ? $_POST['product_id']: 0 ;
        $meta_key =  'digital_goods_dl_type';

        // Check Meta exist
        if ( metadata_exists( 'post', $product_id, $meta_key ) ) {
            update_post_meta( $product_id, $meta_key, $dl_type );
        }else{
            add_post_meta( $product_id, $meta_key, $dl_type );
        }

        // echo "{type: $dl_type,product_id:$product_id, status:$status}";
        echo "{type: $dl_type,product_id:$product_id, status:'success'}";

    }

    function dl_licence_admin_ajax(){
        global $wpdb;
        $id =  isset($_POST['product_id']) ? $_POST['product_id']: '' ;
        $type =  isset($_POST['type']) ? $_POST['type']: '' ;
        if($type === '') return 0;
        $licence =  isset($_POST['licence']) ? $_POST['licence']: '' ;
//        if ($licence === '') return 'Licence Key can\'t be Empty';
        $item =  isset($_POST['item']) ? $_POST['item']: '' ;

        $login_id =  isset($_POST['login_id']) ? $_POST['login_id']: '' ;
        $login_password =  isset($_POST['login_password']) ? $_POST['login_password']: '' ;
        $download_link =  isset($_POST['download_link']) ? $_POST['download_link']: '' ;

        $wpdb->insert(
            $wpdb->prefix.'digital_licences',
            array(
                'product_id' => $id,
                'type' => $type,
                'licence' => $licence,
                'total' => $item,
                'login_id' => $login_id,
                'login_password' => $login_password,
                'download_link' => $download_link,
            ),
            array(
                '%d',
                '%s',
                '%s',
                '%d',
                '%s',
                '%s',
                '%s',
            )
        );
        echo $wpdb->insert_id;
//        exit();

    }

}

if (is_admin()) return new metabox_executor();