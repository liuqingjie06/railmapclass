# Sequel Pro dump
# Version 2210
# http://code.google.com/p/sequel-pro
#
# Host: 127.0.0.1 (MySQL 5.1.48)
# Database: thinksns
# Generation Time: 2010-12-13 14:54:18 +0800
# ************************************************************

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

#  建立地图底图数据表
# Dump of table ts_map_Basemapdata
# ------------------------------------------------------------;
DROP TABLE IF EXISTS  `ts_map_Basemapdata` ;
CREATE TABLE  `ts_map_Basemapdata`  (
   `id` int(11)  unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(32) NOT NULL,
    `note` text NOT NULL,
    `filesize` int(10) NOT NULL DEFAULT '0',  
    `filetype` varchar(10) NOT NULL,
    `fileurl` varchar(255) NOT NULL,
    `ctime` int(11) NOT NULL,
    `mtime` varchar(11) NOT NULL DEFAULT '0',
    `status` tinyint(1) NOT NULL DEFAULT '0',
    `is_del` tinyint(1) NOT NULL DEFAULT '0',  
    `attachId` int(11) unsigned NOT NULL,   
   PRIMARY KEY ( `id` )
) ENGINE=MyISAM DEFAULT CHARSET=utf8;  



# Dump of table ts_map_railwayLine
# ------------------------------------------------------------;

