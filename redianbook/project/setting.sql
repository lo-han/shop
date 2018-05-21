/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : bqread

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-05-18 09:50:49
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for advert
-- ----------------------------
DROP TABLE IF EXISTS `advert`;
CREATE TABLE `advert` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '广告url',
  `image` varchar(255) NOT NULL DEFAULT '' COMMENT '广告图片',
  `big_image` varchar(255) NOT NULL DEFAULT '' COMMENT '作用于大图',
  `site` varchar(20) NOT NULL DEFAULT '' COMMENT '广告位置',
  PRIMARY KEY (`id`),
  UNIQUE KEY `table_advert_site` (`site`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='广告位';

-- ----------------------------
-- Table structure for book_mark
-- ----------------------------
DROP TABLE IF EXISTS `mark`;
CREATE TABLE `mark` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `source_id` varchar(50) NOT NULL DEFAULT '' COMMENT '推介的ID',
  `source_type` varchar(40) NOT NULL DEFAULT '' COMMENT '区块代号',
  PRIMARY KEY (`id`),
  UNIQUE KEY `table_book_mark_source_type` (`source_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='推介表格';
SET FOREIGN_KEY_CHECKS=1;
