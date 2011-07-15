<?php
################################################################################################################################
/**
 * @package Video_sitemap_xml
 * @Create sitemap xml for videos
 */
###############################################################################################################################

/* 
Create different types of form to upload video and thumbnail

*/
?>
<link rel="stylesheet" type="text/css" href="video.css" />
<script language="javascript">
function showhide(id)
{
if(document.getElementById('err'))
	document.getElementById('err').innerHTML = '';
	if(id == 1)
	{
	document.getElementById('url_div').style.display= 'block';
	document.getElementById('local_div').style.display= 'none';
	document.getElementById('library_div').style.display= 'none';
	}
	else if(id == 2)
	{
	document.getElementById('url_div').style.display= 'none';
	document.getElementById('local_div').style.display= 'block';
	document.getElementById('library_div').style.display= 'none';
	}
	else if(id == 3)
	{
	document.getElementById('url_div').style.display= 'none';
	document.getElementById('local_div').style.display= 'none';
	document.getElementById('library_div').style.display= 'block';
	document.getElementById('err1').innerHTML= '';
	}
}

function show_img_video(sid)
{
	if(sid == 1)
	{
	document.getElementById('lib_video_div').style.display= 'block';
	document.getElementById('lib_img_div').style.display= 'none';
	}
	else if(sid == 2)
	{
	document.getElementById('lib_video_div').style.display= 'none';
	document.getElementById('lib_img_div').style.display= 'block';
	}
}

function sel_thumb(nav)
{
	if(nav == 'next')
	{
	var ctr=0;
	radio_button_obj = document.getElementsByName('video_name');
	for(var i=0;i<radio_button_obj.length;i++)
		{
			if(radio_button_obj[i].checked == true)
				ctr++;
		}
	if(ctr==0)
	{
		document.getElementById('err1').innerHTML = 'Please select a video file';	
		return false;
	}
	document.getElementById('lib_video_div').style.display= 'none';
	document.getElementById('lib_img_div').style.display= 'block';
	document.getElementById('btn_pre').innerHTML = 'Select Thumbnail Image';
	document.getElementById('btn_next').innerHTML= '<a href="#" style="text-decoration:none;" onclick="sel_thumb(\'pre\')">back</a>';
	}
	else
	{
	document.getElementById('err1').innerHTML = '';	
	document.getElementById('lib_video_div').style.display= 'block';
	document.getElementById('lib_img_div').style.display= 'none';
	document.getElementById('btn_pre').innerHTML = 'Select Video File';
	document.getElementById('btn_next').innerHTML= '<a href="#" style="text-decoration:none;" onclick="sel_thumb(\'next\')">Next</a>';
	}
}
</script>