DROP TABLE IF EXISTS `ts_map_railwayLine` ;
CREATE TABLE `ts_map_railwayLine`  (
   `id`  int NOT NULL,
   `name`  varchar(255) NOT NULL,
  PRIMARY KEY ( `id` )
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO  `ts_map_railwayLine`  ( `id` , `name` )
VALUES
    (3005,'沪蓉线');
    
# Dump of table ts_map_railwayAdmin
# ------------------------------------------------------------;
    
DROP TABLE IF EXISTS  `ts_map_railwayAdmin` ;
CREATE TABLE  `ts_map_railwayAdmin`  (
   `id`  int(11) NOT NULL,
   `name`  varchar(255) NOT NULL,
  PRIMARY KEY ( `id` )
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO  `ts_map_railwayAdmin`  ( `id` , `name` )
VALUES
    (17,'武汉');



# Dump of table ts_map_POI
# ------------------------------------------------------------;
DROP TABLE IF EXISTS  `ts_map_POI`;
CREATE TABLE  `ts_map_POI`  (
   `id`  int NOT NULL AUTO_INCREMENT,
   `name`  varchar(255) DEFAULT NULL,
   `lat`  float DEFAULT NULL,
   `lng`  float DEFAULT NULL,
   `height`  float DEFAULT NULL,
   `status`  tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:没有经纬度数据 1:有经纬度数据' ,
  PRIMARY KEY ( `id` )
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# Dump of table ts_map_railwayHundred
# ------------------------------------------------------------;

DROP TABLE IF EXISTS  `ts_map_railwayHundred`  ;
CREATE TABLE  `ts_map_railwayHundred`  (
   `id`  int NOT NULL AUTO_INCREMENT,
   `tunnelname`  varchar(255) NOT NULL,
   `mileage`  float NOT NULL,
   `POI_id`  int NOT NULL,
   `Admin_id`  smallint NOT NULL,
   `Line_id`  int(11) NOT NULL,
   `lid`  smallint NOT NULL,
  PRIMARY KEY ( `id` ),
  FOREIGN KEY ( `POI_id` ) references  `ts_map_POI`  ( `id` ),
  FOREIGN KEY ( `Admin_id` ) references  ts_map_railwayAdmin  ( `id` ),
  FOREIGN KEY ( `Line_id` ) references  ts_map_railwayLine  ( `id` )
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# Dump of table ts_map_Tunnel
# ------------------------------------------------------------;

DROP TABLE IF EXISTS  `ts_map_Tunnel`  ;
CREATE TABLE  `ts_map_Tunnel`  (
   `id`  int NOT NULL AUTO_INCREMENT,
   `tunnelname`  varchar(255) NOT NULL,
   `tunneltype`  varchar(255) NOT NULL,
   `mileage`  float NOT NULL,
   `lid`  smallint NOT NULL,
   `centerPOI`  int NOT NULL,
   `Admin_id`  smallint NOT NULL,
   `Line_id`  int(11) NOT NULL,
  PRIMARY KEY ( `id` ),
  FOREIGN KEY ( `centerPOI` ) references  `ts_map_POI`  ( `id` ),
  FOREIGN KEY ( `Admin_id` ) references  ts_map_railwayAdmin  ( `id` ),
  FOREIGN KEY ( `Line_id` ) references  ts_map_railwayLine  ( `id` )
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# Dump of table ts_map_Bridge
# ------------------------------------------------------------;

DROP TABLE IF EXISTS  `ts_map_Bridge`  ;
CREATE TABLE  `ts_map_Bridge`  (
   `id`  int NOT NULL AUTO_INCREMENT,
   `bridgename`  varchar(255) NOT NULL,
   `bridgetype`  varchar(255) NOT NULL,
   `mileage` float NOT NULL,
   `lid`  smallint  NOT NULL,
   `centerPOI`  int NOT NULL,
   `Admin_id`  smallint NOT NULL,
   `Line_id`  int(11) NOT NULL,
  PRIMARY KEY ( `id` ),
  FOREIGN KEY ( `centerPOI` ) references  `ts_map_POI`  ( `id` ),
  FOREIGN KEY ( `Admin_id` ) references  `ts_map_railwayAdmin`  ( `id` ),
  FOREIGN KEY ( `Line_id` ) references  `ts_map_railwayLine`  ( `id` )
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# Dump of table ts_map_Curve
# ------------------------------------------------------------;

DROP TABLE IF EXISTS  `ts_map_Curve`  ;
CREATE TABLE  `ts_map_Curve`  (
   `id`  int NOT NULL AUTO_INCREMENT,
   `length`  float NOT NULL,
   `tangent`  float NOT NULL,
   `angle`  float NOT NULL,
   `radius`  float NOT NULL,
   `gentlecurve`  float NOT NULL,
   `startmileage`  float NOT NULL,
   `endmileage`  float NOT NULL,
   `side`  int(11) NOT NULL,
   `lid`  smallint NOT NULL,
   `centerPOI`  int NOT NULL,
   `Admin_id`  smallint NOT NULL,
   `Line_id`  int(11) NOT NULL,
  PRIMARY KEY ( `id` ),
  FOREIGN KEY ( `centerPOI` ) references  `ts_map_POI`  ( `id` ),
  FOREIGN KEY ( `Admin_id` ) references  ts_map_railwayAdmin  ( `id` ),
  FOREIGN KEY ( `Line_id` ) references  ts_map_railwayLine  ( `id` )
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# Dump of table ts_map_Slope
# ------------------------------------------------------------;

DROP TABLE IF EXISTS  `ts_map_Slope`  ;
CREATE TABLE  `ts_map_Slope`  (
   `id`  int NOT NULL AUTO_INCREMENT,
   `length`  float NOT NULL,
   `slope`  float NOT NULL,
   `startmileage`  float NOT NULL,
   `endmileage`  float NOT NULL,
   `side`  int(11) NOT NULL,
   `lid`  smallint NOT NULL,
   `centerPOI`  int NOT NULL,
   `Admin_id`  smallint NOT NULL,
   `Line_id`  int(11) NOT NULL,
  PRIMARY KEY ( `id` ),
  FOREIGN KEY ( `centerPOI` ) references  ts_map_POI  ( `id` ),
  FOREIGN KEY ( `Admin_id` ) references  ts_map_railwayAdmin  ( `id` ),
  FOREIGN KEY ( `Line_id` ) references  ts_map_railwayLine  ( `id` )
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# Dump of table ts_map_Station
# ------------------------------------------------------------;
DROP TABLE IF EXISTS  `ts_map_Station` ;
CREATE TABLE  `ts_map_Station`  (
   `id`  int NOT NULL AUTO_INCREMENT,
   `stationname`  varchar(255) NOT NULL,
   `tracknum`  smallint ,
   `namehead`  char,
   `cetermileage`  float NOT NULL,
   `lid`  smallint NOT NULL,
   `centerPOI`  int NOT NULL,
   `Admin_id`  smallint NOT NULL,
   `Line_id`  int(11) NOT NULL,
  PRIMARY KEY ( `id` ),
  FOREIGN KEY ( `centerPOI` ) references  `ts_map_POI`  ( `id` ),
  FOREIGN KEY ( `Admin_id` ) references  `ts_map_railwayAdmin`  ( `id` ),
  FOREIGN KEY ( `Line_id` ) references  `ts_map_railwayLine`  ( `id` )
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


