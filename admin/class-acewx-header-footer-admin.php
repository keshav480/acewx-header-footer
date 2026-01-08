<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://acewebx.com
 * @since      1.0.0
 *
 * @package    Acewx_Header_Footer
 * @subpackage Acewx_Header_Footer/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Acewx_Header_Footer
 * @subpackage Acewx_Header_Footer/admin
 * @author     AceWebx Team <Acewebx@gmail.com>
 */
class Acewx_Header_Footer_Admin {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Acewx_Header_Footer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Acewx_Header_Footer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/acewx-header-footer-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style('acewx-select2-css',plugin_dir_url( __FILE__ )  . 'css/select.css',[],	'1.0.0'	);

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Acewx_Header_Footer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Acewx_Header_Footer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/acewx-header-footer-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script('acewx-select2-js',plugin_dir_url( __FILE__ ) . 'js/select.js',['jquery'],'4.1.0',true);
		wp_localize_script($this->plugin_name,'acewx_admin',['nonce' => wp_create_nonce( 'acewx_toggle_nonce' )]);

	}
	/**  
	  Check if Elementor is not active or not installed
	 **/

	public function check_elementor_dependency() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}
		$elementor_plugin = 'elementor/elementor.php';
		if ( is_plugin_active( $elementor_plugin ) ) {
			return;
		}
		$installed_plugins = get_plugins();
		if ( isset( $installed_plugins[ $elementor_plugin ] ) ) {
			$activate_url = wp_nonce_url(
				self_admin_url( 'plugins.php?action=activate&plugin=' . $elementor_plugin ),
				'activate-plugin_' . $elementor_plugin
			);
			add_action( 'admin_notices', function() use ( $activate_url ) {
				echo '<div class="notice notice-warning is-dismissible">
					<p><strong>ACEWX Header Footer</strong> requires <strong>Elementor</strong> plugin. 
					Please <a href="' . esc_url( $activate_url ) . '">activate Elementor</a>.</p>
				</div>';
			});

		} else {
			$install_url = wp_nonce_url(
				self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ),
				'install-plugin_elementor'
			);
			add_action( 'admin_notices', function() use ( $install_url ) {
				echo '<div class="notice notice-error is-dismissible">
					<p><strong>ACEWX Header Footer</strong> requires <strong>Elementor</strong> plugin. 
					<a href="' . esc_url( $install_url ) . '">Install Elementor</a> to continue.</p>
				</div>';
			});
		}
	}
	/**
		 Custom post type for Elementor-based headers and footers
	 */
	public function register_header_footer_cpt() {

		$labels = array(
			'name'                  => _x( 'Ace Header Footers', 'Post Type General Name', 'acewx-header-footer' ),
			'singular_name'         => _x( 'Ace Header Footer', 'Post Type Singular Name', 'acewx-header-footer' ),
			'menu_name'             => __( 'Ace Header Footer', 'acewx-header-footer' ),
			'name_admin_bar'        => __( 'Header Footer', 'acewx-header-footer' ),
			'add_new'               => __( 'Add New', 'acewx-header-footer' ),
			'add_new_item'          => __( 'Add New Header Footer', 'acewx-header-footer' ),
			'edit_item'             => __( 'Edit Header Footer', 'acewx-header-footer' ),
			'new_item'              => __( 'New Header Footer', 'acewx-header-footer' ),
			'view_item'             => __( 'View Header Footer', 'acewx-header-footer' ),
			'search_items'          => __( 'Search Header Footers', 'acewx-header-footer' ),
			'not_found'             => __( 'Not found', 'acewx-header-footer' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'acewx-header-footer' ),
			'all_items'             => __( 'All Header Footers', 'acewx-header-footer' ),
		);
		$args = array(
			'label'                 => __( 'Header Footer', 'acewx-header-footer' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions','elementor' ),
			'hierarchical'          => false,
			'public'                => true,             
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 20,
			'menu_icon'             => 'dashicons-editor-table',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => false,             
			'can_export'            => true,
			'has_archive'           => false,
			'exclude_from_search'   => true,              
			'publicly_queryable'    => true,           
			'capability_type'       => 'post',
			'show_in_rest'          => false,              
		);
		register_post_type( 'acewx_header_footer', $args );
	}
	
	function acewx_set_default_elementor_layout( $post_id, $post, $update ) {
		if ( $update ) {
			return;
		}
		if ( ! did_action( 'elementor/loaded' ) ) {
			return;
		}
 		update_post_meta( $post_id, '_wp_page_template', 'elementor_canvas' );
		update_post_meta( $post_id, '_elementor_hide_title', 'yes' );
	}

	/**
		Add meta box 
	**/
	public function acewx_header_footer_metabox() {
		add_meta_box(
			'acewx_header_display_conditions',
			__( 'Display Conditions', 'acewx-header-footer' ),
			[ $this, 'acewx_render_header_conditions_metabox' ],
			'acewx_header_footer',
			'normal',
			'high'
		);
	}
	/**
		This code for the header and footer show conditions metabox.
	**/
	public function acewx_render_header_conditions_metabox( $post ) {
		wp_nonce_field(
			'acewx_header_conditions_nonce',
			'acewx_header_conditions_nonce_field'
		);
		$display_type  = get_post_meta( $post->ID, '_acewx_display_type', true );
		$display_pages = (array) get_post_meta( $post->ID, '_acewx_display_pages', true );
		$templatetype = get_post_meta($post->ID, '_acewx_hf_type', true);
		if ( empty( $display_type ) ) {
			$display_type = 'entire_site';
		}
		$is_enabled = get_post_meta( $post->ID, '_acewx_hf_enabled', true );
		$is_enabled = ( $is_enabled === '' ) ? '1' : $is_enabled; // default enabled
	
		?>
		<div class="acewx-metabox">
		<p>
			<?php esc_html_e( 'Enable this template', 'acewx-header-footer' ); ?></br>
			<label class="acewx-switch">
					<input type="checkbox"
							name="acewx_hf_enabled"
							value="1"
							<?php checked( $is_enabled, '1' ); ?> />
					<span class="acewx-slider"></span>
			</label>

		</p>

    <p><?php esc_html_e('Display On', 'acewx-header-footer'); ?></p>
    <div class="acewx-radio-group">
        <label>
            <input type="radio" name="acewx_display_type" value="entire_site" <?php checked( $display_type, 'entire_site' ); ?> />
            <?php esc_html_e('Entire Site', 'acewx-header-footer'); ?>
        </label>
        <label>
            <input type="radio" name="acewx_display_type" value="specific_pages" <?php checked( $display_type, 'specific_pages' ); ?> />
            <?php esc_html_e('Specific Pages', 'acewx-header-footer'); ?>
        </label>
    </div>

	<div id="acewx-specific-pages">
			<select id="acewx_display_pages"name="acewx_display_pages[]"multiple style="width:100%; min-height:120px;">
					<?php
					$pages = get_posts([
						'post_type'      => 'page',
						'posts_per_page' => -1,
						'post_status'    => 'publish',
					]);
					foreach ( $pages as $page ) {
						echo '
						<option value="'. esc_html($page->ID) .'"'.selected( in_array( $page->ID, $display_pages ), true, false ).'>'.esc_html( $page->post_title ).'</option>
						';
					}
					?>
				</select>
		</div>

		<p><?php esc_html_e('Template Type', 'acewx-header-footer'); ?></p>

		<select name="acewx_hf_type">
			<option value="header" <?php selected($templatetype, 'header'); ?>><?php esc_html_e('Header', 'acewx-header-footer'); ?></option>
			<option value="footer" <?php selected($templatetype, 'footer'); ?>><?php esc_html_e('Footer', 'acewx-header-footer'); ?></option>
		</select>
	</div>
		<script>
			(function () {
				const radios = document.querySelectorAll('input[name="acewx_display_type"]');
				const pagesBox = document.getElementById('acewx-specific-pages');
				function togglePages() {
					const selected = document.querySelector('input[name="acewx_display_type"]:checked').value;
					pagesBox.style.display = selected === 'specific_pages' ? 'block' : 'none';
				}
				radios.forEach(radio => {
					radio.addEventListener('change', togglePages);
				});
				togglePages(); // on load
			})();
		</script>
		<?php
	}
	/**
		Save header/footer display conditions from the custom metabox.
	**/
	public function acewx_save_header_conditions_metabox( $post_id ) {
		if ( ! isset( $_POST['acewx_header_conditions_nonce_field'] ) || ! wp_verify_nonce( sanitize_text_field (wp_unslash( $_POST['acewx_header_conditions_nonce_field'] )), 'acewx_header_conditions_nonce' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		if ( isset( $_POST['acewx_display_type'] ) ) {
			update_post_meta($post_id,'_acewx_display_type',sanitize_text_field(wp_unslash( $_POST['acewx_display_type']) ));
		}
		if ( isset($_POST['acewx_hf_type']) ) {
			update_post_meta($post_id, '_acewx_hf_type', sanitize_text_field(wp_unslash($_POST['acewx_hf_type'])));
		}
		if ( isset( $_POST['acewx_hf_enabled'] ) ) {
			update_post_meta( $post_id, '_acewx_hf_enabled', '1' );
		} else {
			update_post_meta( $post_id, '_acewx_hf_enabled', '0' );
		}

		if (isset( $_POST['acewx_display_type'] ) && $_POST['acewx_display_type'] === 'specific_pages' && ! empty( $_POST['acewx_display_pages'] )) {
			$pages = array_map( 'intval', (array) $_POST['acewx_display_pages'] );
			update_post_meta($post_id,'_acewx_display_pages',$pages);
			
		} else {
			delete_post_meta( $post_id, '_acewx_display_pages' );
		}
		
	}
	/**
		 Insert custom column into acewx_header_footer admin table
	**/
	public function acewx_template_column( $columns ) {
		$new_columns = [];
		foreach ( $columns as $key => $label ) {
			$new_columns[ $key ] = $label;
			if ( $key === 'title' ) {
				$new_columns['acewx_hf_type'] = __( 'Template Type', 'acewx-header-footer' );
				$new_columns['acewx_hf_status'] = __( 'Status', 'acewx-header-footer' );
			}
		}
		return $new_columns;
	}
	/**
	 * Render data inside the custom column
	 */
	public function acewx_template_column_data( $column, $post_id ) {
			if ( $column === 'acewx_hf_type' ) {
				$type = get_post_meta( $post_id, '_acewx_hf_type', true );

				if ( $type ) {
					echo esc_html( ucfirst( $type ) );
				} else {
					echo '—';
				}
				
			}
			if ( $column === 'acewx_hf_status' ) { $enabled = get_post_meta( $post_id, '_acewx_hf_enabled', true );?>
				<label class="acewx-switch acewx-column-switch"
						data-post-id="<?php echo esc_attr( $post_id ); ?>">
        		<input type="checkbox" class="acewx-toggle-status"
               	<?php checked( $enabled, '1' ); ?> />
        			<span class="acewx-slider"></span>
    			</label>
			<?php
			}
		}
	public function acewx_toggle_hf_status() {

		check_ajax_referer( 'acewx_toggle_nonce' );

		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error();
		}

		$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
		$status = isset( $_POST['status'] ) ? sanitize_text_field( wp_unslash( $_POST['status'] ) ) : '';
		update_post_meta( $post_id, '_acewx_hf_enabled', $status );

		wp_send_json_success();
	}


	/**
	  add menu if not register 
	 */	
	function custom_plugin_register_nav_menu() {
		$locations = get_registered_nav_menus();
		if ( ! isset( $locations['primary_menu'] ) ) {
			register_nav_menus( [
				'primary_menu' => __( 'Primary Menu', 'acewx-header-footer' ),
			] );
		}
	}
	/**
	 * add Widget in elementor editor 
	 */	
	public function register_custom_elementor_widgets( $widgets_manager ) {
		
		require plugin_dir_path( __FILE__ ) .'widget/acewx-header-footer.php';
	}

}
