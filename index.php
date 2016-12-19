<?php
include_once('Lib/GetFile.class.php');	

//获取当前目录
@$getFile=$_GET['file'];	
$getFile=str_replace("\\", "/",$getFile);	
$getFile=str_replace(".", "",$getFile);	
$getFile=trim($getFile);

$rootUrl='./';  				//根目录
$fileUrl='Uploads/'.$getFile; 	//文件目录

//实例化目录类
$file = new GetFile($rootUrl,$fileUrl);
$file_arr=$file->displaydir();

//操作	
$action=@$_GET['action'];
switch($action){
	case 'del': //删除
		$del_url=$_GET['file'];
		$del_name=$_GET['filename'];
		if(file_exists($del_name)){
			if(is_dir($del_name)){//删除目录
				$i=0;
	            if ($handle = @opendir($del_name))
	            {
	                while (false !== ($file = readdir($handle)))//读取文件夹里的文件
	                {
	                   if($file!="."&&$file!="..")
	                   {
	                         $file_array[$i]["filename"]=$file;
	                         $i++;
	                   }
	                }
	                closedir($handle);//关闭文件夹
	            }
				if($i){
					echo "<script>alert('该目录存在子文件，请先删除目录下文件');history.back();</script>";
				}else{
					@rmdir($del_name);
					header("Location:?file=".$del_url);
				}
			}else{ //删除文件
				@unlink($del_name);
				header("Location:?file=".$del_url);
			}
		}
		break;
	case 'mkdir': 
		$name=$_POST['mkdirfile'];
		if(file_exists($name)){
			echo "<script>alert('该目录已存在');</script>";
			echo "<script>history.back();</script>";
		}else{
			if($name){
				mkdir($name, 0700);
				header("Location:?file=".$_GET['file']);
			}else{
				echo '目录名称不能为空';
				header("Location:?file=".$_GET['file']);
			}
		}
		break;	
	case 'add':
		$name=$_POST['addname'];
		if(file_exists($name)){
			echo "<script>alert('该文件已存在');</script>";
			echo "<script>history.back();</script>";
		}else{
			@fopen($name, "w");
			header("Location:?file=".$_GET['file']);
		}
   		break;
	case 'touch':
		$touch_val=$_GET['touch_val'];
		$filename=$_GET['filename'];
		if($touch_val && $filename){
			chmod($filename,$touch_val);
			header("Location:?file=".$_GET['file']);
		}else{
			echo "<script>alert('权限值不能为空');</script>";
			echo "<script>history.back();</script>";
		}
		break;
	case 'uploads':
		//include_once('uploads.class.php');
		$up_file=$_FILES['uploadfile'];

		move_uploaded_file($up_file['tmp_name'], $up_file['name']);
		header("Location:?file=".$_GET['file']);
		break;
}
?>
<html>
	<head>
		<meta charset="utf-8"
		<title></title>
		
		
		<style type="text/css">
			*{
				padding: 0;
				margin: 0;
			}
			img{
				border: none;
			}
			a{
				text-decoration: none;
			}
			.table{
				margin: 0 auto;
			}
			.table tr{
				height:30px;
			}
			
		</style>
	</head>
	<body>
		<table  class="table table-hover " border="0" align="center" cellspacing="3" cellpadding="3" width="1000">
			<tbody>
				<tr>
					<th colspan="2" width="100%" bgcolor="#00bfff">&nbsp;<font size="6" color="white" face="arial, helvetica">文件管理系统</font> &nbsp;</th>
				</tr>
			
				<tr>
					<td colspan="2">
						<hr>
					</td>
				</tr>
				<tr>
					<td colspan="2"><b><font size="3" color="#4C4C99" face="arial, helvetica">当前位置： <?php if($getFile)echo $getFile;else echo '/';?></font></b></td>
				</tr>
				<tr>
					<td colspan="6">
						<table border="0" width="100%">
							<tbody>
								<tr>
									<td colspan="2">
										<hr>
									</td>
								</tr>
								<tr>
									<td><font size="-1" face="arial, helvetica">上传文件</font></td><td>
										<form enctype="multipart/form-data" method="POST" action="?action=uploads&file=<?php echo $getFile; ?>" id="form-upload">
											<input name="uploadfile" id="uploadfile" type="file" size="40">
											<input type="button" name="upload" id="btn-upload" value="上传文件">
										</form></td>
								</tr>
								
								<tr>
									<td><font size="-1" face="arial, helvetica">创建目录</font></td><td>
										<form method="POST" action="?action=mkdir&file=<?php echo $getFile; ?>" id="form-mkdir">
										<input type="TEXT" name="mkdirfile" id="mkdirfile" size="40">
											<input type="button" name="mkdir" id="mkdir" value=" 创建目录  ">
										</form>
									</td>
								</tr>
								
								<form method="POST" action="?action=add&file=<?php echo $getFile; ?>" id="add-form">
								<tr>
									<td><font size="-1" face="arial, helvetica">新建文件</font></td><td>
										<input type="TEXT" name="addname" size="40" id="fopen-name">
										
										<input type="button" name="createfile" id="add-btn" value=" 新建文件 ">
									</td>
								</tr>
								</form>
							</tbody>
						</table>
					</td>
				</tr>
				
				<tr>
					<td colspan="2">
						<hr>
					</td><td></td>
				</tr>
				<tr>
					<td colspan="2">
						<table id="file-table" border="0" cellspacing="1" cellpadding="1" width="100%">
							
								<tr>
									<th bgcolor="#b0c4de"><font color="white" face="arial, helvetica">类型</font></th>
									<th bgcolor="#b0c4de"><font color="white" face="arial, helvetica">名称</font></th>
									<th bgcolor="#b0c4de"><font color="white" face="arial, helvetica">大小</font></th>
									<th bgcolor="#b0c4de"><font color="white" face="arial, helvetica">修改时间</font></th>
									<th bgcolor="#b0c4de"><font color="white" face="arial, helvetica">属性</font></th>
									<th bgcolor="#b0c4de"><font color="white" face="arial, helvetica">操作</font></th>
								</tr>
								
							<tbody>
							
