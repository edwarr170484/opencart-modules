-- phpMyAdmin SQL Dump
-- version 4.0.10.8
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июл 10 2015 г., 11:48
-- Версия сервера: 5.1.73
-- Версия PHP: 5.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `mebeldom`
--

-- --------------------------------------------------------

--
-- Структура таблицы `oc_gallery`
--

CREATE TABLE IF NOT EXISTS `oc_gallery` (
  `gallery_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `seo_name` varchar(255) NOT NULL,
  `text` longtext NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`gallery_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `oc_gallery`
--

INSERT INTO `oc_gallery` (`gallery_id`, `name`, `seo_name`, `text`, `status`) VALUES
(2, 'Материалы', 'materialy', '&lt;p&gt;&lt;br&gt;В производстве мебели под заказ наша компания использует широкий спектр сырья и материалов,что способствует качеству и эстетике мебели предлагаемой Вашему вниманию.&lt;br&gt;&lt;br&gt;Качественные материалы для мебели, в наше время изобилия производителей сырья позволяют выпускать мебельную продукцию высочайшего уровня. Для каждого разрабатываемого изделия мы отбираем свой индивидуальный спектр материалов наиболее оптимально соответствующий выдвигаемым требованиям к производимому товару. Чётко налаженная с поставщиками система поставок сырья позволяет нам своевременно исполнять свои обязательства по изготовлению мебели под заказ в Минске.&lt;br&gt;&lt;br&gt;В данном разделе представлены образцы материалов, которые мы используем при изготовлении&amp;nbsp; мебели. Это плиты ДСП компаний KRONOPOL и EGGER, пластики ABET, ARPA&amp;nbsp; и RESOPAL, стёкла л акобель, образцы профилей, варианты фотопечати (для фасадов), а также материалы, которые используются в качестве декоративной отделки – искусственная кожа и панели SIBU, синтетический ротанг, папирус и бамбук.&lt;br&gt;&lt;br&gt;Узнать о наличии понравившегося материала Вы можете по телефонам или отправив нам запрос через форму обратной связи&lt;br&gt;&lt;br&gt;&amp;nbsp;&lt;br&gt;&lt;br&gt;&lt;span style=&quot;font-weight: bold;&quot;&gt;Телефон дизайнера&amp;nbsp;&amp;nbsp; (029) 307-03-64&lt;/span&gt;&amp;nbsp; Максим.&amp;nbsp; (обмер , доставка — бесплатно).&lt;br&gt;&lt;br&gt;&lt;/p&gt;', 1),
(3, 'Наши работы', 'raboty', '&lt;p&gt;&lt;br&gt;&lt;/p&gt;', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `oc_gallery_category`
--

CREATE TABLE IF NOT EXISTS `oc_gallery_category` (
  `category_id` int(25) NOT NULL AUTO_INCREMENT,
  `gallery_id` int(25) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `oc_gallery_category`
--

INSERT INTO `oc_gallery_category` (`category_id`, `gallery_id`, `category_name`) VALUES
(6, 2, 'Дерево'),
(7, 2, 'Пластик');

-- --------------------------------------------------------

--
-- Структура таблицы `oc_gallery_image`
--

CREATE TABLE IF NOT EXISTS `oc_gallery_image` (
  `gallery_image_id` int(11) NOT NULL AUTO_INCREMENT,
  `gallery_id` int(11) NOT NULL,
  `category_id` int(25) NOT NULL,
  `image` varchar(255) NOT NULL,
  `sort_order` int(25) NOT NULL DEFAULT '0',
  PRIMARY KEY (`gallery_image_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=96 ;

--
-- Дамп данных таблицы `oc_gallery_image`
--

INSERT INTO `oc_gallery_image` (`gallery_image_id`, `gallery_id`, `category_id`, `image`, `sort_order`) VALUES
(93, 3, 9, 'catalog/logo.png', 1),
(95, 2, 7, 'catalog/material/rotang.jpg', 2),
(94, 2, 6, 'catalog/material/33333.jpg', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `oc_gallery_image_description`
--

CREATE TABLE IF NOT EXISTS `oc_gallery_image_description` (
  `gallery_image_id` int(11) NOT NULL,
  `gallery_id` int(11) NOT NULL,
  `title` varchar(64) NOT NULL,
  `description` longtext NOT NULL,
  PRIMARY KEY (`gallery_image_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `oc_gallery_image_description`
--

INSERT INTO `oc_gallery_image_description` (`gallery_image_id`, `gallery_id`, `title`, `description`) VALUES
(93, 3, 'Работа1', 'Работа1'),
(94, 2, 'Бамбук', 'Бамбук — это необычное растение: изделия из него отличаются прочностью и необыкновенной легкостью. Оттенки волокна варьируются от цвета золотистой соломы до насыщенного цвета мореного дуба. Благодаря этому изготовленные из бамбука плиты так разнообразны и неповторимы. Этот натуральный материал оживляет и обогащает интерьеры наших домов. По Фен-Шую в странах Азии бамбук приносит в дом богатство и благополучие. Этот материал используют для привлечения удачи, богатства, успеха в бизнесе, он притягивает положительную энергию и создает в доме гармонию и уют'),
(95, 2, 'Ротанг', 'Натуральный, экологически чистый ротанг родом из индонезийских и малазийских лесов сегодня на пике популярности. И это легко объяснимо: он устойчив к перепадам температур, воздействию влаги и ультрафиолетовых лучей, абсолютно безопасен благодаря отсутствию острых углов и требует минимального ухода. Благодаря своим качествам широко применяется в производстве мебели под заказ');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
