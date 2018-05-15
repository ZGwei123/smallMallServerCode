/*
 Navicat Premium Data Transfer

 Source Server         : MySQL
 Source Server Type    : MySQL
 Source Server Version : 100131
 Source Host           : localhost:3306
 Source Schema         : store

 Target Server Type    : MySQL
 Target Server Version : 100131
 File Encoding         : 65001

 Date: 15/05/2018 19:06:34
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for banner
-- ----------------------------
DROP TABLE IF EXISTS `banner`;
CREATE TABLE `banner`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'Banner名称，通常作为标识',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'Banner描述',
  `delete_time` int(11) NULL DEFAULT NULL,
  `update_time` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'banner管理表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of banner
-- ----------------------------
INSERT INTO `banner` VALUES (1, '首页置顶', '首页轮播图', NULL, NULL);

-- ----------------------------
-- Table structure for banner_item
-- ----------------------------
DROP TABLE IF EXISTS `banner_item`;
CREATE TABLE `banner_item`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `img_id` int(11) NOT NULL COMMENT '外键，关联image表',
  `key_word` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '执行关键字，根据不同的type含义不同',
  `type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '跳转类型，可能导向商品，可能导向专题，可能导向其他。0，无导向；1：导向商品;2:导向专题',
  `delete_time` int(11) NULL DEFAULT NULL,
  `banner_id` int(11) NOT NULL COMMENT '外键，关联banner表',
  `update_time` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'banner子项表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of banner_item
-- ----------------------------
INSERT INTO `banner_item` VALUES (1, 155, '37', 1, NULL, 1, NULL);
INSERT INTO `banner_item` VALUES (2, 153, '46', 1, NULL, 1, NULL);
INSERT INTO `banner_item` VALUES (3, 112, '52', 1, NULL, 1, NULL);
INSERT INTO `banner_item` VALUES (5, 154, '40', 1, NULL, 1, NULL);

-- ----------------------------
-- Table structure for category
-- ----------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '分类名称',
  `topic_img_id` int(11) NULL DEFAULT NULL COMMENT '外键，关联image表',
  `delete_time` int(11) NULL DEFAULT NULL,
  `description` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '描述',
  `update_time` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '商品类目' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of category
-- ----------------------------
INSERT INTO `category` VALUES (1, '坚果', 112, NULL, NULL, NULL);
INSERT INTO `category` VALUES (2, '零食', 153, NULL, NULL, NULL);
INSERT INTO `category` VALUES (3, '甜点', 154, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for image
-- ----------------------------
DROP TABLE IF EXISTS `image`;
CREATE TABLE `image`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '图片路径',
  `from` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1 来自本地，2 来自公网',
  `delete_time` int(11) NULL DEFAULT NULL,
  `update_time` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 159 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '图片总表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of image
-- ----------------------------
INSERT INTO `image` VALUES (70, '/product-jianguo@1(1).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (71, '/product-jianguo@1(2).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (72, '/product-jianguo@1(3).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (73, '/product-jianguo@1(4).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (74, '/product-jianguo@1(5).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (75, '/product-jianguo@1(6).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (76, '/product-jianguo@2 (1).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (77, '/product-jianguo@2 (2).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (78, '/product-jianguo@2 (3).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (79, '/product-jianguo@2 (4).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (80, '/product-jianguo@2 (5).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (81, '/product-jianguo@2 (6).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (82, '/product-jianguo@3(1).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (83, '/product-jianguo@3(2).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (84, '/product-jianguo@3(3).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (85, '/product-jianguo@3(4).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (86, '/product-jianguo@4 (1).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (87, '/product-jianguo@4 (2).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (88, '/product-jianguo@4 (3).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (89, '/product-jianguo@4 (4).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (90, '/product-jianguo@5 (1).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (91, '/product-jianguo@5 (2).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (92, '/product-jianguo@5 (3).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (93, '/product-jianguo@5 (4).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (94, '/product-jianguo@6 (1).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (95, '/product-jianguo@6 (2).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (96, '/product-jianguo@6 (3).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (97, '/product-jianguo@6 (4).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (98, '/product-jianguo@6 (5).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (100, '/product-jianguo@7 (1).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (101, '/product-jianguo@7 (2).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (102, '/product-jianguo@7 (3).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (103, '/product-jianguo@7 (4).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (104, '/product-jianguo@7 (5).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (105, '/product-jianguo@8 (1).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (106, '/product-jianguo@8 (2).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (107, '/product-jianguo@8 (3).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (108, '/product-jianguo@8 (4).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (109, '/product-jianguo@8 (5).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (110, '/product-jianguo@8 (6).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (111, '/product-jianguo@8 (7).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (112, '/product-jianguo.jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (113, '/product-lingshi@1 (1).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (114, '/product-lingshi@1 (2).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (115, '/product-lingshi@1 (3).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (116, '/product-lingshi@1 (4).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (117, '/product-lingshi@2 (1).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (118, '/product-lingshi@2 (2).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (119, '/product-lingshi@2 (3).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (120, '/product-lingshi@2 (4).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (121, '/product-lingshi@3 (1).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (122, '/product-lingshi@3 (2).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (123, '/product-lingshi@3 (3).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (124, '/product-lingshi@3 (4).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (125, '/product-lingshi@3 (5).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (126, '/product-lingshi@3 (6).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (127, '/product-lingshi@4 (1).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (128, '/product-lingshi@4 (2).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (129, '/product-lingshi@4 (3).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (130, '/product-lingshi@4 (4).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (131, '/product-lingshi@5 (1).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (132, '/product-lingshi@5 (2).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (133, '/product-lingshi@5 (3).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (134, '/product-lingshi@5 (4).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (135, '/product-lingshi@6 (1).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (136, '/product-lingshi@6 (2).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (137, '/product-lingshi@6 (3).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (138, '/product-lingshi@6 (4).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (139, '/product-lingshi@6 (5).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (140, '/product-lingshi@6 (6).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (141, '/product-lingshi@7 (1).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (142, '/product-lingshi@7 (2).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (143, '/product-lingshi@7 (3).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (144, '/product-lingshi@7 (4).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (145, '/product-lingshi@7 (5).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (146, '/product-lingshi@7 (6).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (147, '/product-lingshi@8 (1).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (148, '/product-lingshi@8 (2).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (149, '/product-lingshi@8 (3).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (150, '/product-lingshi@8 (4).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (151, '/product-lingshi@8 (5).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (152, '/product-lingshi@8 (6).jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (153, '/product-lingshi.jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (154, '/product-tiandian.jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (155, '/banner.jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (156, '/theme1.jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (157, '/theme2.jpg', 1, NULL, NULL);
INSERT INTO `image` VALUES (158, '/theme3.jpg', 1, NULL, NULL);

-- ----------------------------
-- Table structure for order
-- ----------------------------
DROP TABLE IF EXISTS `order`;
CREATE TABLE `order`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_no` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '订单号',
  `user_id` int(11) NOT NULL COMMENT '外键，用户id，注意并不是openid',
  `delete_time` int(11) NULL DEFAULT NULL,
  `create_time` int(11) NULL DEFAULT NULL,
  `total_price` decimal(6, 2) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1:未支付， 2：已支付，3：已发货 , 4: 已支付，但库存不足',
  `snap_img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '订单快照图片',
  `snap_name` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '订单快照名称',
  `total_count` int(11) NOT NULL DEFAULT 0,
  `update_time` int(11) NULL DEFAULT NULL,
  `snap_items` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '订单其他信息快照（json)',
  `snap_address` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '地址快照',
  `prepay_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '订单微信支付的预订单id（用于发送模板消息）',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `order_no`(`order_no`) USING BTREE,
  INDEX `user_id`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 78 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of order
-- ----------------------------

-- ----------------------------
-- Table structure for order_product
-- ----------------------------
DROP TABLE IF EXISTS `order_product`;
CREATE TABLE `order_product`  (
  `order_id` int(11) NOT NULL COMMENT '联合主键，订单id',
  `product_id` int(11) NOT NULL COMMENT '联合主键，商品id',
  `count` int(11) NOT NULL COMMENT '商品数量',
  `delete_time` int(11) NULL DEFAULT NULL,
  `update_time` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`product_id`, `order_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of order_product
-- ----------------------------

-- ----------------------------
-- Table structure for product
-- ----------------------------
DROP TABLE IF EXISTS `product`;
CREATE TABLE `product`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '商品名称',
  `price` decimal(6, 2) NOT NULL COMMENT '价格,单位：分',
  `stock` int(11) NOT NULL DEFAULT 0 COMMENT '库存量',
  `delete_time` int(11) NULL DEFAULT NULL,
  `category_id` int(11) NULL DEFAULT NULL,
  `main_img_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '主图ID号，这是一个反范式设计，有一定的冗余',
  `from` tinyint(4) NOT NULL DEFAULT 1 COMMENT '图片来自 1 本地 ，2公网',
  `create_time` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) NULL DEFAULT NULL,
  `summary` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '摘要',
  `img_id` int(11) NULL DEFAULT NULL COMMENT '图片外键',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 53 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of product
-- ----------------------------
INSERT INTO `product` VALUES (35, '香炒南瓜子 260g', 1.00, 500, NULL, 1, '/product-jianguo@1(1).jpg', 1, 1525278391, NULL, NULL, 70);
INSERT INTO `product` VALUES (36, '卤味铁蛋鹌鹑蛋 250g', 1.00, 500, NULL, 1, '/product-jianguo@2 (1).jpg', 1, 1525278392, NULL, NULL, 76);
INSERT INTO `product` VALUES (37, '什锦果仁 50g', 1.00, 500, NULL, 1, '/product-jianguo@3(1).jpg', 1, 1525278393, NULL, NULL, 82);
INSERT INTO `product` VALUES (38, '牛肉味兰花豆 2包', 1.00, 500, NULL, 1, '/product-jianguo@4 (1).jpg', 1, 1525278394, NULL, NULL, 86);
INSERT INTO `product` VALUES (39, '夏威夷果 100g', 1.00, 500, NULL, 1, '/product-jianguo@5 (1).jpg', 1, 1525278395, NULL, NULL, 90);
INSERT INTO `product` VALUES (40, '枣泥核桃仁 93g', 1.00, 500, NULL, 1, '/product-jianguo@6 (1).jpg', 1, 1525278396, NULL, NULL, 94);
INSERT INTO `product` VALUES (42, '蟹香豆瓣 100g', 1.00, 500, NULL, 1, '/product-jianguo@7 (1).jpg', 1, 1525278397, NULL, NULL, 100);
INSERT INTO `product` VALUES (43, '紫薯花生 250g', 1.00, 500, NULL, 1, '/product-jianguo@8 (1).jpg', 1, 1525278398, NULL, NULL, 105);
INSERT INTO `product` VALUES (44, '果丹皮 250g', 1.00, 500, NULL, 2, '/product-lingshi@1 (1).jpg', 1, 1525278400, NULL, NULL, 113);
INSERT INTO `product` VALUES (46, '碳烤迷你肠 200g', 1.00, 500, NULL, 2, '/product-lingshi@2 (1).jpg', 1, 1525278401, NULL, NULL, 117);
INSERT INTO `product` VALUES (47, '空心山楂 250g', 1.00, 500, NULL, 2, '/product-lingshi@3 (1).jpg', 1, 1525278402, NULL, NULL, 121);
INSERT INTO `product` VALUES (48, '甜辣鸭锁骨 250g', 1.00, 500, NULL, 2, '/product-lingshi@4 (1).jpg', 1, 1525278403, NULL, NULL, 127);
INSERT INTO `product` VALUES (49, '鸡蛋煎饼 100g', 1.00, 500, NULL, 2, '/product-lingshi@5 (1).jpg', 1, 1525278404, NULL, NULL, 131);
INSERT INTO `product` VALUES (50, '豆腐卷 175g', 1.00, 500, NULL, 3, '/product-lingshi@6 (1).jpg', 1, 1525278405, NULL, NULL, 135);
INSERT INTO `product` VALUES (51, '芝麻脆饼 260g', 1.00, 500, NULL, 3, '/product-lingshi@7 (1).jpg', 1, 1525278406, NULL, NULL, 141);
INSERT INTO `product` VALUES (52, '榴莲干 30g', 1.00, 500, NULL, 3, '/product-lingshi@8 (1).jpg', 1, 1525278407, NULL, NULL, 147);

-- ----------------------------
-- Table structure for product_image
-- ----------------------------
DROP TABLE IF EXISTS `product_image`;
CREATE TABLE `product_image`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `img_id` int(11) NOT NULL COMMENT '外键，关联图片表',
  `delete_time` int(11) NULL DEFAULT NULL COMMENT '状态，主要表示是否删除，也可以扩展其他状态',
  `order` int(11) NOT NULL DEFAULT 0 COMMENT '图片排序序号',
  `product_id` int(11) NOT NULL COMMENT '商品id，外键',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 102 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of product_image
-- ----------------------------
INSERT INTO `product_image` VALUES (4, 19, NULL, 1, 11);
INSERT INTO `product_image` VALUES (5, 20, NULL, 2, 11);
INSERT INTO `product_image` VALUES (6, 21, NULL, 3, 11);
INSERT INTO `product_image` VALUES (7, 22, NULL, 4, 11);
INSERT INTO `product_image` VALUES (8, 23, NULL, 5, 11);
INSERT INTO `product_image` VALUES (9, 24, NULL, 6, 11);
INSERT INTO `product_image` VALUES (10, 25, NULL, 7, 11);
INSERT INTO `product_image` VALUES (11, 26, NULL, 8, 11);
INSERT INTO `product_image` VALUES (12, 27, NULL, 9, 11);
INSERT INTO `product_image` VALUES (13, 28, NULL, 11, 11);
INSERT INTO `product_image` VALUES (14, 29, NULL, 10, 11);
INSERT INTO `product_image` VALUES (18, 62, NULL, 12, 11);
INSERT INTO `product_image` VALUES (19, 63, NULL, 13, 11);
INSERT INTO `product_image` VALUES (20, 70, NULL, 1, 35);
INSERT INTO `product_image` VALUES (21, 71, NULL, 2, 35);
INSERT INTO `product_image` VALUES (22, 72, NULL, 3, 35);
INSERT INTO `product_image` VALUES (23, 73, NULL, 4, 35);
INSERT INTO `product_image` VALUES (24, 74, NULL, 5, 35);
INSERT INTO `product_image` VALUES (25, 75, NULL, 6, 35);
INSERT INTO `product_image` VALUES (26, 76, NULL, 1, 36);
INSERT INTO `product_image` VALUES (27, 77, NULL, 2, 36);
INSERT INTO `product_image` VALUES (28, 78, NULL, 3, 36);
INSERT INTO `product_image` VALUES (29, 79, NULL, 4, 36);
INSERT INTO `product_image` VALUES (30, 80, NULL, 5, 36);
INSERT INTO `product_image` VALUES (31, 81, NULL, 6, 36);
INSERT INTO `product_image` VALUES (32, 82, NULL, 1, 37);
INSERT INTO `product_image` VALUES (33, 83, NULL, 2, 37);
INSERT INTO `product_image` VALUES (34, 84, NULL, 3, 37);
INSERT INTO `product_image` VALUES (35, 85, NULL, 4, 37);
INSERT INTO `product_image` VALUES (36, 86, NULL, 1, 38);
INSERT INTO `product_image` VALUES (37, 87, NULL, 2, 38);
INSERT INTO `product_image` VALUES (38, 88, NULL, 3, 38);
INSERT INTO `product_image` VALUES (39, 89, NULL, 4, 38);
INSERT INTO `product_image` VALUES (40, 90, NULL, 1, 39);
INSERT INTO `product_image` VALUES (41, 91, NULL, 2, 39);
INSERT INTO `product_image` VALUES (42, 92, NULL, 3, 39);
INSERT INTO `product_image` VALUES (43, 93, NULL, 4, 39);
INSERT INTO `product_image` VALUES (44, 94, NULL, 1, 40);
INSERT INTO `product_image` VALUES (45, 95, NULL, 2, 40);
INSERT INTO `product_image` VALUES (46, 96, NULL, 3, 40);
INSERT INTO `product_image` VALUES (47, 97, NULL, 4, 40);
INSERT INTO `product_image` VALUES (48, 98, NULL, 5, 40);
INSERT INTO `product_image` VALUES (49, 99, NULL, 0, 0);
INSERT INTO `product_image` VALUES (50, 100, NULL, 1, 42);
INSERT INTO `product_image` VALUES (51, 101, NULL, 2, 42);
INSERT INTO `product_image` VALUES (52, 102, NULL, 3, 42);
INSERT INTO `product_image` VALUES (53, 103, NULL, 4, 42);
INSERT INTO `product_image` VALUES (54, 104, NULL, 5, 42);
INSERT INTO `product_image` VALUES (55, 105, NULL, 1, 43);
INSERT INTO `product_image` VALUES (56, 106, NULL, 2, 43);
INSERT INTO `product_image` VALUES (57, 107, NULL, 3, 43);
INSERT INTO `product_image` VALUES (58, 108, NULL, 4, 43);
INSERT INTO `product_image` VALUES (59, 109, NULL, 5, 43);
INSERT INTO `product_image` VALUES (60, 110, NULL, 6, 43);
INSERT INTO `product_image` VALUES (61, 111, NULL, 7, 43);
INSERT INTO `product_image` VALUES (62, 113, NULL, 1, 44);
INSERT INTO `product_image` VALUES (63, 114, NULL, 2, 44);
INSERT INTO `product_image` VALUES (64, 115, NULL, 3, 44);
INSERT INTO `product_image` VALUES (65, 116, NULL, 4, 44);
INSERT INTO `product_image` VALUES (66, 117, NULL, 1, 46);
INSERT INTO `product_image` VALUES (67, 118, NULL, 2, 46);
INSERT INTO `product_image` VALUES (68, 119, NULL, 3, 46);
INSERT INTO `product_image` VALUES (69, 120, NULL, 4, 46);
INSERT INTO `product_image` VALUES (70, 121, NULL, 1, 47);
INSERT INTO `product_image` VALUES (71, 122, NULL, 2, 47);
INSERT INTO `product_image` VALUES (72, 123, NULL, 3, 47);
INSERT INTO `product_image` VALUES (73, 124, NULL, 4, 47);
INSERT INTO `product_image` VALUES (74, 125, NULL, 5, 47);
INSERT INTO `product_image` VALUES (75, 126, NULL, 6, 47);
INSERT INTO `product_image` VALUES (76, 127, NULL, 1, 48);
INSERT INTO `product_image` VALUES (77, 128, NULL, 2, 48);
INSERT INTO `product_image` VALUES (78, 129, NULL, 3, 48);
INSERT INTO `product_image` VALUES (79, 130, NULL, 4, 48);
INSERT INTO `product_image` VALUES (80, 131, NULL, 1, 49);
INSERT INTO `product_image` VALUES (81, 132, NULL, 2, 49);
INSERT INTO `product_image` VALUES (82, 133, NULL, 3, 49);
INSERT INTO `product_image` VALUES (83, 134, NULL, 4, 49);
INSERT INTO `product_image` VALUES (84, 135, NULL, 1, 50);
INSERT INTO `product_image` VALUES (85, 136, NULL, 2, 50);
INSERT INTO `product_image` VALUES (86, 137, NULL, 3, 50);
INSERT INTO `product_image` VALUES (87, 138, NULL, 4, 50);
INSERT INTO `product_image` VALUES (88, 139, NULL, 5, 50);
INSERT INTO `product_image` VALUES (89, 140, NULL, 6, 50);
INSERT INTO `product_image` VALUES (90, 141, NULL, 1, 51);
INSERT INTO `product_image` VALUES (91, 142, NULL, 2, 51);
INSERT INTO `product_image` VALUES (92, 143, NULL, 3, 51);
INSERT INTO `product_image` VALUES (93, 144, NULL, 4, 51);
INSERT INTO `product_image` VALUES (94, 145, NULL, 5, 51);
INSERT INTO `product_image` VALUES (95, 146, NULL, 6, 51);
INSERT INTO `product_image` VALUES (96, 147, NULL, 1, 52);
INSERT INTO `product_image` VALUES (97, 148, NULL, 2, 52);
INSERT INTO `product_image` VALUES (98, 149, NULL, 3, 52);
INSERT INTO `product_image` VALUES (99, 150, NULL, 4, 52);
INSERT INTO `product_image` VALUES (100, 151, NULL, 5, 52);
INSERT INTO `product_image` VALUES (101, 152, NULL, 6, 52);

-- ----------------------------
-- Table structure for product_property
-- ----------------------------
DROP TABLE IF EXISTS `product_property`;
CREATE TABLE `product_property`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '详情属性名称',
  `detail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '详情属性',
  `product_id` int(11) NOT NULL COMMENT '商品id，外键',
  `delete_time` int(11) NULL DEFAULT NULL,
  `update_time` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 74 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of product_property
-- ----------------------------
INSERT INTO `product_property` VALUES (10, '品名', '香炒南瓜子', 35, NULL, NULL);
INSERT INTO `product_property` VALUES (11, '产地', '地球', 35, NULL, NULL);
INSERT INTO `product_property` VALUES (12, '净含量', '100g', 35, NULL, NULL);
INSERT INTO `product_property` VALUES (13, '保质期', '360天', 35, NULL, NULL);
INSERT INTO `product_property` VALUES (14, '品名', '卤味铁蛋鹌鹑蛋', 36, NULL, NULL);
INSERT INTO `product_property` VALUES (15, '产地', '地球', 36, NULL, NULL);
INSERT INTO `product_property` VALUES (16, '净含量', '100g', 36, NULL, NULL);
INSERT INTO `product_property` VALUES (17, '保质期', '360天', 36, NULL, NULL);
INSERT INTO `product_property` VALUES (18, '品名', '什锦果仁', 37, NULL, NULL);
INSERT INTO `product_property` VALUES (19, '产地', '地球', 37, NULL, NULL);
INSERT INTO `product_property` VALUES (20, '净含量', '100g', 37, NULL, NULL);
INSERT INTO `product_property` VALUES (21, '保质期', '360天', 37, NULL, NULL);
INSERT INTO `product_property` VALUES (22, '品名', '牛肉味兰花豆', 38, NULL, NULL);
INSERT INTO `product_property` VALUES (23, '产地', '地球', 38, NULL, NULL);
INSERT INTO `product_property` VALUES (24, '净含量', '100g', 38, NULL, NULL);
INSERT INTO `product_property` VALUES (25, '保质期', '360天', 38, NULL, NULL);
INSERT INTO `product_property` VALUES (26, '品名', '夏威夷果', 39, NULL, NULL);
INSERT INTO `product_property` VALUES (27, '产地', '地球', 39, NULL, NULL);
INSERT INTO `product_property` VALUES (28, '净含量', '100g', 39, NULL, NULL);
INSERT INTO `product_property` VALUES (29, '保质期', '360天', 39, NULL, NULL);
INSERT INTO `product_property` VALUES (30, '品名', '枣泥核桃仁', 40, NULL, NULL);
INSERT INTO `product_property` VALUES (31, '产地', '地球', 40, NULL, NULL);
INSERT INTO `product_property` VALUES (32, '净含量', '100g', 40, NULL, NULL);
INSERT INTO `product_property` VALUES (33, '保质期', '360天', 40, NULL, NULL);
INSERT INTO `product_property` VALUES (34, '品名', '蟹香豆瓣', 42, NULL, NULL);
INSERT INTO `product_property` VALUES (35, '产地', '地球', 42, NULL, NULL);
INSERT INTO `product_property` VALUES (36, '净含量', '100g', 42, NULL, NULL);
INSERT INTO `product_property` VALUES (37, '保质期', '360天', 42, NULL, NULL);
INSERT INTO `product_property` VALUES (38, '品名', '紫薯花生', 43, NULL, NULL);
INSERT INTO `product_property` VALUES (39, '产地', '地球', 43, NULL, NULL);
INSERT INTO `product_property` VALUES (40, '净含量', '100g', 43, NULL, NULL);
INSERT INTO `product_property` VALUES (41, '保质期', '360天', 43, NULL, NULL);
INSERT INTO `product_property` VALUES (42, '品名', '果丹皮', 44, NULL, NULL);
INSERT INTO `product_property` VALUES (43, '产地', '地球', 44, NULL, NULL);
INSERT INTO `product_property` VALUES (44, '净含量', '100g', 44, NULL, NULL);
INSERT INTO `product_property` VALUES (45, '保质期', '360天', 44, NULL, NULL);
INSERT INTO `product_property` VALUES (46, '品名', '碳烤迷你肠', 46, NULL, NULL);
INSERT INTO `product_property` VALUES (47, '产地', '地球', 46, NULL, NULL);
INSERT INTO `product_property` VALUES (48, '净含量', '100g', 46, NULL, NULL);
INSERT INTO `product_property` VALUES (49, '保质期', '360天', 46, NULL, NULL);
INSERT INTO `product_property` VALUES (50, '品名', '空心山楂', 47, NULL, NULL);
INSERT INTO `product_property` VALUES (51, '产地', '地球', 47, NULL, NULL);
INSERT INTO `product_property` VALUES (52, '净含量', '100g', 47, NULL, NULL);
INSERT INTO `product_property` VALUES (53, '保质期', '360天', 47, NULL, NULL);
INSERT INTO `product_property` VALUES (54, '品名', '甜辣鸭锁骨', 48, NULL, NULL);
INSERT INTO `product_property` VALUES (55, '产地', '地球', 48, NULL, NULL);
INSERT INTO `product_property` VALUES (56, '净含量', '100g', 48, NULL, NULL);
INSERT INTO `product_property` VALUES (57, '保质期', '360天', 48, NULL, NULL);
INSERT INTO `product_property` VALUES (58, '品名', '鸡蛋煎饼', 49, NULL, NULL);
INSERT INTO `product_property` VALUES (59, '产地', '地球', 49, NULL, NULL);
INSERT INTO `product_property` VALUES (60, '净含量', '100g', 49, NULL, NULL);
INSERT INTO `product_property` VALUES (61, '保质期', '360天', 49, NULL, NULL);
INSERT INTO `product_property` VALUES (62, '品名', '豆腐卷', 50, NULL, NULL);
INSERT INTO `product_property` VALUES (63, '产地', '地球', 50, NULL, NULL);
INSERT INTO `product_property` VALUES (64, '净含量', '100g', 50, NULL, NULL);
INSERT INTO `product_property` VALUES (65, '保质期', '360天', 50, NULL, NULL);
INSERT INTO `product_property` VALUES (66, '品名', '芝麻脆饼', 51, NULL, NULL);
INSERT INTO `product_property` VALUES (67, '产地', '地球', 51, NULL, NULL);
INSERT INTO `product_property` VALUES (68, '净含量', '100g', 51, NULL, NULL);
INSERT INTO `product_property` VALUES (69, '保质期', '360天', 51, NULL, NULL);
INSERT INTO `product_property` VALUES (70, '品名', '榴莲干', 52, NULL, NULL);
INSERT INTO `product_property` VALUES (71, '产地', '地球', 52, NULL, NULL);
INSERT INTO `product_property` VALUES (72, '净含量', '100g', 52, NULL, NULL);
INSERT INTO `product_property` VALUES (73, '保质期', '360天', 52, NULL, NULL);

-- ----------------------------
-- Table structure for theme
-- ----------------------------
DROP TABLE IF EXISTS `theme`;
CREATE TABLE `theme`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '专题名称',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '专题描述',
  `topic_img_id` int(11) NOT NULL COMMENT '主题图，外键',
  `delete_time` int(11) NULL DEFAULT NULL,
  `head_img_id` int(11) NOT NULL COMMENT '专题列表页，头图',
  `update_time` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '主题信息表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of theme
-- ----------------------------
INSERT INTO `theme` VALUES (1, '专题栏位一', '', 156, NULL, 112, NULL);
INSERT INTO `theme` VALUES (2, '专题栏位二', '', 157, NULL, 153, NULL);
INSERT INTO `theme` VALUES (3, '专题栏位三', '', 158, NULL, 158, NULL);

-- ----------------------------
-- Table structure for theme_product
-- ----------------------------
DROP TABLE IF EXISTS `theme_product`;
CREATE TABLE `theme_product`  (
  `theme_id` int(11) NOT NULL COMMENT '主题外键',
  `product_id` int(11) NOT NULL COMMENT '商品外键',
  PRIMARY KEY (`theme_id`, `product_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '主题所包含的商品' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of theme_product
-- ----------------------------
INSERT INTO `theme_product` VALUES (1, 37);
INSERT INTO `theme_product` VALUES (1, 38);
INSERT INTO `theme_product` VALUES (1, 39);
INSERT INTO `theme_product` VALUES (1, 40);
INSERT INTO `theme_product` VALUES (1, 43);
INSERT INTO `theme_product` VALUES (2, 40);
INSERT INTO `theme_product` VALUES (2, 43);
INSERT INTO `theme_product` VALUES (2, 44);
INSERT INTO `theme_product` VALUES (2, 46);
INSERT INTO `theme_product` VALUES (2, 50);
INSERT INTO `theme_product` VALUES (2, 51);
INSERT INTO `theme_product` VALUES (2, 52);
INSERT INTO `theme_product` VALUES (3, 35);
INSERT INTO `theme_product` VALUES (3, 38);
INSERT INTO `theme_product` VALUES (3, 43);
INSERT INTO `theme_product` VALUES (3, 47);
INSERT INTO `theme_product` VALUES (3, 48);
INSERT INTO `theme_product` VALUES (3, 51);
INSERT INTO `theme_product` VALUES (3, 52);

-- ----------------------------
-- Table structure for third_app
-- ----------------------------
DROP TABLE IF EXISTS `third_app`;
CREATE TABLE `third_app`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '应用app_id',
  `app_secret` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '应用secret',
  `app_description` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '应用程序描述',
  `scope` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '应用权限',
  `scope_description` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '权限描述',
  `delete_time` int(11) NULL DEFAULT NULL,
  `update_time` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '访问API的各应用账号密码表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of third_app
-- ----------------------------
INSERT INTO `third_app` VALUES (1, '123', '123', 'CMS', '32', 'Super', NULL, NULL);

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `extend` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `delete_time` int(11) NULL DEFAULT NULL,
  `create_time` int(11) NULL DEFAULT NULL COMMENT '注册时间',
  `update_time` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `openid`(`openid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of user
-- ----------------------------

-- ----------------------------
-- Table structure for user_address
-- ----------------------------
DROP TABLE IF EXISTS `user_address`;
CREATE TABLE `user_address`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '收获人姓名',
  `mobile` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '手机号',
  `province` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '省',
  `city` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '市',
  `country` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '区',
  `detail` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '详细地址',
  `delete_time` int(11) NULL DEFAULT NULL,
  `user_id` int(11) NOT NULL COMMENT '外键',
  `update_time` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `user_id`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of user_address
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
