<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, validate input value, insert in database and
 * hooks for how to enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Contact_Form
 * @subpackage Contact_Form/public
 */
class Contact_Form_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The data string for saving in database.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The data string.
	 */
	private $data = array();

	/**
	 * The display boolean to print or return output
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      boolean
	 */
	private $display = true;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * The Contact_Form_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/contact-form-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * The Contact_Form_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name.'_validate', plugin_dir_url( __FILE__ ) . 'js/jquery.validate.min.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/contact-form-public.js', array( 'jquery' ), $this->version, false );

		wp_localize_script( $this->plugin_name, 'ajax_object',array( 'ajaxurl' => admin_url( 'admin-ajax.php')));

	}


	/**
	 * Display public facing partials
	 *
	 * @since    1.0.0
	 */
	public function footer_form_display() {

		require_once plugin_dir_path( __FILE__ ) . '/partials/contact-form-public-display.php';

	}


	/**
	 * If JavaScript is disabled. form will be submitted to this virtual page.
	 *
	 * @since    1.0.0
	 */
	public function init_virtual_page(){
		if (isset($_GET['plugin_page']) && !empty($_GET['plugin_page']) && ("contactform" == $_GET['plugin_page'])) {
				$output = "";
				$msg_color = "";
				$output_array = array();
				$this->display = false;

				$output .= "<html><body><div style='max-width:1024px; width:100%; margin: 0 auto; text-align:center;'>";
				$output .= "<h1><a href='".esc_url( home_url( '/' )) ."'>".get_bloginfo()."</a></h1>";
				$output .= "<h3>Enable JavaScript on your browser</h3>";

				$output_array = $this->submit_contact_form();
				if(!empty($output_array)){
					$msg_color = ($output_array["status"] == "failed") ? "#ff0000":"#008000";
					foreach($output_array as $key => $value){
						if($key !== "status")
							$output .= "<div style='color:".$msg_color."'>".$value."</div>";
					}
				}

				$output .= "</div></body></html>";


				print_r($output);
				die();
		}
		return true;
	}


	/**
	 * Ajax submit contact form
	 *
	 * @since    1.0.0
	 */
	public function submit_contact_form() {

		global $wpdb;
		$table_name = $wpdb->prefix . 'shift_studio_contact_form';

		$validate_message = array();
		$validate_message['status'] = 'failed';

		/**
		 * Server side validation. Validate submitted data and prepare for insert.
		 */
		$validate_message = $this->validate_input_values();

		if(empty($validate_message)){
			$format= array( '%s', '%s', '%s');

			if( $wpdb->insert( $table_name, $this->data, $format ) ){
				$validate_message['status'] = 'passed';
	            $validate_message[] = 'Your request is submitted successfully';
			}
	        else{
				$validate_message[]= 'We are unable to process your request. Please try later';
			}

		}

		if($this->display == true){
			print_r(json_encode($validate_message));
			die();
		}else{
			return $validate_message;
		}

	}


	/**
	 * Validate input in contact form
	 * Return array
	 * @since    1.0.0
	 */
	protected function validate_input_values() {

		$post_array = $_POST;

		$output_message = array();

		//Name field validation
		if(isset($post_array['name-sscf']) && !empty($post_array['name-sscf'])){
			if(strlen($post_array['name-sscf']) >= 2)
				$this->data['name'] = filter_var($post_array['name-sscf'], FILTER_SANITIZE_STRING);
			else
				$output_message[] = 'Name should be atleast two charater';
		}else{
			$output_message[] = 'Please enter your name';
		}

		//Email field validation
		if(isset($post_array['email-sscf']) && !empty($post_array['email-sscf'])){
			if(filter_var($post_array['email-sscf'], FILTER_VALIDATE_EMAIL))
				$this->data['email'] = filter_var($post_array['email-sscf'], FILTER_SANITIZE_EMAIL);
			else
				$output_message[] = 'Enter valid email';
		}else{
			$output_message[] = 'Please enter your email';
		}

		//Description field validation
		if(isset($post_array['description-sscf']) && !empty($post_array['description-sscf'])){
			$this->data['description'] = filter_var($post_array['description-sscf'], FILTER_SANITIZE_STRING);
		}else{
			$this->data['description'] = "";
		}

		if(!empty($output_message)){
			$output_message['status'] = 'failed';
		}

		return $output_message;
	}


}
