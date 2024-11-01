<?php
/*
	Plugin Name: WP-FlashTagCloud
	Plugin URI: http://premiumcoding.com
	Description: Flash Tag Cloud for WordPress
	Version: 1.13
	Author: Gljivec & Zdrifko
	Author URI: http://premiumcoding.com
	
	Copyright 2011, Gljivec & Zdrifko
*/

// check for WP context
if ( !defined('ABSPATH') ){ die(); }

//set install options
function wp_tagFlash_install () {
	$newoptions = get_option('wptagFlash_options');
	$newoptions['width'] = '160';
	$newoptions['height'] = '160';
	$newoptions['tcolor'] = 'red';
	$newoptions['bgcolor'] =  'ffffff';
	$newoptions['animation'] = 'random';
	$newoptions['mode'] = 'tags';
	$newoptions['reflection'] = 'true';
	$newoptions['numTags'] = '25';
	$newoptions['cColor'] = 'false';
	$newoptions['tagCustomColor'] = '';
	$newoptions['cbgColor'] ='false';
	$newoptions['dragAndDrop'] = 'false';	
	add_option('wptagFlash_options', $newoptions);
}

// add the admin page
function wp_tagFlash_add_pages() {
	add_options_page('WP-FlashTagCloud', 'WP-FlashTagCloud', 8, __FILE__, 'wp_tagFlash_options');
}

// replace tag in content with tag cloud (non-shortcode version for WP 2.3.x)
function wp_tagFlash_init($content){
	if( strpos($content, '[WP-FlashTagCloud]') === false ){
		return $content;
	} else {
		$code = wp_tagFlash_createflashcode(false);
		$content = str_replace( '[WP-FlashTagCloud]', $code, $content );
		return $content;
	}
}

// template function
function wp_tagFlash_insert( $atts=NULL ){
	echo wp_tagFlash_createflashcode( false, $atts );
}

// shortcode function
function wp_tagFlash_shortcode( $atts=NULL ){
	return wp_tagFlash_createflashcode( false, $atts );
}

