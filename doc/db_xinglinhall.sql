/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : db_xinglinhall

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-04-03 11:47:26
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for t_aboutus
-- ----------------------------
DROP TABLE IF EXISTS `t_aboutus`;
CREATE TABLE `t_aboutus` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL COMMENT '名称',
  `content` text COMMENT '内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_aboutus
-- ----------------------------
INSERT INTO `t_aboutus` VALUES ('1', '关于我们', '<p><br/></p><table align=\"center\"><tbody><tr class=\"firstRow\"><td rowspan=\"1\" colspan=\"2\" style=\"word-break: break-all; border-width: 1px; border-style: solid; border-color: rgb(255, 255, 255);\" valign=\"top\" align=\"center\"><p><span style=\"font-family: 微软雅黑,Microsoft YaHei; font-size: 24px;\"><img src=\"/ueditor/php/upload/image/20170706/1499329338141310.jpg\" title=\"1499329338141310.jpg\" alt=\"QQ截图20170706162157.jpg\"/><br/></span></p><p><span style=\"font-family: 微软雅黑,Microsoft YaHei; font-size: 24px;\"><br/></span></p><p><br/><span style=\"font-family: 微软雅黑,Microsoft YaHei; font-size: 24px;\"></span></p></td></tr><tr><td style=\"word-break: break-all; border-width: 1px; border-style: solid; border-color: rgb(255, 255, 255);\" width=\"634\" valign=\"top\" align=\"left\"><p style=\"text-indent: 2em; margin-top: 5px; margin-bottom: 5px; line-height: 2em;\"><span style=\"color: rgb(127, 127, 127); font-family: 微软雅黑,Microsoft YaHei; font-size: 14px;\">照升（广州照升网络科技有限公司）是一家致力于（移动）互联网营销技术研发与服务的高新技术企业。目前，我公司拥有六大核心业务，分别是（照升DSP）平台服务、媒体广告运营、搜索优化、社群营销互动创新、品牌策略），为众多的国际及本土电商、金融、汽车、快消行业的客户提供专业的&nbsp; 、多种类的网络营销综合解决方案。同时，我们还针对大数据应用自主研发了基于海量数据云计算&nbsp;&nbsp;&nbsp;&nbsp; 广告发布平台，照升广告效果分析平台、采用分布式存储技术开发的智能广告交易平台等，用于支撑&nbsp;&nbsp;&nbsp;&nbsp; 公司&nbsp;&nbsp; 整体业务运营。经过3年的快速发展已经发展成为国内互联网广告业领军企业之一，获得了陌陌 信息流&nbsp;&nbsp; 最大业绩贡献奖及运营大奖。</span></p><p style=\"text-indent: 2em; margin-top: 5px; margin-bottom: 5px; line-height: 2em;\"><span style=\"color: rgb(127, 127, 127); font-family: 微软雅黑,Microsoft YaHei; font-size: 14px;\">目前在北京、丽水、杭州等地拥有分公司，全国超过300多名雇员，并与地方媒体建立了深度合作关系 。照升和门户、搜索等达成一级代理合作关系，代理四大门户视频，其中的四大搜索，20DSP平台、移动端、网盟等各大效果类平台。我们与中国各领域数百家数字媒体展开合作，并成为一些最主流媒体&nbsp;&nbsp;&nbsp; 的长期重要战略合作伙伴。</span></p><p style=\"text-indent: 2em; margin-top: 5px; margin-bottom: 5px; line-height: 2em;\"><span style=\"color: rgb(127, 127, 127); font-family: 微软雅黑,Microsoft YaHei; font-size: 14px;\">照升瞬网系统（移动互联网资源整合平台）可以分析出用户的生活形态与行为模式，在不同的时间、地&nbsp; 点推送不同的移动广告。照升愿与您探索数字时代的营销新可能。</span></p><br/></td><td style=\"border-width: 1px; border-style: solid; border-color: rgb(255, 255, 255); word-break: break-all;\" width=\"522\" valign=\"top\"><p><img src=\"/ueditor/php/upload/image/20170706/1499329455511174.png\" title=\"1499329455511174.png\" alt=\"company.png\"/></p><p><br/></p></td></tr><tr><td rowspan=\"1\" colspan=\"2\" style=\"word-break: break-all; border-width: 1px; border-style: solid; border-color: rgb(255, 255, 255);\" valign=\"top\"><p><br/></p><p><br/></p><p style=\"text-align:center\"><img src=\"/ueditor/php/upload/image/20170706/1499329487677306.jpg\" title=\"1499329487677306.jpg\" alt=\"QQ截图20170706162426.jpg\"/></p><p style=\"text-align:center\"><img src=\"/ueditor/php/upload/image/20170707/1499392444322019.png\" title=\"1499392444322019.png\" alt=\"ab01.png\"/></p><p><br/></p></td></tr><tr><td rowspan=\"1\" colspan=\"2\" style=\"word-break: break-all; border-width: 1px; border-style: solid; border-color: rgb(255, 255, 255);\" valign=\"top\"><p style=\"text-align:center\"><img src=\"/ueditor/php/upload/image/20170706/1499329556387056.jpg\" title=\"1499329556387056.jpg\" alt=\"QQ截图20170706162525.jpg\"/></p><p style=\"text-align:center\"><img src=\"/ueditor/php/upload/image/20170707/1499392345397770.png\" title=\"1499392345397770.png\" alt=\"ab02.png\"/></p><p><br/></p></td></tr></tbody></table><p><br/></p>');

