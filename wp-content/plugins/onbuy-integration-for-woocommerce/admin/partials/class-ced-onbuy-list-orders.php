<?php
/**
 * Display list of orders
 *
 * @package  Onbuy_Integration_By_CedCommerce
 * @version  1.0.0
 * @link     https://cedcommerce.com
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

$file = CED_ONBUY_DIRPATH . 'admin/partials/header.php';

if ( file_exists( $file ) ) {
	include_once $file;
}

$file = CED_ONBUY_DIRPATH . 'admin/partials/ced-instructions.php';
if ( file_exists( $file ) ) {
	require_once $file;

}

if ( ! class_exists( 'WP_List_Table' ) ) {
	include_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Ced_Onbuy_List_Orders
 *
 * @since 1.0.0
 */
class Ced_Onbuy_List_Orders extends WP_List_Table {

	/**
	 * Ced_Onbuy_List_Orders construct
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct(
			array(
				'singular' => __( 'ONBUY Order', 'woocommerce-onbuy-integration' ),
				'plural'   => __( 'ONBUY Orders', 'woocommerce-onbuy-integration' ),
				'ajax'     => true,
			)
		);
	}

	/**
	 * Function for preparing data to be displayed
	 *
	 * @since 1.0.0
	 */
	public function prepareItems() {
		$per_page = apply_filters( 'ced_onbuy_orders_list_per_page', 10 );
		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->getSortableColumns();

		$this->_column_headers = array( $columns, $hidden, $sortable );

		$current_page = $this->get_pagenum();
		if ( 1 < $current_page ) {
			$offset = $per_page * ( $current_page - 1 );
		} else {
			$offset = 0;
		}

		$this->items = self::cedonbuyOrders( $per_page, $current_page );

		$count = self::getCount();

		$this->set_pagination_args(
			array(
				'total_items' => $count,
				'per_page'    => $per_page,
				'total_pages' => ceil( $count / $per_page ),
			)
		);

		if ( ! $this->current_action() ) {
			$this->items = self::cedonbuyOrders( $per_page, $current_page );
			$this->renderHTML();
		}
	}

	/**
	 * Function to count number of responses in result
	 *
	 * @since 1.0.0
	 */
	public function getCount() {
		global $wpdb;
		$shop_id        = isset( $_GET['shop_id'] ) ? sanitize_text_field( wp_unslash( $_GET['shop_id'] ) ) : '';
		$orders_post_id = $wpdb->get_results( $wpdb->prepare( "SELECT `post_id` FROM $wpdb->postmeta WHERE `meta_key`=%s AND `meta_value`=%d  group by `post_id` ", 'ced_onbuy_order_shop_id', $shop_id ), 'ARRAY_A' );
		return count( $orders_post_id );
	}

	/**
	 * Text displayed when no  data is available
	 *
	 * @since 1.0.0
	 */
	public function no_items() {
		esc_html_e( 'No Orders To Display.', 'woocommerce-onbuy-integration' );
	}

	/**
	 * Function for id column
	 *
	 * @since 1.0.0
	 * @param array $items Order Data.
	 */
	public function column_id( $items ) {
		foreach ( $items as $key => $value ) {
			$display_orders = $value->get_data();
			echo '<b>' . esc_attr( $display_orders['order_id'] ) . '</b>';
			break;
		}
	}

	/**
	 * Function for name column
	 *
	 * @since 1.0.0
	 * @param array $items Order Data.
	 */
	public function column_name( $items ) {
		foreach ( $items as $key => $value ) {
			$display_orders = $value->get_data();
			$product_id     = $display_orders['product_id'];
			$url            = get_edit_post_link( $product_id, '' );
			echo '<b><a class="ced_onbuy_prod_name" href="' . esc_url( $url ) . '" target="#">' . esc_attr( $display_orders['name'] ) . '</a></b></br>';
		}
	}

	/**
	 * Function for order Id column
	 *
	 * @since 1.0.0
	 * @param array $items Order Data.
	 */
	public function column_onbuy_order_id( $items ) {
		foreach ( $items as $key => $value ) {
			$display_orders = $value->get_data();
			$order_id       = $display_orders['order_id'];
			$onbuy_order_id = get_post_meta( $order_id, '_ced_onbuy_order_id', true );
			echo '<b>' . esc_attr( $onbuy_order_id ) . '</b>';
			break;
		}
	}

	/**
	 * Function for order status column
	 *
	 * @since 1.0.0
	 * @param array $items Order Data.
	 */
	public function column_order_status( $items ) {
		foreach ( $items as $key => $value ) {
			$display_orders = $value->get_data();
			$order_id       = $display_orders['order_id'];
			$status         = get_post_meta( $order_id, '_onbuy_order_status', true );
			echo '<b>' . esc_attr( $status ) . '</b>';
			break;
		}
	}