function wp_xmlURLpath($file){
	//path for xml file
	$blogUrl  = explode('/', get_bloginfo('home'));
	$urlServer = str_replace($blogUrl[3],"",$_SERVER['DOCUMENT_ROOT']);
	$urlPlugin = str_replace("http://".$blogUrl[2],"", plugins_url('wp-flashflyingtags/'));
	$url = $urlServer.$urlPlugin.$file;
	return $url;
}
if (isset($_GET['page']) && $_GET['page'] == 'wp-flashflyingtags/wpFlashTag.php'){
	wp_enqueue_script('jquery');	
	wp_register_script('my-upload', plugins_url("wp-flashflyingtags/script.js"), array('jquery','media-upload','thickbox'));
	wp_enqueue_script('my-upload');
	wp_enqueue_style('thickbox');
	wp_register_script('color', plugins_url("wp-flashflyingtags/jscolor/jscolor.js"), array('jquery','media-upload','thickbox'));
	wp_enqueue_script('color');	
	wp_register_style('myStyleSheets', plugins_url("wp-flashflyingtags/style.css"));
    wp_enqueue_style( 'myStyleSheets');


}
// create html tags and flash tags
function wp_tagFlash_createflashcode( $widget=false, $atts=NULL ){
// get the options
	if( $widget == true ){
		$options = get_option('wptagFlash_widget');
		$soname = "widget_so";
		$divname = "wptagFlashwidgetcontent";
		// get compatibility mode variable from the main options
		$mainoptions = get_option('wptagFlash_options');
		$options['compmode'] = $mainoptions['compmode'];
		$options['showwptags'] = $mainoptions['showwptags'];
	} else if( $atts != NULL ){
		$options = shortcode_atts( get_option('wp_tagFlash_options'), $atts );
		$soname = "shortcode_so";
		$divname = "wptagFlashcontent";
	} else {
		$options = get_option('wptagFlash_options');
		$soname = "so";
		$divname = "wptagFlashcontent";
	}
	//$flashtag .= "<script>alert('".$mainoptions['numTags']."')</script>";
	if( $mainoptions['mode'] != "cats" ){
		$i=0;
		foreach (get_tags() as $tag)
		{
		if(($i<$mainoptions['numTags']) and ($mainoptions['numTags']!=0)){
			$xml_tag .=  "<tag>";
			$xml_tag .=  "<content><![CDATA[<a class='eventTitle'>".$tag->name."</a>]]></content>";	
			$xml_tag .=  "<url>".get_tag_link($tag->term_id)."</url>";
			$xml_tag .=  "</tag>";
			}
		if($mainoptions['numTags']==0){
			$xml_tag .=  "<tag>";
			$xml_tag .=  "<content><![CDATA[<a class='eventTitle'>".$tag->name."</a>]]></content>";	
			$xml_tag .=  "<url>".get_tag_link($tag->term_id)."</url>";
			$xml_tag .=  "</tag>";			
		
		}
		$i++;
		$tagcloud .= "<a href='".get_tag_link($tag->term_id)."'>".$tag->name."</a>";
		}	
	}
		// get categories cloud
	if( $mainoptions['mode'] != "tags" ){
		foreach ((get_the_category()) as $tag)
		{
		if(($i<$mainoptions['numTags']) and ($mainoptions['numTags']!=0)){
			$xml_tag .=  "<tag>";
			$xml_tag .=  "<content><![CDATA[<a class='eventTitle'>".$tag->cat_name."</a>]]></content>";	
			$xml_tag .=  "<url>".get_tag_link($tag->cat_ID)."</url>";
			$xml_tag .=  "</tag>";
			}
		if($mainoptions['numTags']==0){
			$xml_tag .=  "<tag>";
			$xml_tag .=  "<content><![CDATA[<a class='eventTitle'>".$tag->cat_name."</a>]]></content>";	
			$xml_tag .=  "<url>".get_tag_link($tag->cat_ID)."</url>";
			$xml_tag .=  "</tag>";			
		
		}
		$i++;
		$cats .= "<a href='".get_tag_link($tag->cat_ID)."'>".$tag->cat_name ."</a>";}	
	}
	if( function_exists('plugins_url') ){ 
		// 2.6 or better
		$movie = plugins_url('wp-flashflyingtags/tagCloudWP.swf');
	} else {
		// pre 2.6
		$movie = get_bloginfo('wpurl') . "/wp-content/plugins/wp-flashflyingtags/tagCloudWP.swf";
	}
	// write flash tag
	if( $options['compmode']!='true' ){
		$flashtag .= '<div>';
		if( $options['showwptags'] == 'true' ){ $flashtag .= '<p>'; } else { $flashtag .= '<p style="display:none;">'; };
		if( $mainoptions['mode'] != "cats" ){ $flashtag .= $tagcloud; }
		if( $mainoptions['mode'] != "tags" ){ $flashtag .= $cats; }
		$xml .= "<?xml version='1.0' encoding='utf-8'?>";
		$xml .= "<?xml-stylesheet type='text/css' href='text.css'?>";
		$xml .=  "<menu>";
		$xml .=  "<tags>";
		$xml .=  $xml_tag;
		$xml .=  "</tags>";		
		$xml .=  "</menu>";
		$file = wp_xmlURLpath("xml_tag.xml");
		//$flashtag .= "<script>alert('".$file."')</script>";
		$fh = fopen($file, 'w');
		fwrite($fh, $xml);
		fclose($fh);
		$flashtag .= '</p></p>	<script type="text/javascript" src="'.plugins_url("wp-flashflyingtags/swfobject.js").'" charset="utf-8"></script>
							<script type="text/javascript" src="'.plugins_url("wp-flashflyingtags/swfaddress.js").'" charset="utf-8"></script>
							<script type="text/javascript">
								var flashvars = {
									xmlPath:          "'.plugins_url("wp-flashflyingtags/xml_option.xml").'",
									xmlPath1:          "'.plugins_url("wp-flashflyingtags/xml_tag.xml").'",
									cssPath:          "'.plugins_url("wp-flashflyingtags/css/style.css").'",
									fontPath:          "'.plugins_url("wp-flashflyingtags/fonts/Font.swf").'"
									};   
								var params = {};
								var attributes = {};
								params.bgcolor = "'.$mainoptions['bgcolor'].'";
								params.scale = "noscale";
								params.salign = "tl";
								params.wmode = "transparent"; 
 
								swfobject.embedSWF("'.plugins_url("wp-flashflyingtags/tagCloudWP.swf").'", "myAlternativeContent", "'.$mainoptions['width'].'px", "'.$mainoptions['height'].'px", "9.0.0", "'.plugins_url("wp-flashflyingtags/expressInstall.swf").'", flashvars, params, attributes);

								</script>
							<div id="myAlternativeContent"></div></div>';
	}
	return $flashtag;
}

