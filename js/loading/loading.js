
/**
 * loading.js
 * @author zt
 * @date 2014-02-26
 */
(function($EDU){
	$EDU.fn.loading = function(options){
		var defaults = {
			info:'loading...'
		};
			
		var option = $.extend({},defaults,options);
		appendLoading(option);
	};
	
	/*组装加载条*/
	appendLoading = function(option){
		if($(".loadingBox").length >0){
			$(".loadingBox").remove();
		}
		
		if($(".markBox").length >0){
			$(".markBox").remove();
		}
		builder = new $BASE.StringBuilder();
		builder.append("<div class='markBox'></div>");
		builder.append("<div class='loadingBox'>");
		builder.append("<div class='loadImg'></div>");
		builder.append("<div class='loadInfo'>"+option.info+"</div>");
		builder.append("</div>");
		$(document.body).append(builder.toString());
		$(".loadingBox").show();
	};
	
	/*销毁加载条*/
	$EDU.fn.destoryLoading = function(){
		$(".markBox").remove();
		$(".loadingBox").remove();
	};
})(jQuery);
