<?php
################################################################################################################################
/**
 * @package Video_sitemap_xml
 * @Create sitemap xml for videos
 */
###############################################################################################################################

// Add video button to upload video
function wdtsitemap_buttons( ) {
	echo '<a href="'.get_bloginfo('home').'/wp-content/plugins/video_sitemap_xml/sitemap_media-upload.php?post_id=453&type=video&TB_iframe=1&width=100&height=100" id="add_video" class="thickbox" title="Video"><img src="';
	echo esc_url( plugins_url( ) . '/' . dirname( plugin_basename( __FILE__ ) ) . '/camera-video.png' );
	echo '" alt="Video" width="16" height="16" /></a>';
}

// add new button in tools option 
function wdtgenerate_sitemappage () {
	if (function_exists ('add_submenu_page'))
    	add_submenu_page ('tools.php', __('Video Sitemap'), __('Video Sitemap'),
        	'manage_options', 'wdtgenerate_sitemappage', 'wdtgenerate_sitemap');
}

//Checks if a file is writable and tries to make it if not.
function CheckFilePermission($filename) {
	//can we write?
	if(!is_writable($filename)) {
		//no we can't.
		if(!@chmod($filename, 0666)) {
			$pathtofilename = dirname($filename);
			//Lets check if parent directory is writable.
			if(!is_writable($pathtofilename)) {
				//it's not writeable too.
				if(!@chmod($pathtoffilename, 0666)) {
					//darn couldn't fix up parrent directory this hosting is foobar.
					//Lets error because of the permissions problems.
					return false;
				}
			}
		}
	}
	//we can write, return 1/true/happy dance.
	return true;
}


# might give this a delay to avoid running into issues with YouTube.
function play_time ($id) {
	try {
		$ch = curl_init ();
		curl_setopt ($ch, CURLOPT_URL, "http://gdata.youtube.com/feeds/api/videos/$id");
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec ($ch);
		curl_close ($ch);

		preg_match ("/duration=['\"]([0-9]*)['\"]/", $data, $match);
		return $match [1];

	} catch (Exception $e) {
		# returning 0 if the YouTube API fails for some reason.
		return '0';
	}
}


