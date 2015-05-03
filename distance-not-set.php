<?php
/*
Plugin Name: Distance Not Set
Plugin URI: http://coderspress.com/
Description: This plugin replaces default miles/kilometers, if a users Location is not set.
Version: 2015.05.02
Updated: 2nd May 2015
Author: sMarty 
Author URI: http://coderspress.com
License: http://creativecommons.org/licenses/GPL/2.0
*/

add_action( 'init', 'dns_plugin_updater' );
function dns_plugin_updater() {
	include_once( dirname( __FILE__ ) . '/updater.php' );
	define( 'WP_DNS_FORCE_UPDATE', true );
	if ( is_admin() ) { 
		$config = array(
			'slug' => plugin_basename( __FILE__ ),
			'proper_folder_name' => 'distance-not-set',
			'api_url' => 'https://api.github.com/repos/CodersPress/distance-not-set',
			'raw_url' => 'https://raw.github.com/CodersPress/distance-not-set/master',
			'github_url' => 'https://github.com/CodersPress/distance-not-set',
			'zip_url' => 'https://github.com/CodersPress/distance-not-set/archive/master.zip',
			'sslverify' => true,
			'requires' => '3.0',
			'tested' => '4.2.1',
			'readme' => 'README.md',
			'access_token' => '8ea24d3a3b0da51db3d75b52bee69d7818ab8d45',
		);
		new WP_DNS_Updater( $config );
	}
}
add_action('admin_menu', 'distance_menu');
function distance_menu() {
	add_menu_page('Distance NOT SET', 'Distance NOT SET', 'administrator', __FILE__, 'distance_setting_page',plugins_url('/images/navigation.png', __FILE__));
	add_action( 'admin_init', 'register_distance_settings' );
}
function register_distance_settings() {
   	register_setting("distance-settings-group", "distance_message");
    register_setting("distance-settings-group", "distance_icon_color");
}
function distance_defaults()
{
    $option = array(
        "distance_message" => "<strong>Distance</strong>: NOT SET",
        "distance_icon_color" => "#cecece",
    );
  foreach ( $option as $key => $value )
    {
       if (get_option($key) == NULL) {
        update_option($key, $value);
       }
    }
    return;
}
register_activation_hook(__FILE__, "distance_defaults");

function distance_setting_page() {
if ($_REQUEST['settings-updated']=='true') {
echo '<div id="message" class="updated fade"><p><strong>Plugin settings saved.</strong></p></div>';
}
?>
<div class="wrap">
    <h2>Distance Not Set message setting.</h2>
    <hr />
<form method="post" action="options.php">
    <?php settings_fields("distance-settings-group");?>
    <?php do_settings_sections("distance-settings-group");?>
    <table class="widefat" style="width:600px;">

 <thead style="background:#2EA2CC;color:#fff;">
            <tr>
                <th style="color:#fff;">Message Settings</th>
                <th style="color:#fff;"></th>
                <th style="color:#fff;"></th>
            </tr>
        </thead>
<tr>
<td>This message appears when a users Location is not set: It replaces default miles/kilometers message.</td>
<td></td>
<td></td>
 </tr>

<tr>
<td>Replacement Text:</td>
<td><input type="text" size="40" id="distance_message" name="distance_message" value="<?php echo get_option("distance_message");?>"/><br />&nbsp; HTML Allowed: &lt;b&gt;&lt;/b&gt;&lt;font&gt;&lt;/font&gt; etc...</td>
<td></td>
 </tr>
<tr>
<td>Icon color:</td>
<td><input type="text" size="10" id="distance_icon_color" name="distance_icon_color" value="<?php echo get_option("distance_icon_color");?>"/><br />&nbsp; Default: #cecece</td>
<td></td>
 </tr>

  </table>
    <?php submit_button(); ?>
</form>
</div>
<?php
}
function distance(){
?>
<script type="text/javascript">
jQuery('.modal-footer > form:nth-child(1)').attr("action", "");
var latitude = "<?=$_SESSION['mylocation']['lat'];?>";
if (latitude  === '') {
    jQuery(".wlt_shortcode_distance").replaceWith("<span class='wlt_shortcode_distance'><?php echo get_option("distance_message");?><a data-target='#MyLocationModal' data-toggle='modal' onclick='GMApMyLocation();' href='javascript:void(0);'> <i class='fa fa-refresh'  style='cursor:pointer;color:<?php echo get_option('distance_icon_color');?>;'></i></a></span>");
}
</script>
<?php
}
add_action('wp_footer','distance');
?>