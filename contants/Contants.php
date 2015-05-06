<?php

/**
 * 常量类
 * @author zt
 *
 */
class Contants{
	
	public static $PUBLIC_JOKE = "joke";
	public static $PUBLIC_JOKE_CHINESE = "笑话";
	//http://apix.sinaapp.com/joke/?appkey=trialuser
	public static $PUBLIC_JOKE_URL = "http://api100.duapp.com/joke/?appkey=trialuser";
	public static $PUBLIC_JOKE_ERROR = "输入错误!";
	
	public static $PUBLIC_MUSIC = "点歌";
	public static $PUBLIC_MUSIC_EX = "music";
	public static $PUBLIC_MUSIC_ERRORMSG = "请发送“点歌”加上歌曲,如:“点歌伤不起”";
	public static $PUBLIC_MUSIC_NOTFOUND = "没有找到相对应的歌曲,英文歌曲请用||分割!";
	public static $PUBLIC_MUSIC_URL = "http://box.zhangmen.baidu.com/x?op=12&count=1&title=%s$$";
	public static $PUBLIC_MUSIC_URL_AUTHOR = "http://box.zhangmen.baidu.com/x?op=12&count=1&title=%s$$%s$$$$ ";//带歌手
	public static $PUBLIC_MUSIC_NUM_ERRORMSG = "请发3+空格+歌曲,如:“3 伤不起”或“3 伤不起 王麟”或“3@伤不起@王麟”,英文歌曲请用@分割";
	public static $PUBLIC_MUSIC_NUM_ERRORMSG2 = "请发送点歌+空格+歌曲,如:“点歌 伤不起”或“点歌 伤不起 王麟”或“点歌@伤不起@王麟”,英文歌曲请用@分割";
	public static $PUBLIC_MUSIC_NUM_ERRORMSG4 = "发送点歌+空格+歌曲 \n如:“点歌 伤不起”或“点歌 伤不起 王麟”或“点歌@伤不起@王麟”,英文歌曲请用@分割";
	public static $PUBLIC_MUSIC_NUM_ERRORMSG3 = "在线点歌使用指南:\n发送点歌+空格+歌曲,如:“点歌 伤不起”或“点歌 伤不起 王麟”或“点歌@伤不起@王麟”,英文歌曲请用@分割";
	
	public static $PUBLIC_WEATHER = "天气";
	public static $PUBLIC_WEATHER_EX = "weather";
	public static $PUBLIC_WEATHER_ERRORMSG = "请发城市加上“天气”,如:“上海天气”";
	public static $PUBLIC_WEATHER_NOTFOUND = "没有找到相应的城市天气!";
	public static $PUBLIC_WEATHER_ERROR = "查询天气预报失败,请稍微再试!";
	public static $PUBLIC_WEATHER_URL = "http://www.weather.com.cn/data/sk/%s.html";
	public static $PUBLIC_WEATHER_URL_SIX = "http://m.weather.com.cn/data/%s.html";
	public static $PUBLIC_WEATHER_DAYS = "1";//默认获取一天
	public static $PUBLIC_WEATHER_URL_NEW = "http://www.weather.com.cn/weather1d/%s.shtml";
	public static $PUBLIC_WEATHER_NUM_ERRORMSG = "请发1+空格+城市,如:“1 上海”";
	
	//人脸认识
	public static $PUBLIC_FACE = "face";
	public static $PUBLIC_FACE_EX = "人脸";
	public static $PUBLIC_FACE_CX = "人脸识别";
	public static $PUBLIC_FACE_URL = "http://apicn.faceplusplus.com/v2/detection/detect?url=%s&api_secret=%s&api_key=%s";
	public static $PUBLIC_FACE_DETECTION_DETECT_URL = "/detection/detect";//检测给定图片(Image)中的所有人脸(Face)的位置和相应的面部属性
	public static $PUBLIC_FACE_DETECTION_LANDMARK_URL = "/detection/landmark";//检测给定人脸(Face)相应的面部轮廓，五官等关键点的位置，包括25点和83点两种模式。
	public static $PUBLIC_FACE_RECOGNITION_COMPARE_URL = "/recognition/compare";//计算两个Face的相似性以及五官相似度
	public static $PUBLIC_FACE_PERSON_CREATE_URL = "/person/create";//创建一个Person
	public static $PUBLIC_FACE_PERSON_DELETE_URL = "/person/delete";//删除一组Person
	public static $PUBLIC_FACE_ERRORMSG = "检测人脸失败,请稍候再试!";
	public static $PUBLIC_FACE_NOTFOUND = "未识别到人脸,请换一张清晰的照片再试!";
	public static $PUBLIC_FACE_MESSAGE = "人脸检测使用指南:\n发送一张清晰的照片,就能帮你分析出种族、年龄、性别等信息。快来试试吧!";
	public static $PUBLIC_FACE_MESSAGE2 = "发送一张清晰的照片,就能帮你分析出种族、年龄、性别等信息。";
	
