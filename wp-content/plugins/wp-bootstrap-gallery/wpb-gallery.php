<?php
/*
  Plugin Name: WP BootStrap Gallery
  Plugin URI: http://magnigenie.com/wp-bootstrap-gallery-wordpress-image-gallery-plugin/
  Version: 1.0
  Description: WP Boootstrap Gallery  is the ultra responsive image gallery plugin for WordPress which works seamlessly on every device. 
  Author: Nirmal Ram
  Author URI: http://magnigenie.com/
  License: GPLv2 or later
  License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
 
// No direct file access
! defined( 'ABSPATH' ) AND exit;

$plugin_dir = plugin_basename(__FILE__);
$plugin_dir = str_replace(basename($plugin_dir), '', $plugin_dir);
define('WPBGALLERY_DIR', WP_PLUGIN_DIR . '/' . $plugin_dir);
define('WPBGALLERY_URL', WP_PLUGIN_URL . '/' . $plugin_dir);
define('WPBGALLERY_DEBUG', false);
define('WPBGALLERY_VERSION', '1.0');

define('WPB_OPTIONS_FRAMEWORK_URL', WPBGALLERY_URL . 'admin/');
define('WPB_OPTIONS_FRAMEWORK_DIRECTORY', WPBGALLERY_DIR . 'admin/');
define('WPB_OPTIONS_FRAMEWORK_NAME', 'Gallery Settings');
define('WPB_OPTIONS_FRAMEWORK_TAG', 'wpbgallery');
require_once (WPB_OPTIONS_FRAMEWORK_DIRECTORY . 'options-framework.php');

class wpbgallery {

    private static $instance;
    private $admin_thumbnail_size = 109;
    private $thumbnail_size_w = 150;
    private $thumbnail_size_h = 150;

    public static function forge() {
        if (!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }

    private function __construct() {
        $this->thumbnail_size_w = wpb_of_get_option('wpbgallery_thumb_width');
        $this->thumbnail_size_h = wpb_of_get_option('wpbgallery_thumb_height');

        add_action('admin_print_scripts-post.php', array(&$this, 'admin_print_scripts'));
        add_action('admin_print_scripts-post-new.php', array(&$this, 'admin_print_scripts'));
        add_action('admin_print_styles', array(&$this, 'admin_print_styles'));
        add_action('wp_print_scripts', array(&$this, 'print_scripts'));
        add_action('wp_print_styles', array(&$this, 'print_styles'));
        add_filter('the_content', array(&$this, 'output_gallery'), wpb_of_get_option('wpbgallery_filter_priority', 10));
        add_image_size('wpbgallery_admin_thumb', $this->admin_thumbnail_size, $this->admin_thumbnail_size, true);
        add_image_size('wpbgallery_thumb', $this->thumbnail_size_w, $this->thumbnail_size_h, true);
        add_shortcode('wpbgallery', array(&$this, 'shortcode'));
        if (is_admin()) {
            add_action('init', array(&$this, 'register_cpt_wpb_gallery'), 1);
            add_action('add_meta_boxes', array(&$this, 'add_meta_boxes'));
            add_action('admin_init', array(&$this, 'add_meta_boxes'), 1);
            add_action('save_post', array(&$this, 'save_post_meta'), 9, 1);
            add_action('wp_ajax_wpbgallery_get_thumbnail', array(&$this, 'ajax_get_thumbnail'));
            add_action('wp_ajax_wpbgallery_get_all_thumbnail', array(&$this, 'ajax_get_all_attachments'));
        }
    }

    public function admin_print_scripts() {
        wp_enqueue_script('media-upload');
        wp_enqueue_script('wpbgallery-admin-scripts', WPBGALLERY_URL . 'js/wpbgallery-admin.js', array('jquery'), WPBGALLERY_VERSION);
    }

    public function admin_print_styles() {
        wp_enqueue_style('wpbgallery-admin-style', WPBGALLERY_URL . 'css/wpbgallery-admin.css', array(), WPBGALLERY_VERSION);
    }

    public function add_meta_boxes() {
    add_meta_box(
        'wpbgallery', __('Select Images', 'wpbgallery'), array(&$this, 'inner_custom_box'), 'wpb_gallery' );
    }
    
    public function register_cpt_wpb_gallery() {

    $labels = array(
        'name' => _x( 'WPB Galleries', 'wpb_gallery' ),
        'singular_name' => _x( 'WPB Gallery', 'wpb_gallery' ),
        'add_new' => _x( 'Add New', 'wpb_gallery' ),
        'add_new_item' => _x( 'Add New Gallery', 'wpb_gallery' ),
        'edit_item' => _x( 'Edit WPB Gallery', 'wpb_gallery' ),
        'new_item' => _x( 'New Gallery', 'wpb_gallery' ),
        'view_item' => _x( 'View Gallery', 'wpb_gallery' ),
        'search_items' => _x( 'Search Galleries', 'wpb_gallery' ),
        'not_found' => _x( 'No galleries found', 'wpb_gallery' ),
        'not_found_in_trash' => _x( 'No galleries found in Trash', 'wpb_gallery' ),
        'parent_item_colon' => _x( 'Parent Gallery:', 'wpb_gallery' ),
        'menu_name' => _x( 'WPB Galleries', 'wpb_gallery' ),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => false,
        'supports' => array( 'title', 'thumbnail' ),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 10,
        'menu_icon' => WPBGALLERY_URL.'/img/wpb-gallery.png',
        'show_in_nav_menus' => false,
        'publicly_queryable' => false,
        'exclude_from_search' => true,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => false,
        'capability_type' => 'post'
    );

        register_post_type( 'wpb_gallery', $args );
        add_filter( 'manage_edit-wpb_gallery_columns', array(&$this, 'wpb_gallery_columns' )) ;
        add_action( 'manage_wpb_gallery_posts_custom_column', array(&$this, 'wpb_gallery_manage_columns' ), 10, 2 );
        
    }
    function wpb_gallery_columns( $columns ){
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => __( 'Gallery' ),
            'shortcode' => __( 'Gallery Shortcode' ),
            'date' => __( 'Date' )
        );
        
        return $columns;
    }
    function wpb_gallery_manage_columns( $column, $post_id ){
        	global $post;

        switch( $column ) {
      
          /* If displaying the 'duration' column. */
          case 'shortcode' :
            echo '<input type="text" value="[wpbgallery id='.$post_id.']" readonly="readonly" />';
            break;
          /* Just break out of the switch statement for everything else. */
          default :
            break;
        }
    }

    public function inner_custom_box($post) {
        $gallery = get_post_meta($post->ID, 'wpbgallery_gallery', true);
        wp_nonce_field(basename(__FILE__), 'wpbgallery_gallery_nonce');

        $upload_size_unit = $max_upload_size = wp_max_upload_size();
        $sizes = array('KB', 'MB', 'GB');

        for ($u = -1; $upload_size_unit > 1024 && $u < count($sizes) - 1; $u++) {
            $upload_size_unit /= 1024;
        }

        if ($u < 0) {
            $upload_size_unit = 0;
            $u = 0;
        } else {
            $upload_size_unit = (int) $upload_size_unit;
        }

        $upload_action_url = admin_url('async-upload.php');
        $post_params = array(
            "post_id" => $post->ID,
            "_wpnonce" => wp_create_nonce('media-form'),
            "short" => "1",
        );

        $post_params = apply_filters('upload_post_params', $post_params); // hook change! old name: 'swfupload_post_params'

        $plupload_init = array(
            'runtimes' => 'html5,silverlight,flash,html4',
            'browse_button' => 'wpb-plupload-browse-button',
            'file_data_name' => 'async-upload',
            'multiple_queues' => true,
            'max_file_size' => $max_upload_size . 'b',
            'url' => $upload_action_url,
            'flash_swf_url' => includes_url('js/plupload/plupload.flash.swf'),
            'silverlight_xap_url' => includes_url('js/plupload/plupload.silverlight.xap'),
            'filters' => array(array('title' => __('Allowed Files'), 'extensions' => '*')),
            'multipart' => true,
            'urlstream_upload' => true,
            'multipart_params' => $post_params
        );
        ?>
        <script type="text/javascript">
            var POST_ID = <?php echo $post->ID; ?>;
            var WPBwpUploaderInit = <?php echo json_encode($plupload_init) ?>;
        </script>

        <input id="wpb-plupload-browse-button" class="button" type="button" value="<?php echo __('Quick Upload', 'wpbgallery'); ?>" rel="" />
        <input id="wpbgallery_upload_button" data-uploader_title="Upload Image" data-uploader_button_text="Select" class="upload_button button" type="button" value="<?php echo __('Upload Image', 'wpbgallery'); ?>" rel="" />
        <input id="wpbgallery_add_attachments_button" class="button" type="button" value="<?php echo __('Add All Attachments', 'wpbgallery'); ?>" rel="" />
        <input id="wpbgallery_delete_all_button" class="button" type="button" value="<?php echo __('Delete All', 'wpbgallery'); ?>" rel="" />
        <span class="spinner" id="wpbgallery_spinner">Adding Imges...</span>
        <div id="wpbgallery_container">
            <ul id="wpbgallery_thumbs" class="clearfix"><?php
                $gallery = (is_string($gallery)) ? @unserialize($gallery) : $gallery;
                if (is_array($gallery) && count($gallery) > 0) {
                    foreach ($gallery as $id) {
                        echo $this->admin_thumb($id);
                    }
                }
                ?>
            </ul>
        </div>
        <?php
    }

    private function admin_thumb($id) {
        $image = wp_get_attachment_image_src($id, 'wpbgallery_admin_thumb', true);
        ?>
        <li><img src="<?php echo $image[0]; ?>" width="<?php echo $image[1]; ?>" height="<?php echo $image[2]; ?>" /><a href="#" class="wpbgallery_remove"><?php echo __('Remove', 'wpbgallery'); ?></a><input type="hidden" name="wpbgallery_thumb[]" value="<?php echo $id; ?>" /></li>
        <?php
    }

    public function ajax_get_thumbnail() {
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        echo $this->admin_thumb($_POST['imageid']);
        die;
    }

    public function ajax_get_all_attachments() {
        $post_id = $_POST['post_id'];
        $included = (isset($_POST['included'])) ? $_POST['included'] : array();

        $attachments = get_children(array(//do only if there are attachments of these qualifications
            'post_parent' => $post_id,
            'post_type' => 'attachment',
            'numberposts' => -1,
            'order' => 'ASC',
            'post_mime_type' => 'image', //MIME Type condition
                )
        );
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        if (count($attachments) > 0) {
            foreach ($attachments as $a) {
                if (!in_array($a->ID, $included)) {
                    echo $this->admin_thumb($a->ID);
                }
            }
        }
        die;
    }

    private function thumb($id, $post_id) {
        $info = get_posts(array('p' => $id, 'post_type' => 'attachment'));
        $url = wp_get_attachment_url($id);
        if (wpb_of_get_option('wpbgallery_use_timthumb', '0') === '1') {
            $width = $this->thumbnail_size_w;
            $height = $this->thumbnail_size_h;
            $image = array(
                WPBGALLERY_URL . 'timthumb.php?src=' . $url . '&q=85&w=' . $width . '&h=' . $height,
                $width,
                $height
            );
        } else {
            $image = wp_get_attachment_image_src($id);
        }
        $title_string = wpb_of_get_option('wpbgallery_caption', '%title%');
        $alt = get_post_meta($id, '_wp_attachment_image_alt', true);
        $data = array(
            '%title%' => $info[0]->post_title,
            '%alt%' => $alt,
            '%filename%' => basename($url),
            '%caption%' => $info[0]->post_excerpt,
            "\n" => ' - '
        );
        $title = str_replace(array_keys($data), $data, $title_string);
        return '<li><a href="' . $url . '" title="' . $title . '" data-gallery=""><img src="' . $image[0] . '" width="' . $image[1] . '" height="' . $image[2] . '" alt="' . $info[0]->post_title . '" /></a></li>';
    }

    public function save_post_meta($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return '';
        }
        if (!isset($_POST['wpbgallery_gallery_nonce']) || !wp_verify_nonce($_POST['wpbgallery_gallery_nonce'], basename(__FILE__)))
            return (isset($post_id)) ? $post_id : 0;

        $images = (isset($_POST['wpbgallery_thumb'])) ? $_POST['wpbgallery_thumb'] : array();
        $gallery = array();
        if (count($images) > 0) {
            foreach ($images as $i => $img) {
                if (is_numeric($img))
                    $gallery[] = $img;
            }
        }
        update_post_meta($post_id, 'wpbgallery_gallery', $gallery);
        return $post_id;
    }

    public function print_scripts() {
        
        if( wpb_of_get_option( 'bootstrap_script', '1' ) == 1 )
            wp_enqueue_script( 'wpbgallery-bootstrap-js', WPBGALLERY_URL . 'js/bootstrap.min.js', array('jquery') );
        wp_enqueue_script( 'wpbgallery-blueimp-js', WPBGALLERY_URL . 'js/jquery.blueimp-gallery.min.js' , array('jquery' ) );
        wp_enqueue_script( 'wpbgallery-gallery-js', WPBGALLERY_URL . 'js/bootstrap-image-gallery.min.js' , array( 'wpbgallery-blueimp-js' ) );
        wp_enqueue_script( 'wpbgallery-scripts', WPBGALLERY_URL . 'js/wpbgallery.js' , array( 'wpbgallery-gallery-js' ) );
        
    }

    public function print_styles() {
        if( wpb_of_get_option( 'bootstrap_script', '1' ) == 1 )
            wp_enqueue_style('wpbgallery-bootstrap', WPBGALLERY_URL . 'css/bootstrap.min.css');
        wp_enqueue_style('wpbgallery-blueimp', WPBGALLERY_URL . 'css/blueimp-gallery.min.css');
        wp_enqueue_style('wpbgallery-gallery', WPBGALLERY_URL . 'css/bootstrap-image-gallery.min.css');
        wp_enqueue_style('wpbgallery-style', WPBGALLERY_URL . 'css/wpbgallery.css');
    }

    private function gallery($post_id = false) {
        global $post;
        $post_id = (!$post_id) ? $post->ID : $post_id;
        $gallery = get_post_meta($post_id, 'wpbgallery_gallery', true);
        $gallery = (is_string($gallery)) ? @unserialize($gallery) : $gallery;
        $html = '';

        if (is_array($gallery) && count($gallery) > 0) {
            $html = '<div id="wpbgallery_container"><ul id="wpbgallery" class="clearfix">';
            foreach ($gallery as $thumbid) {
                $html .= $this->thumb($thumbid, $post_id);
            }
            $html .= '</ul></div>';
        }
        
        $html .= '<div id="blueimp-gallery" class="blueimp-gallery" data-useBootstrapModal="'.wpb_of_get_option( 'borderless', '0' ).'" data-fullScreen="'.wpb_of_get_option( 'fullscreen', '0' ).'">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">&#xffe9;</a>
    <a class="next">&#xffeb;</a>
    <a class="close">&#120;</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
    <div class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" aria-hidden="true">&times;</button>
                    <h4 class=""></h4><!--class = modal-title-->
                </div>
                <div class="modal-body next"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left prev">
                        <
                    </button>
                    <button type="button" class="btn btn-primary next">
                        >
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>';

        return $html;
    }

    public function output_gallery($content) {
        if (post_password_required()) {
            return $content;
        }

        $append_gallery = wpb_of_get_option('append_gallery', '1');
        if (!post_password_required() && $append_gallery == '1' && (wpb_of_get_option('single_only', '1') !== '1' || is_singular())) {
            $content .= $this->gallery();
        }
        return $content;
    }

    public function shortcode($atts) {
        extract(shortcode_atts(array(
            'id' => false,
                        ), $atts));
        return $this->gallery($id);
    }

}

global $wpbgallery;
$wpbgallery = wpbgallery::forge();