	/**
	 * Function for Edit order column
	 *
	 * @since 1.0.0
	 * @param array $items Order Data.
	 */
	public function column_action( $items ) {
		foreach ( $items as $key => $value ) {
			$display_orders = $value->get_data();
			$woo_order_url  = get_edit_post_link( $display_orders['order_id'], '' );
			echo '<a href="' . esc_url( $woo_order_url ) . '" target="#">' . esc_html( __( 'Edit', 'woocommerce-onbuy-integration' ) ) . '</a>';
			break;
		}
	}

	/**
	 * Function for customer name column
	 *
	 * @since 1.0.0
	 * @param array $items Order Data.
	 */
	public function column_customer_name( $items ) {
		foreach ( $items as $key => $value ) {
			$display_orders = $value->get_data();
			$order_id       = $display_orders['order_id'];
			$details        = wc_get_order( $order_id );
			$details        = $details->get_data();
			echo '<b>' . esc_attr( $details['billing']['first_name'] ) . '</b>';
			break;
		}
	}

	/**
	 * Associative array of columns
	 *
	 * @since 1.0.0
	 */
	public function get_columns() {
		$columns = array(
			'id'             => __( 'WooCommerce Order', 'woocommerce-onbuy-integration' ),
			'name'           => __( 'Product Name', 'woocommerce-onbuy-integration' ),
			'onbuy_order_id' => __( 'Onbuy Order ID', 'woocommerce-onbuy-integration' ),
			'customer_name'  => __( 'Customer Name', 'woocommerce-onbuy-integration' ),
			'order_status'   => __( 'Order Status', 'woocommerce-onbuy-integration' ),
			'action'         => __( 'Action', 'woocommerce-onbuy-integration' ),
		);
		$columns = apply_filters( 'ced_onbuy_orders_columns', $columns );
		return $columns;
	}

	/**
	 * Columns to make sortable.
	 *
	 * @since 1.0.0
	 */
	public function getSortableColumns() {
		$sortable_columns = array();
		return $sortable_columns;
	}

	/**
	 * Render html content
	 *
	 * @since 1.0.0
	 */
	public function renderHTML() {
		?>
		<div class="ced_onbuy_wrap ced_onbuy_wrap_extn">
			<div class="ced_onbuy_setting_header ">
				<?php
				$shop_id = isset( $_GET['shop_id'] ) ? sanitize_text_field( wp_unslash( $_GET['shop_id'] ) ) : '';
				 echo '<button  class="button-primary" id="ced_onbuy_fetch_orders" data-id="' . esc_attr( $shop_id ) . '" >' . esc_html( __( 'Fetch Orders', 'woocommerce-onbuy-integration' ) ) . '</button>';
				?>
			</div>
			<div id="post-body" class="metabox-holder columns-2">
				<div id="">
					<div class="meta-box-sortables ui-sortable">
						<form method="post">
							<?php
							$this->display();
							?>
						</form>
					</div>
				</div>
				<div class="clear"></div>
			</div>
		</div>
		<?php
	}

	/**
	 * Function to get all the orders
	 *
	 * @since 1.0.0
	 * @param      int $per_page    Results per page.
	 * @param      int $page_number   Page number.
	 */
	public function cedonbuyOrders( $per_page, $page_number = 1 ) {
		global $wpdb;
		$shop_id = isset( $_GET['shop_id'] ) ? sanitize_text_field( wp_unslash( $_GET['shop_id'] ) ) : '';
		$offset  = ( $page_number - 1 ) * $per_page;

		$orders_post_id = $wpdb->get_results( $wpdb->prepare( "SELECT `post_id` FROM $wpdb->postmeta WHERE `meta_key`=%s AND `meta_value`=%d  group by `post_id` DESC LIMIT %d OFFSET %d", 'ced_onbuy_order_shop_id', $shop_id, $per_page, $offset ), 'ARRAY_A' );

		foreach ( $orders_post_id as $key => $value ) {
			$post_id        = isset( $value['post_id'] ) ? $value['post_id'] : '';
			$post_details   = wc_get_order( $post_id );
			$order_detail[] = $post_details->get_items();
		}
		$order_detail = isset( $order_detail ) ? $order_detail : '';
		return( $order_detail );
	}
}

$ced_onbuy_orders_obj = new Ced_Onbuy_List_Orders();
$ced_onbuy_orders_obj->prepareItems();