// Search video link in posts
function createSitemap() {
	global $wpdb;

	$posts = $wpdb->get_results ("SELECT id, post_title, post_content, post_date_gmt, post_excerpt 
							FROM $wpdb->posts 
							WHERE post_status = 'publish' 
							AND post_type = 'post'
							AND (post_content LIKE '%youtube.com%' or post_content LIKE '%[video url=%') 
							ORDER BY post_date DESC");

	if (empty ($posts)) {
	//$xml = "Video not available";
		$xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";       
		$xml .= '<!-- Created by (http://wdtmedia.com) -->' . "\n";
		$xml .= '<!-- Generated-on="' . date("F j, Y, g:i a") .'" -->' . "\n";		     
		$xml .= '<?xml-stylesheet type="text/xsl" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/video_sitemap_xml/videositemap.xsl"?>' . "\n" ;		
	    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">' . "\n";
		$xml .= "<dt>5</dt>";
		$xml .= "\n</urlset>";

	} else {

		$xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";       
		$xml .= '<!-- Created by (http://wdtmedia.com) -->' . "\n";
		$xml .= '<!-- Generated-on="' . date("F j, Y, g:i a") .'" -->' . "\n";		     
		$xml .= '<?xml-stylesheet type="text/xsl" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/video_sitemap_xml/videositemap.xsl"?>' . "\n" ;		
	    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">' . "\n";
		
		$selfUrl = get_bloginfo('url');
		foreach ($posts as $post) {
		$meta_title = get_post_meta($post->id, 'mcustom-seo-title',true );
		$meta_desc = get_post_meta($post->id, 'mcustom-seo-desc',true );
				
				if((preg_match_all ("(\[video url=\"http:\/\/[^www.youtube.com][^youtube.com]*)", 
				$post->post_content, $matches2, PREG_SET_ORDER)))
				{
					$flag=1;
				}
				
				if(preg_match_all ("(<embed|<iframe[^>]*src=['\"]http:\/\/www.youtube.com\/v\/([a-zA-Z0-9\-_]*)|youtube.com\/watch\?v=([a-zA-Z0-9\-_]*)|youtube.com\/embed\/([a-zA-Z0-9\-_]*))", 
				$post->post_content, $matches, PREG_SET_ORDER)|| $flag==1){
					$matches=array_merge($matches2,$matches);
					$excerpt = ($post->post_excerpt != "") ? $post->post_excerpt : $post->post_title ; 
					$permalink = get_permalink($post->id); 

					$beg=0;
					$end=0; 
					$contentLen = strlen($post->post_content);
					foreach ($matches as $match) {
						$id = $match [1];
						if ($id == '') $id = $match [2];
						if ($id == '') $id = $match [3];
					$thumb_path = '';
					$beg =  strpos($post->post_content, 'thumb="', $beg);
					if($beg)
					{
						$thumb_path='';
						$end =  strpos($post->post_content,'"]',$beg+7);
						if($end <= $contentLen) {
							$thumb_path = substr($post->post_content,$beg+7,$end-$beg-7);
							$beg = $end;
						}
					}else
					{
						$beg = $end;
					}
					
					
					$xml .= "\n <url>\n";
					$xml .= " <loc>$permalink</loc>\n";
					$xml .= " <video:video>\n";
					$xml .= "  <video:player_loc allow_embed=\"yes\" autoplay=\"autoplay=1\">http://www.youtube.com/v/$id</video:player_loc>\n";
					
					if(!empty($thumb_path) && count(file($thumb_path))>0)
					{
						$xml .= "  <video:thumbnail_loc>".$thumb_path."</video:thumbnail_loc>\n";
					}
					/*else if(!empty($id))
					{
						//$xml .= "  <video:thumbnail_loc>http://i.ytimg.com/vi/$id/2.jpg</video:thumbnail_loc>\n";
						$xml .= "  <video:thumbnail_loc>http://i.ytimg.com/vi/$id/default.jpg</video:thumbnail_loc>\n";
					}*/
					else
					{
						$xml .= "  <video:thumbnail_loc>$selfUrl/wp-content/plugins/video_sitemap_xml/noimage.gif</video:thumbnail_loc>\n";
					}	
					
					
					$xml .= "  <video:title>" . $meta_title . "</video:title>\n";
					$xml .= "  <video:description>" . $meta_desc . "</video:description>\n";
	
                    if ($_POST['time'] == 1) {  
						$duration = play_time ($id);
						if ($duration != 0)
							$xml .= "  <video:duration>".play_time ($id)."</video:duration>\n";
					}

					$xml .= "  <video:publication_date>".date (DATE_W3C, strtotime ($post->post_date_gmt))."</video:publication_date>\n";
	
					$posttags = get_the_tags($post->id); if ($posttags) { 
					$tagcount=0;
					foreach ($posttags as $tag) {
						if ($tagcount++ > 32) break;
						$xml .= "   <video:tag>$tag->name</video:tag>\n";
						}
					}	

					$postcats = get_the_category($post->id); if ($postcats) { 
					foreach ($postcats as $category) {
						$xml .= "   <video:category>$category->name</video:category>\n";
						break;
						}
					}		

					$xml .= " </video:video>\n </url>";
				}
			}
		}

		$xml .= "\n</urlset>";
	}

	$video_sitemap_url = ABSPATH.'videositemap.xml';;
	if (CheckFilePermission($_SERVER["DOCUMENT_ROOT"]) || CheckFilePermission($video_sitemap_url)) {
		if (file_put_contents ($video_sitemap_url, $xml)) {
			return true;
		}
	} 

echo '<br /><div class="wrap"><p>Unable to save xml file because the folder have no write permission. Create a file videositemap.xml in your wordpress root folder and paste  the following text</p><br /><textarea rows="30" cols="150" style="font-family:verdana; font-size:11px;color:#666;background-color:#f9f9f9;padding:5px;margin:5px">' . $xml . '</textarea></div>';	
	exit();
}


// upload default thumbnail
function wdtgenerate_sitemap () {
if(@$_POST['submit_image']){
	if($_FILES['upload_file']['name'] == '')
	{
		echo '<div class="error">Please select a file</div>';
	}
	else
	{
		$image_ext = end(explode('.',$_FILES['upload_file']['name']));
		$img_array = array('jpeg','jpg','gif','png');
		if(in_array($image_ext,$img_array))
		{
			$upload_dir = dirname(__FILE__);
			if(!CheckFilePermission($upload_dir.'/noimage.gif'))
			{
			echo '<div class="error">You have no write permission on '.dirname(__FILE__).'/noimage.gif</div>';
			}
			else
			{
			$res = move_uploaded_file($_FILES['upload_file']['tmp_name'],$upload_dir.'/noimage.gif');
			}
		}
		else
		{
		echo '<div class="error">Valid allowed file types :jpg,jpeg,gif,png</div>';
		}
	}
}

	if ($_POST ['submit']) {
		$st = createSitemap ();
		if (!$st) {
echo '<br /><div class="error"><h2>Oops!</h2><p>None of your blog posts contain Either YouTube videos Or Self-hosted videos.</p></div>';	
exit();
}
?>
<div class="wrap">
<h2>Sitemap for Videos</h2>
<?php $sitemapurl = get_bloginfo('home') . "/videositemap.xml"; ?>
<p>The XML Sitemap has been generated successfully. To confirm that there are no errors,Please open the <a target="_blank" href="<?php echo $sitemapurl; ?>">Sitemap file</a> in web browser.</p>
<?php } else { ?>
<div class="wrap">
  <h2>Sitemap for Videos</h2>
  
  <h3>Please click on the below button to generate video sitemap :</h3>
  <form id="options_form" method="post" action="">
    <div class="submit">
      <input type="submit" name="submit" id="sb_submit" value="Generate Video Sitemap" />
    </div>
  </form>
 <a style="text-decoration:none;" href="<?php echo get_bloginfo('url') ?>/videositemap.xml" target="_blank">Display Video Sitemap</a>
</div>

<div class="wrap">
<h3>Upload a default thumbnail :</h3>
<form action="" method="post" enctype="multipart/form-data" style="padding-top:20px;">
<input type="file" name="upload_file" />
<input type="submit" value="change" name="submit_image" />
</form>
<a href="#" style="text-decoration:none; padding-top:20px; float:left;" onClick="window.open('<?php echo get_bloginfo('url') ?>/wp-content/plugins/video_sitemap_xml/default.html','mywindow','width=300,height=300')">Preview Default Thumbnail</a>
</div>
<?php	}
}

//  shorcode reader video_sitemap_xml
function my_video_shortcode($atts) {
	extract(shortcode_atts(array(
		"url" => '',
		"thumb" => '',
		"width" =>'560',
		"height"=>'350'
	),$atts));
	$vidioLink = substr($url,0,23);
	if($vidioLink == 'http://www.youtube.com/')
	{
		$embUrl = split('[=&?]',$url);
		if(count($embUrl)>1)
			{
			$url = 'http://www.youtube.com/embed/'.$embUrl[2];
			}
		return '<iframe width="'.$width.'" height="'.$height.'" src="'.$url.'" frameborder="0" allowfullscreen></iframe>';
	}
	 else if(substr($url, -3, 3) == 'wmv')
	 {
	 	return '<OBJECT ID="MediaPlayer" WIDTH="'.$width.'" HEIGHT="'.$height.'" CLASSID="CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95"
		STANDBY="Loading Windows Media Player components..." TYPE="application/x-oleobject">
		<PARAM NAME="FileName" VALUE="'.$url.'">
		<PARAM name="autostart" VALUE="false">
		<PARAM name="ShowControls" VALUE="true">
		<param name="ShowStatusBar" value="false">
		<PARAM name="ShowDisplay" VALUE="false">
		<EMBED TYPE="application/x-mplayer2" SRC="'.$url.'" NAME="MediaPlayer"
		WIDTH="'.$width.'" HEIGHT="'.$height.'" ShowControls="1" ShowStatusBar="0" ShowDisplay="0" autostart="0"> </EMBED>
		</OBJECT>';
	 }
	else
	{
	return '<OBJECT CLASSID="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" WIDTH="'.$width.'" HEIGHT="'.$height.'" CODEBASE="http://www.apple.com/qtactivex/qtplugin.cab"><PARAM name="SRC" VALUE="'.$url.'"><PARAM name="AUTOPLAY" VALUE="false"><PARAM name="CONTROLLER" VALUE="true"><embed src="'.$url.'" WIDTH="'.$width.'" HEIGHT="'.$height.'" AUTOPLAY="false" CONTROLLER="true" PLUGINSPAGE="http://www.apple.com/quicktime/download/"></embed></OBJECT>';
	}
	
}
add_shortcode('video', 'my_video_shortcode');
//  ################ End of shortcode reader video_sitemap_xml  ##############//

//  Testing Image type
function check_image_type($ftype)
{
$ext = strtolower(end(explode('.',$ftype)));
$img_array = array('jpeg','jpg','gif','png');

//	if($ftype == "image/jpeg" || $ftype == "image/jpg" || $ftype == "image/gif" || $ftype == "image/png" )
	if(in_array($ext,$img_array))
		return 1;
		else
			return 0;
}

// function to pass content to the wordpress editor
function content_for_editor($html_content)
{
?>
<script language="javascript">
var win = window.dialogArguments || opener || parent || top;
win.send_to_editor('<?php echo addslashes($html_content); ?>');
</script>
<?php
}
?>