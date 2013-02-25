SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `font_data` (
  `font_id` int(4) NOT NULL AUTO_INCREMENT,
  `familyName` varchar(80) COLLATE utf8_general_mysql500_ci NOT NULL,
  `lineHeight` int(10) NOT NULL,
  `underlineThickness` int(10) NOT NULL,
  `descender` int(10) NOT NULL,
  `resolution` int(10) NOT NULL,
  `boundingBox_yMin` int(8) NOT NULL,
  `boundingBox_xMin` int(8) NOT NULL,
  `boundingBox_yMax` int(8) NOT NULL,
  `boundingBox_xMax` int(8) NOT NULL,
  `cssFontStyle` varchar(20) COLLATE utf8_general_mysql500_ci NOT NULL,
  `underlinePosition` int(10) NOT NULL,
  `ascender` int(10) NOT NULL,
  `cssFontWeight` varchar(20) COLLATE utf8_general_mysql500_ci NOT NULL,
  PRIMARY KEY (`font_id`),
  KEY `familyName` (`familyName`,`lineHeight`,`underlineThickness`,`descender`,`resolution`,`boundingBox_yMin`,`boundingBox_xMin`,`boundingBox_yMax`,`boundingBox_xMax`,`cssFontStyle`,`underlinePosition`,`ascender`,`cssFontWeight`),
  KEY `familyName_2` (`familyName`),
  KEY `font_id` (`font_id`,`familyName`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci AUTO_INCREMENT=5 ;

CREATE TABLE IF NOT EXISTS `glyphs` (
  `font_id` int(4) NOT NULL,
  `char` int(8) NOT NULL,
  `x_min` int(5) NOT NULL,
  `x_max` int(5) NOT NULL,
  `ha` int(5) NOT NULL,
  `o` varchar(5000) COLLATE utf8_general_mysql500_ci DEFAULT NULL,
  KEY `font_id` (`font_id`),
  KEY `char` (`char`),
  KEY `font_id_2` (`font_id`,`char`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
