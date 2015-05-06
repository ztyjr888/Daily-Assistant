<?php

/**
 * 用来输出各种xml格式
 * @author zt
 *
 */
class ContantsXml{

	public static $PUBLIC_ITEM_NEWS = "<item>
								        <Title><![CDATA[%s]]></Title>
								        <Description><![CDATA[%s]]></Description>
								        <PicUrl><![CDATA[%s]]></PicUrl>
								        <Url><![CDATA[%s]]></Url>
    								   </item>";
	
	public static $PUBLIC_NEWS = "<xml>
									<ToUserName><![CDATA[%s]]></ToUserName>
									<FromUserName><![CDATA[%s]]></FromUserName>
									<CreateTime>%s</CreateTime>
									<MsgType><![CDATA[news]]></MsgType>
									<Content><![CDATA[]]></Content>
									<ArticleCount>%s</ArticleCount>
									<Articles>%s</Articles>
								</xml>";

	public static $PUBLIC_TEXT = "<xml>
				                  	<ToUserName><![CDATA[%s]]></ToUserName>
				                    <FromUserName><![CDATA[%s]]></FromUserName>
				                    <CreateTime>%s</CreateTime>
				                    <MsgType><![CDATA[text]]></MsgType>
				                    <Content><![CDATA[%s]]></Content>
				                    <FuncFlag>%s</FuncFlag>
                				 </xml>";
	public static $PUBLIC_MUSIC = "<xml>
						            <ToUserName><![CDATA[%s]]></ToUserName>
						            <FromUserName><![CDATA[%s]]></FromUserName>
						            <CreateTime>%s</CreateTime>
						            <MsgType><![CDATA[music]]></MsgType>
						            <Music>
						            <Title><![CDATA[%s]]></Title>
						            <Description><![CDATA[]]></Description>
						            <MusicUrl><![CDATA[%s]]></MusicUrl>
						            <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
						            <FuncFlag><![CDATA[1]]></FuncFlag>
						            </Music>
           						  </xml>";
}
?>
