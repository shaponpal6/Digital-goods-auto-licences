<?php

// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'core/csv_importer.php';

class LC_manager
{

    public function __construct()
    {

        add_action('add_meta_boxes', array($this, 'wporg_add_custom_box'));
        add_action('wp_ajax_dl_licence_admin_ajax', array($this, 'dl_licence_admin_ajax'));
        add_action('wp_ajax_dl_licence_delete_ajax', array($this, 'dl_licence_delete_ajax'));
        add_action('wp_ajax_save_dl_type_ajax', array($this, 'save_dl_type_ajax'));
        add_action('wp_ajax_imput_csv_ajax', array($this, 'imput_csv_ajax'));
        // $this->imput_csv_ajax();
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
        $dl_type = ($dl_type && count($dl_type)>0 )? $dl_type[0] : '';
        $licences = $this->get_licence($dl_type);
        // print_r($product_id);
        // echo '<br/>';
        // print_r($dl_type);
        // echo '<br/>';
        // print_r(get_post_meta( $product_id, '_price' ));
        // echo '<br/>';
        // echo '<br/>';
        

        ob_start();
        if(!$dl_type){
            ?>
<div class="dl_licence_empty">
    <h2>Please Choose Product Licence Type</h2>
    <select name="digital_goods_dl_type" id="digital_goods_dl_type" data-pid="<?php echo $product_id;?>">
        <option value=""><?php echo $dl_type ? $dl_type : 'Select Type';?></option>
        <option value="licence_key">Licence Key</option>
        <option value="login_details">Login Details</option>
        <option value="download_link">Download Link</option>
    </select>
</div>
<?php
        }else{
        ?>
<div class="dl_licence_class">
    <div id="dlLog"></div>
    <table class="dl_form dl_table">
        <tr></tr>
        <tr>
            <td>
                <!-- <div class="sp_poll_sp_text"><strong>Choose Type </strong></div> -->
                <select name="digital_goods_dl_type" id="digital_goods_dl_type" data-pid="<?php echo $product_id;?>">
                    <option value=""><?php echo $dl_type;?></option>
                    <option value="licence_key">Licence Key</option>
                    <option value="login_details">Login Details</option>
                    <option value="download_link">Download Link</option>
                </select>
            </td>
            <td>
                <h4 class="button dl_add_licence">Add Licence</h4>
            </td>
            <td>
                <h4 class="button dl_view_licence">View Licence</h4>
            </td>
            <td>
                <!-- <label class="col-md-4 control-label">Choose CSV File</label>  -->
                <input type="file" name="file" id="dlCSVFile" placeholder="Choose CSV file" accept=".csv">
            </td>
            <td><button class="button" id="dl_delete_all">Delete All</button></td>
        </tr>
    </table>

    <table id="dl_licence_container" style="display: none" class="dl_form dl_table">
        <tr></tr>
        <tr>
            <td>
                <?php if($dl_type === 'licence_key'){?>
                <div class="sp_poll_sp_text"><strong>Add Licence Key: </strong></div>
                <input id="dl_licence_key" class="sp_poll_field regular-text" type="text" value="">
                <div class="sp_poll_sp_text"><strong>Licence Items: </strong></div>
                <input id="dl_licence_item" class="sp_poll_field regular-text" type="number" value="1">
                <br /><br />
                <?php } else if($dl_type === 'login_details'){?>
                <div class="sp_poll_sp_text"><strong>Add Login ID: </strong></div>
                <input id="dl_login_id" class="sp_poll_field regular-text" type="text" value="">
                <div class="sp_poll_sp_text"><strong>Add Login Password: </strong></div>
                <input id="dl_login_password" class="sp_poll_field regular-text" type="text" value="">
                <br /><br />
                <?php } else if($dl_type === 'download_link'){?>
                <div class="sp_poll_sp_text"><strong>Add Serial Key: </strong></div>
                <input id="dl_licence_key" class="sp_poll_field regular-text" type="text" value="">
                <div class="sp_poll_sp_text"><strong>Add Licensed Download link: </strong></div>
                <input id="dl_download_link" class="sp_poll_field regular-text" type="text" value="">
                <br /><br />
                <?php }?>
                <div id="dl_licence_add" data-id="<?php echo  get_the_ID();?>" data-row-id="" data-type="add"
                    class="button button-primary">Add new</div>
            </td>
        </tr>
    </table>

    <div class="dl_licence_class2" style="height: 200px;overflow: auto;">
        <table id="dl_licence_view_container" class="dl_form dl_table">
            <?php if($dl_type === 'licence_key'){?>
            <tr>
                <td><input type="checkbox" aria-checked="false" id="dlCheckAll4Delete" /></td>
                <td>#SI</td>
                <td>Licence Key</td>
                <td>Sold</td>
                <td>Total</td>
                <td>Action</td>
            </tr>
            <?php if(count($licences) > 0){
                     for ($i =0; count($licences) > $i; $i++){ ?>
            <tr class="dlRow">
                <?php
                        echo '<td><input data-id="'.$licences[$i]->id.'" type="checkbox" class="dlMultiDelete"/></td>';
                        echo '<td>'.($i+1).'</td>';
                        echo '<td class="dlRowLicence">'.$licences[$i]->licence.'</td>';
                        echo '<td>'.$licences[$i]->sold.'</td>';
                        echo '<td class="dlRowTotal">'.$licences[$i]->total.'</td>';
                        echo '<td>
                            <button data-id="'.$licences[$i]->id.'" class="dlRowEdit" type="download_link">Edit</button>
                            <button data-id="'.$licences[$i]->id.'" class="dlRowDelete" type="download_link">Delete</button>
                        </td>';
                        ?>
            </tr>
            <?php }
                } else{ 
                    echo '<tr><td></td><td>No Data </td><td></td><td></td></tr>';
                } ?>

            <?php } else if($dl_type === 'login_details'){?>

            <tr>
                <td><input type="checkbox" aria-checked="false" id="dlCheckAll4Delete" /></td>
                <td>#SI</td>
                <td>Login ID</td>
                <td>Login Password</td>
                <td>Sold</td>
                <td>Action</td>
            </tr>
            <?php if(count($licences) > 0){
                     for ($i =0; count($licences) > $i; $i++){ ?>
            <tr class="dlRow">
                <?php
                        echo '<td><input data-id="'.$licences[$i]->id.'" type="checkbox" class="dlMultiDelete"/></td>';
                        echo '<td>'.($i+1).'</td>';
                        echo '<td class="dlRowLoginId">'.$licences[$i]->login_id.'</td>';
                        echo '<td class="dlRowLoginPassword">'.$licences[$i]->login_password.'</td>';
                        echo '<td>'.$licences[$i]->sold.'</td>';
                        echo '<td>
                            <button data-id="'.$licences[$i]->id.'" class="dlRowEdit" type="download_link">Edit</button>
                            <button data-id="'.$licences[$i]->id.'" class="dlRowDelete" type="download_link">Delete</button>
                        </td>';
                        ?>
            </tr>
            <?php }
                } else{ 
                    echo '<tr><td></td><td>No Data </td><td></td><td></td></tr>';
                } ?>

            <?php } else if($dl_type === 'download_link'){?>


            <tr>
                <td><input type="checkbox" aria-checked="false" id="dlCheckAll4Delete" /></td>
                <td>#SI</td>
                <td>Serial Key</td>
                <td>Licensed Download link</td>
                <td>Sold</td>
                <td>Action</td>
            </tr>
            <?php if(count($licences) > 0){
                     for ($i =0; count($licences) > $i; $i++){ ?>
            <tr class="dlRow">
                <?php
                        echo '<td><input data-id="'.$licences[$i]->id.'" type="checkbox" class="dlMultiDelete"/></td>';
                        echo '<td>'.($i+1).'</td>';
                        echo '<td class="dlRowLicence">'.$licences[$i]->licence.'</td>';
                        echo '<td class="dlRowDownloadLink">'.$licences[$i]->download_link.'</td>';
                        echo '<td>'.$licences[$i]->sold.'</td>';
                        echo '<td>
                            <button data-id="'.$licences[$i]->id.'" class="dlRowEdit" type="download_link">Edit</button>
                            <button data-id="'.$licences[$i]->id.'" class="dlRowDelete" type="download_link">Delete</button>
                        </td>';
                        ?>
            </tr>
            <?php }
                    } else{ 
                        echo '<tr><td></td><td>No Data </td><td></td><td></td></tr>';
                    } ?>

            <?php }?>



        </table>
    </div>


</div>
<?php
        }
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
    function imput_csv_ajax(){
        global $wpdb;
        $csv_data =  isset($_POST['csv_data']) ? $_POST['csv_data']: '';
        $dl_type =  isset($_POST['dl_type']) ? $_POST['dl_type']: 'licence_key' ;
        $product_id =  isset($_POST['product_id']) ? $_POST['product_id']: 0 ;
        $response =  '' ;
        $total =  1 ;
        $count =  0 ;

        $licence =  '' ;
        $login_id =  '' ;
        $login_password =  '' ;
        $download_link =  '' ;

        try{
            $data =  json_decode( str_replace('u00a0', '', str_replace('\\', '', $csv_data)), true);
            
            if($data){
                foreach($data as $row){ 
                    $valid =  false ;
                    
                    if($dl_type === 'licence_key' && isset($row['product_key']) && isset($row['limit'])){
                        $licence =  $row['product_key'];
                        $total = $row['limit'];
                        if($licence !=='' && is_numeric($total) )
                        $valid =  true;
                    } 
                    else if($dl_type === 'login_details' && (isset($row['login_id']) && isset($row['login_password']))){
                        $login_id =  $row['login_id'];
                        $login_password = $row['login_password'];
                         if($login_id !=='' && $login_password !=='' )
                        $valid =  true;
                    } 
                    else if($dl_type === 'download_link'){
                        $licence =  $row['serial_key'];
                        $download_link =  $row['download_link'] ;
                        if($licence !=='' )
                        $valid =  true;
                    } 

                    if($valid){
                        $count += 1;
                        $wpdb->insert(
                            $wpdb->prefix.'digital_licences',
                            array(
                                'product_id' => $product_id,
                                'type' => $dl_type,
                                'licence' => $licence,
                                'total' => $total,
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
                    }
                }
            }
            
            $response =  'success' ;
        }catch (Exception $e) {
            $response =  'error: ' + $e ;
        }

        $results ="{type: $dl_type, count: $count, csv_data: $csv_data, status:$response}";
        // echo $results;
        echo $count;
        // print_r($results);
        // exit();
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
        $item =  isset($_POST['item']) ? $_POST['item']: '' ;

        $login_id =  isset($_POST['login_id']) ? $_POST['login_id']: '' ;
        $login_password =  isset($_POST['login_password']) ? $_POST['login_password']: '' ;
        $download_link =  isset($_POST['download_link']) ? $_POST['download_link']: '' ;

        $row_id =  isset($_POST['row_id']) ? $_POST['row_id']: '' ;
        $update_type =  isset($_POST['update_type']) ? $_POST['update_type']: '' ;
        
        $data = array(
                'product_id' => $id,
                'type' => $type,
                'licence' => $licence,
                'total' => $item,
                'login_id' => $login_id,
                'login_password' => $login_password,
                'download_link' => $download_link,
            );

        if($update_type==='update'){
             $wpdb->update(
            $wpdb->prefix.'digital_licences', $data,
            array('id' => $row_id,),
            array(
                '%d',
                '%s',
                '%s',
                '%d',
                '%s',
                '%s',
                '%s',
            ), 
            array('%d')
        );

        }else{

        $wpdb->insert(
            $wpdb->prefix.'digital_licences', $data,
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
        }
        echo $wpdb->insert_id;
    }

    function dl_licence_delete_ajax(){
        global $wpdb;
        $row_ids =  isset($_POST['row_ids']) ? $_POST['row_ids']: '' ;
        $action_type =  isset($_POST['action_type']) ? $_POST['action_type']: '' ;
        if($action_type === 'deleteAll'){
            $arr =  explode(',', $row_ids);
            $ids = implode( ',', array_map( 'absint', $arr ) );
            $wpdb->query( "DELETE FROM `{$wpdb->prefix}digital_licences` WHERE ID IN($ids)" );
            echo $ids;

        }else{
            $wpdb->delete(
                $wpdb->prefix.'digital_licences',
                array('id' => $row_ids ),
                array('%d')
            );
            echo $row_ids;
        }
    }

    /**
     * Render Page
     */
    function render()
    {
        include_once plugin_dir_path( dirname( __FILE__ ) ).'admin/templates/lc-manager.php'; 
        // For Rest API
        include_once plugin_dir_path( dirname( __FILE__ ) ).'admin/templates/lc-rest-crud.php'; 
        echo '<tr>rrrrrrrrrrrrrrrrrr<td>';
    }

}

if (is_admin()) return new LC_manager();