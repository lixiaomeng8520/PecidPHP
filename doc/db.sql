/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.55
Source Server Version : 50714
Source Host           : 192.168.1.55:3306
Source Database       : vote

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2016-09-14 16:34:15
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for dh_act
-- ----------------------------
DROP TABLE IF EXISTS `dh_act`;
CREATE TABLE `dh_act` (
  `aid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '活动ID',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '活动名',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态;1,正常投票;2,暂停;3,结束',
  `vote_start` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '投票开始时间',
  `vote_end` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '投票结束时间',
  `vote_interval` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '投票间隔时间',
  `desc` varchar(2000) NOT NULL DEFAULT '' COMMENT '描述',
  `banner` varchar(255) NOT NULL DEFAULT '' COMMENT '顶部图片',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`aid`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dh_admin
-- ----------------------------
DROP TABLE IF EXISTS `dh_admin`;
CREATE TABLE `dh_admin` (
  `adminid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `adminname` varchar(20) NOT NULL DEFAULT '' COMMENT '登录名',
  `password` varchar(50) NOT NULL DEFAULT '' COMMENT '密码',
  PRIMARY KEY (`adminid`),
  UNIQUE KEY `adminname` (`adminname`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dh_log
-- ----------------------------
DROP TABLE IF EXISTS `dh_log`;
CREATE TABLE `dh_log` (
  `lid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `adminid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `adminname` varchar(20) NOT NULL DEFAULT '' COMMENT '登录名',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1,登录;2,添加管理员;3,编辑管理员;4,添加活动;5,编辑活动;6,添加选手;7,编辑选手;',
  `url` varchar(100) NOT NULL DEFAULT '' COMMENT '访问地址',
  `data` varchar(1000) NOT NULL DEFAULT '' COMMENT '序列化数据',
  `ip` varchar(15) NOT NULL DEFAULT '' COMMENT '操作IP',
  `time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '日志时间',
  PRIMARY KEY (`lid`)
) ENGINE=MyISAM AUTO_INCREMENT=115 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dh_player
-- ----------------------------
DROP TABLE IF EXISTS `dh_player`;
CREATE TABLE `dh_player` (
  `pid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '选手ID',
  `aid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '活动ID',
  `number` int(10) unsigned NOT NULL DEFAULT '1',
  `mobile` char(11) NOT NULL DEFAULT '' COMMENT '手机号',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '名字',
  `num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '投票数量',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1,正常;2,冻结',
  `cover` varchar(100) NOT NULL DEFAULT '' COMMENT '选手封面',
  `gallery` varchar(2000) NOT NULL DEFAULT '' COMMENT '选手相册',
  `desc` text NOT NULL COMMENT '描述',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`pid`),
  UNIQUE KEY `aid,number` (`aid`,`number`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1678606 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dh_user
-- ----------------------------
DROP TABLE IF EXISTS `dh_user`;
CREATE TABLE `dh_user` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `openid` varchar(50) CHARACTER SET latin1 NOT NULL DEFAULT '' COMMENT '微信OPENID',
  `nickname` varchar(50) CHARACTER SET latin1 NOT NULL DEFAULT '' COMMENT '昵称',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0未知;1男;2女;',
  `language` varchar(50) CHARACTER SET latin1 NOT NULL DEFAULT '' COMMENT '语言',
  `city` varchar(50) CHARACTER SET latin1 NOT NULL DEFAULT '' COMMENT '城市',
  `province` varchar(50) CHARACTER SET latin1 NOT NULL DEFAULT '' COMMENT '省份',
  `country` varchar(50) CHARACTER SET latin1 NOT NULL DEFAULT '' COMMENT '国家',
  `headimgurl` varchar(200) CHARACTER SET latin1 NOT NULL DEFAULT '' COMMENT '头像',
  `privilege` varchar(1000) CHARACTER SET latin1 NOT NULL DEFAULT '' COMMENT '特权',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `openid` (`openid`)
) ENGINE=MyISAM AUTO_INCREMENT=1123237 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dh_vote
-- ----------------------------
DROP TABLE IF EXISTS `dh_vote`;
CREATE TABLE `dh_vote` (
  `vid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '投票ID',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT '微信OPENID',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '选手ID',
  `aid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '活动ID，这里做个冗余',
  `time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '投票时间',
  PRIMARY KEY (`vid`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
SET FOREIGN_KEY_CHECKS=1;