function wp_tagFlash_short($atts){
// use [WP-FlashTagCloud id=1 width=300 height=300] 
//      id must be uniq value
//      width and height without px only number
		wp_tagFlash_createflashcode( true, NULL );
		$flashtags = '<script type="text/javascript" src="'.plugins_url("wp-flashflyingtags/swfobject.js").'" charset="utf-8"></script><script type="text/javascript" src="'.plugins_url("wp-flashflyingtags/swfaddress.js").'" charset="utf-8"></script><script type="text/javascript">
				var flashvars = {
				xmlPath:          "'.plugins_url("wp-flashflyingtags/xml_option.xml").'",
				xmlPath1:          "'.plugins_url("wp-flashflyingtags/xml_tag.xml").'",
				cssPath:          "'.plugins_url("wp-flashflyingtags/css/style.css").'",
				fontPath:          "'.plugins_url("wp-flashflyingtags/fonts/Font.swf").'"
									};   
				var params = {};
				var attributes = {};
				params.bgcolor = "'.$mainoptions['bgcolor'].'";
				params.scale = "noscale";
				params.salign = "tl";
				params.wmode = "transparent"; swfobject.embedSWF("'.plugins_url("wp-flashflyingtags/tagCloudWP.swf").'", "myAlternativeContent-'.$atts[id].'", "'.$atts[width].'px", "'.$atts[height].'px", "9.0.0", "'.plugins_url("wp-flashflyingtags/expressInstall.swf").'", flashvars, params, attributes);</script>
			   <div id="myAlternativeContent-'.$atts[id].'"></div>';
							
return $flashtags;
}
function wp_tagFlash_short_php($id,$height,$width){
/*     use <?php echo wp_tagFlash_short_php(8,300,300); ?>
			<?php echo wp_tagFlash_short_php(id,height,eidth); ?>
//      id must be uniq value
//      height and width without px only number*/
		wp_tagFlash_createflashcode( false, NULL );
		$flashtagphp = '<script type="text/javascript" src="'.plugins_url("wp-flashflyingtags/swfobject.js").'" charset="utf-8"></script>
			  <script type="text/javascript" src="'.plugins_url("wp-flashflyingtags/swfaddress.js").'" charset="utf-8"></script>
			  <script type="text/javascript">
				var flashvars = {
				xmlPath:          "'.plugins_url("wp-flashflyingtags/xml_option.xml").'",
				xmlPath1:          "'.plugins_url("wp-flashflyingtags/xml_tag.xml").'",
				cssPath:          "'.plugins_url("wp-flashflyingtags/css/style.css").'",
				fontPath:          "'.plugins_url("wp-flashflyingtags/fonts/Font.swf").'"
									};   
				var params = {};
				var attributes = {};
				params.bgcolor = "'.$mainoptions['bgcolor'].'";
				params.scale = "noscale";
				params.salign = "tl";
				params.wmode = "transparent"; 
 
				swfobject.embedSWF("'.plugins_url("wp-flashflyingtags/tagCloudWP.swf").'", "myAlternativeContent-'.$id.'", "'.$width.'px", "'.$height.'px", "9.0.0", "'.plugins_url("wp-flashflyingtags/expressInstall.swf").'", flashvars, params, attributes);

			   </script>
			   <div id="myAlternativeContent-'.$id.'"></div>';
							
return $flashtagphp;
}

