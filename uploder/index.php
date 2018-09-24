<?php
$hash=htmlspecialchars(stripslashes($_GET["hash"]));
$hash=md5("test");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html><head>
<title>video.novgorod.ru</title>
<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">

<style type="text/css">
#cnuploader_progressbar {display:none;margin-top:10px;height:16px;font-family:sans-serif;font-size:12px;padding:3px;width:300px;position:absolute;text-align:center;color:black;border:1px solid black;display:hidden;}
#cnuploader_progresscomplete {display:none;margin-top:10px;height:16px;font-family:sans-serif;font-size:12px;padding:3px;width:0;text-align:center;background-color:blue;color:white;border:1px solid transparent;display:hidden;}
</style>

<script type="text/javascript" src="fileuploader.js?nc=<?php print time();?>"></script>

</head>
<body onload="ShowForm();">
<div class="content">

<form action="./" method="post" id="uploadform" onsubmit="return false;" style="display:none;">

    <table class="colortable" cellspacing=1>
        <tr><td><div id="message">url:</div></td><td><input type="text" name="url" id="url-form" /></td></t>
		<tr><td><div id="message">Выберите файл:</div></td><td><input type="file" id="files" name="files[]" /></td></t>
	</table>
    <input type="submit" value="Загрузить &gt;&gt;" />

</form>

<div id="cnuploader_progressbar"></div>
<div id="cnuploader_progresscomplete"></div>

</div>

<script type="text/javascript">

function ShowForm() {

	var uploader=new FileUploader( {
		message_error: 'Ошибка при загрузке файла', 
		form: 'uploadform',
		formfiles: 'files',
		uploadid: '<?php print $hash;?>',
		uploadscript: 'http://api.amazonS3.ddev/app_dev.php/v1/file',
		redirect_success: './step2.php?hash=<?php print $hash;?>',
		redirect_abort: './abort.php?hash=<?php print $hash;?>',
		portion: 1024*512
//		portion: 1024*1024*2
		});

	if (!uploader) document.location='/upload/iframe.php?hash=<?php print $hash;?>';
	else {
		if (!uploader.CheckBrowser()) document.location='/upload/iframe.php?hash=<?php print $hash;?>';
		else {
			var e=document.getElementById('uploadform');
			if (e) e.style.display='block';

			}
		}
	}

</script>

</body>
</html>