<?php
/**
 * @package Beatnik
 * @version 0.1.3
 */
/*
Plugin Name: Beatnik
Plugin URI: https://www.dailymaverick.co.za
Description: Publishes Beatnik articles on the Daily Maverick CMS
Author: Jason Norwood-Young
Version: 0.1.3
Author Email: jason@10layer.com
*/

class beatnikprojectMetabox {
	private $screen = array(
		'post',
		'article',
	);

	public function __construct() {
		$this->projects = $this->get_projects();
		$this->meta_fields = array(
			array(
				'label' => 'Project',
				'id' => 'beatnikproject',
				'type' => 'select',
				'options' => $this->projects,
			),
		);
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_fields' ) );
	}

	private function get_projects() {
		$projects = ["None"];
		foreach(glob("../paid-posts/*", GLOB_ONLYDIR) as $file) {
			array_push($projects, basename($file) );
		}
		return $projects;
	}

	public function add_meta_boxes() {
		foreach ( $this->screen as $single_screen ) {
			add_meta_box(
				'beatnikproject',
				__( 'Beatnik', 'textdomain' ),
				array( $this, 'meta_box_callback' ),
				$single_screen,
				'advanced',
				'low'
			);
		}
	}
	public function meta_box_callback( $post ) {
		wp_nonce_field( 'beatnikproject_data', 'beatnikproject_nonce' );
		echo 'Select a Beatnik Project to make this a Beatnik article';
		$this->field_generator( $post );
	}
	public function field_generator( $post ) {
		$output = '';
		foreach ( $this->meta_fields as $meta_field ) {
			$label = '<label for="' . $meta_field['id'] . '">' . $meta_field['label'] . '</label>';
			$meta_value = get_post_meta( $post->ID, $meta_field['id'], true );
			if ( empty( $meta_value ) && isset($meta_field['default']) ) {
				$meta_value = $meta_field['default']; }
			switch ( $meta_field['type'] ) {
				case 'select':
					$input = sprintf(
						'<select id="%s" name="%s">',
						$meta_field['id'],
						$meta_field['id']
					);
					foreach ( $meta_field['options'] as $key => $value ) {
						$meta_field_value = !is_numeric( $key ) ? $key : $value;
						$input .= sprintf(
							'<option %s value="%s">%s</option>',
							$meta_value === $meta_field_value ? 'selected' : '',
							$meta_field_value,
							$value
						);
					}
					$input .= '</select>';
					break;
				default:
					$input = sprintf(
						'<input %s id="%s" name="%s" type="%s" value="%s">',
						$meta_field['type'] !== 'color' ? 'style="width: 100%"' : '',
						$meta_field['id'],
						$meta_field['id'],
						$meta_field['type'],
						$meta_value
					);
			}
			$output .= $this->format_rows( $label, $input );
		}
		echo '<table class="form-table"><tbody>' . $output . '</tbody></table>';
	}
	public function format_rows( $label, $input ) {
		return '<tr><th>'.$label.'</th><td>'.$input.'</td></tr>';
	}

	public function save_fields( $post_id ) {
		if ( ! isset( $_POST['beatnikproject_nonce'] ) )
			return $post_id;
		$nonce = $_POST['beatnikproject_nonce'];
		if ( !wp_verify_nonce( $nonce, 'beatnikproject_data' ) )
			return $post_id;
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;
		foreach ( $this->meta_fields as $meta_field ) {
			if ( isset( $_POST[ $meta_field['id'] ] ) ) {
				switch ( $meta_field['type'] ) {
					case 'email':
						$_POST[ $meta_field['id'] ] = sanitize_email( $_POST[ $meta_field['id'] ] );
						break;
					case 'text':
						$_POST[ $meta_field['id'] ] = sanitize_text_field( $_POST[ $meta_field['id'] ] );
						break;
				}
				update_post_meta( $post_id, $meta_field['id'], $_POST[ $meta_field['id'] ] );
			} else if ( $meta_field['type'] === 'checkbox' ) {
				update_post_meta( $post_id, $meta_field['id'], '0' );
			}
		}
	}
}

class beatnikprojectDisplay {

	public function __construct() {
		add_filter("template_redirect", array( $this, 'template_redirect' ));
	}

	public function template_redirect($content) {
		if (is_single()) {
			global $post;
			$meta = get_post_meta($post->ID, "beatnikproject");
			if ((sizeof($meta) == 1) && ($meta[0] !== "None")) {
				global $wp;
				header('Location: ' . site_url("/paid-posts/" . $meta[0] . "/"));
				die();
			}
		}
	}
}

class beatnikArticleType {
	public static $beatnik_tile_count = 3;

	public function __construct() {
		add_action( 'init', array($this, 'create_post_type') );
		// add_action('add_meta_boxes', array($this, 'beatnik_sponsor_box') );
		add_action('add_meta_boxes', array($this, 'beatnik_promo_box') );
		add_action('save_post', array($this, 'beatnik_save_post') );
		// add_filter( 'tiny_mce_before_init', array($this, 'my_mce_before_init_insert_formats') );
		// add_filter( 'mce_buttons_2', array($this, 'my_mce_buttons_2') );
	}

