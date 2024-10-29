<?php 
/**
 * Plugin Name: Awesome Wp Photo Gallery
 * Author: Nayon
 * Author URI: http://nayonbd.com
 * Description: An easy to use lightbox clone for WordPress.obviously show your image default custom post type .When you click the image it will create the pretty photo zoom effect.
 * Version: 2.0
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
add_action('init', 'Awpg_plugin_textdomain');
function Awpg_plugin_textdomain() {
	add_theme_support('title-tag');
	register_post_type('Awpg-image-photo',array(
		'labels'=>array(
			'name'=>'Image Gallery'
		),
		'public'=>true,
		'supports'=>array('title','editor','thumbnail'),
		'menu_icon'=>'dashicons-format-gallery'
	));
	load_plugin_textdomain('Awpg_photo_textdomain', false, dirname( __FILE__).'/lang');
}
 
 /**
 * Main plugin class
 */
class Awpg_Gallery_Photo_Area{
	 /*
	     * Class constructor
	     *
	     * @access public
	   
	*/
	public function __construct(){
		/*
		   * prettyPhoto css enqueue hook added
		*/
		wp_enqueue_style('prettyPhoto-css',plugins_url('css/prettyPhoto.css',__FILE__));
		wp_enqueue_style('main-css',plugins_url('css/main.css',__FILE__));

		/*
			* prettyPhoto js enqueue hook added
		*/
		wp_enqueue_script('prettyPhoto-js',plugins_url('js/jquery.prettyPhoto.js',__FILE__),array('jquery'));
		wp_enqueue_script('pretiphoto-custom',plugins_url('js/pretiphoto.js',__FILE__),array('jquery'));


		/*
			* script Action hook added
		*/

		add_action('wp_enqueue_scripts',array($this,'prettyphoto_script_area'));

		/*
			* widget Action hook added
		*/

		add_action('widgets_init',array($this,'Awpg_gallery_widget_area'));
	}

	public function prettyphoto_script_area(){
		/*
			*  css enqueue hook added
		*/
		
		wp_enqueue_style('main-css',plugins_url('css/main.css',__FILE__));
	}
		/*
			* register widget function
		*/
	public function Awpg_gallery_widget_area(){
		register_widget('Awpg_gallery_photo');
	}

}
new Awpg_Gallery_Photo_Area();

class Awpg_gallery_photo extends wp_widget{
	 /**
	     * Class constructor
	     *
	     * @access public
	     * @return void
	*/
	public function __construct(){
		parent::__construct('gallery-area','Widget Gallery prettyPhoto',array(
			'description'=>'An easy to use lightbox clone for WordPress.'
		));
	}
		/*
			* widget function of the widget
		*/
	public function widget($args,$instance){
		?>
			<!-- started widget area -->
			<?php echo $args['before_widget'];?>
				<div class="image-area"> 
					<div class="gallary-title">
					<!-- started title area --> 
						<?php echo $args['before_title']; ?><h3><?php echo $instance['title'] ?></h3><?php echo $args['after_title']; ?> <!-- ended title area --> 
					</div>
					<?php
						/*
							* wp query function  
						*/
						$coont = isset( $instance['count'] ) ? $instance['count'] : '';
						$gallery = new wp_Query(array(
							'post_type'=>'Awpg-image-photo',
							'posts_per_page'=>$coont
						));
						/*
							* wp query function ended
						*/
					?>
					<?php 
						/*
							* while function  started
						*/
					?>
					<?php while( $gallery->have_posts() ) : $gallery->the_post(); ?>
					<div class="image-section"> 
					<?php
						global $post; 
						 $prettyid = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' ); ?>
					<a href="<?php echo $prettyid[0];?>" rel="prettyPhoto[pp_gal]" ><?php the_post_thumbnail(); ?></a>
					</div>
					<?php 
					/*
						* while function  ended
					*/
					?>
					<?php endwhile; ?>
				</div>
			<?php echo $args['after_widget'];?>
		<!-- ended widget area -->
		<?php
	}
		/*
			* form function of the widget
		*/
	public function form($instance){
	?>
		<!-- // 1st section of the widget level for the title-->

		<label for="<?php echo $this->get_field_id('title'); ?>">Title</label>
		<input type="text" class="widefat"  id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php if( isset($instance['title'])){ echo $instance['title']; } ?>">

		<!-- // 2nd section of the widget level for the count -->
		<label for="<?php echo $this->get_field_id('count'); ?>">Count</label>
		<input type="number" class="widefat"  id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" value="<?php if( isset($instance['count'])){ echo $instance['count']; } ?>">
	<?php
	}
}
