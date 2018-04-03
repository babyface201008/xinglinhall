# Host: 112.74.64.185  (Version: 5.5.28-log)
# Date: 2017-06-16 14:47:47
# Generator: MySQL-Front 5.3  (Build 1.27)

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE */;
/*!40101 SET SQL_MODE='' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES */;
/*!40103 SET SQL_NOTES='ON' */;

#
# Source for table "t_admin_menu"
#

CREATE TABLE `t_admin_menu` (
  `menuid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `menuname` varchar(100) NOT NULL,
  `parentid` int(11) NOT NULL COMMENT '父id',
  `sort` int(11) NOT NULL COMMENT 'sort',
  `url` varchar(500) NOT NULL,
  `rel` varchar(100) NOT NULL COMMENT '跳转位置',
  `icon` varchar(200) NOT NULL COMMENT '图标',
  `isshow` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否显示(1为显示，2不显示。默认是1)',
  PRIMARY KEY (`menuid`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;

#
# Data for table "t_admin_menu"
#

INSERT INTO `t_admin_menu` VALUES (1,'全局配置',0,1,'#','','icon-system_monitor',1),(2,'banner图片',1,2,'banner/index','','icon-photo',1),(3,'商城快讯',1,3,'article/index','','icon-building',1),(4,'商品管理',0,2,'#','','icon-box_picture',1),(5,'品牌信息',4,1,'brand/index','','icon-card_front',1),(6,'分类信息',4,2,'category/index','','icon-chart_pie',1),(7,'商品信息',4,3,'product/index','','icon-package',1),(8,'订单管理',0,3,'#','','icon-coins',1),(9,'订单信息',8,1,'order/index','','icon-coins_in_hand',1),(10,'会员管理',0,4,'#','','icon-user_earth',1),(11,'会员信息',10,1,'member/index','','icon-user_home',1),(13,'评论管理',8,1,'comment/index','','icon-comment_add',1),(14,'意见反馈',22,3,'feedback/index','','icon-apache_handlers',1),(15,'促销管理',0,8,'#','','icon-shopping',1),(16,'限时秒杀',15,1,'miaosha/index','','icon-time',1),(17,'优惠劵管理',15,2,'coupon/index','','icon-money',1),(18,'系统管理',0,10,'#','','icon-cog',1),(19,'系统管理员',18,1,'admin/index','','icon-user',1),(20,'权限管理',18,2,'admingroup/index','','icon-vcard',1),(21,'操作日志',18,3,'operationlog/index','','icon-note_log',1),(22,'帮助中心',0,11,'#','','icon-help',1),(23,'帮助中心分类',22,1,'helpcate/index','','icon-catalog_pages',1),(24,'帮助中心文章',22,2,'helpcontent/index','','icon-align_right',1),(25,'我的处方',10,2,'recipe/index','','icon-client_account_template',1),(26,'系统设置',1,1,'setting/system','','icon-manage_user',1),(27,'资讯管理',0,6,'#','','icon-application_view_gallery',1),(28,'资讯栏目',27,1,'newscatalog/index','','icon-male',1),(29,'所有资讯',27,6,'newscontent/index','','icon-newspaper_add',1),(30,'问答管理',0,7,'#','','icon-ask_and_answer',1),(31,'栏目管理',30,1,'askcatalog/index','','icon-centroid',1),(33,'医师列表',1,7,'doctor/index','','icon-user_nude',1),(34,'广告管理',1,4,'advert/index','','icon-gps_automotive',1),(35,'问题管理',30,2,'askquestion/index','','icon-at_sign',1),(36,'合作媒体',1,5,'mediapartner/index','','icon-participation_rate',1),(37,'友情链接',1,6,'friendlink/index','','icon-friendfeed',1),(38,'向我提问',30,3,'askquestion/index?type=2','','icon-hand_point',1),(39,'组合套装',4,4,'productcomb/index','','icon-chart_organisation',1),(40,'健康资讯',27,2,'newscontent/healthIndex','','icon-health',1),(41,'用药指导',27,3,'newscontent/medicationIndex','','icon-emotion_medic',1),(42,'关于我们',27,4,'newscontent/aboutIndex','','icon-building',1),(43,'媒体报道',27,5,'newscontent/reportIndex','','icon-monitor_sidebar',1),(44,'咨询电话',1,8,'consult/index','','icon-phone_vintage',1),(45,'专题管理',27,7,'subject/index','','icon-text_padding_left',1),(46,'积分规则',10,5,'scorerule/index','','icon-text_ruler',0),(47,'充值设置',1,10,'recharge_money/index','','icon-money_bag',0),(48,'充值记录',10,4,'recharge/index','','icon-table_money',1),(49,'seo设置',1,1,'setting/seoIndex','','icon-setting_tools',0),(50,'统计报表',0,3,'#','','icon-map',1),(51,'订单报表',50,1,'orderreport/index','','icon-application_view_icons',1),(52,'商品销售报表',50,2,'productreport/index','','icon-application_view_tile',1),(53,'积分明细',10,3,'score/index','','icon-coins',1);

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
