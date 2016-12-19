function $(id){
	var div= document.getElementById(id);
	return div;
}
//表格Hover样式
var f_table=$('file-table');
var f_tr=f_table.getElementsByTagName('tr');
for(var i in f_tr){
	f_tr[i].onmouseenter=(function(){
		this.style.background='#f5f5f5';
	});
	f_tr[i].onmouseleave=(function(){
		this.style.background='#ffffff';
	})
}
/*删除文件*/
var del=document.getElementsByClassName('del');
for(var i in del){
	del[i].onclick=(function(){
		if(confirm('确定要删除吗？')){
			location.href="?action=del&file="+this.getAttribute('url')+"&filename="+this.getAttribute('data');
		}
		return false;
	})
}

/*更改权限*/
var _touch=document.getElementsByClassName('touch');
for(var i in _touch){
	_touch[i].onclick=(function(){
		var s=prompt('权限值：');
		var reg_touch=/^[0-9]{4}$/;
		if(!reg_touch.test(s)){
			alert('请输入4位权限值,只能是数字');
			return false;
		}else{
			location.href="?action=touch&touch_val="+s+"&filename="+this.getAttribute('data')+"&file="+this.getAttribute('url');
		}
		return false;
	})
}

var reg=/^((?!exe)[\w\-_\.]){3,255}$/g;
/*创建目录*/
$("mkdir").onclick=function(){
	var txt=$('mkdirfile').value;
	if(trim(txt)=='' || trim(txt)==null || !reg.test(trim(txt))){
		alert('请输入合法的目录名称,至少3个字符，字母数字下划线等');
		$('mkdirfile').focus();
		return false;
	}else{
		$('form-mkdir').submit();
	}
	return false;
}

/*创建文件*/
$('add-btn').onclick=function(){
	var addname=$('fopen-name').value;
	if(trim(addname)=='' ||trim(addname)==null || !reg.test(addname)){
		alert('请输入合法的文件名称，至少3个字符，字母数字下划线等');
		$('fopen-name').focus();
		return false;
	}else{
		$('add-form').submit();
	}
	return false;
}

/*文件上传*/
$('btn-upload').onclick=function(){
	var filename=$('uploadfile').value;
	if(filename=='' || filename==null){
		alert('未选中任何文件');
		return false;
	}else{
		$('form-upload').submit();
	}
	return false;
}


/** 
 * 去除字符串前后空格 
 * @param str 
 * @returns {void|*|string|XML} 
 */  
function trim(str){  
    return str.replace(/(^\s*)|(\s*$)/g,"");  
} 