<?php
display_form();
function display_form(){
$frm_type = @$_POST['form_type'];
?>
<h2 class="headingH3">Select Your Video</h2><br />
<input type="radio" name="upload_type" value="url" <?php echo ($frm_type==1 || $frm_type =='') ? "checked" : ''?> onclick="showhide(1)"><b class="headingH3">Add  Youtube or Self Hosted Video Url</b>
<input type="radio" name="upload_type" value="computer" <?php echo ($frm_type==2) ? "checked" : ''?> onclick="showhide(2)"><b class="headingH3">Add File From Your Computer</b>
<input type="radio" name="upload_type" value="computer" <?php echo ($frm_type==3) ? "checked" : ''?> onclick="showhide(3)"><b class="headingH3">Add File From Library</b>
<hr>
<div id="url_div" <?php echo ($frm_type==1 || $frm_type=='') ? "" : "style=display:none;" ?> >
<form action="#" method="post" enctype="multipart/form-data">
<input type="hidden" name="form_type" value="1" />
<table width="100%">
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>Paste Video Url:<span style="color:#FF0000;">*</span></td><td><input type="text" name="txt_url" style="width:400px;" value="<?php echo isset($_POST['txt_url']) ? $_POST['txt_url'] : '' ?>"></td></tr>
<tr><td>Select Thumbnail Image:</td><td><input type="file" name="thumb"></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td colspan="2" align="center"><input type="submit" name="url_submit" value="Insert into Post" class="btn_submit"></td></tr>
</table>
</form>
</div>
<div id="local_div" <?php echo ($frm_type==2) ? "" : "style=display:none;" ?> >
<form action="#" method="post" enctype="multipart/form-data" >
<input type="hidden" name="form_type" value="2" />
<table width="100%">
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td class="headingH3" colspan="2">Maximum upload file size: 120MB</td></tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td>Select Video File:<span style="color:#FF0000;">*</span></td><td><input type="file" name="media_file" value="test"></td></tr>
<tr><td>Select Thumbnail Image:</td><td><input type="file" name="media_thumb"></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td colspan="2" align="center"><input type="submit" name="file_submit" value="Insert into Post" class="btn_submit"></td></tr>
</table>
</form>
</div>
<div id="library_div"  <?php echo ($frm_type==3) ? "" : "style=display:none;" ?> >
<form action="" method="post" name="frm_library">
<input type="hidden" name="form_type" value="3" />
<input type="radio" onclick="show_img_video(1)" checked="checked" name="liv_select" /><b class="headingH3" id="btn_pre">Select Video File</b>
<br /><br />
<div id="lib_video_div">
<?php
$count_file = 0;
if ($handle = opendir(dirname(__FILE__).'/upload/video_file'))
{
echo '<table  border="1" cellspacing="0" width=100%>';
    while (false !== ($file = readdir($handle))) {
		if ($file != "." && $file != ".."){
        echo '<tr><td>'.substr($file,10).'</td><td><input type="radio" name="video_name" value="'.$file.'">select</td></tr>';
		$count_file++;
		}
    }
echo '</table>';
    closedir($handle);
	
	if($count_file == 0)
		echo '<div style="color:#FF0000;" align="center">Video library is empty</div>';
}	
?>
	<div id="err1"  style="color:#FF0000;" align="center"></div>
</div>

<div id="lib_img_div" style="display:none;">
<?php
if ($handle = opendir(dirname(__FILE__).'/upload/thumb'))
{
echo '<table  border="1" cellspacing="0"  width=100%>';
    while (false !== ($file = readdir($handle))) {
		if ($file != "." && $file != "..")
        echo '<tr><td><img src="'. plugins_url() . '/video_sitemap_xml/upload/thumb/'.$file.'" height="40" width="40" /></td><td width="300" align="right" style="padding-right:5px;"><input type="radio" name="thumb_name" value="'.$file.'">select</td></tr>';
    }
echo '</table>';
    closedir($handle);
}	
?>
<div align="center" style="padding-top:10px;"><input type="submit" value="Insert into Post" name="lib_submit" class="btn_submit" /></div>
</div>
<?php if($count_file!=0) {?>
<div class="headingH3" id="btn_next" align="center" style="padding-top:10px;"><a href="#" style="text-decoration:none;" onclick="sel_thumb('next')">Next</a></div>
<?php } ?>
</form>
</div>
<?php
}
$upload_dir = dirname(__FILE__).'/upload';

