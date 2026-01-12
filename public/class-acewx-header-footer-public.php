<?php
if (! defined('ABSPATH')) exit;
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://acewebx.com
 * @since      1.0.0
 *
 * @package    Acewx_Header_Footer
 * @subpackage Acewx_Header_Footer/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Acewx_Header_Footer
 * @subpackage Acewx_Header_Footer/public
 * @author     AceWebx Team <Acewebx@gmail.com>
 */
class Acewx_Header_Footer_Public
{

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

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

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/acewx-header-footer-public.css', array(), $this->version, 'all');
		$css_file = plugin_dir_path(__FILE__) . 'css/widget.css';
		wp_enqueue_style($this->plugin_name . '-widget', plugin_dir_url(__FILE__) . 'css/widget.css', [], file_exists($css_file) ? filemtime($css_file) : $this->version);
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

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
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/acewx-header-footer-public.js', array('jquery'), $this->version, false);
	}
	// Ace replaces the header.
	public function replace_header()
	{
		$current_page_id = get_queried_object_id();
		$query = new WP_Query([
			'post_type'      => 'acewx_header_footer',
			'posts_per_page' => 1,
			'post_status'    => 'publish',
			'no_found_rows'  => true,
			'fields'         => 'ids',
			'meta_query'     => [
				[
					'key'   => '_acewx_hf_type',
					'value' => 'header',
				],
				[
					'key'   => '_acewx_hf_enabled',
					'value' => '1',
				],
			],
		]);
		if (! $query->have_posts()) {
			return;
		}
		$query->the_post();
		$display_type  = get_post_meta(get_the_ID(), '_acewx_display_type', true);
		$display_pages = (array) get_post_meta(get_the_ID(), '_acewx_display_pages', true);
		$show_header = false;
		if ($display_type === 'entire_site') {
			$show_header = true;
		} elseif ($display_type === 'specific_pages' && in_array($current_page_id, $display_pages, true)) {
			$show_header = true;
		}
		if ($show_header) {
?>
			<header id="acexw-custom-header">
				<div class="container">
					<?php the_content(); ?>
				</div>
			</header>
		<?php
		}
		wp_reset_postdata();
	}

	// Ace eliminates the default header
	public function acewx_default_header(): void
	{
		$current_page_id = get_queried_object_id();
		$show_header = $this->Check_header_footer_validation($current_page_id, 'header');
		if ($show_header) {
			require plugin_dir_path(__FILE__) . 'partials/themes/default/acewx-default-header.php';
			$templates   = [];
			$templates[] = 'header.php';
			// Avoid running wp_head hooks again.
			remove_all_actions('wp_head');
			ob_start();
			locate_template($templates, true);
			ob_get_clean();
		}
	}
	// Ace replaces the footer. 
	public function acewx_replace_footer()
	{
		$current_page_id = get_queried_object_id();
		$query = new WP_Query([
			'post_type'      => 'acewx_header_footer',
			'posts_per_page' => 1,
			'post_status'    => 'publish',
			'meta_query'     => [
				[
					'key'   => '_acewx_hf_type',
					'value' => 'footer',
				],
				[
					'key'   => '_acewx_hf_enabled',
					'value' => '1',
				],
			],
			'orderby'        => 'modified',
			'order'          => 'DESC',
		]);
		if (! $query->have_posts()) {
			return;
		}
		$query->the_post();
		$display_type  = get_post_meta(get_the_ID(), '_acewx_display_type', true);
		$display_pages = (array) get_post_meta(get_the_ID(), '_acewx_display_pages', true);
		$show_footer = false;
		if ($display_type === 'entire_site') {
			$show_footer = true;
		} elseif ($display_type === 'specific_pages' && in_array($current_page_id, $display_pages, true)) {
			$show_footer = true;
		}
		if ($show_footer) {
		?>
		<footer id="acexw-custom-footer">
			<div class="container">
				<?php the_content(); ?>
			</div>
		</footer>
	<?php
		}
		wp_reset_postdata();
	}
	// Ace eliminates the default footer
	public function acewx_default_footer(): void
	{
		$current_page_id = get_queried_object_id();
		$show_footer = $this->Check_header_footer_validation($current_page_id, 'footer');
		if ($show_footer) {
			require plugin_dir_path(__FILE__) . 'partials/themes/default/acewx-default-footer.php';
			$templates   = [];
			$templates[] = 'footer.php';
			remove_all_actions('wp_footer');
			ob_start();
			locate_template($templates, true);
			ob_get_clean();
		}
	}
	// Ace verify validation of the header and footer
	public function Check_header_footer_validation($id, $type)
	{
		$current_page_id = get_the_ID();
		$header_query = new WP_Query([
			'post_type'      => 'acewx_header_footer',
			'posts_per_page' => 1,
			'post_status'    => 'publish',
			'orderby'        => 'date',
			'order'          => 'DESC',
			'meta_query'     => [
				[
					'key'   => '_acewx_hf_type',
					'value' => $type,
				],
				[
					'key'   => '_acewx_hf_enabled',
					'value' => '1',
				],
			],
		]);
		if ($header_query->have_posts()) {
			while ($header_query->have_posts()) {
				$header_query->the_post();
				$display_type  = get_post_meta(get_the_ID(), '_acewx_display_type', true);
				$display_pages = (array) get_post_meta(get_the_ID(), '_acewx_display_pages', true);
				$show_header = false;
				if ($display_type === 'entire_site') {
					$show_header = true;
				} elseif ($display_type === 'specific_pages' && in_array($current_page_id, $display_pages)) {
					$show_header = true;
				}
			}
		}
		return $show_header;
	}
}