<?php	
foreach($file_arr[0] as $key=>$value){
	if(($value!='.' &&  $value!='..')  ||  @$_GET['file']!=''){
?>
								<tr>
									<td align="center" nobreak="">
										<?php 
										if($value=='.'){
											echo '<a href="?"><img src="images/PARENT.GIF" alt="Unknown filetype" border="0"></a>';
										}elseif($value=='..'){
											if(@$getFile){
												$prev_url=dirname(@$_GET['file']);
											}else{
												$prev_url=$value;
											}
											$prev_url=str_replace("\\", "/", $prev_url);
											echo '<a href="?file='.$prev_url.'"><img src="images/PARENT.GIF" alt="Unknown filetype" border="0"></a>';
										}else{
											if(is_dir($value)){
												echo '<a href="?file='.@$_GET['file'].'/'.$value.'"><img src="images/FOLDER.GIF" alt="Unknown filetype" border="0"></a>';
											}else{
												echo '<img src="images/text.gif" alt="Unknown filetype" border="0">';
											}
										}
										
										?>
										
									</td>
									<td><font size="-1" face="arial, helvetica">
										<?php 
										if($value=='.'){
											echo '/';
										}elseif($value=='..'){
											echo '上级目录';
										}else{
											echo $value;
										}
									
									?>
									</font></td>
									<td align="center"><font size="-1" face="arial, helvetica"><?php echo $file->getsize($value); ?></font></td>
									<td align="center"><font size="-1" face="arial, helvetica"><?php echo date("Y-m-d H:i:s",filectime($value));?></font></td>
									<td align="center">
										<a href="?action=chmod&amp;wdir=/&amp;file=%2F100.PHP" title="Change permission level on 100.PHP">
											<font size="-1" face="arial, helvetica"><?php echo substr(sprintf('%o', fileperms($value)), -4); ?></font>
										</a>
									</td>
									<td align="center"> 
										<?php if($value!='.' && $value!='..'){ ?>
											<a href="javascript:void(0);" class="touch" data="<?php echo $value;?>" url="<?php echo $getFile;?>" >
												<img src="images/SECURITY.GIF" alt="Touch 100.PHP" border="0" title="授权">
											</a>
											<!--<a href="?wdir=/&amp;action=edit&amp;file=%2F100.PHP">
												<img src="images/edit.gif" alt="Edit" border="0" title="编辑">
											</a>-->
											<a href="javascript:void(0);" class="del" data="<?php echo $value;?>" url="<?php echo $getFile;?>">
												<img src="images/delete.gif" alt="Delete 100.PHP" border="0" title="删除">
											</a>
										<?php } ?>
								    </td>
								</tr>
<?php 
	}
}
if($file_arr[1]){
foreach($file_arr[1] as $value){
?>								
								<tr>
									<td align="center" nobreak="">
										<?php 
										if(preg_match('/.jpg|.gif|.jpeg|.bmp/i',$value)){
											echo '<img src="images/IMAGE.GIF">';
										}
										elseif(preg_match('/.txt/i',$value)){
											echo '<img src="images/TEXT.GIF">';
										}
										elseif(preg_match('/.zip|.rar/i',$value)){
											echo '<img src="images/HELP.GIF">';
										}
										elseif(preg_match('/.phps|.php|.php2|.php3|.php4|.asp|.asa|.cgi|.pl|.shtml/i',$value)){
											echo '<img src="images/webscript.gif">';
										}
										elseif(preg_match('/.htaccess/i',$value)){
											echo '<img src="images/security.gif">';
										}
										elseif(preg_match('/.html|.htm/i',$value)){
											echo '<img src="images/webpage.gif">';
										}else{
											echo '<img src="images/TEXT.gif">';
										}
										
										?>
										
									</td>
									<td><font size="-1" face="arial, helvetica"><?php echo $value;?></font></td>
									<td align="center"><font size="-1" face="arial, helvetica"><?php echo $file->getsize($value); ?></font></td>
									<td align="center"><font size="-1" face="arial, helvetica"><?php echo date("Y-m-d H:i:s",filectime($value));?></font></td>
									<td align="center">
										<a href="?action=chmod&amp;wdir=/&amp;file=%2F100.PHP" title="Change permission level on 100.PHP">
											<font size="-1" face="arial, helvetica"><?php echo substr(sprintf('%o', fileperms($value)), -4); ?></font>
										</a>
									</td>
									<td align="center"> 
										
										<a href="javascript:void(0);" class="touch" data="<?php echo $value;?>" url="<?php echo $getFile;?>" >
											<img src="images/SECURITY.GIF" alt="Touch 100.PHP" border="0" title="授权">
										</a>
										<!--<a href="?wdir=/&amp;action=edit&amp;file=%2F100.PHP">
											<img src="images/edit.gif" alt="Edit" border="0" title="编辑">
										</a>-->
										<a href="javascript:void(0);" class="del" data="<?php echo $value;?>" url="<?php echo $getFile;?>">
											<img src="images/delete.gif"  border="0" title="删除">
										</a>
										
								    </td>
								</tr>

<?php 
	}
}
?>
							</tbody>
						</table>
						</td>
				</tr>
			</tbody>
		</table>
		
		<script type="text/javascript" src="js/file.js" ></script>
	</body>
</html>
