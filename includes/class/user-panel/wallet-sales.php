<?php

if( ! defined( 'ABSPATH' ) )
{
    die('You are not allowed to call this page directly !');
}

if( ! is_user_logged_in() )
{
    wp_die('You are not allowed here !');
}

$user_id	= get_current_user_id();
$wallet_url	= trailingslashit( mlm_page_url('panel') ) . 'section/sales/';
$per		= 20;
$start		= intval( ($attributes['page'] - 1) * $per );
$results	= mlmFire()->db->query_rows(
    "SELECT * FROM {TABLE} WHERE user_id = %d AND type = %d ORDER BY id DESC LIMIT %d, %d",
    array( $user_id, 1, $start, $per ),
    'wallet'
);

$count_rows	= mlmFire()->db->count_query_rows(
    "SELECT COUNT(id) FROM {TABLE} WHERE user_id = %d AND type = %d",
    array( $user_id, 1 ),
    'wallet'
);
?>

    <h3 class="mlm-box-title sm mb-3 py-2"><?php _e( 'Sale transactions', 'mlm' ); ?></h3>

<?php if( ! empty( $results ) ): ?>

    <div class="table-responsive">
        <table class="mlm-table mlm-wallet-table table table-borderless table-hover border-0">
            <thead>
            <tr>
                <th class="sm" scope="col"><?php _e( 'No.', 'mlm' ); ?></th>
                <th class="md" scope="col"><?php _e( 'Type', 'mlm' ); ?></th>
                <th class="md" scope="col"><?php _e( 'Amount', 'mlm' ); ?></th>
                <th class="lg" scope="col"><?php _e( 'Product name', 'mlm' ); ?></th>
                <th class="lg" scope="col"><?php _e( 'Shipping address', 'mlm' ); ?></th>
                <th class="lg" scope="col"><?php _e( 'Order details', 'mlm' ); ?></th>
                <th class="lg" scope="col"><?php _e( 'Description', 'mlm' ); ?></th>
                <th class="md" scope="col"><?php _e( 'Date', 'mlm' ); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach( $results as $trans ): ?>
                <tr class="<?php echo mlmFire()->wallet->get_type_class( $trans->type ); ?>">
                    <th scope="row"><strong>#<?php echo $trans->id; ?></strong></th>
                    <td><?php echo mlmFire()->wallet->get_type_text( $trans->type ); ?></td>
                    <td><?php echo mlm_filter( $trans->amount ); ?></td>
                    <td><?php echo mlm_get_post_title( $trans->post_id ); ?></td>
                    <td>
                        <?php
                        $order_data = new WC_Order( $trans->order_id);
                        echo $order_data->get_formatted_shipping_address();
                        ?>
                    </td>
                    <td>
                        <div class=""><?php // _e( 'Customer', 'mlm' ); ?> <?php //echo get_post_meta( $trans->order_id, '_billing_first_name', true ) . ' ' . get_post_meta( $trans->order_id, '_billing_last_name', true ); ?></div>
                        <div class=""><?php _e( 'Total paid', 'mlm' ); ?>: <?php echo mlm_filter( get_post_meta( $trans->order_id, '_order_total', true ) ); ?></div>
                        <div class=""><?php _e( 'Discount', 'mlm' ); ?>: <?php echo mlm_filter( get_post_meta( $trans->order_id, '_cart_discount', true ) ); ?></div>
                    </td>
                    <td>
                        <?php echo $trans->description; ?>
                        <?php if( $trans->status != 2 && $trans->status != 4 && $trans->status != 3 ): ?>
                            <button class="btn btn-sm btn-warning py-0 disabled" disabled="disabled" style="line-height: 16px;"><?php echo mlmFire()->wallet->get_status_text( $trans->status ); ?></button>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php echo date_i18n( get_option('date_format'), strtotime( $trans->date ) ); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php mlm_db_pagination( $count_rows, $wallet_url, $per, $attributes['page'] ); ?>

<?php else: ?>

    <div class="alert alert-warning"><?php _e( 'No items found.', 'mlm' ); ?></div>

<?php endif; ?>