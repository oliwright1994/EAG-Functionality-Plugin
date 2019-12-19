<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/oliwright1994
 * @since             1.0.0
 * @package           Eag_functionality
 *
 * @wordpress-plugin
 * Plugin Name:       EAG Functionality Plugin
 * Plugin URI:        https://github.com/oliwright1994/EAG-Functionality-Plugin
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Oliver Wright
 * Author URI:        https://github.com/oliwright1994
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       eag_functionality
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'EAG_FUNCTIONALITY_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-eag_functionality-activator.php
 */
function activate_eag_functionality() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-eag_functionality-activator.php';
	Eag_functionality_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-eag_functionality-deactivator.php
 */
function deactivate_eag_functionality() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-eag_functionality-deactivator.php';
	Eag_functionality_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_eag_functionality' );
register_deactivation_hook( __FILE__, 'deactivate_eag_functionality' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-eag_functionality.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_eag_functionality() {

	$plugin = new Eag_functionality();
	$plugin->run();

}
run_eag_functionality();

add_filter( 'template_include', 'var_template_include', 1000 );
function var_template_include( $t ){
    $GLOBALS['current_theme_template'] = basename($t);
    return $t;
}


/**
 * registers a new menu called Secondary Nav that is used in the header
*/
function register_secondary_menu() {
	register_nav_menu('nav-secondary',__( 'Secondary Nav' ));
	}
	add_action( 'init', 'register_secondary_menu' );


	/**
	 * Register a new widget for the sidebar called Contact Info
	 */
	class Contact_Info_Sidebar extends WP_Widget
{
	/**
	 * Sets up the widgets name etc
	 */
	public function __construct()
	{
		$widget_ops = array(
			'classname' => 'contact_info_widget_sidebar',
			'description' => 'Customize contact info',
		);
		parent::__construct('contact_info_sidebar', 'Contact Info Sidebar', $widget_ops);
	}
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget($args, $instance)
	{
		echo $args['before_widget'];
		echo '<h1 class="contact-info-widget-title ">Contact Information </h1>';
		echo '<h2 class="contact-widget-subheading">Email:</h2>';
		echo '<a href="mailto:' . $instance['email'] . '" class="contact-widget-email">' . $instance['email'] . '</a>';
		if ($instance['phone']) {
			echo '<h2 class="contact-widget-subheading">Phone:</h2>';
			echo '<a class="contact-widget-phonenumber" href="tel:'.$instance['phone'].'">'.$instance['phone'].'</a>';
		}
		echo '<br>';
		echo '<h2 class="contact-widget-subheading">Address:</h2>';
		echo '<p class="contact-widget-address-body">'. nl2br($instance['address']) . '</p>';
		echo $args['after_widget'];
	}
	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form($instance)
	{
		$email = !empty($instance['email']) ? $instance['email'] : null;
		?>
	<p>
		<label for="<?php echo esc_attr($this->get_field_id('email')); ?>"><?php esc_attr_e('Email:', 'text_domain'); ?></label>
		<input class="widefat" placeholder="Enter email here..." id="<?php echo esc_attr($this->get_field_id('email')); ?>" name="<?php echo esc_attr($this->get_field_name('email')); ?>" type="text" value="<?php echo esc_attr($email); ?>">
	</p>
	<?php
	$phone = !empty($instance['phone']) ? $instance['phone'] : null;
	?>
	<p>
		<label for="<?php echo esc_attr($this->get_field_id('phone')); ?>"><?php esc_attr_e('Phone Number(optional):', 'text_domain'); ?></label>
		<input class="widefat" placeholder="Enter phone number here..." id="<?php echo esc_attr($this->get_field_id('phone')); ?>" name="<?php echo esc_attr($this->get_field_name('phone')); ?>" type="tel" value="<?php echo esc_attr($phone); ?>">
	</p>

	<?php
	$address = !empty($instance['address']) ? $instance['address'] : null;
	?>
	<p>
		<label for="<?php echo esc_attr($this->get_field_id('address')); ?>"><?php esc_attr_e('Address:', 'text_domain'); ?></label>
		<textarea class="widefat" placeholder="Enter address here..." id="<?php echo esc_attr($this->get_field_id('address')); ?>" name="<?php echo esc_attr($this->get_field_name('address')); ?>"  value="<?php echo esc_attr($address); ?>" rows="6"><?php echo esc_attr($address); ?></textarea>
	</p>
<?php
}
/**
 * Sanitize widget form values as they are saved.
 *
 * @see WP_Widget::update()
 *
 * @param array $new_instance Values just sent to be saved.
 * @param array $old_instance Previously saved values from database.
 *
 * @return array Updated safe values to be saved.
 */
public function update($new_instance, $old_instance)
{
	$instance = array();
	$instance['email'] = (!empty($new_instance['email'])) ? sanitize_text_field($new_instance['email']) : '';
	$instance['phone'] = (!empty($new_instance['phone'])) ? sanitize_text_field($new_instance['phone']) : '';
	$instance['address'] = (!empty($new_instance['address'])) ? sanitize_textarea_field($new_instance['address']) : '';
	return $instance;
}
}
add_action('widgets_init', function () {
	register_widget('Contact_Info_Sidebar');
});


/**
 * Stops WP enqueing the WooCommerce stylesheet
 */
add_filter( 'woocommerce_enqueue_styles', '__return_false' );


/**
 * Prevents a customer being able to add more that 1 of a given report to a cart
 */
add_filter( 'woocommerce_is_purchasable', 'disable_add_to_cart_if_product_is_in_cart', 10, 2 );
function disable_add_to_cart_if_product_is_in_cart ( $is_purchasable, $product ){
    // Loop through cart items checking if the product is already in cart
    foreach ( WC()->cart->get_cart() as $cart_item ){
        if( $cart_item['data']->get_id() == $product->get_id() ) {
            return false;
        }
    }
    return $is_purchasable;
}

add_filter( 'woocommerce_add_to_cart_validation', 'limit_cart_items_from_category', 10, 3 );
function limit_cart_items_from_category ( $passed, $product_id, $quantity ){

    // Check quantity and display notice
    if( $quantity > 1){
        wc_add_notice( __('Only one item quantity allowed for this product', 'woocommerce' ), 'error' );
        return false;
    }

    // Loop through cart items checking if the product is already in cart
    foreach ( WC()->cart->get_cart() as $cart_item ){
        if( $cart_item['data']->get_id() == $product_id) {
            wc_add_notice( __('This product is already in cart (only one item is allowed).', 'woocommerce' ), 'error' );
            return false;
        }
    }
    return $passed;
}


/**
 * Defaults new WC products to be sold individually
 */
function default_no_quantities( $individually, $product ){
	$individually = true;
	return $individually;
	}
	add_filter( 'woocommerce_is_sold_individually', 'default_no_quantities', 10, 2 );

/**
 * Removes order notes field from WC checkout
 */

add_filter( 'woocommerce_enable_order_notes_field', '__return_false', 9999 );


/**
 * Add custom fields to WC products - author and insitution
 */
function eag_create_product_custom_fields() {

	// Author
 $author_args = array(
 'id' => 'eag_author',
 'label' => __( 'Author', 'eag' ),
 'class' => 'eag-custom-field',
 'desc_tip' => false,
 );
 woocommerce_wp_text_input( $author_args );

	// Institution
	$institution_args = array(
 'id' => 'eag_institution',
 'label' => __( 'Institution (if applicable)', 'eag' ),
 'class' => 'eag-custom-field',
 );
 woocommerce_wp_text_input( $institution_args );

}
add_action( 'woocommerce_product_options_general_product_data', 'eag_create_product_custom_fields' );

function eag_save_custom_field( $post_id ) {
 $product = wc_get_product( $post_id );

 $author = isset( $_POST['eag_author'] ) ? $_POST['eag_author'] : '';
 $product->update_meta_data( 'eag_author', sanitize_text_field( $author ) );

 $institution = isset( $_POST['eag_institution'] ) ? $_POST['eag_institution'] : '';
 $product->update_meta_data( 'eag_institution', sanitize_text_field( $institution ) );


 $product->save();
}
add_action( 'woocommerce_process_product_meta', 'eag_save_custom_field' );


/**
 * Redirects users from the 'register' page to 'my-account' if they are logged in
 */
add_action( 'template_redirect', 'redirect_logged_in_users_from_register_page' );

function redirect_logged_in_users_from_register_page() {

if ( is_page('register') && is_user_logged_in() ) {

	$my_account_url = get_permalink( get_page_by_path( 'my-account' ) );

	wp_redirect( $my_account_url, 301 );
  exit;
    }
}


/**
 * Edits WC checkout field labels
 */
add_filter( 'woocommerce_checkout_fields' , 'eag_customize_checkout_fields' );

function eag_customize_checkout_fields($fields) {
	     $fields['billing']['billing_address_2']['label'] = 'Adress Details';
	 	 $fields['billing']['billing_address_2']['placeholder'] = 'Apartment, unit, suite etc.';
		 return $fields;
}


/**
 * Customize the columns of the 'downloads' table in my-account > downloads
 */
function filter_woocommerce_account_downloads_columns( $download_table_columns ) {
	unset($download_table_columns['download-expires']);
	unset($download_table_columns['download-remaining']);

		$download_table_columns['download-file'] = 'Download File';
	$download_table_columns['view_report'] = 'View Report';

    return $download_table_columns;
};

// add the filter
add_filter( 'woocommerce_account_downloads_columns', 'filter_woocommerce_account_downloads_columns', 10, 1 );


/**
 * Edit the tabs on the 'my-account' page
 */
// Remove account tabs
add_filter( 'woocommerce_account_menu_items', 'eag_remove_address_my_account', 999 );

function eag_remove_address_my_account( $items ) {
	$removeTabs = array('edit-address', 'dashboard');

	foreach($removeTabs as $tab) {
 	  unset($items[$tab]);
}
	return $items;
}

// Rename account tabs
add_filter( 'woocommerce_account_menu_items', 'eag_reorder_my_account_tabs', 999 );

function eag_reorder_my_account_tabs( $items ) {
    $neworder = array(
        'downloads'          => __( 'Your Reports', 'woocommerce' ),
        'orders'             => __( 'Orders', 'woocommerce' ),
        'edit-account'       => __( 'Account Details', 'woocommerce' ),
        'customer-logout'    => __( 'Logout', 'woocommerce' ),
    );
    return $neworder;
}

/**
 * Adds billing information to customer registration
 */

if( !is_admin() )
{
    // Function to check starting char of a string
    function startsWith($haystack, $needle)
    {
        return $needle === '' || strpos($haystack, $needle) === 0;
    }

    // Custom function to display the Billing Address form to registration page
    function my_custom_function()
    {
        global $woocommerce;
        $checkout = $woocommerce->checkout();
		$checkout_billing_fields = $checkout->checkout_fields['billing'];
			unset($checkout_billing_fields['billing_email']);
			unset($checkout_billing_fields['billing_phone']);
			unset($checkout_billing_fields['billing_first_name']);
			unset($checkout_billing_fields['billing_last_name']);
		$checkout_billing_fields['billing_company']['required'] = 1;
		$checkout_billing_fields['billing_company']['label'] = 'Company/Organisation';



        ?>
            <h3><?php _e( 'Address', 'woocommerce' ); ?></h3>
        <?php

        foreach ($checkout_billing_fields as $key => $field) :
            woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
        endforeach;
    }
    add_action('woocommerce_register_form','my_custom_function');


    // Custom function to save Usermeta or Billing Address of registered user
    function save_address($user_id)
    {
        global $woocommerce;
        $address = $_POST;

        foreach ($address as $key => $field) :
            if(startsWith($key,'billing_'))
            {
                // Condition to add firstname and last name to user meta table
                if($key == 'billing_first_name' || $key == 'billing_last_name')
                {
                    $new_key = explode('billing_',$key);
                    update_user_meta( $user_id, $new_key[1], $_POST[$key] );
                }
                update_user_meta( $user_id, $key, $_POST[$key] );
            }
        endforeach;
    }
    add_action('woocommerce_created_customer','save_address');


    // Registration page billing address form Validation
    function custom_validation()
    {
        global $woocommerce;
        $address = $_POST;

        foreach ($address as $key => $field) :

            // Validation: Required fields
            if(startsWith($key,'billing_'))
            {

                if($key == 'billing_country' && $field == '')
                {
                    $woocommerce->add_error( '' . __( 'ERROR', 'woocommerce' ) . ': ' . __( 'Please select a country.', 'woocommerce' ) );
                }

                if($key == 'billing_first_name' && $field == '')
                {
                    $woocommerce->add_error( '' . __( 'ERROR', 'woocommerce' ) . ': ' . __( 'Please enter first name.', 'woocommerce' ) );
                }

                if($key == 'billing_last_name' && $field == '')
                {
                    $woocommerce->add_error( '' . __( 'ERROR', 'woocommerce' ) . ': ' . __( 'Please enter last name.', 'woocommerce' ) );
                }

                if($key == 'billing_address_1' && $field == '')
                {
                    $woocommerce->add_error( '' . __( 'ERROR', 'woocommerce' ) . ': ' . __( 'Please enter address.', 'woocommerce' ) );
                }

                if($key == 'billing_city' && $field == '')
                {
                    $woocommerce->add_error( '' . __( 'ERROR', 'woocommerce' ) . ': ' . __( 'Please enter city.', 'woocommerce' ) );
                }

                if($key == 'billing_state' && $field == '')
                {
                    $woocommerce->add_error( '' . __( 'ERROR', 'woocommerce' ) . ': ' . __( 'Please enter state.', 'woocommerce' ) );
                }

                if($key == 'billing_postcode' && $field == '')
                {
                    $woocommerce->add_error( '' . __( 'ERROR', 'woocommerce' ) . ': ' . __( 'Please enter a postcode.', 'woocommerce' ) );
                }

                if($key == 'billing_email' && $field == '')
                {
                    $woocommerce->add_error( '' . __( 'ERROR', 'woocommerce' ) . ': ' . __( 'Please enter billing email address.', 'woocommerce' ) );
                }

                if($key == 'billing_phone' && $field == '')
                {
                    $woocommerce->add_error( '' . __( 'ERROR', 'woocommerce' ) . ': ' . __( 'Please enter phone number.', 'woocommerce' ) );
                }
            }

        endforeach;
    }
    add_action('register_post','custom_validation');

}

/**
 * Adds customs fields to user registration
 */

function eag_get_account_fields() {
	return apply_filters( 'eag_account_fields', array(
			'user_company_authorised_and_regulated' => array(
					'type'        => 'checkbox',
					'label'       => __( 'My company/organisation is authorised or regulated by a finacial or prudential regulator' ),
					'placeholder' => __( '' ),
			),'user_company_net_of_5m' => array(
					'type'        => 'checkbox',
					'label'       => __( 'My company/organisation or holding company has called up share capital or net assets of at least £5 million (or its equivalent in any other currency at the relevant time)' ),
					'placeholder' => __( '' ),
			),'user_company_2_of_criteria' => array(
					'type'        => 'checkbox',
					'label'       => __( 'My company/organisation or holding company meets two of the following criteria
a balance sheet total of EUR 12,500,000;
a net turnover of EUR 25,000,000;
an average number of employees during the year of 250;' ),
					'placeholder' => __( '' ),
			),'user_company_partnership_assets_5m' => array(
					'type'        => 'checkbox',
					'label'       => __( 'My organisation is a partnership which has (or has had at any time during the previous two years) net assets of at least £5 million (or its equivalent in any other currency at the relevant time) and calculated in the case of a limited partnership without deducting loans owing to any of the partners;' ),
					'placeholder' => __( '' ),
			),'user_trustee_assets_10m' => array(
					'type'        => 'checkbox',
					'label'       => __( 'I am a trustee of a trust (other than an occupational pension scheme, SSAS, personal pension scheme or stakeholder pension scheme) which has (or has had at any time during the previous two years) assets of at least £10 million (or its equivalent in any other currency at the relevant time) calculated by aggregating the value of the cash and designated investments forming part of the trust\'s assets, but before deducting its liabilities;
' ),
					'placeholder' => __( '' ),
			),
	'user_trustee_pension_scheme' => array(
					'type'        => 'checkbox',
					'label'       => __( 'I am a trustee of an occupational pension scheme or SSAS, or a trustee or operator of a personal pension scheme or stakeholder pension scheme where the scheme has (or has had at any time during the previous two years)
at least 50 members; and
assets under management of at least £10 million (or its equivalent in any other currency at the relevant time).' ),
					'placeholder' => __( '' ),
			),
	'user_is_regional_national_government' => array(
					'type'        => 'checkbox',
					'label'       => __( 'My organisation is a national or regional government, including a public body that manages public debt at national or regional level, a central bank, an international or supranational institution (such as the World Bank, the IMF, the ECB, the EIB) or another similar international organisation' ),
					'placeholder' => __( '' ),
			),
	) );
}

add_filter('woocommerce_save_account_details_required_fields', 'wc_save_account_details_required_fields' );
function wc_save_account_details_required_fields( $required_fields ){
	unset( $required_fields['account_display_name'] );
	return $required_fields;
}

add_action( 'woocommerce_register_form_start', 'bbloomer_add_name_woo_account_registration' );

function bbloomer_add_name_woo_account_registration() {
	?>

	<p class="form-row form-row-first">
	<label for="reg_billing_first_name"><?php _e( 'First name', 'woocommerce' ); ?> <span class="required">*</span></label>
	<input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name" value="<?php if ( ! empty( $_POST['billing_first_name'] ) ) esc_attr_e( $_POST['billing_first_name'] ); ?>" />
	</p>

	<p class="form-row form-row-last">
	<label for="reg_billing_last_name"><?php _e( 'Last name', 'woocommerce' ); ?> <span class="required">*</span></label>
	<input type="text" class="input-text" name="billing_last_name" id="reg_billing_last_name" value="<?php if ( ! empty( $_POST['billing_last_name'] ) ) esc_attr_e( $_POST['billing_last_name'] ); ?>" />
	</p>

	<div class="clear"></div>

	<?php
}

///////////////////////////////
// 2. VALIDATE FIELDS

add_filter( 'woocommerce_registration_errors', 'bbloomer_validate_name_fields', 10, 3 );

function bbloomer_validate_name_fields( $errors, $username, $email ) {
	if ( isset( $_POST['billing_first_name'] ) && empty( $_POST['billing_first_name'] ) ) {
			$errors->add( 'billing_first_name_error', __( '<strong>Error</strong>: First name is required!', 'woocommerce' ) );
	}
	if ( isset( $_POST['billing_last_name'] ) && empty( $_POST['billing_last_name'] ) ) {
			$errors->add( 'billing_last_name_error', __( '<strong>Error</strong>: Last name is required!.', 'woocommerce' ) );
	}
	return $errors;
}

///////////////////////////////
// 3. SAVE FIELDS

add_action( 'woocommerce_created_customer', 'bbloomer_save_name_fields' );

function bbloomer_save_name_fields( $customer_id ) {
	if ( isset( $_POST['billing_first_name'] ) ) {
			update_user_meta( $customer_id, 'billing_first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
			update_user_meta( $customer_id, 'first_name', sanitize_text_field($_POST['billing_first_name']) );
	}
	if ( isset( $_POST['billing_last_name'] ) ) {
			update_user_meta( $customer_id, 'billing_last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
			update_user_meta( $customer_id, 'last_name', sanitize_text_field($_POST['billing_last_name']) );
	}

}

function eag_get_userdata( $user_id, $key ) {
	if ( ! eag_is_userdata( $key ) ) {
			return get_user_meta( $user_id, $key, true );
	}

	$userdata = get_userdata( $user_id );

	if ( ! $userdata || ! isset( $userdata->{$key} ) ) {
			return '';
	}

	return $userdata->{$key};
}

function eag_get_edit_user_id() {
return isset( $_GET['user_id'] ) ? (int) $_GET['user_id'] : get_current_user_id();
}

function eag_print_user_frontend_fields() {
	$fields = eag_get_account_fields();
 $is_user_logged_in = is_user_logged_in();
?>

 <h3><?php _e( 'Additional Information', 'woocommerce' ); ?></h3>

<?php
	foreach ( $fields as $key => $field_args ) {
		$value = null;

	if ( $is_user_logged_in ) {
		$user_id = eag_get_edit_user_id();
		$value   = eag_get_userdata( $user_id, $key );
	}

	$value = isset( $field_args['value'] ) ? $field_args['value'] : $value;

	woocommerce_form_field( $key, $field_args, $value );
	}
}

add_action( 'woocommerce_register_form', 'eag_print_user_frontend_fields', 10 ); // register form

function eag_print_user_admin_fields() {
$fields = eag_get_account_fields();
?>
<h2><?php _e( 'Additional Information' ); ?></h2>
<table class="form-table" id="eag-additional-information">
	<tbody>
	<?php foreach ( $fields as $key => $field_args ) { ?>
		<?php
		if ( ! empty( $field_args['hide_in_admin'] ) ) {
			continue;
		}

		$user_id = eag_get_edit_user_id();
		$value   = eag_get_userdata( $user_id, $key );
		?>
		<tr>
			<th>
				<label for="<?php echo $key; ?>"><?php echo $field_args['label']; ?></label>
			</th>
			<td>
				<?php $field_args['label'] = false; ?>
				<?php woocommerce_form_field( $key, $field_args, $value ); ?>
			</td>
		</tr>
	<?php } ?>
	</tbody>
</table>
<?php
}

function eag_is_userdata( $key ) {
	$userdata = array(
			'user_pass',
			'user_login',
			'user_nicename',
			'user_url',
			'user_email',
			'display_name',
			'nickname',
			'first_name',
			'last_name',
			'description',
			'rich_editing',
			'user_registered',
			'role',
			'jabber',
			'aim',
			'yim',
			'show_admin_bar_front',
	);

	return in_array( $key, $userdata );
}


function eag_save_account_fields( $customer_id ) {
	$fields = eag_get_account_fields();
	$sanitized_data = array();

	foreach ( $fields as $key => $field_args ) {

			$sanitize = isset( $field_args['sanitize'] ) ? $field_args['sanitize'] : 'wc_clean';
			$value    = isset( $_POST[ $key ] ) ? call_user_func( $sanitize, $_POST[ $key ] ) : '';

			if ( eag_is_userdata( $key ) ) {
					$sanitized_data[ $key ] = $value;
					continue;
			}

			update_user_meta( $customer_id, $key, $value );
	}

	if ( ! empty( $sanitized_data ) ) {
			$sanitized_data['ID'] = $customer_id;
			wp_update_user( $sanitized_data );
	}
}

function eag_confirm_password_email_match(){
		$email1 = $_POST['email'];
	$email2 = $_POST['email_confirmation'];
		if ( $email2 !== $email1 ) {
			wc_add_notice( 'Your email addresses do not match', 'error' );
	}
}
add_action( 'woocommerce_created_customer', 'eag_confirm_password_email_match' ); // register/checkout
add_action( 'woocommerce_created_customer', 'eag_save_account_fields' ); // register/checkout
add_action( 'personal_options_update', 'eag_save_account_fields' ); // edit own account admin
add_action( 'edit_user_profile_update', 'eag_save_account_fields' ); // edit other account admin
add_action( 'woocommerce_save_account_details', 'eag_save_account_fields' ); // edit WC account
add_action( 'show_user_profile', 'eag_print_user_admin_fields', 30 ); // admin: edit profile
add_action( 'edit_user_profile', 'eag_print_user_admin_fields', 30 ); // admin: edit other users
add_action( 'woocommerce_edit_account_form', 'eag_print_user_frontend_fields', 10 ); // add fields to my account

function eag_add_post_data_to_account_fields( $fields ) {
	if ( empty( $_POST ) ) {
			return $fields;
	}

	foreach ( $fields as $key => $field_args ) {
			if ( empty( $_POST[ $key ] ) ) {
					$fields[ $key ]['value'] = '';
					continue;
			}

			$fields[ $key ]['value'] = $_POST[ $key ];
	}

	return $fields;
}

add_filter( 'eag_account_fields', 'eag_add_post_data_to_account_fields', 10, 1 );

function eag_validate_user_frontend_fields( $errors ) {
	$fields = eag_get_account_fields();

	foreach ( $fields as $key => $field_args ) {
			if ( empty( $field_args['required'] ) ) {
					continue;
			}

			if ( ! isset( $_POST['register'] ) && ! empty( $field_args['hide_in_account'] ) ) {
					continue;
			}

			if ( isset( $_POST['register'] ) && ! empty( $field_args['hide_in_registration'] ) ) {
					continue;
			}

			if ( empty( $_POST[ $key ] ) ) {
					$message = sprintf( __( '%s is a required field.' ), '<strong>' . $field_args['label'] . '</strong>' );
					$errors->add( $key, $message );
			}
	}

	return $errors;
}

add_filter( 'woocommerce_registration_errors', 'eag_validate_user_frontend_fields', 10 );
add_filter( 'woocommerce_save_account_details_errors', 'eag_validate_user_frontend_fields', 10 );

/**
 * Rmove text editor from wc add new product
 */

function remove_product_editor() {
  remove_post_type_support( 'product', 'editor' );
}
add_action( 'init', 'remove_product_editor' );