// testing submit for url
if(isset($_POST['url_submit']))  
{
	$html_data = '';
	if(trim($_POST['txt_url']) !='')
	{
		$html_data = '[video url="'.$_POST['txt_url'];
		if($_FILES["thumb"]["error"] == 0)
		{
		
		if(!CheckFilePermission($upload_dir))
			{
				echo '<div id="err"  style="color:#FF0000;" align="center">You have no write permission on<br>'.dirname(__FILE__).'</div>';
				exit;
			}
			if(check_image_type($_FILES["thumb"]['name']))
			{
				$upload_dir2 = dirname(__FILE__).'/upload/thumb';
				$tmp_name2 = $_FILES["thumb"]["tmp_name"];
				$file2 = time().$_FILES["thumb"]['name'];
				$st2 = move_uploaded_file($tmp_name2, "$upload_dir2/$file2");
				
				$thumbUrl = plugins_url()."/video_sitemap_xml/upload/thumb/$file2";
				$html_data .= '" thumb="'.$thumbUrl;
			}
			else
			{
				echo '<div id="err" style="color:#FF0000;" align="center">Valid allowed file types :jpg,jpeg,gif,png</div>';
				exit;
			}
		}
		$html_data .= '"]';
	}
	else
	{
		echo '<div id="err" style="color:#FF0000;" align="center">Please fill the youtube or self-hosted video url</div>';
		exit;
	}
	
	content_for_editor($html_data);
}
// End url submit 

// testing for upload from PC
if(isset($_POST['file_submit']))  
{
$fileUrl = '';
$thumbUrl = '';
$html_data = '';

if($_FILES["media_file"]["tmp_name"] == '')
	{
		echo '<div id="err"  style="color:#FF0000;" align="center">Please select video file</div>';
		exit;
	}

if(!CheckFilePermission($upload_dir))
	{
		echo '<div id="err"  style="color:#FF0000;" align="center">You have no write permission on<br>'.dirname(__FILE__).'</div>';
		exit;
	}
$file_size = $_FILES["media_file"]["size"];
if($file_size <= 125829120)  // testing file size is less than 120MB  
{
if($_FILES["media_file"]["error"] == 0)
{
	$video_ext = array('mp4','wmv','mov','avi');
	$ext = strtolower(end(explode('.',$_FILES["media_file"]['name'])));
	if(in_array($ext,$video_ext))
	{
		$upload_dir1 = dirname(__FILE__).'/upload/video_file';
		$tmp_name1 = $_FILES["media_file"]["tmp_name"];
		$file1 = time().$_FILES["media_file"]['name'];
		$st1 = move_uploaded_file($tmp_name1, "$upload_dir1/$file1");
		
		$fileUrl = plugins_url()."/video_sitemap_xml/upload/video_file/$file1";
		$html_data = '[video url="'.$fileUrl;
		
		if($_FILES["media_thumb"]["error"] == 0)
		{
			if(check_image_type($_FILES["media_thumb"]['name']))
			{
			$upload_dir2 = dirname(__FILE__).'/upload/thumb';
			$tmp_name2 = $_FILES["media_thumb"]["tmp_name"];
			$file2 = time().$_FILES["media_thumb"]['name'];
			$st2 = move_uploaded_file($tmp_name2, "$upload_dir2/$file2");
			$thumbUrl = plugins_url()."/video_sitemap_xml/upload/thumb/$file2";
			$html_data .= '" thumb="'.$thumbUrl;
			}
			else
			{
				echo '<div id="err" style="color:#FF0000;" align="center">Valid allowed file types :jpg,jpeg,gif,png</div>';
				exit;
			}
		}
		$html_data .= '"]';
	}
	else
	{
		echo '<div id="err"  style="color:#FF0000;" align="center">Please select only video File i.e(mp4, wmv, mov, avi) file</div>';
		exit;
	}
}
}
else
	{
		echo '<div id="err"  style="color:#FF0000;" align="center">This file exceeds the maximum upload size for this site</div>';
		exit;
	}

content_for_editor($html_data);
}
// end pc submit

// Submit action for library
if(isset($_POST['lib_submit']))
{
$html_data = '';
if(trim($_POST['video_name']) != '')
{
	$html_data .= '[video url="' . plugins_url() . "/video_sitemap_xml/upload/video_file/".$_POST['video_name'];
	if(trim($_POST['thumb_name']) != '')
	$html_data .= '" thumb="' . plugins_url() . "/video_sitemap_xml/upload/thumb/".$_POST['thumb_name'];
	$html_data .= '"]';
}

content_for_editor($html_data);
}
// end library submit
?>