	public function create_post_type() {
		register_post_type( 'beatnik-article',
			array(
			  'labels' => array(
			    'name' => __( 'Beatnik Articles' ),
			    'singular_name' => __( 'Beatnik Article' )
			  ),
			  'public' => true,
			  'has_archive' => true,
			  'rewrite' => array('slug' => 'paid-posts'),
			  'taxonomies' => ['featured'],
			  'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions', 'post_tag'),
			)
		);
	}

	public function beatnik_sponsor_box() {
        add_meta_box(
            'beatnik_sponsor_box',           // Unique ID
            'Sponsor',  // Box title
            array($this, 'beatnik_sponsor_box_html'),  // Content callback, must be of type callable
            'beatnik-article'                   // Post type
        );
	}

	public function beatnik_promo_box() {
        add_meta_box(
            'beatnik_promo_box',           // Unique ID
            'Promo Tiles',  // Box title
            array($this, 'beatnik_promo_box_html'),  // Content callback, must be of type callable
            'beatnik-article'                   // Post type
        );
	}

	public function beatnik_sponsor_box_html($post) {
		wp_enqueue_script('beatnik-script', plugin_dir_url(__FILE__) . 'js/beatnik.js', array('jquery'), null, true);
		$upload_link = esc_url( get_upload_iframe_src( 'image', $post->ID ) );
		$your_img_id = get_post_meta( $post->ID, 'beatnik_sponsor_logo-img-id', true );
		$your_img_src = wp_get_attachment_image_src( $your_img_id, 'full' );
		$you_have_img = is_array( $your_img_src );
	?>
    <label for="beatnik_sponsor_name">Sponsor Name</label>
    <input type="text" name="beatnik_sponsor_name" id="beatnik_sponsor_name" class="widefat" />
	<label for="beatnik_sponsor_url">Sponsor URL</label>
    <input type="url" name="beatnik_sponsor_url" id="beatnik_sponsor_url" class="widefat" placeholder="https://" />
	<label for="beatnik_sponsor_logo">Sponsor Logo</label>
	<div class="beatnik_sponsor_logo-container">
	    <?php if ( $you_have_img ) : ?>
	        <img src="<?php echo $your_img_src[0] ?>" alt="" style="max-width:300px;" />
	    <?php endif; ?>
	</div>
	<p class="hide-if-no-js">
	    <a class="upload-beatnik_sponsor_logo-img <?php if ( $you_have_img  ) { echo 'hidden'; } ?>" href="<?php echo $upload_link ?>">
	        <?php _e('Set custom image') ?>
	    </a>
	    <a class="delete-beatnik_sponsor_logo-img <?php if ( ! $you_have_img  ) { echo 'hidden'; } ?>"
	      href="#">
	        <?php _e('Remove this image') ?>
	    </a>
	</p>
    <input class="beatnik_sponsor_logo-img-id" name="beatnik_sponsor_logo-img-id" type="hidden" value="<?php echo esc_attr( $your_img_id ); ?>" />
    <?php
	}

	public function beatnik_promo_box_html($post) {
		function get_beatnik_image($post_id, $name, $x) {
			$result = [];
			$result["id"] = "beatnik_" . $name . "_img_" . $x;
			$result["img_id"] = get_post_meta( $post_id, $result["id"], true );
			$result["img_src"] = wp_get_attachment_image_src($result["img_id"]);
			$result["is_set"] = is_array($result["img_src"]);
			return $result;
		}

		wp_enqueue_script('beatnik-promo-script', plugin_dir_url(__FILE__) . 'js/beatnik-promo.js', array('jquery'), null, true);
		wp_enqueue_style( 'beatnikAdminStyleSheet' );
		$upload_link = esc_url( get_upload_iframe_src( 'image', $post->ID ) );
		print "<div class='beatnik'>";
		for ($x = 0; $x < self::$beatnik_tile_count; $x++) {
			$sponsor = get_beatnik_image($post->ID, "sponsor", $x);
			$background = get_beatnik_image($post->ID, "background", $x);
			$blurb = get_beatnik_image($post->ID, "blurb", $x);
			include(plugin_dir_path(__FILE__) . "views/admin/tile.php");
		}
		print "</div>";
	}

	public function beatnik_save_post($post_id) {
		if (isset($_POST['beatnik_sponsor_logo-img-id'])){
			update_post_meta($post_id, 'beatnik_sponsor_logo-img-id', $_POST['beatnik_sponsor_logo-img-id']);
		}
		// if (isset($_POST["excerpt"])) {
		// 	update_post_meta($post_id, 'excerpt', $_POST['excerpt']);
		// }
		for($x = 0; $x < self::$beatnik_tile_count; $x++) {
			$name = "beatnik_background_img_" . $x;
			if (isset($_POST[$name])) {
				update_post_meta($post_id, $name, $_POST[$name]);
			}
			$name = "beatnik_sponsor_img_" . $x;
			if (isset($_POST[$name])) {
				$result = update_post_meta($post_id, $name, $_POST[$name]);
			}

			$name = "beatnik_blurb_img_" . $x;
			if (isset($_POST[$name])) {
				$result = update_post_meta($post_id, $name, $_POST[$name]);
			}
		}
	}

	// Callback function to filter the MCE settings
	public function my_mce_before_init_insert_formats( $init_array ) {
		// Define the style_formats array
		$style_formats = array(
			// Each array child is a format with it's own settings
			array(
				'title' => '.translation',
				'block' => 'blockquote',
				'classes' => 'translation',
				'wrapper' => true,

			),
			array(
				'title' => '⇠.rtl',
				'block' => 'blockquote',
				'classes' => 'rtl',
				'wrapper' => true,
			),
			array(
				'title' => '.ltr⇢',
				'block' => 'blockquote',
				'classes' => 'ltr',
				'wrapper' => true,
			),
		);
		// Insert the array, JSON ENCODED, into 'style_formats'
		$init_array['style_formats'] = json_encode( $style_formats );
		print_r($init_array);

		return $init_array;
	}

	public function my_mce_buttons_2( $buttons ) {
		array_unshift( $buttons, 'styleselect' );
		return $buttons;
	}
}