-- ----------------------------
-- Table structure for t_admin
-- ----------------------------
DROP TABLE IF EXISTS `t_admin`;
CREATE TABLE `t_admin` (
  `adminid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `adminname` varchar(60) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `salt` char(6) NOT NULL DEFAULT '',
  `realname` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(60) NOT NULL DEFAULT '',
  `phone` varchar(30) NOT NULL DEFAULT '',
  `last_login_time` int(11) unsigned NOT NULL DEFAULT '0',
  `last_login_ip` varchar(40) NOT NULL DEFAULT '',
  `islocked` tinyint(1) NOT NULL DEFAULT '0',
  `createtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`adminid`)
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_admin
-- ----------------------------
INSERT INTO `t_admin` VALUES ('1', 'admin', '02975a7c26790da131dc054736581585', '4l6mht', '何纯敏', '657951486@qq.com', '15920376750', '1522640454', '127.0.0.1', '0', '1459221200');
INSERT INTO `t_admin` VALUES ('95', 'test1', '0232da4c9676979ea5f91e38e758d228', 'fp6ij9', '测试1', 'hello1@tom.com', '15899561152', '1498893742', '61.242.42.55', '0', '1498892353');

-- ----------------------------
-- Table structure for t_admin_group
-- ----------------------------
DROP TABLE IF EXISTS `t_admin_group`;
CREATE TABLE `t_admin_group` (
  `groupid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `groupname` varchar(100) DEFAULT NULL COMMENT '角色名称',
  `desc` text COMMENT '角色描述',
  `status` tinyint(4) DEFAULT NULL COMMENT '状态(1启用,0停用,默认1)',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`groupid`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_admin_group
-- ----------------------------
INSERT INTO `t_admin_group` VALUES ('1', '超级管理员', '测试', '1', null);
INSERT INTO `t_admin_group` VALUES ('3', '商品管理', '测试2', '1', '1497684699');
INSERT INTO `t_admin_group` VALUES ('4', '全局配置权限', '123', '1', '1497835079');
INSERT INTO `t_admin_group` VALUES ('5', '测试', '测试', '1', '1498207004');
INSERT INTO `t_admin_group` VALUES ('7', '新闻管理', '信息发布', '0', '1498268383');
INSERT INTO `t_admin_group` VALUES ('12', '测试权限', '测试权限', '0', '1498468105');
INSERT INTO `t_admin_group` VALUES ('13', '订单管理员', '测试', '1', '1498468284');

-- ----------------------------
-- Table structure for t_admin_menu
-- ----------------------------
DROP TABLE IF EXISTS `t_admin_menu`;
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
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_admin_menu
-- ----------------------------
INSERT INTO `t_admin_menu` VALUES ('1', '全局配置', '0', '0', '#', '', 'icon-system_monitor', '1');
INSERT INTO `t_admin_menu` VALUES ('19', '系统管理员', '1', '1', 'admin/index', '', 'icon-user', '1');
INSERT INTO `t_admin_menu` VALUES ('26', '系统设置', '1', '1', 'setting/index', '', 'icon-manage_user', '1');
INSERT INTO `t_admin_menu` VALUES ('66', '菜单管理', '1', '1', 'adminMenu/index', '', 'icon-setting_tools', '1');
INSERT INTO `t_admin_menu` VALUES ('67', '权限管理', '1', '1', 'adminGroup/index', '', 'icon-vcard', '1');
INSERT INTO `t_admin_menu` VALUES ('68', '测试', '1', '0', 'asdasd', '', 'icon-Account_Edit', '1');
INSERT INTO `t_admin_menu` VALUES ('69', '请问', '0', '0', '阿萨德', '', 'icon-system_monitor', '1');
INSERT INTO `t_admin_menu` VALUES ('84', '阿萨德', '1', '0', '阿萨德', '', '爱上', '1');

-- ----------------------------
-- Table structure for t_admin_menurole
-- ----------------------------
DROP TABLE IF EXISTS `t_admin_menurole`;
CREATE TABLE `t_admin_menurole` (
  `groupid` smallint(5) DEFAULT NULL,
  `menuid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_admin_menurole
-- ----------------------------
INSERT INTO `t_admin_menurole` VALUES ('3', '1');
INSERT INTO `t_admin_menurole` VALUES ('3', '19');
INSERT INTO `t_admin_menurole` VALUES ('3', '26');
INSERT INTO `t_admin_menurole` VALUES ('3', '66');
INSERT INTO `t_admin_menurole` VALUES ('3', '67');
INSERT INTO `t_admin_menurole` VALUES ('4', '1');
INSERT INTO `t_admin_menurole` VALUES ('4', '19');
INSERT INTO `t_admin_menurole` VALUES ('4', '26');
INSERT INTO `t_admin_menurole` VALUES ('4', '66');
INSERT INTO `t_admin_menurole` VALUES ('4', '67');
INSERT INTO `t_admin_menurole` VALUES ('12', '69');
INSERT INTO `t_admin_menurole` VALUES ('12', '1');
INSERT INTO `t_admin_menurole` VALUES ('12', '26');
INSERT INTO `t_admin_menurole` VALUES ('7', '69');
INSERT INTO `t_admin_menurole` VALUES ('7', '70');
INSERT INTO `t_admin_menurole` VALUES ('7', '1');
INSERT INTO `t_admin_menurole` VALUES ('7', '68');
INSERT INTO `t_admin_menurole` VALUES ('7', '19');
INSERT INTO `t_admin_menurole` VALUES ('7', '26');
INSERT INTO `t_admin_menurole` VALUES ('7', '66');
INSERT INTO `t_admin_menurole` VALUES ('7', '67');
INSERT INTO `t_admin_menurole` VALUES ('1', '69');
INSERT INTO `t_admin_menurole` VALUES ('5', '1');
INSERT INTO `t_admin_menurole` VALUES ('5', '66');
INSERT INTO `t_admin_menurole` VALUES ('5', '67');
INSERT INTO `t_admin_menurole` VALUES ('13', '1');
INSERT INTO `t_admin_menurole` VALUES ('13', '68');
INSERT INTO `t_admin_menurole` VALUES ('13', '19');
INSERT INTO `t_admin_menurole` VALUES ('13', '26');
INSERT INTO `t_admin_menurole` VALUES ('13', '66');
INSERT INTO `t_admin_menurole` VALUES ('13', '69');

-- ----------------------------
-- Table structure for t_admin_usergroup
-- ----------------------------
DROP TABLE IF EXISTS `t_admin_usergroup`;
CREATE TABLE `t_admin_usergroup` (
  `groupid` smallint(5) NOT NULL COMMENT '用户组ID',
  `adminid` int(11) DEFAULT NULL COMMENT '父ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_admin_usergroup
-- ----------------------------
INSERT INTO `t_admin_usergroup` VALUES ('1', '71');
INSERT INTO `t_admin_usergroup` VALUES ('3', '71');
INSERT INTO `t_admin_usergroup` VALUES ('4', '71');
INSERT INTO `t_admin_usergroup` VALUES ('4', '79');
INSERT INTO `t_admin_usergroup` VALUES ('3', '80');
INSERT INTO `t_admin_usergroup` VALUES ('1', '81');
INSERT INTO `t_admin_usergroup` VALUES ('1', '88');
INSERT INTO `t_admin_usergroup` VALUES ('3', '88');
INSERT INTO `t_admin_usergroup` VALUES ('4', '88');
INSERT INTO `t_admin_usergroup` VALUES ('5', '88');
INSERT INTO `t_admin_usergroup` VALUES ('1', '87');
INSERT INTO `t_admin_usergroup` VALUES ('3', '87');
INSERT INTO `t_admin_usergroup` VALUES ('4', '87');

-- ----------------------------
-- Table structure for t_banner
-- ----------------------------
DROP TABLE IF EXISTS `t_banner`;
CREATE TABLE `t_banner` (
  `bannerid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `bannerurl` varchar(300) NOT NULL,
  `bannerimg` varchar(300) NOT NULL,
  `display` tinyint(1) NOT NULL DEFAULT '1',
  `sort` smallint(5) NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`bannerid`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_banner
-- ----------------------------
INSERT INTO `t_banner` VALUES ('55', '#', '20180402\\4337d5b1262829a661dd91910522e9d0.jpg', '1', '1', '1', '1522658200');
INSERT INTO `t_banner` VALUES ('56', '#', '20180402\\918cb3009c27c90f3d63747394eeadb7.jpg', '1', '2', '1', '1522658238');
INSERT INTO `t_banner` VALUES ('57', '#', '20180402\\343328665818c48e0645e8276e405dba.jpg', '1', '3', '1', '1522658249');
INSERT INTO `t_banner` VALUES ('58', '#', '20180402\\186468e1ac2e91898adb2425d4e34b1e.jpg', '1', '4', '1', '1522658261');
INSERT INTO `t_banner` VALUES ('59', '#', '20180402\\1e10200546338db5ea676927f7033d9c.jpg', '1', '5', '1', '1522658273');

-- ----------------------------
-- Table structure for t_category
-- ----------------------------
DROP TABLE IF EXISTS `t_category`;
CREATE TABLE `t_category` (
  `catid` smallint(10) unsigned NOT NULL AUTO_INCREMENT,
  `catname` varchar(100) NOT NULL DEFAULT '' COMMENT '分类名称',
  `pid` smallint(5) NOT NULL COMMENT '父级ID(0表示一级分类)',
  `sort` smallint(5) NOT NULL DEFAULT '0' COMMENT '排序',
  `isshow` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  `level` tinyint(3) NOT NULL DEFAULT '0' COMMENT '级别',
  `desc` varchar(255) DEFAULT NULL COMMENT '分类介绍',
  PRIMARY KEY (`catid`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_category
-- ----------------------------
INSERT INTO `t_category` VALUES ('15', '精美单品', '0', '1', '1', '1522661320', '0', '精美单品');
INSERT INTO `t_category` VALUES ('16', '优选套装', '0', '2', '1', '1522661336', '0', '优选套装');

-- ----------------------------
-- Table structure for t_friendlink
-- ----------------------------
DROP TABLE IF EXISTS `t_friendlink`;
CREATE TABLE `t_friendlink` (
  `friendid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `webname` varchar(200) NOT NULL COMMENT '网站名称',
  `url` varchar(100) NOT NULL COMMENT '网址',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排列位置(由小排到大)',
  `logo` varchar(100) NOT NULL COMMENT '网站logo',
  `desc` varchar(255) NOT NULL COMMENT '网站简介',
  `isshow` tinyint(3) NOT NULL DEFAULT '1' COMMENT '是否显示(1显示，0不显示，默认1)',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`friendid`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_friendlink
-- ----------------------------
INSERT INTO `t_friendlink` VALUES ('26', '陌陌', '#', '1', '20170707/76b0473814104a40a73dc47598b93c4c.png', '', '1', '0');
INSERT INTO `t_friendlink` VALUES ('27', '广点通', '#', '2', '20170707/4523bf6f67d1ced7d82974ee09149caf.png', '12', '1', '0');
INSERT INTO `t_friendlink` VALUES ('28', '微信', '#', '45621', '20170707/1c8ce078cc0d088b30cb8dd0b416e443.png', '', '1', '0');
INSERT INTO `t_friendlink` VALUES ('29', '今日头条', '#', '456', '20170707/b0d3b00d287d987d3125f7ec7dec3b93.png', '', '1', '0');
INSERT INTO `t_friendlink` VALUES ('30', 'UC', '#', '123', '20170707/a4f94ab70aa572246c0dea8f692d5283.png', '阿萨德', '1', '0');
INSERT INTO `t_friendlink` VALUES ('31', '百度', '#', '4', '20170707/71a922650a2b523f24e3da07b95d23a3.jpg', '', '1', '0');
INSERT INTO `t_friendlink` VALUES ('32', '新浪', '#', '123', '20170707/617fb27d594336d0a6403b21329b1143.jpg', '', '1', '0');
INSERT INTO `t_friendlink` VALUES ('39', '万能wify', '#', '9', '20170707/e5a15f8d818f310798cab60034d5ac62.jpg', '', '1', '1499412193');

-- ----------------------------
-- Table structure for t_message
-- ----------------------------
DROP TABLE IF EXISTS `t_message`;
CREATE TABLE `t_message` (
  `messageid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `message_name` varchar(100) DEFAULT NULL COMMENT '姓名',
  `message_email` varchar(50) DEFAULT NULL COMMENT '邮箱',
  `message_phone` varchar(50) DEFAULT NULL COMMENT '电话',
  `message` varchar(500) DEFAULT NULL COMMENT '内容',
  `createtime` int(11) DEFAULT NULL COMMENT '添加时间',
  `message_status` smallint(4) DEFAULT NULL COMMENT '留言状态（1已查看，0未查看，默认0）',
  `uploadtime` int(11) DEFAULT NULL COMMENT '留言时间',
  PRIMARY KEY (`messageid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_message
-- ----------------------------
INSERT INTO `t_message` VALUES ('1', 'ceshi', 'ceshi', '15511111111', 'ceshiceshi', '1499496512', '1', '1498789156');

-- ----------------------------
-- Table structure for t_nav
-- ----------------------------
DROP TABLE IF EXISTS `t_nav`;
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
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_nav
-- ----------------------------
INSERT INTO `t_nav` VALUES ('1', '产品服务', '0', '2', 'product/index', '#', '59603d564a42b.jpg', '1');
INSERT INTO `t_nav` VALUES ('7', '解决方案', '0', '3', 'solution/index', '#', '595de04c2df1c.jpg', '1');
INSERT INTO `t_nav` VALUES ('8', '新闻动态', '0', '4', 'news/index', '#', '595de068ba57c.jpg', '1');
INSERT INTO `t_nav` VALUES ('85', '首页', '0', '1', '/', '#', '595de010d4491.jpg,59603d031a6f0.jpg', '1');
INSERT INTO `t_nav` VALUES ('88', '关于我们', '0', '5', 'aboutus/index', '#', '595ddf1594b8f.jpg', '1');
INSERT INTO `t_nav` VALUES ('89', '联系我们', '0', '6', 'contactus/index', '#', '595ddf6d1104c.jpg', '1');
INSERT INTO `t_nav` VALUES ('90', '招兵买马', '0', '7', 'recruit/index', '#', '595ddfdc290c7.jpg', '1');

-- ----------------------------
-- Table structure for t_news
-- ----------------------------
DROP TABLE IF EXISTS `t_news`;
CREATE TABLE `t_news` (
  `newsid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `pid` smallint(5) NOT NULL DEFAULT '0' COMMENT '类型ID(为0时属于新闻动态下的文章)',
  `author` varchar(100) NOT NULL DEFAULT '' COMMENT '作者',
  `image` varchar(255) NOT NULL DEFAULT '' COMMENT '图片地址',
  `intro` varchar(500) NOT NULL DEFAULT '' COMMENT '简介',
  `contant` text NOT NULL COMMENT '新闻内容',
  `istuijian` tinyint(4) NOT NULL DEFAULT '2' COMMENT '是否推荐(1为是，2否，默认为2)',
  `isshow` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否显示(1为显示，2不显示，默认为1)',
  `sort` smallint(5) NOT NULL DEFAULT '0' COMMENT '排序字段',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`newsid`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_news
-- ----------------------------
INSERT INTO `t_news` VALUES ('72', '厉害了！照升网络荣获UC头条汇川代理权!', '0', '何纯敏', '20170708/142640dc222e341f1167bfa17eb8e6b5.png', '2016年11月，帝都传来喜讯：照升网络正式成为UC头条汇川代理，有权代理旗下UC、UC头条、PP助手等产品的推广服务，达成合作共识。这是继成为陌陌、小知&华为悦读核心代理之后，照升网络再添殊荣，成为UC头条汇川合作伙伴', '<p>2016年11月，帝都传来喜讯：照升网络正式成为UC头条汇川代理，有权代理旗下UC、UC头条、PP助手等产品的推广服务，达成合作共识。这是继成为陌陌、小知&amp;华为悦读核心代理之后，照升网络再添殊荣，成为UC头条汇川合作伙伴。</p>', '2', '1', '0', '1499443200');
INSERT INTO `t_news` VALUES ('78', '微博月活跃用户达2.82亿', '0', '何纯敏', '20170708/d5f75ab6ef983052aea40db146276ea3.png', '2016年11月，帝都传来喜讯：照升网络正式成为UC头条汇川代理，有权代理旗下UC、UC头条、PP助手等产品的推广服务，达成合作共识。这是继成为陌陌、小知&华为悦读核心代理之后，照升网络再添殊荣，成为UC头条汇川合作伙伴', '<p>2016年11月，帝都传来喜讯：照升网络正式成为UC头条汇川代理，有权代理旗下UC、UC头条、PP助手等产品的推广服务，达成合作共识。这是继成为陌陌、小知&amp;华为悦读核心代理之后，照升网络再添殊荣，成为UC头条汇川合作伙伴。</p><p>据悉，参与此次竞标的都是行业内知名的代理公司，经过严格的打分和实地考察，照升网络脱颖而出，荣获UC头条汇川代理权。获此殊荣，非常感谢的是UC对我们的认可，未来我们将一起努力，为更多的广告主提供优质的服务。</p><p style=\"text-align:center\"><img src=\"/ueditor/php/upload/image/20170708/1499495083235591.png\" title=\"1499495083235591.png\" alt=\"news_pic01.png\"/></p><p>2016年11月，帝都传来喜讯：照升网络正式成为UC头条汇川代理，有权代理旗下UC、UC头条、PP助手等产品的推广服务，达成合作共识。这是继成为陌陌、小知&amp;华为悦读核心代理之后，照升网络再添殊荣，成为UC头条汇川合作伙伴。</p><p>据悉，参与此次竞标的都是行业内知名的代理公司，经过严格的打分和实地考察，照升网络脱颖而出，荣获UC头条汇川代理权。获此殊荣，非常感谢的是UC对我们的认可，未来我们将一起努力，为更多的广告主提供优质的服务。</p><p>&nbsp; &nbsp; &nbsp; &nbsp;\n &nbsp; &nbsp; &nbsp; &nbsp;\n &nbsp;</p><p><br/></p>', '2', '1', '0', '1499443200');

-- ----------------------------
-- Table structure for t_news_category
-- ----------------------------
DROP TABLE IF EXISTS `t_news_category`;
CREATE TABLE `t_news_category` (
  `catid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `catname` varchar(100) NOT NULL DEFAULT '',
  `pid` smallint(5) NOT NULL DEFAULT '0',
  `sort` smallint(5) NOT NULL DEFAULT '0',
  `level` smallint(5) NOT NULL DEFAULT '0',
  `isshow` tinyint(4) NOT NULL DEFAULT '1',
  `createtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`catid`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_news_category
-- ----------------------------
INSERT INTO `t_news_category` VALUES ('30', '默认分类', '0', '0', '0', '1', '1499486280');

-- ----------------------------
-- Table structure for t_order
-- ----------------------------
DROP TABLE IF EXISTS `t_order`;
CREATE TABLE `t_order` (
  `orderid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orderno` varchar(30) NOT NULL DEFAULT '',
  `memberid` int(11) NOT NULL DEFAULT '0',
  `sumqty` int(11) NOT NULL DEFAULT '0',
  `totalamount` decimal(18,2) NOT NULL DEFAULT '0.00',
  `sumprice` decimal(18,2) NOT NULL DEFAULT '0.00',
  `freight` decimal(10,2) NOT NULL DEFAULT '0.00',
  `pay_actual` decimal(18,2) NOT NULL DEFAULT '0.00',
  `payid` tinyint(4) NOT NULL DEFAULT '0',
  `name` varchar(60) NOT NULL DEFAULT '',
  `sex` tinyint(4) NOT NULL DEFAULT '2',
  `phone` varchar(50) NOT NULL DEFAULT '',
  `zipcode` varchar(60) NOT NULL DEFAULT '',
  `area` varchar(255) NOT NULL DEFAULT '',
  `address` varchar(200) NOT NULL DEFAULT '',
  `message` varchar(200) NOT NULL DEFAULT '',
  `bankid` varchar(20) NOT NULL DEFAULT '',
  `creditid` varchar(20) NOT NULL DEFAULT '',
  `trade_no` varchar(100) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `iscomment` tinyint(4) NOT NULL DEFAULT '2',
  `delivery_time` varchar(30) NOT NULL DEFAULT '0',
  `islocked` tinyint(4) NOT NULL DEFAULT '2',
  `paytime` int(11) NOT NULL DEFAULT '0',
  `createtime` int(11) NOT NULL DEFAULT '0',
  `out_trade_no` varchar(30) NOT NULL DEFAULT '',
  `express_company` varchar(100) NOT NULL DEFAULT '',
  `express_number` varchar(255) NOT NULL DEFAULT '',
  `ispay` tinyint(4) NOT NULL DEFAULT '0',
  `platform` tinyint(3) NOT NULL DEFAULT '0' COMMENT '支付平台(0移动，1PC，默认0)',
  `completetime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`orderid`),
  KEY `memberid` (`memberid`),
  KEY `orderno` (`orderno`) USING BTREE,
  KEY `createtime` (`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_order
-- ----------------------------

-- ----------------------------
-- Table structure for t_order_detail
-- ----------------------------
DROP TABLE IF EXISTS `t_order_detail`;
CREATE TABLE `t_order_detail` (
  `orderdetailid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '//',
  `orderno` varchar(30) NOT NULL DEFAULT '',
  `productid` int(11) NOT NULL DEFAULT '0',
  `prodname` varchar(255) NOT NULL DEFAULT '' COMMENT '//',
  `qty` int(11) NOT NULL DEFAULT '0',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `amount` decimal(18,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`orderdetailid`),
  KEY `orderno` (`orderno`),
  KEY `productid` (`productid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_order_detail
-- ----------------------------

-- ----------------------------
-- Table structure for t_product
-- ----------------------------
DROP TABLE IF EXISTS `t_product`;
CREATE TABLE `t_product` (
  `productid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catid` smallint(5) NOT NULL COMMENT '分类ID',
  `sourceimg` varchar(200) NOT NULL COMMENT '产品主图',
  `image` varchar(300) NOT NULL DEFAULT '' COMMENT '产品图地址',
  `prodname` varchar(200) NOT NULL DEFAULT '' COMMENT '产品名称',
  `standard` varchar(255) NOT NULL DEFAULT '',
  `price` decimal(8,2) NOT NULL DEFAULT '0.00',
  `advantage ` varchar(255) NOT NULL DEFAULT '',
  `usemethod` varchar(255) NOT NULL DEFAULT '',
  `result` varchar(255) NOT NULL DEFAULT '',
  `intro` varchar(200) NOT NULL COMMENT '产品简介',
  `desc` text COMMENT '产品详情',
  `isshow` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否显示 1显示 2隐藏 默认1',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  `sort` smallint(6) DEFAULT NULL COMMENT '排序',
  PRIMARY KEY (`productid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_product
-- ----------------------------

-- ----------------------------
-- Table structure for t_recruit
-- ----------------------------
DROP TABLE IF EXISTS `t_recruit`;
CREATE TABLE `t_recruit` (
  `recruitid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL COMMENT '招聘标题',
  `post` varchar(100) NOT NULL COMMENT '招聘岗位',
  `desc` text COMMENT '招聘详情',
  `sort` tinyint(3) DEFAULT NULL COMMENT '排序',
  `isshow` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`recruitid`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_recruit
-- ----------------------------
INSERT INTO `t_recruit` VALUES ('2', 'SEM优化师/运营专员 ', 'SEM优化师/运营专员 ', '<p>\n	<span style=\"font-family: 微软雅黑,Microsoft YaHei; font-size: 16px; color: rgb(127, 127, 127);\">招聘人数：多人<br/>工作地点：广州市天河区中山大道东圃<br/>发布时间：2017-3-8</span></p><p><br/><span style=\"font-family: 微软雅黑,Microsoft YaHei; font-size: 16px; color: rgb(127, 127, 127);\">岗位职责：</span></p><p><span style=\"font-family: 微软雅黑,Microsoft YaHei; font-size: 16px; color: rgb(127, 127, 127);\">\n	1、协调各部门的账户优化支持需求，对各部门客户进行账户分析及消耗监控 ，并提交相应数据报告;<br/>2、对所负责客户进行阶段性工作总结，及时与各部门沟通协调问题处理事宜;<br/>3、将所负责客户群体进行资源整合，针对客户效果要求给出全方位的整合营 销方案;<br/>4、本质工作为协助各部门提升客户消耗，同时需完成上级领导安排的其他工作事宜;</span></p><p><br/><span style=\"font-family: 微软雅黑,Microsoft YaHei; font-size: 16px; color: rgb(127, 127, 127);\">任职要求：</span></p><p><span style=\"font-family: 微软雅黑,Microsoft YaHei; font-size: 16px; color: rgb(127, 127, 127);\">\n	1、大专及以上学历;<br/>2、半年以上SEM方面工作经验，有减肥、丰胸等行业工作经验优先考虑;<br/>3、责任心强，能承受一定的工作压力，热衷互联网行业，期望长期在此行业发展;<br/>4、熟练使用office办公软件;</span></p><p>\n\n	</p>', '12', '1', '1499410943');
INSERT INTO `t_recruit` VALUES ('3', '销售总监', '销售总监', '<p><span style=\"font-family: 微软雅黑,Microsoft YaHei; font-size: 16px; color: rgb(127, 127, 127);\">招聘人数：1人<br/>工作地点：广州市天河区中山大道东圃<br/>发布时间：2017-3-6</span></p><p><br/><span style=\"font-family: 微软雅黑,Microsoft YaHei; font-size: 16px; color: rgb(127, 127, 127);\">岗位职责：<br/>1、重点开发快消品牌客户（直客或对接4A），以CPC/CPM/CPD投放业务为主，提升毛利率为工作重心;<br/>2、能与媒介运营团队紧密沟通、配合，完成公司既定的销售目标，并及时制定完整、有效、可执行的工作计划。特别在公司策略型媒介资源的销售方面给出销售成绩;<br/>3、建立和管理品牌销售团队;</span></p><p><br/><span style=\"font-family: 微软雅黑,Microsoft YaHei; font-size: 16px; color: rgb(127, 127, 127);\">任职要求：<br/>1、具备5年以上广告销售经验;<br/>2、熟悉华南地区客户情况，熟悉4A广告公司架构及运作流程；<br/>3、全面了解移动互联网多种广告投放模式及客户对投放KPI的解读与引导；<br/>4、良好的语言沟通能力、敏锐的市场触觉，对通过多种方式了解客户需求；<br/>5、有2年以上团队管理经验，带领团队完成任务的同时保持团队稳定；<br/>6、抗压能力强，能担负业绩指标压力；<br/>7、客户资源以快消、O2O行业为优先;</span></p><p><br/></p>', '12', '1', '1499408859');
INSERT INTO `t_recruit` VALUES ('7', '运营助理 ', '运营助理 ', '<p><span style=\"font-family: 微软雅黑,Microsoft YaHei; color: rgb(127, 127, 127); font-size: 16px;\">招聘人数：3人<br/>工作地点：广州市天河区中山大道东圃<br/>发布时间：2017-3-7</span></p><p><br/><span style=\"font-family: 微软雅黑,Microsoft YaHei; color: rgb(127, 127, 127); font-size: 16px;\">岗位职责： <br/>1、负责客户日常消耗数据监控及播报;<br/>2、负责每日客户消耗日报输出;<br/>3、协助其他部门的工作，并跟产品厂商进行日常事宜对接;<br/>4、完成上级领导安排的其他工作事宜;<br/><br/>任职要求：<br/>1、大专及以上学历;<br/>2、半年以上SEM方面工作经验，细心耐心、对数据敏感、逻辑能力强;<br/>3、责任心强，能承受一定的工作压力，热衷互联网行业，期望长期在此行业发展;<br/>4、熟练使用office办公软件;</span></p>', '12', '1', '1499410921');
INSERT INTO `t_recruit` VALUES ('8', '媒介专员 ', '媒介专员 ', '<p><span style=\"font-family: 微软雅黑,Microsoft YaHei; font-size: 16px; color: rgb(127, 127, 127);\">\n	招聘人数：3人</span></p><p><span style=\"font-family: 微软雅黑,Microsoft YaHei; font-size: 16px; color: rgb(127, 127, 127);\">\n	工作地点：广州市天河区中山大道中2号粤保中心广场610-615室<br/>发布时间：2017-3-8</span></p><p><br/><span style=\"font-family: 微软雅黑,Microsoft YaHei; font-size: 16px; color: rgb(127, 127, 127);\">岗位职责：<br/>1、负责与媒介对接广告项目排期的制作，合作合同的制作、审核、 签订、返回的跟进;<br/>2、与媒体跟进广告上线前的沟通，收集客户需求，反馈给媒体，并协调上线所需准备工作;<br/>3、在广告上线执行期间与媒体进行有效的内、外部协调，包括新的广告资源的跟进；上线时间的把控；与投放顾问进行准确的信息传达及沟通;<br/>4、投放过程中对项目数据进行及时跟进，对投放中的问题及时反馈给媒体并协调解决;<br/>5、开拓新的媒体资源，维护媒体关系，确保与媒体能够顺畅的开展日常工作;<br/>6、日常文档以及数据汇总日报周报的撰写等等，协助相关销售人员完成对账、追款、结案报告的制作等工作，负责公司的媒体关系维护以及处理<br/>团队内外部的支持工作;</span></p><p><br/><span style=\"font-family: 微软雅黑,Microsoft YaHei; font-size: 16px; color: rgb(127, 127, 127);\">任职要求：<br/>大专本科或以上学历，营销相关专业，热爱广告行业。对移动、互联网感兴趣，细致认真，能够承受压力;</span></p>', '15', '1', '1499153488');
INSERT INTO `t_recruit` VALUES ('11', 'AE客服 ', 'AE客服 ', '<p><span style=\"font-family: 微软雅黑,Microsoft YaHei; color: rgb(127, 127, 127); font-size: 16px;\">招聘人数：1人<br/>工作地点：广州市天河区中山大道东圃<br/>发布时间：2017-3-7</span></p><p><br/><span style=\"font-family: 微软雅黑,Microsoft YaHei; color: rgb(127, 127, 127); font-size: 16px;\">岗位职责： <br/>1.负责项目的对接沟通和执行，与客户保持良好的沟通与联络;<br/>2.能精准把握客户需求，并能准确及条理清晰的对内传达客户需求；<br/>3.负责协调公司内部对客户项目的执行跟进工作及项目相关工作；<br/>4.统计每日各账户的消耗情况，并及时汇报部门主管；<br/>5.跟进对接客户广告投放效果及问题反馈；<br/><br/>任职要求：<br/>1、大专以上学历，广告、传媒、市场营销、一年以上互联网或广告行业相关工作经验优先；<br/>2、对互联网广告感兴趣，细致认真，有一定的抗压能力；<br/>3、熟练使用各种办公软件，优越的人际关系协调能力，团队组织协调能力和执行力;</span></p>', '5', '1', '1499410857');
INSERT INTO `t_recruit` VALUES ('12', '销售代表 ', '销售代表 ', '<p><span style=\"font-family: 微软雅黑,Microsoft YaHei; font-size: 16px; color: rgb(127, 127, 127);\">招聘人数：5人<br/>工作地点：广州市天河区中山大道东圃<br/>发布时间：2017-3-7<br/><br/>岗位职责：<br/>1、 进行新客户的开拓，筛选有意向的客户达成合作。并维护好客户关系，稳定长期合作关系；<br/>2、以网络营销为主，偶尔需要外出拜访（针对不同项目），对公司的产品进行销售推广；<br/>3、掌握公司的产品和推广策略及其他销售工作要求；<br/>4、在部门主管的带领下，实现个人业绩目标；<br/>5、与其他部门同事协作，充分把握客户需求，完成售前服务，跟进售后服务；<br/>6、根据客户需求和市场变化，对公司的产品推广提出改进建议;<br/><br/>任职要求：<br/>1、 男女不限，大专以上，市场营销相关专业；<br/>2、能吃苦耐劳，具有良好的口头表达能力和沟通技巧；<br/>3、坦诚自信，乐观进取，高度的工作热情，乐于在计算机和互联网行业发展；<br/>4、有良好的团队合作精神，有敬业精神；<br/>5、具有独立的分析和解决问题的能力；<br/>6、良好的沟通技巧和说服能力，能承受较大的工作压力；<br/>7、性格开朗、务实，能吃苦耐劳,富有协作和团队精神，积极、自信、敬业，具有开拓精神；</span></p>', '7', '1', '1499410742');

-- ----------------------------
-- Table structure for t_setting
-- ----------------------------
DROP TABLE IF EXISTS `t_setting`;
CREATE TABLE `t_setting` (
  `settingid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `variable` varchar(100) NOT NULL DEFAULT '',
  `value` text,
  `code` varchar(30) NOT NULL DEFAULT '',
  `desc` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`settingid`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_setting
-- ----------------------------
INSERT INTO `t_setting` VALUES ('66', 'webname', '广州菩昱贸易有限公司', 'basic', '网站名称');
INSERT INTO `t_setting` VALUES ('67', 'copyright', 'Copyright © 广州菩昱贸易有限公司', 'basic', '版权信息');
INSERT INTO `t_setting` VALUES ('68', 'service_phone', '020-87600109', 'basic', '客服电话');
INSERT INTO `t_setting` VALUES ('69', 'company_address', '广州市天河区', 'basic', '公司地址');
INSERT INTO `t_setting` VALUES ('70', 'company_url', 'http://www.xinglinhall.com', 'basic', '公司网址');
INSERT INTO `t_setting` VALUES ('71', 'dawk', '510520', 'basic', '邮政编码');
INSERT INTO `t_setting` VALUES ('72', 'company_call', '020-87600109', 'basic', '公司电话');
INSERT INTO `t_setting` VALUES ('73', 'company_email', '123456@163.com', 'basic', '公司邮箱');
INSERT INTO `t_setting` VALUES ('77', 'logo', '20180402\\56a1b9e41556b68e4666e8e39e39a52d.png', 'basic', '网站logo');

-- ----------------------------
-- Table structure for t_solution
-- ----------------------------
DROP TABLE IF EXISTS `t_solution`;
CREATE TABLE `t_solution` (
  `solutionid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catid` smallint(5) DEFAULT NULL COMMENT '分类ID',
  `name` varchar(200) DEFAULT NULL COMMENT '案例名称',
  `desc` text COMMENT '案例详情',
  `isshow` tinyint(4) DEFAULT NULL COMMENT '是否显示 1显示 2隐藏 默认1',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `sort` smallint(6) DEFAULT NULL COMMENT '排序',
  PRIMARY KEY (`solutionid`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_solution
-- ----------------------------
INSERT INTO `t_solution` VALUES ('14', '8', 'ces1', '<p>12333</p>', '1', '1499443200', '0');
INSERT INTO `t_solution` VALUES ('15', '9', 'ces1', '<p>123</p>', '1', '1499443200', '0');
INSERT INTO `t_solution` VALUES ('18', '13', 'APP推广', '<p style=\"text-align:center\"><img src=\"/ueditor/php/upload/image/20170708/1499502524165669.jpg\" title=\"1499502524165669.jpg\" alt=\"27.jpg\"/></p><p style=\"text-align:center\"><img src=\"/ueditor/php/upload/image/20170708/1499502537234847.jpg\" title=\"1499502537234847.jpg\" alt=\"28.jpg\"/><img src=\"/ueditor/php/upload/image/20170708/1499502549577159.jpg\" title=\"1499502549577159.jpg\" alt=\"29.jpg\"/></p><p><br/></p>', '1', '1499443200', '1');
INSERT INTO `t_solution` VALUES ('19', '13', '游戏行业', '<p style=\"text-align:center\"><img src=\"/ueditor/php/upload/image/20170708/1499502722844341.jpg\" title=\"1499502722844341.jpg\" alt=\"30.jpg\"/></p><p style=\"text-align:center\"><img src=\"/ueditor/php/upload/image/20170708/1499502733398917.jpg\" title=\"1499502733398917.jpg\" alt=\"31.jpg\"/></p><p style=\"text-align:center\"><img src=\"/ueditor/php/upload/image/20170708/1499502742684088.jpg\" title=\"1499502742684088.jpg\" alt=\"32.jpg\"/></p><p><br/></p><p><br/></p><p><br/></p><p><br/></p><p><br/></p>', '1', '1499443200', '2');
INSERT INTO `t_solution` VALUES ('20', '13', '美容行业', '<p style=\"text-align:center\"><img src=\"/ueditor/php/upload/image/20170708/1499502802734000.jpg\" title=\"1499502802734000.jpg\" alt=\"35.jpg\"/></p><p style=\"text-align:center\"><img src=\"/ueditor/php/upload/image/20170708/1499502811150792.jpg\" title=\"1499502811150792.jpg\" alt=\"36.jpg\"/></p><p style=\"text-align:center\"><img src=\"/ueditor/php/upload/image/20170708/1499502832650206.jpg\" title=\"1499502832650206.jpg\" alt=\"37.jpg\"/></p><p><br/></p>', '1', '1499443200', '3');
INSERT INTO `t_solution` VALUES ('21', '13', '婚恋交友', '<p>网站资料更新中...</p>', '1', '1499443200', '4');
INSERT INTO `t_solution` VALUES ('22', '13', '数码产品', '<p>网站资料更新中...</p>', '1', '1499443200', '5');
INSERT INTO `t_solution` VALUES ('23', '13', '电商行业', '<p>网站资料更新中...</p>', '1', '1499443200', '6');
INSERT INTO `t_solution` VALUES ('24', '14', '腾讯情境广告', '<p style=\"text-align:center\"><img src=\"/ueditor/php/upload/image/20170708/1499502970216414.jpg\" title=\"1499502970216414.jpg\" alt=\"39.jpg\"/></p><p><br/></p>', '1', '1499443200', '1');
INSERT INTO `t_solution` VALUES ('25', '14', '新浪智投', '<p>网站资料更新中...</p>', '1', '1499443200', '2');
INSERT INTO `t_solution` VALUES ('26', '14', 'SEM营销', '<p>网站资料更新中...</p>', '1', '1499443200', '3');
INSERT INTO `t_solution` VALUES ('27', '14', '搜狗搜索', '<p>网站资料更新中...</p>', '1', '1499443200', '4');
INSERT INTO `t_solution` VALUES ('28', '15', '移动营销', '<p style=\"text-align:center\"><img src=\"/ueditor/php/upload/image/20170708/1499503097353361.jpg\" title=\"1499503097353361.jpg\" alt=\"40.jpg\"/></p><p><br/></p>', '1', '1499443200', '1');
INSERT INTO `t_solution` VALUES ('29', '15', 'SEO品牌营销', '<p>网站资料更新中...</p>', '1', '1499443200', '2');
INSERT INTO `t_solution` VALUES ('30', '15', '网络营销', '<p>网站资料更新中...</p>', '1', '1499443200', '3');
INSERT INTO `t_solution` VALUES ('31', '15', '电商促销', '<p>网站资料更新中...</p>', '1', '1499443200', '4');

-- ----------------------------
-- Table structure for t_solution_type
-- ----------------------------
DROP TABLE IF EXISTS `t_solution_type`;
CREATE TABLE `t_solution_type` (
  `catid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `catname` varchar(100) DEFAULT NULL COMMENT '分类名称',
  `pid` smallint(5) DEFAULT NULL COMMENT '父级ID(0表示一级分类)',
  `sort` smallint(5) DEFAULT NULL COMMENT '排序',
  `isshow` tinyint(4) DEFAULT NULL COMMENT '是否显示 1显示 2隐藏 默认1',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `level` tinyint(255) DEFAULT NULL COMMENT '级别',
  PRIMARY KEY (`catid`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_solution_type
-- ----------------------------
INSERT INTO `t_solution_type` VALUES ('13', '行业解决方案', '0', '1', '1', null, '0');
INSERT INTO `t_solution_type` VALUES ('14', '媒介解决方案', '0', '2', '1', null, '0');
INSERT INTO `t_solution_type` VALUES ('15', '需求解决方案', '0', '3', '1', null, '0');
