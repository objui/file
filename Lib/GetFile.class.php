<?php

/**
 * 目录类
 */
class GetFile{
	private $fileUrl;		//目录根目录
	private $rootUrl;
	public function __construct($rootUrl,$fileUrl){
		$this->rootUrl=$rootUrl;
		$this->fileUrl=$fileUrl;
	}
	/**
	 * 计算文件大小
	 * @param $file 文件名
	 * @return $file_size string
	 */
	public function getsize($file){
		$file_size=filesize($file);
		
		if($file_size>=1073741824){
			$file_size = round($file_size/1073741824*100)/100;
			$file_size.='G';
		}elseif($file_size>=1048576){
			$file_size = round($file_size/1048576*100)/100;
			$file_size.='M';
		}elseif($file_size>=1024){
			$file_size = round($file_size/1024*100)/100;
			$file_size.='K';
		}else{
			$file_size.='B';
		}
		return $file_size;
	}
	
	/**
	 * 文件列表
	*/
	public function displaydir(){
		$fileUrl=$this->fileUrl;
		$rootUrl=$this->rootUrl;
		
		if(!is_dir($rootUrl) || !is_dir($fileUrl)){
			header("Content-type:text/html;charset=utf-8;");
			exit('目录不存在');
			return flase;
		}

		@chdir($rootFile.$fileUrl);
		$handle=opendir(".");
		$dirlist=array();
		$filelist=array();
		while ($file = readdir($handle))
			{
			if(is_dir($file)) $dirlist[] = $file;
			if(is_file($file)) $filelist[] = $file;
			}
		closedir($handle);
		return array($dirlist,$filelist);
	}
	
	/**
	 * 获取当前URL
	 */
	public function getUrl(){
		$url=$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$parse_url=parse_url($url);
		$reg="?".@$parse_url['query'];
		$url=str_replace($reg, "",$url);
		return $url;
	}
}