// options page
function wp_tagFlash_options() {	
	$options = $newoptions = get_option('wptagFlash_options');
	// if submitted, process results
	if ( $_POST["wptagFlash_submit"] ) {
		$newoptions['width'] = strip_tags(stripslashes($_POST["width"]));
		$newoptions['height'] = strip_tags(stripslashes($_POST["height"]));
		$newoptions['tcolor'] = strip_tags(stripslashes($_POST["tcolor"]));
		$newoptions['bgcolor'] = strip_tags(stripslashes($_POST["bgcolor"]));
		$newoptions['animation'] = strip_tags(stripslashes($_POST["animation"]));
		$newoptions['mode'] = strip_tags(stripslashes($_POST["mode"]));
		$newoptions['reflection'] = strip_tags(stripslashes($_POST["reflection"]));
		$newoptions['numTags'] = strip_tags(stripslashes($_POST["numTags"]));
		$newoptions['cColor'] = strip_tags(stripslashes($_POST["cColor"]));
		$newoptions['tagCustomColor'] = strip_tags(stripslashes($_POST["tagCustomColor"]));
		$newoptions['cbgColor'] = strip_tags(stripslashes($_POST["cbgColor"]));
		$newoptions['dragAndDrop'] = strip_tags(stripslashes($_POST["dragAndDrop"]));

	}
	// if changes save!
	if ( $options != $newoptions ) {
		$boundryX = (int)$newoptions['width']+50;
		$boundryY = (int)$newoptions['height']+50;
		$options = $newoptions;
		update_option('wptagFlash_options', $options);
		$xml .= "<?xml version='1.0' encoding='utf-8'?>";
		$xml .= "<?xml-stylesheet type='text/css' href='text.css'?>";
		$xml .=  "<menu ";
		if($newoptions['tcolor'] == '') 
		$xml .=  " tagColor = 'blue' ";
		else
		$xml .=  "tagColor = '".$newoptions['tcolor']."'";
		$xml .= " animation = '".$newoptions['animation']."'
			width = '".$newoptions['width'] ."'
			height = '".$newoptions['height'] ."'
			boundryX = '". $boundryX ."'
			boundryY = '". $boundryY  ."'
			xPosition = '25'
			yPosition = '25'
			dragAndDrop = '".$newoptions['dragAndDrop'] ."'
			bgColor = '".$newoptions['bgcolor'] ."'
			background = '".$newoptions['cbgColor'] ."'
			marginBetweenTags = '10'
			imageBackground = '".plugins_url('wp-flashflyingtags/countdown.png')."'
			customColor = '".$newoptions['cColor'] ."'
			tagCustomColor = '0xffffff'
			reflection = '".$newoptions['reflection'] ."'>";
		$xml .=  "</menu>";
		
		$file = wp_xmlURLpath("xml_option.xml");
		$fh = fopen($file, 'w');
		fwrite($fh, $xml);
		fclose($fh);
	}
	// options form
	echo '<div class="allBanner">
	<div class = "buttons">
	<div class= "settingsB" id = "settingsB"><a href="" onClick="return false;">Settings</a></div>
	<div class = "helpB" id = "helpB"><a href="" onClick="return false;">Help</a></div>	
	</div>
		<div id="help"><h2 >Help</h2>
	Short Code : <b>[ WP-FlashTagCloud id=1 width=300 height=300 ]</b><br>
				 id must be unique value<br>
				 width and height withot "px" only number<br><br>
	PHP   Code : <b>< ? php echo wp_tagFlash_short_php(8,300,300); ? ></b><br>
				 id must be unique value<br>
				 width and height withot "px" only number	<br>	<br>
    Visit our support site for more help <a href="http://premiumcoding.com">PremiumCoding</a>.<br>
    In case you need additional support please contact us at <a href="mailto:info@premiumcoding.com">info@premiumcoding.com</a>	<br>	<br>
	Support our work :
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----MIIHPwYJKoZIhvcNAQcEoIIHMDCCBywCAQExggEwMIIBLAIBADCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwDQYJKoZIhvcNAQEBBQAEgYChNdx5z7hYL9fmkB9AzCXF44c70ibADUYsHMiLnGabHWJRBh5w5RoEJiH31RNE8ZMUloTwfL1RMQgn0kz6Jd2sLu3evjyHGQKGLG6PsTxYmFs7OZ6R6Q1lu+aOfRMnqqt97pi9D+OdhGO4tL6sRjZToH2QYDfZywrrNW4m7JzD/jELMAkGBSsOAwIaBQAwgbwGCSqGSIb3DQEHATAUBggqhkiG9w0DBwQIrpF/kdFaswyAgZi7cZ/Z7H0b9BMvB+MvI+Yky07GPj0KRUUaNYy1o3MsL7Fp6gZ1M86e1ZD+ISjmEVq1PoG/izCRKowcpMvAE9aIjXht/uVgkeQg5/qYbx+arqvpVlFCxGnnTcNSTlcUF8MeIygBk+a3vgpC1yMLUpB/E66i54A4jCLB2+bnT6rWigIOI58dTzqtRbGPbyFBXOLI9dXXzfDUmKCCA4cwggODMIIC7KADAgECAgEAMA0GCSqGSIb3DQEBBQUAMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbTAeFw0wNDAyMTMxMDEzMTVaFw0zNTAyMTMxMDEzMTVaMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbTCBnzANBgkqhkiG9w0BAQEFAAOBjQAwgYkCgYEAwUdO3fxEzEtcnI7ZKZL412XvZPugoni7i7D7prCe0AtaHTc97CYgm7NsAtJyxNLixmhLV8pyIEaiHXWAh8fPKW+R017+EmXrr9EaquPmsVvTywAAE1PMNOKqo2kl4Gxiz9zZqIajOm1fZGWcGS0f5JQ2kBqNbvbg2/Za+GJ/qwUCAwEAAaOB7jCB6zAdBgNVHQ4EFgQUlp98u8ZvF71ZP1LXChvsENZklGswgbsGA1UdIwSBszCBsIAUlp98u8ZvF71ZP1LXChvsENZklGuhgZSkgZEwgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tggEAMAwGA1UdEwQFMAMBAf8wDQYJKoZIhvcNAQEFBQADgYEAgV86VpqAWuXvX6Oro4qJ1tYVIT5DgWpE692Ag422H7yRIr/9j/iKG4Thia/Oflx4TdL+IFJBAyPK9v6zZNZtBgPBynXb048hsP16l2vi0k5Q2JKiPDsEfBhGI+HnxLXEaUWAcVfCsQFvd2A1sxRr67ip5y2wwBelUecP3AjJ+YcxggGaMIIBlgIBATCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwCQYFKw4DAhoFAKBdMBgGCSqGSIb3DQEJAzELBgkqhkiG9w0BBwEwHAYJKoZIhvcNAQkFMQ8XDTExMDcxNTEyNTQyNFowIwYJKoZIhvcNAQkEMRYEFMBLxjuXlklWUJz0OGyHxb4KzuzqMA0GCSqGSIb3DQEBAQUABIGAW1tPC/3YKLP3orQ+6Y9mNubjPX7rCnqG8AYrBgkyoU+HI/Q7il3qVMPo7St/khFfRxTx3ze9SUegW80NdrXHT6cbYyh2lxW+LHE5glCLskXxTWVnt61bSvhKGAlzq7mXmt7MlkhTzoz3KxUMPRmXVlUUrWlR/YPH7H9mL7zLgFs=-----END PKCS7-----">
	<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
	</form>
	
	</div>
	<form method="post"><div id="settings">';
	echo "<div class=\"wrap\"><h2>Flash Tag with HTML links Display options</h2>";
	echo '<table class="form-table">';
	// width
	echo '<tr valign="top"><th scope="row">Width of the Flash tag cloud</th>';
	echo '<td><input type="text" name="width" value="'.$options['width'].'" size="5"></input><br />Width in pixels (200 or more is recommended)</td></tr>';
	// height
	echo '<tr valign="top"><th scope="row">Height of the Flash tag cloud</th>';
	echo '<td><input type="text" name="height" value="'.$options['height'].'" size="5"></input><br />Height in pixels (300 or more is recommended)</td></tr>';
	// text color
	if($newoptions['cColor']=='true'){echo '<tr valign="top"><th scope="row">Color of the tags</th><td style="color:red;">You can select custom color, if you wish preset colors (gradients) set custom color to False</td>';}
	else{
	echo '<tr valign="top"><th scope="row">Color of the tags</th>';
		echo '<td><select name="tcolor">';
		 if($newoptions['tcolor']=='blue')
			echo '<option selected="selected" value="blue">blue</option>';
		 else
			echo '<option value="blue">blue</option>';	
		 if($newoptions['tcolor']=='red')
			echo '<option selected="selected" value="red">Red</option>';
		 else
			echo '<option value="red">red</option>';	
		 if($newoptions['tcolor']=='green')
			echo '<option selected="selected" value="green">green</option>';
		 else
			echo '<option value="green">green</option>';	
		 if($newoptions['tcolor']=='white')
			echo '<option selected="selected" value="white">white</option>';
		 else
			echo '<option value="white">white</option>';
		 if($newoptions['tcolor']=='black')
			echo '<option selected="selected" value="black">black</option>';
		 else
			echo '<option value="black">black</option>';			
	echo '</select></td></tr>';}
	echo '</table>';
	echo '<table class="form-table"><tr valign="top">';
	// transition
	echo '<tr valign="top"><th scope="row">Type of animation</th>';
	echo '<td><select name="animation">';
		 if($newoptions['animation']=='random')
			echo '<option selected="selected" value="random">random</option>';
		 else
			echo '<option value="random">random</option>';		
		 if($newoptions['animation']=='staticVertical')
			echo '<option selected="selected" value="staticVertical">staticVertical</option>';
		 else
			echo '<option value="staticVertical">staticVertical</option>';			
		 if($newoptions['animation']=='staticHorizontal')
			echo '<option selected="selected" value="staticHorizontal">staticHorizontal</option>';
		 else
			echo '<option value="staticHorizontal">staticHorizontal</option>';		
		 if($newoptions['animation']=='staticVerticalShuffle')
			echo '<option selected="selected" value="staticVerticalShuffle">staticVerticalShuffle</option>';
		 else
			echo '<option value="staticVerticalShuffle">staticVerticalShuffle</option>';	
		 if($newoptions['animation']=='staticHorizontalShuffle')
			echo '<option selected="selected" value="staticHorizontalShuffle">staticHorizontalShuffle</option>';
		 else
			echo '<option value="staticHorizontalShuffle">staticHorizontalShuffle</option>';		
	echo '</select></td></tr>';
	// Reflection
	echo '<tr valign="top"><th scope="row">Reflection:</th>';
	echo '<td><input type="radio" name="reflection" value="true"';
	if( $options['reflection'] == 'true' ){ echo ' checked="checked" '; }
	echo '></input> True<br /><input type="radio" name="reflection" value="false"';
	if( $options['reflection'] == 'false' ){ echo ' checked="checked" '; }
	echo '></input> False<br /></td></tr>';
	//drag and drop
		echo '<tr valign="top"><th scope="row">Drag and Drop:</th>';
	echo '<td><input type="radio" name="dragAndDrop" value="true"';
	if( $options['dragAndDrop'] == 'true' ){ echo ' checked="checked" '; }
	echo '></input> True<br /><input type="radio" name="dragAndDrop" value="false"';
	if( $options['dragAndDrop'] == 'false' ){ echo ' checked="checked" '; }
	echo '></input> False<br /></td></tr>';
	// Numbers of tags
	echo '<tr valign="top"><th scope="row">Numbers of tags:</th>';
	echo '<td><input type="text" name="numTags" value="'.$options['numTags'].'" size="8"></input><br />Numbers of tags to display in widget. 0 means no limit. We recommend no more than 25 tags. All tags generated are in HTML for SEO purposes.</td></tr>';
	echo '</table>';
	/*echo '<h3>Custom Tag Color</h3>';	
	// use advance color options
	echo '<table class="form-table" style="border:1px solid red; width:650px;"><tr valign="top"><th scope="row">Use custom color:</th>';
	echo '<td><input type="radio" name="cColor" value="true"';
	if( $options['cColor'] == 'true' ){ echo ' checked="checked" '; }
	echo '></input> True (if you select true "Color of the tags" will be disabled)<br /><input type="radio" name="cColor" value="false"';
	if( $options['cColor'] == 'false' ){ echo ' checked="checked" '; }
	echo '></input> False<br /></td></tr>';
	// custom tag color
	echo '<tr valign="top"><th scope="row">Tag custom color</th>';
	if($newoptions['cColor']!='true'){echo '<td style="color:red;">If you wish custom tag color you must select "Use custom color" to true</td>';}
	else{
	echo '<td><input type="text" name="tagCustomColor" value="'.$options['tagCustomColor'].'" size="8"></input><br />6 character hex color value</td></tr>';}
	echo '</table><br>';*/
	//custom background color
	echo '<h3>Custom Background Color</h3>';	
	// use advance color options
	echo '<table class="form-table" ><tr valign="top"><th scope="row">Use custom color</th>';
	echo '<td><input type="radio" name="cbgColor" value="false"';
	if( $options['cbgColor'] == 'false' ){ echo ' checked="checked" '; }
	echo '></input> True (if you select true default background will be disabled)<br /><input type="radio" name="cbgColor" value="true"';
	if( $options['cbgColor'] == 'true' ){ echo ' checked="checked" '; }
	echo '></input> False<br /></td></tr>';
	// custom bg color
	echo '<tr valign="top"><th scope="row">Background Color</th>';
	if($newoptions['cbgColor']!='false'){echo '<td style="color:red;">If you wish custom background color you must set "Use custom color" to true</td>';}
	else{
	echo '<td><input type="text" class="color" name="bgcolor" value="'.$options['bgcolor'].'" size="8"></input><br />6 character hex color value</td>';}
	echo '</tr></table><br>';
	// tags, cats, both?
	echo '<h3>Output options</h3>';
	echo '<table class="form-table" >';
	echo '<tr valign="top"><th scope="row">Display:</th>';
	echo '<td><input type="radio" name="mode" value="tags"';
	if( $options['mode'] == 'tags' ){ echo ' checked="checked" '; }
	echo '></input> Tags<br /><input type="radio" name="mode" value="cats"';
	if( $options['mode'] == 'cats' ){ echo ' checked="checked" '; }
	echo '></input> Categories<br /><input type="radio" name="mode" value="both"';
	if( $options['mode'] == 'both' ){ echo ' checked="checked" '; }
	echo '></input> Both (you may want to consider lowering the number of tags)';
	// end table
	echo '</table>';
	echo '<input type="hidden" name="wptagFlash_submit" value="true"></input>';
	echo '<p class="submit"><input type="submit" value="Update Options &raquo;"></input></p>';
	echo "</div></div>";
	echo '</form></div>';
	//$paypal = new paypal_donations();
	//$atts='';
    //echo '<div style="font-size:10px;">Support our work and donate. <div style="position:absolute;margin:-25px 0 0 180px;">'.$paypal->paypal_shortcode($atts).'<br/><br/></div>';
	echo '<br/><br/><div style="font-size:10px;">Visit our support site <a href = "http://premiumcoding.com/wordpress-fyling-tags-plugin/">PremiumCoding</a>.</div>';
}

