<?php
/*
Plugin Name: Reservation Demo
Plugin URI:
Description: Reservation Demo using WooCommerce and WordPress API
Version: 1.0
Author: LWHH
Author URI: https://hasin.me
License: GPLv2 or later
Text Domain: reservation
Domain Path: /languages/
*/

add_action( "plugins_loaded", "reservation_bootstrap" );
function reservation_bootstrap() {
	load_plugin_textdomain( 'akismet', false, dirname( __FILE__ ) . "/languages" );
}

add_action( 'init', 'reservation_register_cpt' );
function reservation_register_cpt() {
	$labels = array(
		"name"          => __( "Reservation", "reservation" ),
		"singular_name" => __( "Reservations", "reservation" ),
	);

	$args = array(
		"label"               => __( "Reservation", "reservation" ),
		"labels"              => $labels,
		"description"         => "",
		"public"              => false,
		"publicly_queryable"  => false,
		"show_ui"             => true,
		"show_in_rest"        => false,
		"rest_base"           => "",
		"has_archive"         => false,
		"show_in_menu"        => true,
		"show_in_nav_menus"   => false,
		"exclude_from_search" => true,
		"capability_type"     => "post",
		"map_meta_cap"        => true,
		"hierarchical"        => false,
		"rewrite"             => array( "slug" => "reservation", "with_front" => true ),
		"query_var"           => true,
		"menu_position"       => 20,
		"menu_icon"           => "dashicons-media-document",
		"supports"            => array( "title" ),
	);

	register_post_type( "reservation", $args );

}

add_action( "wp_enqueue_scripts", "reservation_assets" );
function reservation_assets() {
	$js_files = [
		"reservation-main-js" => [
			'src'          => plugin_dir_url( __FILE__ ) . "/assets/js/reservation.js",
			'dependencies' => [ 'jquery' ],
			'version'      => time(),
			'localize'     => [
				'handle' => 'reservation',
				'data'   => [
					'ajax_url' => admin_url( 'admin-ajax.php' )
				]
			]
		]
	];

	foreach ( $js_files as $handle => $js_file ) {
		wp_enqueue_script( $handle, $js_file['src'], $js_file['dependencies'], $js_file['version'], true );
		if ( isset( $js_file['localize'] ) ) {
			wp_localize_script( $handle, $js_file['localize']['handle'], $js_file['localize']['data'] );
		}
	}


}

add_shortcode( 'reservation_form', 'reservation_form_shortcode' );
function reservation_form_shortcode() {
	$nonce        = wp_nonce_field( 'reservation', 'rn' );
	$strings      = [
		'name' => __( 'Name', 'hestia' ),
		'namep' => __( 'Your Name', 'hestia' ),
		'email'=>__( 'Email', 'hestia' ),
		'emailp'=>__( 'Your Email', 'hestia' ),
		'phone'=>__( 'Phone', 'hestia' ),
		'phonep'=>__( 'Your Phone Number', 'hestia' ),
		'np'=>__( 'Number of Persons', 'hestia' ),
		'np1'=>__( '1 person', 'hestia' ),
		'np2'=>__( '2 persons', 'hestia' ),
		'np3'=>__( '3 persons', 'hestia' ),
		'np4'=>__( '4 persons', 'hestia' ),
		'np5'=>__( '5 persons', 'hestia' ),
		'date'=>__( 'Date', 'hestia' ),
		'datep'=>__( 'Date (dd/mm/yyyy)', 'hestia' ),
		'time'=>__( 'Time', 'hestia' ),
		'timep'=>__( 'Time (hour:minute am/pm)', 'hestia' ),
		'button'=>__( 'Reserve Now', 'hestia' ),
		'success'=>__( 'Complete Payment & Reservation', 'hestia' ),
	];
	$form         = <<<EOD
<div class="reservation_form">
    <h2> Reservation Form</h2>
    <form action="#">
		{$nonce}
        <div class="row mb-4">
            <div class="form-group col-md-4">
                <label for="name" class="label">{$strings['name']}</label>
                <div class="form-field-icon-wrap">
                    <span class="icon ion-android-person"></span>
                    <input placeholder="{$strings['namep']}" type="text" class="form-control" id="name" value="Hasin Hayder">
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="email" class="label">{$strings['email']}</label>
                <div class="form-field-icon-wrap">
                    <span class="icon ion-email"></span>
                    <input placeholder="{$strings['emailp']}" type="email" class="form-control" id="email" value="hasin@leevio.com" >
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="phone" class="label">{$strings['phone']}</label>
                <div class="form-field-icon-wrap">
                    <span class="icon ion-android-call"></span>
                    <input placeholder="{$strings['phonep']}" type="text" class="form-control" id="phone" value="1234">
                </div>
            </div>

            <div class="form-group col-md-4">
                <label for="persons" class="label">{$strings['np']}</label>
                <div class="form-field-icon-wrap">
                    <span class="icon ion-android-arrow-dropdown"></span>
                    <select name="persons" id="persons" class="form-control">
                        <option value="1">{$strings['np1']}</option>
                        <option value="2">{$strings['np2']}</option>
                        <option value="3">{$strings['np3']}</option>
                        <option value="4">{$strings['np4']}</option>
                        <option value="5">{$strings['np5']}</option>
                    </select>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="date" class="label">{$strings['date']}</label>
                <div class="form-field-icon-wrap">
                    <span class="icon ion-calendar"></span>
                    <input placeholder="{$strings['datep']}" type="text" class="form-control" id="date" value="28/08/2019">
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="time" class="label">{$strings['time']}</label>
                <div class="form-field-icon-wrap">
                    <span class="icon ion-android-time"></span>
                    <input placeholder="{$strings['timep']}" type="text" class="form-control" id="time" value="5:45 PM">
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-4">
                <input type="submit" id="reservenow" class="btn btn-primary btn-outline-primary btn-block"
                       value="{$strings['button']}">
                <a href="#" target="_blank" class="btn btn-primary btn-outline-primary btn-block"
                   style="display: none" id="paynow">
					{$strings['success']}
                </a>
            </div>
        </div>
    </form>
</div>
EOD;

	return $form;
}

