<?php
/**
 * Quick Stock Management Admin Class
 *
 * @package QuickStockManagement
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'QSM_Admin' ) ) :

/**
 * QSM_Admin Class.
 */
class QSM_Admin {

    /**
     * Constructor.
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'wp_ajax_update_quick_stock_status', array( $this, 'handle_stock_update' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    /**
     * Add admin menu page.
     */
    public function add_admin_menu() {
        add_submenu_page(
            'edit.php?post_type=product',
            __( 'Quick Stock Management', 'quick-stock-management' ),
            __( 'Quick Stock Management', 'quick-stock-management' ),
            'manage_woocommerce',
            'quick-stock-management',
            array( $this, 'quick_stock_management_page_content' )
        );
    }

    /**
     * Quick Stock Management page content.
     */
    public function quick_stock_management_page_content() {
        if ( ! current_user_can( 'manage_woocommerce' ) ) {
            wp_die( __( 'You do not have sufficient permissions to access this page.', 'quick-stock-management' ) );
        }

        $products = $this->get_products_for_display();
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <p><?php esc_html_e( 'Quickly manage your WooCommerce product stock status.', 'quick-stock-management' ); ?></p>

            <div id="qsm-product-list">
                <?php if ( ! empty( $products ) ) : ?>
                    <table class="wp-list-table widefat fixed striped products">
                        <thead>
                            <tr>
                                <th scope="col" class="manage-column column-product-name"><span><?php esc_html_e( 'Product Name', 'quick-stock-management' ); ?></span></th>
                                <th scope="col" class="manage-column column-stock-status"><span><?php esc_html_e( 'In Stock', 'quick-stock-management' ); ?></span></th>
                            </tr>
                        </thead>
                        <tbody id="the-list">
                            <?php foreach ( $products as $product ) : ?>
                                <tr id="product-<?php echo esc_attr( $product->ID ); ?>">
                                    <td class="product-name">
                                        <strong><?php echo esc_html( $product->post_title ); ?></strong>
                                    </td>
                                    <td class="stock-status">
                                        <?php
                                        $stock_status = get_post_meta( $product->ID, '_stock_status', true );
                                        $is_checked   = ( 'instock' === $stock_status ) ? 'checked' : '';
                                        $badge_class  = ( 'instock' === $stock_status ) ? 'qsm-badge-instock' : 'qsm-badge-outofstock';
                                        $badge_text   = ( 'instock' === $stock_status ) ? __( 'In Stock', 'quick-stock-management' ) : __( 'Out of Stock', 'quick-stock-management' );
                                        ?>
                                        <label class="qsm-toggle-switch">
                                            <input type="checkbox" class="qsm-stock-toggle" data-product-id="<?php echo esc_attr( $product->ID ); ?>" <?php echo $is_checked; ?> />
                                            <span class="qsm-slider round"></span>
                                        </label>
                                        <span class="qsm-stock-badge <?php echo esc_attr( $badge_class ); ?>"><?php echo esc_html( $badge_text ); ?></span>
                                        <span class="spinner"></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <p><?php esc_html_e( 'No products found.', 'quick-stock-management' ); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    /**
     * Get products for display.
     *
     * @return array
     */
    private function get_products_for_display() {
        $args = array(
            'post_type'      => 'product',
            'post_status'    => array( 'publish', 'private' ),
            'posts_per_page' => -1, // Retrieve all products for now, pagination can be added later.
            'orderby'        => 'title',
            'order'          => 'ASC',
            'meta_query'     => array(
                'relation' => 'OR',
                array(
                    'key'     => '_stock_status',
                    'value'   => 'instock',
                    'compare' => '='
                ),
                array(
                    'key'     => '_stock_status',
                    'value'   => 'outofstock',
                    'compare' => '='
                ),
            ),
        );

        $products_query = new WP_Query( $args );
        $products       = $products_query->posts;

        // Custom sorting: instock first, then outofstock, then alphabetical.
        usort( $products, function( $a, $b ) {
            $status_a = get_post_meta( $a->ID, '_stock_status', true );
            $status_b = get_post_meta( $b->ID, '_stock_status', true );

            if ( $status_a === $status_b ) {
                return strcmp( $a->post_title, $b->post_title );
            }

            if ( 'instock' === $status_a ) {
                return -1;
            }

            return 1;
        } );

        return $products;
    }

    /**
     * Handle AJAX stock update.
     */
    public function handle_stock_update() {
        check_ajax_referer( 'quick_stock_nonce', 'nonce' );

        if ( ! current_user_can( 'manage_woocommerce' ) ) {
            wp_send_json_error( array( 'message' => __( 'You do not have sufficient permissions to perform this action.', 'quick-stock-management' ) ) );
        }

        $product_id   = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
        $stock_status = isset( $_POST['stock_status'] ) ? sanitize_text_field( wp_unslash( $_POST['stock_status'] ) ) : '';

        if ( ! $product_id || ! in_array( $stock_status, array( 'instock', 'outofstock' ), true ) ) {
            wp_send_json_error( array( 'message' => __( 'Invalid product ID or stock status.', 'quick-stock-management' ) ) );
        }

        $product = wc_get_product( $product_id );

        if ( ! $product ) {
            wp_send_json_error( array( 'message' => __( 'Product not found.', 'quick-stock-management' ) ) );
        }

        // Update stock status.
        wc_update_product_stock_status( $product_id, $stock_status );

        wp_send_json_success( array(
            'product_id' => $product_id,
            'new_status' => $stock_status,
            'message'    => __( 'Stock status updated successfully.', 'quick-stock-management' ),
        ) );
    }

    /**
     * Enqueue scripts and styles.
     */
    public function enqueue_scripts() {
        $screen = get_current_screen();

        if ( 'product_page_quick-stock-management' === $screen->id ) {
            wp_enqueue_style( 'qsm-admin-styles', QSM_PLUGIN_URL . 'assets/css/quick-stock-management.min.css', array(), QSM_VERSION );
            wp_enqueue_script( 'qsm-admin-scripts', QSM_PLUGIN_URL . 'assets/js/quick-stock-management.min.js', array( 'jquery' ), QSM_VERSION, true );

            wp_localize_script( 'qsm-admin-scripts', 'qsm_ajax_object', array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( 'quick_stock_nonce' ),
            ) );
        }
    }

}

endif;

return new QSM_Admin();


