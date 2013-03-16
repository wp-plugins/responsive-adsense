<?php
/*
Plugin Name: Responsive Adsense
Plugin URI: http://webmaestro.fr/blog/responsive-adsense-plugin-for-wordpress/
Description: Display the ads that fit.
Version: 1.1
Author: WebMaestro.Fr
Author URI: http://webmaestro.fr
License: GPL2
*/

class ResponsiveAdsenseWidget extends WP_Widget {

	public function __construct() {
		// widget actual processes
		parent::__construct(
	 		'responsive_adsense', // Base ID
			'Responsive Adsense', // Name
			array( 'description' => 'A responsive Google Adsense unit.' ) // Args
		);
		wp_enqueue_script( 'jquery' );
	}

 	public function form($instance) {
		// outputs the options form on admin
		$pub_ID = isset($instance['pub_ID']) ? $instance['pub_ID'] : '';
		$ws = isset($instance['ws']) ? $instance['ws'] : '';
		$mr = isset($instance['mr']) ? $instance['mr'] : '';
		$mb = isset($instance['mb']) ? $instance['mb'] : '';
		$lr = isset($instance['lr']) ? $instance['lr'] : '';
		$lb = isset($instance['lb']) ? $instance['lb'] : '';
		?>
		<p>
			<label for="<?php echo $this->get_field_id('pub_ID'); ?>"><?php _e('Publisher ID:'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('pub_ID'); ?>" name="<?php echo $this->get_field_name('pub_ID'); ?>" type="text" value="<?php echo esc_attr($pub_ID); ?>" />
		</p>
		<p>Depending on there sizes, fill here the IDs of the units you want to display.</p>
		<p>
			<label for="<?php echo $this->get_field_id('mr'); ?>"><?php _e('300x250 - Medium rectangle:'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('mr'); ?>" name="<?php echo $this->get_field_name('mr'); ?>" type="text" value="<?php echo esc_attr($mr); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('lr'); ?>"><?php _e('336x280 - Large rectangle:'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('lr'); ?>" name="<?php echo $this->get_field_name('lr'); ?>" type="text" value="<?php echo esc_attr($lr); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('lb'); ?>"><?php _e('728x90 - Leaderboard:'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('lb'); ?>" name="<?php echo $this->get_field_name('lb'); ?>" type="text" value="<?php echo esc_attr($lb); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('ws'); ?>"><?php _e('160x600 - Wide skyscraper:'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('ws'); ?>" name="<?php echo $this->get_field_name('ws'); ?>" type="text" value="<?php echo esc_attr($ws); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('mb'); ?>"><?php _e('320x50 - Mobile banner:'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('mb'); ?>" name="<?php echo $this->get_field_name('mb'); ?>" type="text" value="<?php echo esc_attr($mb); ?>" />
		</p>
		<?php
	}

	public function update($new_instance, $old_instance) {
		// processes widget options to be saved
		$instance = array();
		$instance['pub_ID'] = str_replace( 'pub-', '', strip_tags($new_instance['pub_ID']) );
		$instance['ws'] = strip_tags($new_instance['ws']);
		$instance['mr'] = strip_tags($new_instance['mr']);
		$instance['mb'] = strip_tags($new_instance['mb']);
		$instance['lr'] = strip_tags($new_instance['lr']);
		$instance['lb'] = strip_tags($new_instance['lb']);
		return $instance;
	}

	public function widget($args, $instance) {
		// outputs the content of the widget
		extract( $args );
		$output = '';
		echo $before_widget;
		echo '<script type="text/javascript"><!--
var adWidget = jQuery(\'script\').last().parent(), adWidth = adWidget.width(), google_ad_client = "ca-pub-'.$instance['pub_ID'].'";
';
		if ( !empty($instance['lb']) ) {
			$output .= 'if ( adWidth>=728 ) {
	var google_ad_slot = "'.$instance['lb'].'", google_ad_width = 728, google_ad_height = 90;
}';
		}
		if ( !empty($instance['lr']) ) {
			if ($output !== '') { $output .= ' else '; }
			$output .= 'if ( adWidth>=336 ) {
	var google_ad_slot = "'.$instance['lr'].'", google_ad_width = 336, google_ad_height = 280;
}';
		}
		if ( !empty($instance['mb']) ) {
			if ($output !== '') { $output .= ' else '; }
			$output .= 'if ( adWidth>=320 ) {
	var google_ad_slot = "'.$instance['mb'].'", google_ad_width = 320, google_ad_height = 50;
}';
		}
		if ( !empty($instance['mr']) ) {
			if ($output !== '') { $output .= ' else '; }
			$output .= 'if ( adWidth>=300 ) {
	var google_ad_slot = "'.$instance['mr'].'", google_ad_width = 300, google_ad_height = 250;
}';
		}
		if ( !empty($instance['ws']) ) {
			if ($output !== '') { $output .= ' else '; }
			$output .= 'if ( adWidth>=160 ) {
	var google_ad_slot = "'.$instance['ws'].'", google_ad_width = 160, google_ad_height = 600;
}';
		}
		echo $output.'
adWidget.width(google_ad_width);
//-->
</script>
<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>'.$after_widget;
	}
}
add_action('widgets_init', create_function('', 'register_widget("ResponsiveAdsenseWidget");'));

?>