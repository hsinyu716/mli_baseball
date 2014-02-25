/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50516
Source Host           : localhost:3306
Source Database       : mli_baseball

Target Server Type    : MYSQL
Target Server Version : 50516
File Encoding         : 65001

Date: 2014-02-25 10:36:47
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `admin`
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `FBAPP_ID` varchar(32) NOT NULL,
  `FBAPP_SECRET` varchar(32) NOT NULL,
  `FBAPP_TITLE` varchar(32) NOT NULL,
  `FBAPP_TITLE_TC` varchar(32) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES ('1', '410408705649269', '2fe9b3354971580ef65a60ec333d0248', 'mli_baseball', '三商美邦 誰是你的強力後盾', '2014-01-17 14:14:52');

-- ----------------------------
-- Table structure for `share_record`
-- ----------------------------
DROP TABLE IF EXISTS `share_record`;
CREATE TABLE `share_record` (
  `serial_id` int(11) NOT NULL AUTO_INCREMENT,
  `fbid` varchar(32) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`serial_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of share_record
-- ----------------------------
INSERT INTO `share_record` VALUES ('1', '100000289183379', '2014-01-15 10:19:30');
INSERT INTO `share_record` VALUES ('2', '100000289183379', '2014-01-15 10:22:00');
INSERT INTO `share_record` VALUES ('3', '100000289183379', '2014-02-06 15:37:30');
INSERT INTO `share_record` VALUES ('4', '100000289183379', '2014-02-06 17:15:57');
INSERT INTO `share_record` VALUES ('5', '100000289183379', '2014-02-20 16:02:14');

-- ----------------------------
-- Table structure for `tag_record`
-- ----------------------------
DROP TABLE IF EXISTS `tag_record`;
CREATE TABLE `tag_record` (
  `serial_id` int(11) NOT NULL AUTO_INCREMENT,
  `fbid` varchar(32) NOT NULL,
  `tofbid` varchar(32) NOT NULL,
  `message` text CHARACTER SET utf8,
  PRIMARY KEY (`serial_id`)
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tag_record
-- ----------------------------
INSERT INTO `tag_record` VALUES ('115', '100000289183379', '622554760', '我來了');
INSERT INTO `tag_record` VALUES ('116', '100000289183379', '100005447947973', 'coming');
INSERT INTO `tag_record` VALUES ('117', '100000289183379', '100003949592576', 'coming');
INSERT INTO `tag_record` VALUES ('118', '100000289183379', '100001559739966', 'coming');
INSERT INTO `tag_record` VALUES ('119', '100000289183379', '1067188483', 'coming');
INSERT INTO `tag_record` VALUES ('120', '100000289183379', '100003332283799', 'coming');
INSERT INTO `tag_record` VALUES ('121', '100000289183379', '634547875', 'coming');
INSERT INTO `tag_record` VALUES ('122', '100000289183379', '100001011253143', 'coming');
INSERT INTO `tag_record` VALUES ('123', '100000289183379', '100000289183379', '123');

-- ----------------------------
-- Table structure for `user_info`
-- ----------------------------
DROP TABLE IF EXISTS `user_info`;
CREATE TABLE `user_info` (
  `serial_id` int(11) NOT NULL AUTO_INCREMENT,
  `fbid` varchar(32) NOT NULL,
  `username` varchar(64) CHARACTER SET utf8 DEFAULT NULL,
  `fbname` varchar(64) CHARACTER SET utf8 DEFAULT NULL,
  `tel` varchar(15) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `is_join` enum('Y','N') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`serial_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of user_info
-- ----------------------------
INSERT INTO `user_info` VALUES ('6', '100000289183379', 'admin', 'HsinyuMr Chen', '222222222', 'hiemerpcu@hotmail.com', 'N', '2014-02-20 16:02:00');