//uninstall all options
function wp_tagFlash_uninstall () {
	delete_option('cumulus_options');
	delete_option('cumulus_widget');
}


// widget
function widget_init_wp_tagFlash_widget() {
	// Check for required functions
	if (!function_exists('register_sidebar_widget'))
		return;

	function wp_tagFlash_widget($args){
	    extract($args);
		$options = get_option('wptagFlash_widget');
		?>
	        <?php echo $before_widget; ?>
			<?php if( !empty($options['title']) ): ?>
				<?php echo $before_title . $options['title'] . $after_title; ?>
			<?php endif; ?>
			<?php
				if( !stristr( $_SERVER['PHP_SELF'], 'widgets.php' ) ){
					echo wp_tagFlash_createflashcode(true);
				}
			?>
	        <?php echo $after_widget; ?>
		<?php
	}
	
	function wp_tagFlash_widget_control() {
		$options = $newoptions = get_option('wptagFlash_widget');
		if ( $_POST["wptagFlash_widget_submit"] ) {
			$newoptions['title'] = strip_tags(stripslashes($_POST["wptagFlash_widget_title"]));
		}
		if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option('wptagFlash_widget', $options);
		}
		$title = attribute_escape($options['title']);

		?>
			<p><label for="wptagFlash_widget_title"><?php _e('Title:'); ?> <input class="widefat" id="wptagFlash_widget_title" name="wptagFlash_widget_title" type="text" value="<?php echo $title; ?>" /></label></p>
			<input type="hidden" id="wptagFlash_widget_submit" name="wptagFlash_widget_submit" value="1" />
		<?php
	}
	
	wp_register_sidebar_widget( "WP-FlashTagCloud", "WP-FlashTagCloud","wp_tagFlash_widget" );
	wp_register_widget_control( "WP-FlashTagCloud", "WP-FlashTagCloud",'wp_tagFlash_widget_control' );
}

// Delay plugin execution until sidebar is loaded
add_action('widgets_init', 'widget_init_wp_tagFlash_widget');

// add the actions
add_action('admin_menu', 'wp_tagFlash_add_pages');
register_activation_hook( __FILE__, 'wp_tagFlash_install' );
register_deactivation_hook( __FILE__, 'wp_tagFlash_uninstall' );

	add_shortcode('WP-FlashTagCloud', 'wp_tagFlash_short');


?>