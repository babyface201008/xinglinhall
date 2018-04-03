# Host: 112.74.64.185  (Version: 5.5.28-log)
# Date: 2017-07-05 14:04:43
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
# Source for table "t_nav"
#

CREATE TABLE `t_nav` (
  `navid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `navname` varchar(100) NOT NULL,
  `parentid` int(11) NOT NULL COMMENT '父id',
  `sort` int(11) NOT NULL COMMENT 'sort',
  `url` varchar(500) NOT NULL,
  `ad_url` varchar(100) NOT NULL COMMENT '广告链接',
  `ad_src` varchar(200) NOT NULL COMMENT '图标',
  `isshow` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否显示(1为显示，2不显示。默认是1)',
  PRIMARY KEY (`navid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

#
# Data for table "t_nav"
#

INSERT INTO `t_nav` VALUES (1,'全局配置',0,0,'#','','icon-system_monitor',1),(19,'系统管理员',1,1,'admin/index','','icon-user',1),(26,'系统设置',1,1,'setting/index','','icon-manage_user',1),(66,'菜单管理',1,1,'adminMenu/index','','icon-setting_tools',1),(67,'权限管理',1,1,'adminGroup/index','','icon-vcard',1),(68,'测试',1,0,'asdasd','','icon-Account_Edit',1),(69,'请问',0,0,'阿萨德','','icon-system_monitor',1),(84,'阿萨德',1,0,'阿萨德','','爱上',1);

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
