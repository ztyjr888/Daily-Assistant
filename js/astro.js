/**
 * 星座
 */
var astro = {};
$(function(){
	$("#select_div").click(function(event){
		if($(this).hasClass("open")){
			$(this).removeClass("open");
			$(this).find("a").removeClass("open_select");
			$(this).find("ul").hide();
		}else{
 		 	$(this).addClass("open");
			$(this).find("a").addClass("open_select");
 			$(this).find("ul").show();
		}
	});
});

astro.select_astro = function(obj){
    var vtype = $(obj).attr("vtype");
	var ptype = $(obj).attr("ptype");
	if(vtype && ptype){
		$(obj).parent().prev().find(".hidden-phone span").text(vtype);
		$(obj).parent().prev().find(".hidden-phone input").val(ptype);
		$(obj).parent().hide();
		$(obj).parent().removeClass("open_select");
	}
};

astro.query = function(){
	var vtype = $("#astro_select").find(".hidden-phone span").text();
	var ptype = $("#astro_select").find(".hidden-phone input").val();
	
	$url = "astro_detail.php?vcode="+ptype;
	$str = astro.getUrlParam("svptod");
	
	if($str){
		$str = "&svptod="+$str;
		$url +=$str;
	}
	
	window.location.href=$url;
};

/*
 * 设为默认
 */
astro.setDefault =function(){
	$str = astro.getUrlParam("svptod");
	var vtype = $("#astro_select").find(".hidden-phone span").text();
	var ptype = $("#astro_select").find(".hidden-phone input").val();
	
	if(confirm("即将设置该星座为默认星座,一旦设置后无法更改,是否继续?")){
		var params = {};
		params.svptod = $str;
		params.name = vtype;
		params.code = ptype;
		$BASE.ajaxForm(params,"opterAstro.php",astro.setDefault_callback,false,false);
	}
};

/*
 * 设置默认星座
 */
astro.setDefault_callback = function(data){
	if(data.msg == "success"){
		alert("设置默认星座成功!");
		astro.query();
	}else if(data.msg == "exists"){
		alert("您已设置过默认星座!");
	}else if(data.msg == "error"){
		alert("系统异常,请稍候再试!");
	}else if(data.msg == "updateSuccess"){
		alert("修改默认星座成功!");
		astro.query();
	}else if(data.msg == "errorUser"){
		alert("用户不存在,无法设置!");
	}else if(data.msg == "setNo"){
		alert("已设置过该星座!");
	}
}

astro.getUrlParam =  function(name){
	var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
	var r = window.location.search.substr(1).match(reg);  //匹配目标参数
	if (r!=null) return unescape(r[2]); return null; //返回参数值
} 

/*
 * 返回
 */
astro.goBack = function(){
	$str = astro.getUrlParam("svptod");
	window.location.href="astro_index.php?svptod="+$str;
	//window.opener=null;
	//window.open('','_self');
	//window.close();
};