function reservation_process_data() {

	if ( check_ajax_referer( 'reservation', 'rn' ) ) {
		$name    = sanitize_text_field( $_POST['name'] );
		$email   = sanitize_text_field( $_POST['email'] );
		$persons = sanitize_text_field( $_POST['persons'] );
		$phone   = sanitize_text_field( $_POST['phone'] );
		$date    = sanitize_text_field( $_POST['date'] );
		$time    = sanitize_text_field( $_POST['time'] );

		$data = array(
			'name'    => $name,
			'email'   => $email,
			'phone'   => $phone,
			'persons' => $persons,
			'date'    => $date,
			'time'    => $time
		);
		//print_r( $data );

		$reservation_arguments = array(
			'post_type'   => 'reservation',
			'post_author' => 1,
			'post_date'   => date( 'Y-m-d H:i:s' ),
			'post_status' => 'publish',
			'post_title'  => sprintf( '%s - Reservation for %s persons on %s - %s', $name, $persons, $date . " : " . $time, $email ),
			'meta_input'  => $data
		);

		$reservations = new WP_Query( array(
			'post_type'   => 'reservation',
			'post_status' => 'publish',
			'meta_query'  => array(
				'relation'    => 'AND',
				'email_check' => array(
					'key'   => 'email',
					'value' => $email
				),
				'date_check'  => array(
					'key'   => 'date',
					'value' => $date
				),
				'time_check'  => array(
					'key'   => 'time',
					'value' => $time
				),
			)
		) );
		if ( $reservations->found_posts > 0 ) {
			echo 'duplicate';
		} else {
			$wp_error       = '';
			$reservation_id = wp_insert_post( $reservation_arguments, $wp_error );

			//transient check
			$reservation_count = get_transient( 'res_count' ) ? get_transient( 'res_count' ) : 0;
			//transient check end

			if ( ! $wp_error ) {

				$reservation_count ++;
				set_transient( 'res_count', $reservation_count, 0 );

				$_name      = explode( " ", $name );
				$order_data = array(
					'first_name' => $_name[0],
					'last_name'  => isset( $_name[1] ) ? $_name[1] : '',
					'email'      => $email,
					'phone'      => $phone,
				);
				$order      = wc_create_order();
				$order->set_address( $order_data );
				$order->add_product( wc_get_product( 18 ), 1 );
				$order->set_customer_note( $reservation_id );
				$order->calculate_totals();

				add_post_meta( $reservation_id, 'order_id', $order->get_id() );

				echo $order->get_checkout_payment_url();
			}
		}

	} else {
		echo 'Not allowed';
	}
	die();
}

add_action( 'wp_ajax_reservation', 'reservation_process_data' );
add_action( 'wp_ajax_nopriv_reservation', 'reservation_process_data' );

function reservation_order_status_processing( $order_id ) {
	$order          = wc_get_order( $order_id );
	$reservation_id = $order->get_customer_note();
	if ( $reservation_id ) {
		$reservation = get_post( $reservation_id );
		wp_update_post( array(
			'ID'         => $reservation_id,
			'post_title' => "[Paid] - " . $reservation->post_title
		) );

		add_post_meta( $reservation_id, 'paid', 1 );
	}
}

add_filter( 'woocommerce_order_status_processing', 'reservation_order_status_processing' );


function reservation_change_menu( $menu ) {
	$reservation_count = get_transient( 'res_count' ) ? get_transient( 'res_count' ) : 0;
	if ( $reservation_count > 0 ) {
		$menu[5][0] = "Reservation <span class='awaiting-mod'>{$reservation_count}</span> ";
	}

	return $menu;
}

add_filter( 'add_menu_classes', 'reservation_change_menu' );

function reservation_admin_scripts( $screen ) {
	$_screen = get_current_screen();
	if ( 'edit.php' == $screen && 'reservation' == $_screen->post_type ) {
		delete_transient( 'res_count' );
	}
}

add_action( 'admin_enqueue_scripts', 'reservation_admin_scripts' );