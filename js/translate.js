/**
 * 在线翻译
 */

$(function(){
	
	/*
	 * textarea 鼠标事件
	 */
	/*$(".input-wrap").mouseover(function(){
		$(".input-wrap").addClass("warp-hov");
	}).mouseout(function(){
		$(".input-wrap").removeClass("warp-hov");
	});*/
	
	/*
	 * textarea keyup事件
	 */
	$("#translate_input").live("keyup",function(){
		var text = $("#translate_input").val();
		var reg = /^[ ]+$/;
		if(text!="" && !reg.test(text)){
			$(".textarea-bg .prompt-text").hide();
		}else{
			$(".textarea-bg .prompt-text").show();
		}
	});
	
	/*
	$("#translate_input").live("blur",function(){
		translate.isOnTextAreaFocus();
	});
	
    $(document).bind("blur",function(e){ 
		var target = $(e.target); 
		if(target.closest("#translate_input").length == 0){ 
			 $(".input-wrap").removeClass("warp-hov");
		} 
	});*/
	
	/*
	 * 更换语言
	 */
	$(".select-from-language").live("click",function(){
		if($(".from-language-list").is(":hidden")){
			$(".from-language-list").show();
		}else{
			$(".from-language-list").hide();
		}
	});
	
	$(".select-to-language").live("click",function(){
		if($(".from-language-list2").is(":hidden")){
			$(".from-language-list2").show();
		}else{
			$(".from-language-list2").hide();
		}
	});
	
	 $(document).bind("click",function(e){ 
			var target = $(e.target); 
			if(target.closest(".select-from-language").length == 0){ 
				if(!$(".from-language-list").is(":hidden")){
					$(".from-language-list").hide();
				}
			} 
			if(target.closest(".select-to-language").length == 0){ 
				if(!$(".from-language-list2").is(":hidden")){
					$(".from-language-list2").hide();
				}
			} 
	});
	
	/*
	 * 语言选择点击事件
	 */
	$(".from-language-list .language-data td a").live("click",function(){
		 value = $(this).attr("value");
		 text = $(this).text();
		 $(".select-from-language .language-selected").attr("data-lang",value);
		 $(".select-from-language .language-selected span").text(text);
	});
	
	$(".from-language-list2 .language-data td a").live("click",function(){
		value = $(this).attr("value");
		text = $(this).text();
		$(".select-to-language .language-selected").attr("data-lang",value);
		$(".select-to-language .language-selected span").text(text);
	});
	
	$("#translate_input").keypress(function(e){
		//var event=arguments.callee.caller.arguments[0]||window.event;//消除浏览器差异  
		//if( event.keyCode == 13 ){
			//translate.query();
		//}
		
		var et=e||window.event;
	    var keycode=et.charCode||et.keyCode;   
	    if(keycode==13){
	        if(window.event){
	       	   translate.query();
	           window.event.returnValue = false;
	        }else
	           e.preventDefault();//for firefox
	    }
	});
	 
});

var translate = {};

translate.isOnTextAreaFocus = function(){
     if(document.activeElement.id=='translate_input'){
    	 $(".input-wrap").addClass("warp-hov");
     }
     else{
    	 $(".input-wrap").removeClass("warp-hov");
     }
};

/*
 * 查询
 */
translate.query = function(){
	$(".output-wrap").text("");
	var text = $("#translate_input").val();
	var reg = /^[ ]+$/;
	if(text =="" || reg.test(text))
		return;
		
	//text = text.replace(/\r\n/g,"");
	
	var fromCode = $(".select-from-language .language-selected").attr("data-lang");
	var toCode = $(".select-to-language .language-selected").attr("data-lang");
	if(fromCode && toCode){
		var params = {};
		params.fromCode = fromCode;
		params.toCode = toCode;
		params.content = text;
		$BASE.ajaxForm(params,"translate_detail.php",translate.query_callback,false,false);
	}
};

translate.query_callback = function(data){
	if(data){
		if(data.msg == "success"){
			$(".output-wrap").text(data.content);
		}
	}
};