	//快递
	public static $PUBLIC_EXPRESS_URL = "http://m.kuaidi100.com/index_all.html?type=%s&postid=%s&callbackurl=#";
	public static $PUBLIC_EXPRESS_ERRORMSG = "请发5+空格+快递公司+空格+快递单号,如:“5 申通 868188157135”";
	public static $PUBLIC_EXPRESS_NOTFOUND = "没有找到相应的快递公司!";
	public static $PUBLIC_EXPRESS_Menu_URL = "http://m.kuaidi100.com/index_all.html";
	
	//历史上的今天
	public static $PUBLIC_TODAY_URL = "http://www.rijiben.com/";
	
	//本地天气
	public static $PUBLIC_WEATHER_LOCAL_URL = "http://m.hao123.com/a/tianqi";
	
	//翻译
	public static $PUBLIC_TRANSLATE = "翻译";
	public static $PUBLIC_TRANSLATE_EX = "FY";
	public static $PUBLIC_TRANSLATE_EX2 = "fy";
	public static $PUBLIC_TRANSLATE_EX3 = "Fy";
	public static $PUBLIC_TRANSLATE_EX4 = "fY";
	public static $PUBLIC_TRANSLATE_MESSAGE = "翻译使用指南:\n发送fy+@+内容+@语言,如:“fy@我爱你”或“fy@我爱你@法语”";
	public static $PUBLIC_TRANSLATE_MESSAGE2 = "在线翻译提供即时免费的翻译服务";
	public static $PUBLIC_TRANSLATE_MESSAGE3 = "也可发送fy+@+内容+@语言 \n如:“fy@我爱你”或“fy@我爱你@法语”";
	
	//电影排行榜
	public static $PUBLIC_MOVIE_URL = "http://sh.meituan.com/shop/1469758?mtt=1.movie%2Fcinemalist.0.0.hz11k2kh";
	
	//小黄鸡
	public static $PUBLIC_XIAOHUANGJI_URL = "http://www.xiaohuangji.com/ajax.php";
	
	//菜单
	public static $PUBLIC_MENU_WEATHER_NAME = "天气预报";
	public static $PUBLIC_MENU_WEATHER = "weather";
	public static $PUBLIC_MENU_JOKE_NAME = "幽默笑话";
	public static $PUBLIC_MENU_MUSIC_NAME = "在线点歌";
	public static $PUBLIC_MENU_FACE_NAME = "人脸识别";
	public static $PUBLIC_MENU_EXPRESS_NAME = "快递查询";
	public static $PUBLIC_MENU_EXPRESS = "express";
	public static $PUBLIC_MENU_HISTORY_NAME = "历史上的今天";
	public static $PUBLIC_MENU_HISTORY = "history";
	public static $PUBLIC_MENU_ABOUT_NAME = "关于我们";
	public static $PUBLIC_MENU_ABOUT = "about";
	public static $PUBLIC_MENU_ABOUT_WEATHER_NAME = "关于天气";
	public static $PUBLIC_MENU_ABOUT_WEATHER = "aboutWeather";
	public static $PUBLIC_MENU_TRANSLATE_NAME = "在线翻译";
	public static $PUBLIC_MENU_TRANSLATE = "fanyi";
	public static $PUBLIC_MENU_MOVIE_NAME = "电影排行";
	public static $PUBLIC_MENU_MOVIE = "movie";
	public static $PUBLIC_MENU_ASTRO_NAME = "星座运势";
	public static $PUBLIC_MENU_ASTRO = "astro";
	public static $PUBLIC_MENU_ASTRO_URL = "http://wxmain.sinaapp.com/astro_index.php";
	public static $PUBLIC_MENU_ASTRO_DETAIL_URL = "http://wxmain.sinaapp.com/astro_detail.php";
}
?>
