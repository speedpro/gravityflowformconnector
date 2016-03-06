<?php
/*
Plugin Name: Gravity Flow Form Connector
Plugin URI: http://gravityflow.io
Description: Form Connector Extension for Gravity Flow.
Version: 1.0.1
Author: Steve Henty
Author URI: http://gravityflow.io
License: GPL-3.0+

------------------------------------------------------------------------
Copyright 2015-2016 Steven Henty

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define( 'GRAVITY_FLOW_FORM_CONNECTOR_VERSION', '1.0.1' );
define( 'GRAVITY_FLOW_FORM_CONNECTOR_EDD_ITEM_NAME', 'Form Connector' );

add_action( 'gravityflow_loaded', array( 'Gravity_Flow_Form_Connector_Bootstrap', 'load' ), 1 );

class Gravity_Flow_Form_Connector_Bootstrap {

	public static function load() {

		require_once( 'includes/class-step-new-entry.php' );
		require_once( 'includes/class-step-update-entry.php' );

		Gravity_Flow_Steps::register( new Gravity_Flow_Step_New_Entry() );
		Gravity_Flow_Steps::register( new Gravity_Flow_Step_Update_Entry() );

		require_once( 'class-form-connector.php' );

		gravity_flow_form_connector();
	}
}

function gravity_flow_form_connector() {
	if ( class_exists( 'Gravity_Flow_Form_Connector' ) ) {
		return Gravity_Flow_Form_Connector::get_instance();
	}
}


add_action( 'admin_init', 'gravityflow_form_connector_edd_plugin_updater', 0 );

function gravityflow_form_connector_edd_plugin_updater() {

	if ( ! function_exists( 'gravity_flow_form_connector' ) ) {
		return;
	}

	$gravity_flow_form_connector = gravity_flow_form_connector();
	if ( $gravity_flow_form_connector ) {
		$settings = $gravity_flow_form_connector->get_app_settings();

		$license_key = trim( rgar( $settings, 'license_key' ) );

		$edd_updater = new Gravity_Flow_EDD_SL_Plugin_Updater( GRAVITY_FLOW_EDD_STORE_URL, __FILE__, array(
			'version'   => GRAVITY_FLOW_FORM_CONNECTOR_VERSION,
			'license'   => $license_key,
			'item_name' => GRAVITY_FLOW_FORM_CONNECTOR_EDD_ITEM_NAME,
			'author'    => 'Steven Henty',
		) );
	}

}
