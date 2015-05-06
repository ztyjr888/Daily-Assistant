<?php
/*
 * 快100 手机端快递公司代码(http://m.kuaidi100.com/)
 * http://m.kuaidi100.com/index_all.html?type=[快递公司编码]&postid=[快递单号]&callbackurl=[点击"返回"跳转的地址]
	名称	类型	是否必需	描述
	type	String	是	要查询的快递公司的代码，支持中文和模糊输入，支持本网站的所有的快递公司，对应的公司代码见
	《Chaxun URL 支持的快递公司及参数说明》。 
	如果找不到您所需的公司，请发邮件至 kuaidi@kingdee.com 咨询（大小写不敏感）
	postid	String	是	要查询的快递单号，请勿带特殊符号，不支持中文（大小写不敏感）
	callbackurl	String	否	在查询结果页面点击"返回"时跳转的地址，让用户查询后能返回原来的网站或APP
 */
$express_company_code = array(
		"aae"=>"aae全球专递",
		"anjie"=>"安捷快递",
		"anxindakuaixi"=>"安信达快递",
		"biaojikuaidi"=>"彪记快递",
		"bht"=>"bht",
		"baifudongfang"=>"百福东方国际物流",
		"coe"=>"中国东方（COE）",
		"changyuwuliu"=>"长宇物流",
		"datianwuliu"=>"大田物流",
		"debangwuliu"=>"德邦物流",
		"dhl"=>"dhl",
		"dpex"=>"dpex",
		"dsukuaidi"=>"d速快递",
		"disifang"=>"递四方",
		"ems"=>"ems快递",
		"fedex"=>"fedex（国外）",
		"feikangda"=>"飞康达物流",
		"fenghuangkuaidi"=>"凤凰快递",
		"feikuaida"=>"飞快达",
		"guotongkuaidi"=>"国通快递",
		"ganzhongnengda"=>"港中能达物流",
		"guangdongyouzhengwuliu"=>"广东邮政物流",
		"gongsuda"=>"共速达",
		"huitongkuaidi"=>"汇通快运/汇通快递",
		"hengluwuliu"=>"恒路物流",
		"huaxialongwuliu"=>"华夏龙物流",
		"haihongwangsong"=>"海红",
		"haiwaihuanqiu"=>"海外环球",
		"jiayiwuliu"=>"佳怡物流",
		"jinguangsudikuaijian"=>"京广速递",
		"jixianda"=>"急先达",
		"jjwl"=>"佳吉物流",
		"jymwl"=>"加运美物流",
		"jindawuliu"=>"金大物流",
		"jialidatong"=>"嘉里大通",
		"jykd"=>"晋越快递",
		"kuaijiesudi"=>"快捷速递",
		"lianb"=>"联邦快递（国内）",
		"lianhaowuliu"=>"联昊通物流",
		"longbanwuliu"=>"龙邦物流",
		"lijisong"=>"立即送",
		"lejiedi"=>"乐捷递",
		"lejiedi"=>"乐捷递",
		"minghangkuaidi"=>"民航快递",
		"meiguokuaidi"=>"美国快递",
		"menduimen"=>"门对门",
		"ocs"=>"OCS",
		"peisihuoyunkuaidi"=>"配思货运",
		"quanchenkuaidi"=>"全晨快递",
		"quanfengkuaidi"=>"全峰快递",
		"quanjitong"=>"全际通物流",
		"quanritongkuaidi"=>"全日通快递",
		"quanyikuaidi"=>"全一快递",
		"rufengda"=>"如风达",
		"santaisudi"=>"三态速递",
		"shenghuiwuliu"=>"盛辉物流",
		"shentong"=>"申通快递",
		"shunfeng"=>"顺丰快递",
		"sue"=>"速尔物流",
		"shengfeng"=>"盛丰物流",
		"saiaodi"=>"赛澳递/上海赛澳递",
		"tiandihuayu"=>"天地华宇",
		"tiantian"=>"天天快递",
		"tnt"=>"tnt",
		"ups"=>"ups",
		"wanjiawuliu"=>"万家物流",
		"wenjiesudi"=>"文捷航空速递",
		"wuyuan"=>"伍圆",
		"wxwl"=>"万象物流",
		"xinbangwuliu"=>"新邦物流",
		"xinfengwuliu"=>"信丰物流",
		"yafengsudi"=>"亚风速递",
		"yibangwuliu"=>"一邦速递",
		"youshuwuliu"=>"优速物流",
		"youzhengguonei"=>"邮政包裹挂号信",
		"youzhengguoji"=>"邮政国际包裹挂号信",
		"yuanchengwuliu"=>"远成物流",
		"yuantong"=>"圆通速递/圆通快递",
		"yuanweifeng"=>"源伟丰快递",
		"yuanzhijiecheng"=>"元智捷诚快递",
		"yunda"=>"韵达快运/韵达快递",
		"yuntongkuaidi"=>"运通快递",
		"yuefengwuliu"=>"越丰物流",
		"yad"=>"源安达",
		"yinjiesudi"=>"银捷速递",
		"zhaijisong"=>"宅急送",
		"zhongtiekuaiyun"=>"中铁快运",
		"zhongtong"=>"中通速递/中通快递",
		"zhongyouwuliu"=>"中邮物流",
		"zhongxinda"=>"忠信达",
		"zhimakaimen"=>"芝麻开门"
	);

?>
