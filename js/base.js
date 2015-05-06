/**
 * 基础JS类,通用类方法
 * @author zt
 * @date 2014-08-20
 */

$(function(){
});

var $BASE = {};

/**
 * 字符串拼接
 */
$BASE.StringBuilder = function(){
	this.data = new Array;
};


$BASE.StringBuilder.prototype.append=function(str){
	this.data.push(str);
};

$BASE.StringBuilder.prototype.toString=function(){
    return this.data.join("");
};

/**
 * 转秒为时分秒格式
 */
$BASE.formatSeconds = function(value){
	var second = parseInt(value);// 秒 
	var minute = 0;// 分 
	var hour = 0;// 小时 
	
	if(second >= 60) { 
		minute = parseInt(second/60); 
		second = parseInt(second%60); 
	 
		if(minute >= 60) { 
		hour = parseInt(minute/60); 
		minute = parseInt(minute%60); 
		} 
	} 
	
	var sec="";
	var result ="";
	if(second == 0){
		sec  = "00";
		result +=sec; 
 	}else{
	    if(second>=10){
		   result+=second;
		}else{
		   result = result+"0"+second;
		}
	    
	}
	//var result = ""+parseInt(second)+":"; 
	if(minute >= 0) { 
	    if(minute>=10){
			result = ""+parseInt(minute)+":"+result; 		
		}else{
			result = "0"+parseInt(minute)+":"+result;
		}
		
	} 
	if(hour >= 0) { 
	    if(hour>=10){
			result = ""+parseInt(hour)+":"+result; 
		}else{
		    result = "0"+parseInt(hour)+":"+result; 
		}
		
	} 
	return result; 
};

/**
 * params
 * 		--参数:JSON格式
 * url
 * 		--请求路径
 * callback
 * 		--回调函数
 * options_loading(info---加载信息)
 * 		--加载滚动条参数(可选)
 * showError 
 * 		---是否显示错误信息(可选)
 * 异常Ajax请求
 */
$BASE.ajaxForm = function(params,url,callback,options_loading,showError){
	$.ajax({
		 type: 'POST', 
	     url:url,
	     data: params, 
	     dataType:'json', 
	     beforeSend:function(){
	    	if(options_loading)
	    		$("body").loading(options_loading);
	     },
	     success:callback,
	     error:function(XMLHttpRequest, textStatus, errorThrown){ 
	    	 if(showError || showError == false){
	    		 alert("服务器异常,请稍候再试试!");
	    	 }
	    	 
	    	 if(options_loading)
	    		 $("body").destoryLoading();
	        /* alert(XMLHttpRequest.status);
             alert(XMLHttpRequest.readyState);
             alert(textStatus);*/
	        
	     } 
	});
};

/**
 * params
 * 		--参数:JSON格式
 * url
 * 		--请求路径
 * callback
 * 		--回调函数
 * showError 
 * 		---是否显示错误信息(可选)
 * 异常Ajax请求不加载滚动条
 */
$BASE.ajaxFormNoLoading = function(params,url,callback,showError){
	$.ajax({
		 type: 'POST', 
	     url:url,
	     data: params, 
	     dataType:'json', 
	     success:callback,
	     error:function(XMLHttpRequest, textStatus, errorThrown){ 
	     	if(showError || showError != false){
		     	alert("服务器异常,请稍候再试试!"); 
		        /* alert(XMLHttpRequest.status);
	             alert(XMLHttpRequest.readyState);
	             alert(textStatus);*/
	     	}
	     } 
	});
};
