<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    	<link type="text/css" href="css/astro/layout.css" rel="stylesheet">
    	<script type="text/javascript" src="js/jquery-1.7.min.js"></script> 
    	<script type="text/javascript" src="js/base.js"></script>
    	<script type="text/javascript" src="js/astro.js"></script>
    	<title>星座运势</title>
    </head>
<body style="background-color: #eee;">
	<div id="select_div" style="width: 622px;z-index: 1100;margin-top:200px;margin-left: 15%;">
      <a class="button dropdown-toggle work_select" href="javascript:;" id="astro_select">
        <span class="hidden-phone">
           <span>白羊座</span>
           <input type="hidden" value="Aries">
         </span>
        <span class="caret"></span>
      </a>
      <ul class="select_info ul_select" id="select_info">
       <!--  <li class="data" vtype="请要查询的星座" ptype="0" onclick="astro.select_astro(this);">
           	 请要查询的星座
        </li>
        <div class = "divider"></div> -->
        <li class="data" vtype="白羊座" ptype="Aries" onclick="astro.select_astro(this);">
           	 白羊座
        </li>
        <div class = "divider"></div>
        <li class="data" vtype="金牛座" ptype="Taurus" onclick="astro.select_astro(this);">
           	 金牛座
        </li>
        <div class = "divider"></div>
        <li class="data" vtype="双子座" ptype="Gemini" onclick="astro.select_astro(this);">
           	双子座
        </li>
        <div class = "divider"></div>
        <li class="data" vtype="巨蟹座" ptype="Cancer" onclick="astro.select_astro(this);">
           	 巨蟹座
        </li>
        <div class = "divider"></div>
        <li class="data" vtype="狮子座" ptype="leo" onclick="astro.select_astro(this);">
           	狮子座
        </li>
        <div class = "divider"></div>
        <li class="data" vtype="处女座" ptype="Virgo" onclick="astro.select_astro(this);">
           	处女座
        </li>
        <div class = "divider"></div>
        <li class="data" vtype="天秤座" ptype="Libra" onclick="astro.select_astro(this);">
           	天秤座
        </li>
        <div class = "divider"></div>
        <li class="data" vtype="天蝎座" ptype="Scorpio" onclick="astro.select_astro(this);">
           	天蝎座
        </li>
        <div class = "divider"></div>
        <li class="data" vtype="射手座" ptype="Sagittarius" onclick="astro.select_astro(this);">
           	射手座
        </li>
        <div class = "divider"></div>
        <li class="data" vtype="摩羯座" ptype="Capricorn" onclick="astro.select_astro(this);">
           	摩羯座
        </li>
        <div class = "divider"></div>
        <li class="data" vtype="水瓶座" ptype="Aquarius" onclick="astro.select_astro(this);">
           	水瓶座
        </li>
        <div class = "divider"></div>
        <li class="data" vtype="双鱼座" ptype="Pisces" onclick="astro.select_astro(this);">
           	双鱼座
        </li>
       </ul>
     </div>
     
     <div class="query-select" style="margin-top: 100px;margin-left: 15%;">
     	<a href="javascript:;" style="float: left;width: 250px!important;" class="a-query ui-btn ui-shadow ui-btn-corner-all ui-btn-up-c" onclick="astro.query();">
     		<span class="ui-btn-inner ui-btn-corner-all">
     			<span class="ui-btn-text">
<!--      				<span id="query-wait" class="icon-query"></span> -->
     				<span class="query_button">查   询</span>
     			</span>
     		</span>
     	</a>
     	
     	<a href="javascript:;" style="float: left;margin-left: 100px!important;width: 300px!important;" class="a-query ui-btn ui-shadow ui-btn-corner-all ui-btn-up-c" onclick="astro.setDefault();">
     		<span class="ui-btn-inner ui-btn-corner-all">
     			<span class="ui-btn-text">
<!--      				<span id="query-wait" class="icon-query"></span> -->
     				<span class="query_button">设为默认</span>
     			</span>
     		</span>
     	</a>
     </div>
</body>
</html>
