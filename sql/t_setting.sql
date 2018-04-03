CREATE TABLE `t_config` (
  `configid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `confname` varchar(100) NOT NULL DEFAULT ''comment '配置名称',
  `value` text comment '配置值',
  `code` varchar(30) NOT NULL DEFAULT '' comment '配置类型' ,
  `desc` varchar(100) NOT NULL DEFAULT '' comment '配置简介',
  PRIMARY KEY (`configid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

INSERT INTO `t_config` VALUES (1,'express_val','','system','运费'),(2,'order_tel','400-4000-002','system','订购热线'),(3,'consult_tel','400-4000-003','system','咨询热线'),(4,'phone','100','score','电话'),(5,'email','100','score','邮箱'),(6,'qq','10','score','qq'),(7,'sex','10','score','性别'),(8,'real_name','10','score','真实姓名'),(9,'common_medicine','10','score','常用药'),(10,'birthday','10','score','生日'),(11,'height','10','score','身高'),(12,'weight','10','score','体重'),(13,'maritalStatus','10','score','婚姻状况'),(14,'monthcome','10','score','月收入'),(15,'occupation','10','score','工作性质'),(16,'face','100','score','头像'),(17,'register','100','score','注册'),(18,'nickname','10','score','昵称'),(19,'follow_order_tel','400-4000-000','system','跟单热线'),(20,'consume','1','score','购买'),(21,'colophon','版权所有 Copyright©2006-2016 www.gdyaoqubing.com All rights reserved','system','版权设置'),(22,'orderPrompt','0','system','订单设置'),(23,'is_express','0','system','是否开启运费'),(24,'keywords','药去病,服务最好的网上药店，专业的网上药店,网上买药,网上药房,网上购药网站','system','网站关键字'),(25,'description','药去病网,做中国最大网上药店,经国家药监局认证的合法网上药店和正规药房网,买药省30%,100%正品.提供专业、优质和便捷的网上购药服务,执业医师为您提供24小时健康咨询!网上药店哪个好?首选药去病网！','system','网站描述'),(26,'title','药去病网-做中国服务最好网上药店','system','网页标题'),(27,'product_keywords','药去病,服务最好的网上药店，专业的网上药店,网上买药,网上药房,网上购药网站,药品分类','system','药品关键字'),(28,'product_description','药去病网,药品分类','system','商品分类描述'),(29,'product_title','药去病网-药品分类','system','药品分类标题'),(30,'news_keywords','药去病,服务最好的网上药店，专业的网上药店,网上买药,网上药房,网上购药网站,健康资讯','system','健康资讯关键字'),(31,'news_description','药去病网,健康资讯','system','健康资讯描述'),(32,'news_title','药去病网-健康资讯','system','健康资讯标题'),(33,'medication_keywords','药去病,服务最好的网上药店，专业的网上药店,网上买药,网上药房,网上购药网站,用药指导','system','用药指导关键字'),(34,'medication_description','药去病网,用药指导','system','用药指导描述'),(35,'medication_title','药去病网-用药指导','system','用药指导标题'),(36,'question_keywords','药去病,服务最好的网上药店，专业的网上药店,网上买药,网上药房,网上购药网站,健康问答','system','健康问答关键字'),(37,'question_description','药去病网,健康问答','system','健康问答描述'),(38,'question_title','药去病网-健康问答','system','健康问答标题'),(39,'SMTP_SERVER','smtp.mxhichina.com','system','邮件服务器'),(40,'SMTP_PORT','25','system','邮件服务器端口'),(41,'SMTP_USER_EMAIL','support@1yqian.com','system','SMTP服务器的用户邮箱'),(12,'SMTP_USER','support@1yqian.com','system','SMTP服务器账户名'),(43,'SMTP_PWD','K5Zv6N3vBAz8mcZq','system','SMTP服务器账户密码'),(44,'SMTP_MAIL_TYPE','HTML','system','发送邮件类型:HTML,TXT('),(45,'SMTP_TIME_OUT','30','system','超时时间'),(46,'SMTP_AUTH','1','system','邮箱验证口'),(47,'enterpriseID','16555','system','短信企业ID'),(18,'loginName','yaoqubing','system','短信登陆名'),(48,'password','yyq1014','system','短信登录密码'),(50,'url','http://119.145.9.12/sendSMS.action','system','短信http接口'),(51,'subPort','03','system','短信扩展');



CREATE TABLE `t_category` (
  `catid` Smallint(10) unsigned NOT NULL AUTO_INCREMENT,
  `catname` varchar(100) NOT NULL DEFAULT ''comment '分类名称',
  `pid` Smallint(5) NOT NULL comment '父级ID(0表示一级分类)',
  `sort` Smallint(5) NOT NULL DEFAULT '' comment '排序' ,
  `isshow` tinyint NOT NULL DEFAULT '' comment '是否显示',
  `createtime` int NOT NULL DEFAULT '' comment '创建时间',
  PRIMARY KEY (`catid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `t_product` (
  `productid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catid`Smallint(5) NOT NULL DEFAULT ''comment '分类ID',
  `sourceimg` varchar(200)  NOT NULL comment '产品主图',
  `image` varchar(300)  comment '产品图地址' ,
  `prodname` varchar(200) NOT NULL DEFAULT '' comment '产品名称',
  `intro` varchar(500) NOT NULL DEFAULT '' comment '产品简介',
  `desc` text  DEFAULT '' comment '产品详情',
  `isshow`tinyint NOT NULL DEFAULT '1' comment '是否显示 1显示 2隐藏 默认1',
  `createtime` int NOT NULL DEFAULT '' comment '创建时间',
  PRIMARY KEY (`productid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE `t_recruit` (
  `recruitid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL DEFAULT ''comment '招聘标题',
  `post` varchar(100) NOT NULL DEFAULT ''comment '招聘岗位',
  `require` text comment '招聘详情',
  `isshow` tinyint NOT NULL DEFAULT '' comment '是否显示' ,
  `createtime` int NOT NULL DEFAULT '' comment '创建时间',
  PRIMARY KEY (`recruitid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE `t_friendlink` (
  `friendid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `webname` varchar(200) NOT NULL DEFAULT ''comment '网站名称',
  `url` varchar(100) NOT NULL DEFAULT ''comment '网址',
  `sort`  varchar(100) NOT NULL DEFAULT '' comment '排列位置(由小排到大)',
  `logo` varchar(100)  NOT NULL DEFAULT '' comment '网站logo' ,
  `desc` varchar(255)  NOT NULL DEFAULT '' comment '网站简介' ,
  `isshow` tinyint NOT NULL DEFAULT '1' comment '是否显示(1显示，2不显示，默认1)' ,
  `createtime` int NOT NULL DEFAULT '' comment '创建时间',
  PRIMARY KEY (`friendid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;