if (class_exists('beatnikprojectMetabox')) {
	new beatnikprojectMetabox;
};

if (class_exists('beatnikprojectDisplay')) {
	new beatnikprojectDisplay;
};

if (class_exists('beatnikArticleType')) {
	new beatnikArticleType;
};

function beatnik_logo_url() {
	return plugins_url( 'img/beatnik_logo.png', __FILE__ );
}

wp_register_style( 'beatnikStyleSheet', plugins_url( 'css/beatnik.css', __FILE__ ) );
wp_register_style( 'beatnikAdminStyleSheet', plugins_url( 'css/beatnik-admin.css', __FILE__ ) );

define("THREECOLUMN", 0);
define("WIDE", 1);
define("FULLWIDTH", 2);

function beatnik_frontpage_display($post, $layout) {
	// wp_enqueue_style( 'beatnikStyleSheet' );
	$title = get_the_title($post->ID);
    $postUrl = get_permalink($post);
    $authorId = $post->post_author;
    $postType = $post->post_type;
	$images = [];
	for($x = 0; $x < 3; $x++) {
		$images[$x] = new stdClass();
		$backgroundImgId = get_post_meta($post->ID, "beatnik_background_img_" . $x)[0];
		$images[$x]->background = wp_get_attachment_image_src($backgroundImgId, "full")[0];
		$sponsorImgId = get_post_meta($post->ID, "beatnik_sponsor_img_" . $x)[0];
		$images[$x]->sponsor = wp_get_attachment_image_src($sponsorImgId, "full")[0];
		$blurbImgId = get_post_meta($post->ID, "beatnik_blurb_img_" . $x)[0];
		$images[$x]->blurb = wp_get_attachment_image_src($blurbImgId, "full")[0];
	}
    $beatnikLogo = beatnik_logo_url();
	$beatnik_images = $images[$layout];
	echo "<style>";
	include_once(plugin_dir_path(__FILE__) . "css/beatnik.css");
	echo "</style>";
	if ($layout === THREECOLUMN) {
		include(plugin_dir_path(__FILE__) . "views/threecolumn.php");
	} elseif ($layout === WIDE) {
		include(plugin_dir_path(__FILE__) . "views/wide.php");
	} elseif ($layout === FULLWIDTH) {
		include(plugin_dir_path(__FILE__) . "views/fullwidth.php");
	}
}

add_action('init', 'registerBeatnikFlagTaxonomy', 1);

function registerBeatnikFlagTaxonomy() {
	$labels = array(
        'name'                          => __('Flags', 'taxonomy general name'),
        'singular_name'                 => __('Flag', 'taxonomy singular name'),
        'search_items'                  => __('Search Flags'),
        'popular_items'                 => __('Popular Flags'),
        'all_items'                     => __('All Flags'),
        'edit_item'                     => __('Edit Flag'),
        'update_item'                   => __('Update Flag'),
        'add_new_item'                  => __('Add Flag'),
        'new_item_name'                 => __('New Flag'),
        'separate_items_with_commas'    => __('Separate Flags with commas'),
        'add_or_remove_items'           => __('Add or remove Flags'),
        'choose_from_most_used'         => __('Choose from most used Flags'),
        'menu_name'                     => __('Flags'),
    );
    register_taxonomy('flag', ['beatnik-article', 'article', 'cartoon', 'opinion-piece'], array(
        'hierarchical'  => true,
        'labels'        => $labels,
        'show_ui'       => true,
        'query_var'     => true,
        'rewrite'       => array('slug' => 'flag'),
		'show_in_rest'  => true
    ));
	register_taxonomy_for_object_type( 'flag', 'beatnik-article' );
}
