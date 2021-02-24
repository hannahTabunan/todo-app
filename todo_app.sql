/*
 Navicat Premium Data Transfer

 Source Server         : local
 Source Server Type    : MySQL
 Source Server Version : 100411
 Source Host           : localhost:3306
 Source Schema         : todo_app

 Target Server Type    : MySQL
 Target Server Version : 100411
 File Encoding         : 65001

 Date: 24/02/2021 17:14:56
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tasks
-- ----------------------------
DROP TABLE IF EXISTS `tasks`;
CREATE TABLE `tasks`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `status` enum('P','IP','C','D') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'P',
  `date_created` datetime(0) NULL DEFAULT NULL,
  `date_updated` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tasks
-- ----------------------------
INSERT INTO `tasks` VALUES (5, 'Wash carrots', 'C', '2021-02-24 03:48:34', '2021-02-24 10:08:20');
INSERT INTO `tasks` VALUES (6, 'Peel carrots', 'P', '2021-02-24 03:48:55', '2021-02-24 10:08:45');
INSERT INTO `tasks` VALUES (7, 'Mince carrots', 'P', '2021-02-24 03:49:47', '2021-02-24 10:08:48');
INSERT INTO `tasks` VALUES (8, 'Saute carrots', 'C', '2021-02-24 07:33:40', '2021-02-24 08:51:17');
INSERT INTO `tasks` VALUES (9, 'Serve carrots', 'IP', '2021-02-24 07:33:54', '2021-02-24 10:08:30');
INSERT INTO `tasks` VALUES (10, 'Eat the carrots', 'IP', '2021-02-24 08:57:15', '2021-02-24 10:08:31');
INSERT INTO `tasks` VALUES (11, 'Digest the carrots', 'P', '2021-02-24 08:58:07', '2021-02-24 10:08:47');
INSERT INTO `tasks` VALUES (12, 'Read nutrition facts about carrots', 'C', '2021-02-24 09:14:33', '2021-02-24 10:03:56');

SET FOREIGN_KEY_CHECKS = 1;
