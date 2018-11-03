/*
Navicat MySQL Data Transfer

Source Server         : 112.124.59.159
Source Server Version : 50722
Source Host           : 112.124.59.159:3306
Source Database       : redianbook

Target Server Type    : MYSQL
Target Server Version : 50722
File Encoding         : 65001

Date: 2018-08-03 15:15:50
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户的主键',
  `name` varchar(10) NOT NULL COMMENT '用户名称',
  `account` varchar(20) NOT NULL COMMENT '登陆账号',
  `password` varchar(50) NOT NULL COMMENT '用户密码',
  `direction` varchar(255) NOT NULL DEFAULT '' COMMENT '主攻方向',
  `image` varchar(255) NOT NULL DEFAULT '' COMMENT '用户头像',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '用户邮箱',
  `tel` varchar(12) NOT NULL DEFAULT '' COMMENT '用户手机号',
  `qq` varchar(15) NOT NULL DEFAULT '' COMMENT '制作人QQ',
  `wechar_name` varchar(20) NOT NULL DEFAULT '' COMMENT '制作人微信名称',
  `wechar_qrcode` varchar(255) NOT NULL DEFAULT '' COMMENT '制作人微信二维码',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '管理员描述',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '用户的状态',
  `role` tinyint(4) NOT NULL DEFAULT '0' COMMENT '用户的角色分配',
  `ip` varchar(16) NOT NULL DEFAULT '' COMMENT '用户最后登陆IP',
  `update_time` int(11) NOT NULL COMMENT '用户修改时间戳',
  `create_time` int(11) NOT NULL COMMENT '用户创建时间戳',
  PRIMARY KEY (`id`),
  UNIQUE KEY `table_admin_unique_name` (`name`),
  UNIQUE KEY `table_admin_unique_account` (`account`),
  KEY `table_admin_unique_email` (`email`),
  KEY `table_admin_unique_tel` (`tel`),
  KEY `table_admin_index_status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='制作人用户表格';

INSERT INTO `admin` VALUES ('1', 'admin', 'admin', '14e1b600b1fd579f47433b88e8d85291', '', '', '', '', '', '', '', '', '1', '1', '', '0', '0');

-- ----------------------------
-- Table structure for advert
-- ----------------------------
DROP TABLE IF EXISTS `advert`;
CREATE TABLE `advert` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '广告url',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `image` varchar(255) NOT NULL DEFAULT '' COMMENT '广告图片',
  `big_image` varchar(255) NOT NULL DEFAULT '' COMMENT '作用于大图',
  `site` varchar(20) NOT NULL DEFAULT '' COMMENT '广告位置',
  PRIMARY KEY (`id`),
  UNIQUE KEY `table_advert_site` (`site`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='广告位';

-- ----------------------------
-- Table structure for book
-- ----------------------------
DROP TABLE IF EXISTS `book`;
CREATE TABLE `book` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '书本主键',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '作者ID',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '制作人ID',
  `category_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `title` varchar(50) NOT NULL COMMENT '书本名称',
  `cover` varchar(255) NOT NULL COMMENT '书本封面',
  `description` varchar(500) NOT NULL COMMENT '书本简介',
  `char_number` int(11) DEFAULT '0' COMMENT '所有章节的文字总和',
  `copyright` tinyint(4) DEFAULT NULL COMMENT '书本版权',
  `status` tinyint(4) NOT NULL COMMENT '书本是否完结',
  `check` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态是否在审核',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '章节排序',
  `update_time` int(11) NOT NULL COMMENT '修改时间戳',
  `create_time` int(11) NOT NULL COMMENT '创建时间戳',
  PRIMARY KEY (`id`),
  KEY `table_book_index_admin-id` (`admin_id`),
  KEY `table_book_index_category-id` (`category_id`),
  KEY `table_book_index_status` (`status`),
  KEY `table_book_index_check` (`check`),
  KEY `table_book_index_copyright` (`copyright`),
  KEY `table_book_index_sort` (`sort`),
  FULLTEXT KEY `table_book_index_title` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='书本表格';

-- ----------------------------
-- Table structure for book_section
-- ----------------------------
DROP TABLE IF EXISTS `book_section`;
CREATE TABLE `book_section` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '章节主键',
  `book_id` int(10) unsigned DEFAULT NULL COMMENT '书本ID',
  `user_id` int(10) unsigned DEFAULT NULL COMMENT '作者ID',
  `title` varchar(255) NOT NULL COMMENT '章节标题',
  `content` text NOT NULL COMMENT '章节正文',
  `char` int(11) DEFAULT '0' COMMENT '章节文字数',
  `attr` tinyint(4) DEFAULT NULL COMMENT '章节属性',
  `check` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态是否在审核',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '章节排序',
  `update_time` int(11) NOT NULL COMMENT '修改时间戳',
  `create_time` int(11) NOT NULL COMMENT '创建时间戳',
  PRIMARY KEY (`id`),
  KEY `table_book-section_index_book-id` (`book_id`),
  KEY `table_book-section_index_user-id` (`user_id`),
  KEY `table_book-section_index_check` (`check`),
  KEY `table_book-section_index_attr` (`attr`),
  KEY `table_book-section_index_sort` (`sort`),
  FULLTEXT KEY `table_book-section_index_title` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='书本章节表格';

-- ----------------------------
-- Table structure for category
-- ----------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类主键',
  `title` varchar(20) NOT NULL COMMENT '分类标题',
  `spell` varchar(100) NOT NULL COMMENT '分类拼写',
  `sort` int(11) NOT NULL DEFAULT '20' COMMENT '分类排序',
  `update_time` int(11) NOT NULL COMMENT '修改时间戳',
  `create_time` int(11) NOT NULL COMMENT '创建时间戳',
  PRIMARY KEY (`id`),
  UNIQUE KEY `table_category_index_spell` (`spell`) USING BTREE,
  UNIQUE KEY `table_category_index_title` (`title`) USING BTREE,
  KEY `table_category_index_sort` (`sort`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='类型';

-- ----------------------------
-- Table structure for mark
-- ----------------------------
DROP TABLE IF EXISTS `mark`;
CREATE TABLE `mark` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `source_id` varchar(50) NOT NULL DEFAULT '' COMMENT '推介的ID',
  `source_type` varchar(40) NOT NULL DEFAULT '' COMMENT '区块代号',
  PRIMARY KEY (`id`),
  UNIQUE KEY `table_book_mark_source_type` (`source_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='推介表格';

-- ----------------------------
-- Table structure for news
-- ----------------------------
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `category_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `title` varchar(50) NOT NULL COMMENT '资讯名称',
  `cover` varchar(255) NOT NULL COMMENT '资讯封面',
  `content` text NOT NULL COMMENT '资讯内容',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '资讯简介',
  `check` tinyint(4) NOT NULL COMMENT '资讯状态',
  `update_time` int(11) NOT NULL COMMENT '修改时间戳',
  `create_time` int(11) NOT NULL COMMENT '创建时间戳',
  PRIMARY KEY (`id`),
  KEY `table_news_index_admin-id` (`admin_id`),
  KEY `table_news_index_category-id` (`category_id`),
  KEY `table_news_index_check` (`check`),
  FULLTEXT KEY `table_book_index_title` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='资讯表格';

-- ----------------------------
-- Table structure for place
-- ----------------------------
DROP TABLE IF EXISTS `place`;
CREATE TABLE `place` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(10) NOT NULL COMMENT '分销网站名称',
  `image` varchar(255) NOT NULL COMMENT 'LOGO',
  `url` varchar(255) NOT NULL COMMENT '分销网站的url',
  `apikey` varchar(255) NOT NULL DEFAULT '' COMMENT '渠道的唯一接口秘钥',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间戳',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间戳',
  PRIMARY KEY (`id`),
  UNIQUE KEY `table_place_unique_name` (`name`),
  UNIQUE KEY `table_place_unique_apikey` (`apikey`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分销网站';

-- ----------------------------
-- Table structure for place_relation
-- ----------------------------
DROP TABLE IF EXISTS `place_relation`;
CREATE TABLE `place_relation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `place_id` int(10) unsigned NOT NULL COMMENT '分销商ID',
  `book_id` int(10) unsigned NOT NULL COMMENT '书本ID',
  PRIMARY KEY (`id`),
  UNIQUE KEY `table_place-relation_unique_book-id_place-id` (`place_id`,`book_id`) USING BTREE,
  KEY `table_place-relation_index_place-id` (`place_id`),
  KEY `table_place-relation_index_book-id` (`book_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分销商索引';

-- ----------------------------
-- Table structure for tag
-- ----------------------------
DROP TABLE IF EXISTS `tag`;
CREATE TABLE `tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '标签主键',
  `name` varchar(10) NOT NULL COMMENT '标签',
  `create_time` int(11) NOT NULL COMMENT '创建时间戳',
  PRIMARY KEY (`id`),
  UNIQUE KEY `table_tag_unique_name` (`name`) USING BTREE,
  FULLTEXT KEY `table_tag_index_name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='标签库';

-- ----------------------------
-- Table structure for tag_relation
-- ----------------------------
DROP TABLE IF EXISTS `tag_relation`;
CREATE TABLE `tag_relation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag_id` int(10) unsigned DEFAULT NULL COMMENT '标签ID',
  `book_id` int(10) unsigned DEFAULT NULL COMMENT '书本ID',
  PRIMARY KEY (`id`),
  UNIQUE KEY `table_tag-relation_unique_book-id_tag-id` (`tag_id`,`book_id`) USING BTREE,
  KEY `table_tag-relation_index_tag-id` (`tag_id`),
  KEY `table_tag-relation_index_book-id` (`book_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='标签索引';

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户的主键',
  `admin_id` int(10) unsigned DEFAULT NULL COMMENT '制作人主键',
  `name` varchar(10) NOT NULL COMMENT '用户名称',
  `pen_name` varchar(20) NOT NULL COMMENT '用户笔名',
  `password` varchar(50) NOT NULL COMMENT '用户密码',
  `image` varchar(255) NOT NULL DEFAULT '' COMMENT '用户头像',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '用户邮箱',
  `tel` varchar(12) NOT NULL DEFAULT '' COMMENT '用户手机号',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '用户描述',
  `status` tinyint(4) NOT NULL COMMENT '用户的状态',
  `ip` varchar(16) NOT NULL COMMENT '用户最后登陆IP',
  `update_time` int(11) NOT NULL COMMENT '用户修改时间戳',
  `create_time` int(11) NOT NULL COMMENT '用户创建时间戳',
  PRIMARY KEY (`id`),
  UNIQUE KEY `table_user_unique_name` (`name`),
  UNIQUE KEY `table_user_unique_pen-name` (`pen_name`),
  KEY `table_user_unique_email` (`email`),
  KEY `table_user_unique_tel` (`tel`),
  KEY `table_user_index_status` (`status`),
  KEY `table_user_index_admin-id` (`admin_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户个人信息表';
SET FOREIGN_KEY_CHECKS=1;
