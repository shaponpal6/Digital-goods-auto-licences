<?php

class metabox_executor
{

    public function __construct()
    {

        add_action('add_meta_boxes', array($this, 'wporg_add_custom_box'));
        add_action('wp_ajax_dl_licence_admin_ajax', array($this, 'dl_licence_admin_ajax'));
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
        $licences = $this->get_licence();

        ob_start();
        ?>
        <div class="dl_licence_class">
            <table class="dl_form dl_table">
                <tr></tr>
                <tr>
                    <td><h4 class="button dl_add_licence">Add Licence</h4></td>
                    <td><h4 class="button dl_view_licence">View Licence</h4></td>
                    <td></td>
                    <td></td>
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
                        <div class="sp_poll_sp_text"><strong>Add Licence Key: </strong></div>
                        <input id="dl_licence_key" class="sp_poll_field regular-text" type="text" value="">
                        <div class="sp_poll_sp_text"><strong>Licence Items: </strong></div>
                        <input id="dl_licence_item" class="sp_poll_field regular-text" type="number" value="1">
                        <br/><br/>
                        <div id="dl_licence_add" data-id="<?php echo  get_the_ID();?>" class="button button-primary">Add new</div>
                    </td>
                </tr>
            </table>

            <table id="dl_licence_view_container" class="dl_form dl_table">
                <tr>
                    <td>#SI</td>
                    <td>Licence Key</td>
                    <td>Sold</td>
                    <td>Total</td>
                </tr>
                <?php for ($i =0; count($licences) > $i; $i++){ ?>
                    <tr>
                        <?php
                        echo '<td>'.($i+1).'</td>';
                        echo '<td>'.$licences[$i]->licence.'</td>';
                        echo '<td>'.$licences[$i]->sold.'</td>';
                        echo '<td>'.$licences[$i]->total.'</td>';
                        ?>
                    </tr>
                <?php }?>

            </table>


        </div>
        <?php
        $html = ob_get_clean();
        echo $html;
    }

    function get_licence(){
        global $wpdb;
        $id = get_the_ID();
        try{
            $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}digital_licences WHERE product_id = {$id}", OBJECT );
            return $results && count($results) > 0 ? $results : array();
        }catch (Exception $e){

        }
//        echo '<pre>';
//        print_r($results);
        return array();

    }

    function dl_licence_admin_ajax(){
        global $wpdb;
        $id =  isset($_POST['product_id']) ? $_POST['product_id']: '' ;
        $licence =  isset($_POST['licence']) ? $_POST['licence']: '' ;
//        if ($licence === '') return 'Licence Key can\'t be Empty';
        $item =  isset($_POST['item']) ? $_POST['item']: '' ;
        $wpdb->insert(
            $wpdb->prefix.'digital_licences',
            array(
                'product_id' => $id,
                'licence' => $licence,
                'total' => $item
            ),
            array(
                '%d',
                '%s',
                '%d'
            )
        );
        echo $wpdb->insert_id;
//        exit();

    }

}

if (is_admin()) return new metabox_executor();