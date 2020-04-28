/*
 Navicat Premium Data Transfer

 Source Server         : IMpBear
 Source Server Type    : MySQL
 Source Server Version : 80019
 Source Host           : localhost:3306
 Source Schema         : userDB

 Target Server Type    : MySQL
 Target Server Version : 80019
 File Encoding         : 65001

 Date: 28/04/2020 18:33:55
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for article
-- ----------------------------
DROP TABLE IF EXISTS `article`;
CREATE TABLE `article` (
  `articleid` int NOT NULL AUTO_INCREMENT COMMENT '文章id',
  `title` text NOT NULL COMMENT '文章标题',
  `content` text NOT NULL COMMENT '文章内容',
  `userid` int NOT NULL COMMENT '发表的用户id',
  `edit_time` varchar(100) NOT NULL COMMENT '发表的日期',
  PRIMARY KEY (`articleid`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of article
-- ----------------------------
BEGIN;
INSERT INTO `article` VALUES (1, '德玛西亚编辑了标题', '德玛西亚用丝长存', 10, '2020-04-24 14:50:21');
INSERT INTO `article` VALUES (4, '这是第一篇文章', '今天天气可真好哈', 10, '2020-04-24 14:53:26');
INSERT INTO `article` VALUES (5, '这是第一篇文章', '今天天气可真好哈', 10, '2020-04-24 14:55:51');
INSERT INTO `article` VALUES (6, '接口编辑的标题', '接口编辑的文章内容', 10, '2020-04-24 14:56:07');
INSERT INTO `article` VALUES (7, '接口添加的标题', '接口添加的文章内容', 10, '2020-04-27 10:51:04');
INSERT INTO `article` VALUES (8, '接口添加的标题', '接口添加的文章内容', 10, '2020-04-27 10:51:38');
INSERT INTO `article` VALUES (9, '接口编辑的标题', '接口编辑的文章内容', 12, '2020-04-27 10:51:58');
COMMIT;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `username` text NOT NULL COMMENT '用户名称',
  `userid` int NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `userpassword` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '用户密码',
  `register_date` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '注册日期',
  `user_mobile` int DEFAULT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of user
-- ----------------------------
BEGIN;
INSERT INTO `user` VALUES ('11111111', 10, '7ef407406353495e65c9b6aa6c28778a', '2020-04-27 15:16:48', NULL);
INSERT INTO `user` VALUES ('user91', 13, '7ef407406353495e65c9b6aa6c28778a', '2020-04-24 04:13:41', NULL);
INSERT INTO `user` VALUES ('user85', 14, '7ef407406353495e65c9b6aa6c28778a', '2020-04-24 04:14:03', NULL);
INSERT INTO `user` VALUES ('user54', 15, '7ef407406353495e65c9b6aa6c28778a', '2020-04-24 05:54:12', NULL);
INSERT INTO `user` VALUES ('user44', 16, '7ef407406353495e65c9b6aa6c28778a', '2020-04-24 05:54:24', NULL);
INSERT INTO `user` VALUES ('user60', 17, '7ef407406353495e65c9b6aa6c28778a', '2020-04-24 05:54:27', NULL);
INSERT INTO `user` VALUES ('user21', 18, '7ef407406353495e65c9b6aa6c28778a', '2020-04-24 05:56:33', NULL);
INSERT INTO `user` VALUES ('user55', 19, '7ef407406353495e65c9b6aa6c28778a', '2020-04-24 05:56:38', NULL);
INSERT INTO `user` VALUES ('user51', 20, '7ef407406353495e65c9b6aa6c28778a', '2020-04-24 05:59:11', NULL);
INSERT INTO `user` VALUES ('user38', 21, '7ef407406353495e65c9b6aa6c28778a', '2020-04-24 05:59:28', NULL);
INSERT INTO `user` VALUES ('user70', 22, '7ef407406353495e65c9b6aa6c28778a', '2020-04-24 06:00:47', NULL);
INSERT INTO `user` VALUES ('user18', 23, '7ef407406353495e65c9b6aa6c28778a', '2020-04-24 06:01:47', NULL);
INSERT INTO `user` VALUES ('user97', 24, '7ef407406353495e65c9b6aa6c28778a', '2020-04-24 06:03:01', NULL);
INSERT INTO `user` VALUES ('user84', 25, '7ef407406353495e65c9b6aa6c28778a', '2020-04-24 06:03:01', NULL);
INSERT INTO `user` VALUES ('user56', 26, '7ef407406353495e65c9b6aa6c28778a', '2020-04-24 14:06:34', NULL);
INSERT INTO `user` VALUES ('user94', 27, '7ef407406353495e65c9b6aa6c28778a', '2020-04-24 14:06:34', NULL);
INSERT INTO `user` VALUES ('user46', 28, '7ef407406353495e65c9b6aa6c28778a', '2020-04-24 14:06:34', NULL);
INSERT INTO `user` VALUES ('111111', 38, '370e6c113f8ee273e1ac60e21895308d', '2020-04-27 15:16:48', NULL);
INSERT INTO `user` VALUES ('用户1', 39, '370e6c113f8ee273e1ac60e21895308d', '2020-04-27 14:41:52', NULL);
INSERT INTO `user` VALUES ('user1', 40, '370e6c113f8ee273e1ac60e21895308d', '2020-04-27 15:16:55', NULL);
INSERT INTO `user` VALUES ('事件用户1', 41, '1234567889', '2020 04-28 15:19:20', NULL);
INSERT INTO `user` VALUES ('事件用户2', 42, '1234567889', '2020 04-28 15:19:20', NULL);
COMMIT;

-- ----------------------------
-- Table structure for userInfo
-- ----------------------------
DROP TABLE IF EXISTS `userInfo`;
CREATE TABLE `userInfo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `userName` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `userAddress` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of userInfo
-- ----------------------------
BEGIN;
INSERT INTO `userInfo` VALUES (1, '德玛西亚', '符文大陆', 'demaxiya@163.com');
INSERT INTO `userInfo` VALUES (2, '德玛西亚', '符文大陆', 'demaxiya@163.com');
INSERT INTO `userInfo` VALUES (3, '63德玛西亚', '符文大陆', 'demaxiya@163.com');
INSERT INTO `userInfo` VALUES (4, '54德玛西亚', '符文大陆', 'demaxiya@163.com');
INSERT INTO `userInfo` VALUES (5, '96德玛西亚', '符文大陆', 'demaxiya@163.com');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
