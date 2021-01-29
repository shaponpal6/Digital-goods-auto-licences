<?php
function get_dl_licence_log(){
    global $wpdb;
    try{
        $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}dl_order_log WHERE 1 = 1 ORDER BY id DESC", OBJECT );
        return $results && count($results) > 0 ? $results : array();
    }catch (Exception $e){

    }
//        echo '<pre>';
//        print_r($results);
    return array();

}
$dl_logs = get_dl_licence_log();
//echo '<pre>';
//print_r($dl_logs);
?>
<div class="wrap">
    <link data-require="bootstrap@*" data-semver="3.3.7" rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <div class='tabla' id='tabla'>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>#SI</th>
                <th>Order ID</th>
                <th>Product ID</th>
                <th>Licence Key</th>
                <th>Order Time</th>
                <th>Order Status</th>
            </tr>
            </thead>
            <tbody>
            <?php for ($i = 0; count($dl_logs) > $i; $i++){ ?>
            <tr>
                <th><?php echo $i+1;?></th>
                <td><?php echo $dl_logs[$i]->order_id;?></td>
                <td><?php echo $dl_logs[$i]->product_id;?></td>
                <td><?php echo $dl_logs[$i]->licence;?></td>
                <td><?php echo $dl_logs[$i]->updated_at;?></td>
                <td>Completed</td>
            </tr>
            <?php }?>

            </tbody>
        </table>
    </div>
</div>