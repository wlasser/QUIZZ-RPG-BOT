-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               10.1.31-MariaDB - mariadb.org binary distribution
-- Операционная система:         Win32
-- HeidiSQL Версия:              9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Дамп структуры для таблица bot_game.accounts
DROP TABLE IF EXISTS `accounts`;
CREATE TABLE IF NOT EXISTS `accounts` (
  `autoId` bigint(20) NOT NULL AUTO_INCREMENT,
  `login` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `ip` text NOT NULL,
  `date` text NOT NULL,
  `character` text NOT NULL,
  PRIMARY KEY (`autoId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы bot_game.accounts: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `accounts` ENABLE KEYS */;

-- Дамп структуры для таблица bot_game.conversations
DROP TABLE IF EXISTS `conversations`;
CREATE TABLE IF NOT EXISTS `conversations` (
  `id` int(11) DEFAULT NULL,
  `dialog_id` int(11) DEFAULT NULL,
  `dialog_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы bot_game.conversations: ~19 rows (приблизительно)
/*!40000 ALTER TABLE `conversations` DISABLE KEYS */;
REPLACE INTO `conversations` (`id`, `dialog_id`, `dialog_text`) VALUES
	(1, 2, 'Всякое разное болтают в этом городе. Но я особо ничего не знаю, сам тут недавно.'),
	(1, 3, 'Мы можем долго беседовать... Но вот я слышал о #1. Странная вообще вещь.'),
	(1, 4, 'Ты либо поможешь мне, либо проваливай. Вот для тебя задание.'),
	(2, 1, 'Привет! Я рад тебя видеть! А ты меня?'),
	(2, 2, 'Я занят. Но всегда могу поговорить про #2. Остальное меня сейчас не интересует.'),
	(2, 3, 'Отстань! Я занят! Хотя здесь у меня есть одно дело... Может поможешь?'),
	(3, 1, 'Я рад тебя видеть. Может составишь мне компанию за кружкой пива?'),
	(3, 2, 'А может чего покрепче? И найдем тему для разговора!'),
	(3, 3, 'Хорошо. Расскажу тебе о #3, если конечно тебе интересно.'),
	(3, 4, 'Все. Мне пора. Пока!'),
	(1, 1, 'Меня зовут... Если честно это не важно, это просто тестовый набросок'),
	(5, 1, 'Я простой работяга. Ты что-то хочешь узнать?'),
	(5, 2, 'Нет, ничего не знаю и ни о чем не хочу говорить... Хотя только если тебя интересует всякое... ну ты понимаешь...'),
	(5, 3, 'Так вот. Меня волнуют #4 . Ну как волнуют... Я просто хочу чтобы кто-то с этим разобрался.'),
	(5, 4, 'Ладно. Я устал от этого. Пока.'),
	(6, 1, 'Оу, я тебя здесь раньше не видел... Давно здесь? Я давненько. Лет 20 почитай тут живу.'),
	(6, 2, 'Да не заморачивайся, всё нормально будет. Здесь в баре тебе рады, я сам бывало выкашивал нечисть всякую.'),
	(6, 3, 'Ну ладно, вот тебе немножко информации: #5.  Что хочешь с этим, то и делай!'),
	(6, 4, 'Пока. Если что - я здесь частенько.');
/*!40000 ALTER TABLE `conversations` ENABLE KEYS */;

-- Дамп структуры для таблица bot_game.create_quest
DROP TABLE IF EXISTS `create_quest`;
CREATE TABLE IF NOT EXISTS `create_quest` (
  `autoid` int(11) NOT NULL AUTO_INCREMENT,
  `scores_for_that` text NOT NULL,
  `title` text NOT NULL,
  `variant_1` text NOT NULL,
  `variant_2` text NOT NULL,
  `variant_3` text NOT NULL,
  `variant_4` text NOT NULL,
  `scores_1` text NOT NULL,
  `scores_2` text NOT NULL,
  `scores_3` text NOT NULL,
  `scores_4` text NOT NULL,
  PRIMARY KEY (`autoid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы bot_game.create_quest: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `create_quest` DISABLE KEYS */;
/*!40000 ALTER TABLE `create_quest` ENABLE KEYS */;

-- Дамп структуры для таблица bot_game.creatures
DROP TABLE IF EXISTS `creatures`;
CREATE TABLE IF NOT EXISTS `creatures` (
  `autoid` bigint(20) NOT NULL AUTO_INCREMENT,
  `id` bigint(20) NOT NULL DEFAULT '0',
  `conv_id` bigint(20) NOT NULL DEFAULT '0',
  `sublocation` bigint(20) DEFAULT '0',
  `location` bigint(20) DEFAULT '0',
  PRIMARY KEY (`autoid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='spawns\r\n';

-- Дамп данных таблицы bot_game.creatures: ~6 rows (приблизительно)
/*!40000 ALTER TABLE `creatures` DISABLE KEYS */;
REPLACE INTO `creatures` (`autoid`, `id`, `conv_id`, `sublocation`, `location`) VALUES
	(1, 1, 1, 1, 0),
	(2, 2, 2, 0, 0),
	(3, 3, 3, 1, 0),
	(4, 4, 4, 0, 0),
	(5, 8, 5, 0, 0),
	(6, 10, 6, 1, 0);
/*!40000 ALTER TABLE `creatures` ENABLE KEYS */;

-- Дамп структуры для таблица bot_game.creature_quest_start
DROP TABLE IF EXISTS `creature_quest_start`;
CREATE TABLE IF NOT EXISTS `creature_quest_start` (
  `autoid_creature` int(11) DEFAULT NULL,
  `quest_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы bot_game.creature_quest_start: ~9 rows (приблизительно)
/*!40000 ALTER TABLE `creature_quest_start` DISABLE KEYS */;
REPLACE INTO `creature_quest_start` (`autoid_creature`, `quest_id`) VALUES
	(3, 4),
	(1, 3),
	(1, 4),
	(1, 1),
	(2, 2),
	(1, 5),
	(2, 8),
	(3, 9),
	(5, 10);
/*!40000 ALTER TABLE `creature_quest_start` ENABLE KEYS */;

-- Дамп структуры для таблица bot_game.creature_template
DROP TABLE IF EXISTS `creature_template`;
CREATE TABLE IF NOT EXISTS `creature_template` (
  `id` int(11) NOT NULL,
  `name` varchar(45) COLLATE utf8_bin NOT NULL,
  `location` varchar(45) COLLATE utf8_bin NOT NULL,
  `sublocation` varchar(45) COLLATE utf8_bin NOT NULL,
  `description` varchar(45) COLLATE utf8_bin NOT NULL,
  `hp` varchar(45) COLLATE utf8_bin NOT NULL,
  `reward_exp` varchar(45) COLLATE utf8_bin NOT NULL,
  `loot_id` varchar(45) COLLATE utf8_bin NOT NULL,
  `type` varchar(45) COLLATE utf8_bin NOT NULL,
  `resistance_type1` varchar(45) COLLATE utf8_bin NOT NULL,
  `resistance1` varchar(45) COLLATE utf8_bin NOT NULL,
  `resistance_type2` varchar(45) COLLATE utf8_bin NOT NULL,
  `resistance2` varchar(45) COLLATE utf8_bin NOT NULL,
  `resistance_type3` varchar(45) COLLATE utf8_bin NOT NULL,
  `resistance3` varchar(45) COLLATE utf8_bin NOT NULL,
  `minDamage` varchar(45) COLLATE utf8_bin NOT NULL,
  `maxDamage` varchar(45) COLLATE utf8_bin NOT NULL,
  `sickness_type` varchar(45) COLLATE utf8_bin NOT NULL,
  `damage_school` varchar(45) COLLATE utf8_bin NOT NULL,
  `normal_damage` varchar(45) COLLATE utf8_bin NOT NULL,
  `boss` varchar(45) COLLATE utf8_bin NOT NULL,
  `looseFatigue` varchar(45) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Дамп данных таблицы bot_game.creature_template: ~10 rows (приблизительно)
/*!40000 ALTER TABLE `creature_template` DISABLE KEYS */;
REPLACE INTO `creature_template` (`id`, `name`, `location`, `sublocation`, `description`, `hp`, `reward_exp`, `loot_id`, `type`, `resistance_type1`, `resistance1`, `resistance_type2`, `resistance2`, `resistance_type3`, `resistance3`, `minDamage`, `maxDamage`, `sickness_type`, `damage_school`, `normal_damage`, `boss`, `looseFatigue`) VALUES
	(1, 'Подозрительный парень', '', '', 'В нем есть загадка.', '100', '10', '1', '1', '', '', '', '', '', '', '', '10', '', '1', '2', '', ''),
	(2, 'Михаил Поперечный', '', '', 'Просто прохожий', '100', '10', '2', '1', '', '', '', '', '', '', '', '10', '', '1', '2', '', ''),
	(3, 'Задумчивая женщина', '', '', 'Проходила мимо', '100', '10', '2', '1', '', '', '', '', '', '', '', '10', '', '1', '2', '', ''),
	(4, 'Павел Петров', '', '', 'Знает толк в выпивке', '100', '10', '2', '1', '', '', '', '', '', '', '', '10', '', '1', '2', '', ''),
	(5, 'Бессмертный бог', '', '', 'Тот, кто зовется Бессмертным!', '200', '20', '5', '1', '', '', '', '', '', '', '12', '17', '3', '0', '2', '', ''),
	(6, 'Черная Шенель', '', '', 'Тот кто Черная Шенель.', '200', '30', '6', '2', '1', '50', '3', '50', '', '', '20', '22', '2', '1', '2', '1', ''),
	(7, 'Женщина в белом', '', '', 'Призрак женщины в белом.', '215', '35', '7', '2', '1', '50', '3', '50', '', '', '21', '23', '2', '1', '2', '1', ''),
	(8, 'Василий Ломовой', '', '', 'Вампиры в городе.', '200', '10', '8', '1', '', '', '', '', '', '', '20', '22', '', '1', '5', '', ''),
	(9, 'Безумный Макс', '', '', 'БЕСКОНЕЧНОЕ БЕЗУМИЕ!!!', '100', '100', '9', '1', '', '', '', '', '', '', '5', '10', '', '', '7', '1', '50'),
	(10, 'Замкнутый человек', '', '', 'Просто человек со своими заморочками', '200', '0', '10', '1', '', '', '', '', '', '', '100', '100', '', '', '100', '1', '100');
/*!40000 ALTER TABLE `creature_template` ENABLE KEYS */;

-- Дамп структуры для таблица bot_game.fm_id
DROP TABLE IF EXISTS `fm_id`;
CREATE TABLE IF NOT EXISTS `fm_id` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `radio_id` int(11) NOT NULL,
  `message` text COLLATE utf8_bin NOT NULL,
  `quest_id` int(11) NOT NULL,
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Дамп данных таблицы bot_game.fm_id: ~14 rows (приблизительно)
/*!40000 ALTER TABLE `fm_id` DISABLE KEYS */;
REPLACE INTO `fm_id` (`message_id`, `radio_id`, `message`, `quest_id`) VALUES
	(1, 1, 'Все хорошо прекрасная Маркиза, все хорошо, все хорошо!', 1),
	(2, 1, 'Мы к вам заехали на час, а ну скорей любите нас!', 0),
	(3, 1, 'В черном цилиндре, наряде старинном...', 0),
	(4, 4, 'Способность ходить на двух ногах.', 0),
	(5, 4, 'Способность вызвать у себя камни в почках.', 0),
	(6, 4, 'Способность выкакать 1 копейку раз в день.', 0),
	(7, 4, 'Способность дышать под водой, но только тогда, когда в нее кто-нибудь помочился.', 0),
	(8, 4, 'Способность путешествовать во времени, но только к тому моменту, когда ты умрешь.', 0),
	(9, 4, 'Способность делать все то, что ты умеешь делать.', 0),
	(10, 4, 'Способность становиться умственно отсталым, настолько чтобы забыть, как стать нормальным снова.', 0),
	(11, 4, 'Способность откладывать всё на последний момент.', 0),
	(12, 4, 'Способность определить по запаху того, кто перднул.', 0),
	(13, 4, 'Способность чувствовать боль через 30 секунд, после того как это случилось.', 0),
	(14, 4, 'Способность сделать домашнее задание, но никогда не получать оценку выше тройки.', 0);
/*!40000 ALTER TABLE `fm_id` ENABLE KEYS */;

-- Дамп структуры для таблица bot_game.item_template
DROP TABLE IF EXISTS `item_template`;
CREATE TABLE IF NOT EXISTS `item_template` (
  `id` int(11) NOT NULL,
  `name` varchar(45) COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `min_dmg` varchar(45) COLLATE utf8_bin NOT NULL,
  `max_dmg` varchar(45) COLLATE utf8_bin NOT NULL,
  `param_type_1` varchar(45) COLLATE utf8_bin NOT NULL,
  `param_1` varchar(45) COLLATE utf8_bin NOT NULL,
  `param_type_2` varchar(45) COLLATE utf8_bin NOT NULL,
  `param_2` varchar(45) COLLATE utf8_bin NOT NULL,
  `param_type_3` varchar(45) COLLATE utf8_bin NOT NULL,
  `param_3` varchar(45) COLLATE utf8_bin NOT NULL,
  `param_type_4` varchar(45) COLLATE utf8_bin NOT NULL,
  `param_4` varchar(45) COLLATE utf8_bin NOT NULL,
  `special` varchar(45) COLLATE utf8_bin NOT NULL,
  `charges` int(11) NOT NULL,
  `icon` varchar(45) COLLATE utf8_bin NOT NULL,
  `type` varchar(45) COLLATE utf8_bin NOT NULL,
  `specType` varchar(45) COLLATE utf8_bin NOT NULL,
  `stackable` varchar(45) COLLATE utf8_bin NOT NULL,
  `max_count` varchar(45) COLLATE utf8_bin NOT NULL,
  `price_buy` int(11) NOT NULL,
  `price_sell` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Дамп данных таблицы bot_game.item_template: ~32 rows (приблизительно)
/*!40000 ALTER TABLE `item_template` DISABLE KEYS */;
REPLACE INTO `item_template` (`id`, `name`, `description`, `min_dmg`, `max_dmg`, `param_type_1`, `param_1`, `param_type_2`, `param_2`, `param_type_3`, `param_3`, `param_type_4`, `param_4`, `special`, `charges`, `icon`, `type`, `specType`, `stackable`, `max_count`, `price_buy`, `price_sell`) VALUES
	(1, 'Документы ФСБ', 'Позволяют проводить расследование - особые возможности.', '', '', '', '', '', '', '', '', '', '', '1', 0, 'doc_fsb.png', '', '', '0', '1', 0, 0),
	(2, 'Черный похоронный костюм', 'Используется вместе с документами для того, чтобы произвести впечатление.', '', '', '', '', '', '', '', '', '', '', '1', 0, 'suit.png', '', '', '0', '1', 0, 0),
	(3, 'Дешевый радиоприемник', 'Необходим для поиска заданий. Не помешает опытному охотнику.', '', '', '', '', '', '', '', '', '', '', '1', 0, 'radio.png', '', '', '0', '1', 100, 40),
	(4, 'Флакон святой воды', 'Хорошее средство для выявления демонов.', '', '', '', '', '', '', '', '', '', '', '1', 5, 'flacon.png', '', '', '0', '1', 50, 15),
	(5, 'Простой фонарик', 'Освещает темные места. Необходим, если вы работаете ночью.', '', '', '', '', '', '', '', '', '', '', '1', 100, 'flashlight.png', '', '', '0', '1', 120, 50),
	(6, 'Кусочек бекона.', 'Замечательная вещь. На завтрак - обжарить с яищницей. Запить кофе. Мммм...', '', '', '1', '10', '', '', '', '', '', '', '0', 0, 'bacon.png', '1', '', '1', '10', 10, 1),
	(7, 'Собранный из хлама измеритель ЭМП', 'Чувствительность высокая, но частенько подводит. Помогает обнаружить призраков.', '', '', '', '', '', '', '', '', '', '', '1', 10, 'cheap_emp.png', '', '', '0', '1', 0, 0),
	(8, 'Ангельский клинок', 'Используется против ангелов и демонов.', '', '', '', '', '', '', '', '', '', '', '1', 0, 'angel_sword.png', '2', '5', '0', '1', 0, 0),
	(9, 'Хлам', 'Оружейник может использовать для получения полезных материалов.', '', '', '', '', '', '', '', '', '', '', '', 0, 'trash.png', '5', '', '1', '15', 0, 0),
	(10, 'Гильза', 'В комбинации с порохом и пистоном создает пулю', '', '', '', '', '', '', '', '', '', '', '', 0, 'shell.png', '6', '', '1', '10', 0, 0),
	(11, 'Пистон', 'В комбинации с порохом и гильзой получается патрон', '', '', '', '', '', '', '', '', '', '', '', 0, 'piston.png', '6', '', '1', '10', 0, 0),
	(12, 'Металическая труба', 'Часто используется оружейником для создания дополнительных модификаций к оружию.', '', '', '', '', '', '', '', '', '', '', '', 0, 'plumb.png', '6', '', '1', '10', 0, 0),
	(13, 'Обычная пуля для пистолета.', 'Просто пуля. Эффективно для использования против всех, у кого есть плоть. Останавливающий эффект.', '1', '7', '', '', '', '', '', '', '', '', '', 0, 'bullet_pistol.png', '10', '1', '1', '12', 0, 0),
	(14, 'Оружейный порох', 'Необходим для создания пуль. ', '', '', '', '', '', '', '', '', '', '', '', 0, 'gunpowder.png', '6', '', '1', '10', 0, 0),
	(15, 'Спички', 'Из одного коробка получается серы на один заряд пороха.', '', '', '', '', '', '', '', '', '', '', '', 0, 'matches.png', '5', '', '1', '10', 50, 10),
	(16, 'Селитра', 'Одной порции достаточно для создания пороха.', '', '', '', '', '', '', '', '', '', '', '', 0, 'saltpeter.png', '6', '', '1', '10', 0, 0),
	(17, 'Уголь', 'Одной порции угля достаточно для создания пороха.', '', '', '', '', '', '', '', '', '', '', '', 0, 'goal.png', '6', '', '1', '10', 0, 0),
	(18, 'Сера', 'Используется для создания пороха в комбинации с углем и селитрой.', '', '', '', '', '', '', '', '', '', '', '', 0, 'sulfur.png', '6', '', '1', '10', 0, 0),
	(19, 'Складной нож', 'Необходим для работы с хламом. Зачистки спичек и некоторых других производственных необходимостей. Так-же является оружием.', '1', '3', '', '', '', '', '', '', '', '', '', 20, 'pocket_knife.png', '2', '1', '0', '1', 70, 20),
	(20, 'Увеличительное стекло', 'Иногда позволяет рассмотреть что-нибудь.', '', '', '', '', '', '', '', '', '', '', '', 5, 'lupa.png', '0', '', '0', '1', 70, 20),
	(21, 'Старый добрый пистолет', 'Самый простой из доступных пистолетов. Надежен и хорош', '5', '15', '', '', '', '', '', '', '', '', '', 100, 'normal_pistol.png', '3', '1', '', '', 200, 100),
	(22, 'Мобильный телефон', 'Иногда можно связаться со знакомыми охотниками', '0', '0', '', '', '', '', '', '', '', '', '1', 0, '', '0', '0', '0', '1', 1000, 200),
	(23, 'Дневник охотника', 'Здесь все записи о различных проишествиях, с которым столкнулся бывший владелец', '0', '0', '', '', '', '', '', '', '', '', '1', 0, '', '0', '0', '0', '', 0, 0),
	(24, 'Ржавая лопата', 'Старая, но надежная', '1', '3', '', '', '', '', '', '', '', '', '', 5, '', '2', '', '0', '', 0, 0),
	(25, 'Соль', 'Простая повареная соль', '', '', '', '', '', '', '', '', '', '', '1', 5, '', '0', '', '', '1', 0, 0),
	(26, 'Библиотечный билет', 'Позволяет пользоваться услугами библиотеки', '', '', '', '', '', '', '', '', '', '', '1', 10, '', '', '', '', '', 0, 0),
	(27, 'Ампула с кровью мертвеца.', 'Ослабляет вампиров.', '', '', '', '', '', '', '', '', '', '', '1', 10, '', '0', '', '0', '5', 500, 10),
	(28, 'Тесак "Безумие"', 'Рубит бошки.', '10', '14', '', '', '', '', '', '', '', '', '', 5, '', '2', '', '', '1', 1000, 20),
	(29, 'Точило', 'Позволяет обновить оружие ближнего боя. Можно использовать только на отдыхе.', '0', '0', '', '', '', '', '', '', '', '', '1', 0, '', '', '6', '', '', 500, 5),
	(30, 'Портативная радиостанция Иваново', 'Позволяет получить задание из радиоэфира, для того чтобы передать его другим охотникам.', '0', '0', '', '', '', '', '', '', '', '', '1', 0, '', '', '7', '', '', 90000, 0),
	(31, 'Сверток махорки', 'Пахнет отменно. Охотники это ценят.', '', '', '', '', '', '', '', '', '', '', '0', 0, '', '', '', '0', '1', 500, 5),
	(32, 'Русский Грог', 'Один глоток выжигает мозг.', '', '', '', '', '', '', '', '', '', '', '', 0, '', '', '', '', '', 100, 1);
/*!40000 ALTER TABLE `item_template` ENABLE KEYS */;

-- Дамп структуры для таблица bot_game.locations
DROP TABLE IF EXISTS `locations`;
CREATE TABLE IF NOT EXISTS `locations` (
  `id` int(11) NOT NULL,
  `name` varchar(45) COLLATE utf8_bin NOT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Дамп данных таблицы bot_game.locations: ~6 rows (приблизительно)
/*!40000 ALTER TABLE `locations` DISABLE KEYS */;
REPLACE INTO `locations` (`id`, `name`, `x`, `y`) VALUES
	(0, 'Иваново', 0, 0),
	(1, 'Петухово', 7, 12),
	(2, 'Переделкино', 2, 7),
	(3, 'Гадюкино', 1, 6),
	(4, 'Туманново', 10, 15),
	(100, 'Чистилище', -10, -10);
/*!40000 ALTER TABLE `locations` ENABLE KEYS */;

-- Дамп структуры для таблица bot_game.loot_template
DROP TABLE IF EXISTS `loot_template`;
CREATE TABLE IF NOT EXISTS `loot_template` (
  `id` int(11) DEFAULT NULL,
  `item` int(11) DEFAULT NULL,
  `chance` int(11) DEFAULT NULL,
  `count` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы bot_game.loot_template: ~4 rows (приблизительно)
/*!40000 ALTER TABLE `loot_template` DISABLE KEYS */;
REPLACE INTO `loot_template` (`id`, `item`, `chance`, `count`) VALUES
	(5, 9, 5, 3),
	(5, 6, 1, 1),
	(5, 9, 5, 3),
	(5, 6, 1, 1);
/*!40000 ALTER TABLE `loot_template` ENABLE KEYS */;

-- Дамп структуры для таблица bot_game.player
DROP TABLE IF EXISTS `player`;
CREATE TABLE IF NOT EXISTS `player` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8_bin NOT NULL,
  `class` varchar(45) COLLATE utf8_bin NOT NULL,
  `money` varchar(45) COLLATE utf8_bin NOT NULL,
  `level` varchar(45) COLLATE utf8_bin NOT NULL,
  `location` varchar(45) COLLATE utf8_bin NOT NULL,
  `curr_exp` varchar(45) COLLATE utf8_bin NOT NULL,
  `sublocation` varchar(45) COLLATE utf8_bin NOT NULL,
  `state` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `flag` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `sex` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `online` varchar(2) COLLATE utf8_bin DEFAULT NULL,
  `lastActivityTime` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Дамп данных таблицы bot_game.player: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `player` DISABLE KEYS */;
/*!40000 ALTER TABLE `player` ENABLE KEYS */;

-- Дамп структуры для таблица bot_game.players_rumors_collect
DROP TABLE IF EXISTS `players_rumors_collect`;
CREATE TABLE IF NOT EXISTS `players_rumors_collect` (
  `auto` bigint(20) NOT NULL AUTO_INCREMENT,
  `rumors_id` bigint(20) DEFAULT NULL,
  `player_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`auto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы bot_game.players_rumors_collect: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `players_rumors_collect` DISABLE KEYS */;
/*!40000 ALTER TABLE `players_rumors_collect` ENABLE KEYS */;

-- Дамп структуры для таблица bot_game.player_achievements_log
DROP TABLE IF EXISTS `player_achievements_log`;
CREATE TABLE IF NOT EXISTS `player_achievements_log` (
  `autoId` bigint(20) NOT NULL AUTO_INCREMENT,
  `player_id` bigint(20) DEFAULT NULL,
  `criteriaType` int(11) DEFAULT NULL,
  `criteriaId` int(11) DEFAULT NULL,
  `actionCount` bigint(20) DEFAULT NULL,
  `startTime` text,
  `lastTime` text,
  PRIMARY KEY (`autoId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы bot_game.player_achievements_log: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `player_achievements_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `player_achievements_log` ENABLE KEYS */;

-- Дамп структуры для таблица bot_game.player_battle
DROP TABLE IF EXISTS `player_battle`;
CREATE TABLE IF NOT EXISTS `player_battle` (
  `battle_id` int(11) NOT NULL AUTO_INCREMENT,
  `who_start` int(11) DEFAULT NULL,
  `player_id` int(11) DEFAULT NULL,
  `enemy_id` int(11) DEFAULT NULL,
  `enemy_hp` int(11) DEFAULT NULL,
  `last_damage` text,
  `step` int(11) DEFAULT NULL,
  `state` int(11) DEFAULT NULL,
  `start_time` text,
  `end_time` text,
  PRIMARY KEY (`battle_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы bot_game.player_battle: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `player_battle` DISABLE KEYS */;
/*!40000 ALTER TABLE `player_battle` ENABLE KEYS */;

-- Дамп структуры для таблица bot_game.player_garage
DROP TABLE IF EXISTS `player_garage`;
CREATE TABLE IF NOT EXISTS `player_garage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vehicle_id` int(11) NOT NULL,
  `coruption` int(11) NOT NULL,
  `fuel` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Дамп данных таблицы bot_game.player_garage: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `player_garage` DISABLE KEYS */;
/*!40000 ALTER TABLE `player_garage` ENABLE KEYS */;

-- Дамп структуры для таблица bot_game.player_instance
DROP TABLE IF EXISTS `player_instance`;
CREATE TABLE IF NOT EXISTS `player_instance` (
  `uid` bigint(50) NOT NULL AUTO_INCREMENT,
  `player_id` int(50) DEFAULT NULL,
  `dungeon_id` int(50) DEFAULT NULL,
  `map` longtext,
  `current_line` int(30) DEFAULT NULL,
  `start_time` text,
  `end_time` text,
  `completed` int(5) DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы bot_game.player_instance: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `player_instance` DISABLE KEYS */;
/*!40000 ALTER TABLE `player_instance` ENABLE KEYS */;

-- Дамп структуры для таблица bot_game.player_inventory
DROP TABLE IF EXISTS `player_inventory`;
CREATE TABLE IF NOT EXISTS `player_inventory` (
  `total_id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `item_id` varchar(45) COLLATE utf8_bin NOT NULL,
  `count` varchar(45) COLLATE utf8_bin NOT NULL,
  `charges` varchar(45) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`total_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Дамп данных таблицы bot_game.player_inventory: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `player_inventory` DISABLE KEYS */;
/*!40000 ALTER TABLE `player_inventory` ENABLE KEYS */;

-- Дамп структуры для таблица bot_game.player_inventory_rewrite
DROP TABLE IF EXISTS `player_inventory_rewrite`;
CREATE TABLE IF NOT EXISTS `player_inventory_rewrite` (
  `autoId` bigint(20) NOT NULL AUTO_INCREMENT,
  `player_id` bigint(20) DEFAULT NULL,
  `inventory` bigint(20) DEFAULT NULL,
  `equip` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`autoId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы bot_game.player_inventory_rewrite: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `player_inventory_rewrite` DISABLE KEYS */;
/*!40000 ALTER TABLE `player_inventory_rewrite` ENABLE KEYS */;

-- Дамп структуры для таблица bot_game.player_profession
DROP TABLE IF EXISTS `player_profession`;
CREATE TABLE IF NOT EXISTS `player_profession` (
  `uniq_id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `profession` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `skill` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`uniq_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Дамп данных таблицы bot_game.player_profession: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `player_profession` DISABLE KEYS */;
/*!40000 ALTER TABLE `player_profession` ENABLE KEYS */;

-- Дамп структуры для таблица bot_game.player_quests
DROP TABLE IF EXISTS `player_quests`;
CREATE TABLE IF NOT EXISTS `player_quests` (
  `quest_id` int(11) NOT NULL,
  `scores` varchar(45) COLLATE utf8_bin NOT NULL,
  `complete` varchar(45) COLLATE utf8_bin NOT NULL,
  `player_id` varchar(45) COLLATE utf8_bin NOT NULL,
  `time_accepted` bigint(255) NOT NULL,
  `time_finished` bigint(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Дамп данных таблицы bot_game.player_quests: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `player_quests` DISABLE KEYS */;
/*!40000 ALTER TABLE `player_quests` ENABLE KEYS */;

-- Дамп структуры для таблица bot_game.player_quest_fight
DROP TABLE IF EXISTS `player_quest_fight`;
CREATE TABLE IF NOT EXISTS `player_quest_fight` (
  `autoid` int(11) NOT NULL AUTO_INCREMENT,
  `npc_id` int(11) NOT NULL DEFAULT '0',
  `player_id` int(11) DEFAULT NULL,
  `quest_id` int(11) DEFAULT NULL,
  `quest_variant` int(11) DEFAULT NULL,
  `complete` int(11) DEFAULT NULL,
  PRIMARY KEY (`autoid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы bot_game.player_quest_fight: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `player_quest_fight` DISABLE KEYS */;
/*!40000 ALTER TABLE `player_quest_fight` ENABLE KEYS */;

-- Дамп структуры для таблица bot_game.player_stats
DROP TABLE IF EXISTS `player_stats`;
CREATE TABLE IF NOT EXISTS `player_stats` (
  `player_id` int(11) NOT NULL,
  `hp` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `max_hp` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `accuracy` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `armor` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `intellect` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `stamina` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `resistance` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `agility` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `fatigue` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `maxFatigue` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Дамп данных таблицы bot_game.player_stats: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `player_stats` DISABLE KEYS */;
/*!40000 ALTER TABLE `player_stats` ENABLE KEYS */;

-- Дамп структуры для таблица bot_game.quest_template
DROP TABLE IF EXISTS `quest_template`;
CREATE TABLE IF NOT EXISTS `quest_template` (
  `id` int(11) NOT NULL,
  `min_lvl` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `name` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `caption` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `title` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `objective` text COLLATE utf8_bin,
  `require1` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `require_type1` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `require2` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `require_type2` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `require3` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `require_type3` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `require4` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `require_type4` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `rewardExp` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `rewardMoney` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `reward1` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `reward2` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `reward3` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `reward4` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `variants_index` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `location` int(11) DEFAULT NULL,
  `looseFatigue` int(11) DEFAULT NULL,
  `timeToFinish` text COLLATE utf8_bin,
  `nextQuest` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Дамп данных таблицы bot_game.quest_template: ~12 rows (приблизительно)
/*!40000 ALTER TABLE `quest_template` DISABLE KEYS */;
REPLACE INTO `quest_template` (`id`, `min_lvl`, `name`, `caption`, `title`, `objective`, `require1`, `require_type1`, `require2`, `require_type2`, `require3`, `require_type3`, `require4`, `require_type4`, `rewardExp`, `rewardMoney`, `reward1`, `reward2`, `reward3`, `reward4`, `variants_index`, `location`, `looseFatigue`, `timeToFinish`, `nextQuest`) VALUES
	(1, '5', 'Вы что-нибудь хотите?', 'Тест', 'Вы что-нибудь хотите?', 'Вы находитесь на территории сверхъестественного. Ваша миссия проста - найдите мотель для организации базы.', '1', '', '', '', '', '', '', '', '100', '50', '1', '', '', '', '1', 0, 15, '0', 0),
	(2, '5', 'Здравствуй охотник!', 'Тест', 'Здравствуй охотник!', 'Вы держите в руках старый дневник одного охотника. Он расследовал дело о странных убийствах на трассе е95. Запись обрывается после слов "возможно призрак...".', '2', '1', '3', '2', '', '', '', '', '200', '70', '25', '', '', '', '2', 0, 20, '0', 0),
	(3, '', 'Что-то случилось. Перемены?', 'Тест', 'Что-то случилось. Перемены?', 'Существует много всего, что люди не могут объяснить. Так случилось и в семье Беловых. Одним осенним утром, старший сын ушел из дома. На его столе остались записи со странными символами. Чета Беловых, а именно Артем - ваш старый друг - первым делом позвонил вам. Может стоит взяться за это дело?', '3', '2', '3', '2', '', '', '', '', '300', '90', '1', '', '', '', '0', 0, 30, '0', 0),
	(4, '', 'Прямиком из эфира.', 'Тест', 'Прямиком из эфира.', 'В новостях промелькнуло сообщение о странной активности саранчи. Она напала на человека и убила его. Ничто не указывает на влияние сверхъестественного, однако может стоит разузнать побольше.', '1', '3', '1', '3', '', '', '', '', '400', '110', '19', '', '', '', '1', 0, 30, '+10 min', 0),
	(5, '5', 'Черная Шенель', 'Тест', 'Черная Шенель', 'Множество слухов плодится вокруг сверхъестественного. Это как-раз такой случай. Речь идет о некто "Черная шенель". Некоторые утверждают, что видели нечто сверхъестественное. Это как-раз нужное дело.', '1', '5', '', '', '', '', '', '', '500', '100', '', '', '', '', '', 0, 0, '0', 0),
	(6, '0', 'Квест на выбор класса', 'Первый квест', 'Вы столкнулись с необъяснимым.', 'Определитесь со своей ролью в этой вселенной.', '', '', '', '', '', '', '', '', '0', '0', '', '', '', '', '', 0, 0, '0', 0),
	(7, '0', 'Приветствие.', 'Я просто хочу с вами поздороваться.', 'Добро пожаловать в Сверхъестественное!', 'Спасибо за проявленный интерес. К сожалению в данный момент это лишь альфа-бета версия. Здесь еще ничего нет. Ну как нет... Функционала много, возможностей много, но вот контента пока не завезли...', '', '', '', '', '', '', '', '', '100', '100', '0', '', '', '', '', 0, 5, '0', 0),
	(8, '1', 'Женщина в белом.', 'Квест про женщину в белом.', 'Женщина в белом. Кто-же она?', 'Необходимо разобраться с делом, о некой женщине, которая возможно являетя призраком. Вперед, пора.', '1', '2', '', '', '', '', '', '', '300', '100', '25', '', '', '', '', 0, 25, '0', 0),
	(9, '0', 'Разбор полетов', 'Квест о полтергейсте.', 'Дело о полтергейсте.', 'Говорят на улице Отваги появился полтергейст. Нужно-бы разобраться.', '', '', '', '', '', '', '', '', '120', '50', '25', '', '', '', '', 0, 50, '0', 0),
	(10, '1', 'Ночной клуб "Пищевая цепь"', 'Пищевая цепь.', 'Что не так с клубом "Пищевая цепь"?', 'Подозрительное место. Никого не пускают, много шумят. Клуб работает по ночам.', '', '', '', '', '', '', '', '', '150', '100', '27', '25', '', '', '', 0, 50, '0', 11),
	(11, '1', 'Кровные страсти.', 'Клуб "Пищевая цепь".', 'С клубом действительно всё не так просто.', 'Необходимо найти как можно больше информации об этих тварях и подыскать снаряжение', '', '', '', '', '', '', '', '', '150', '100', '28', '29', '', '', '', 0, 50, '0', 12),
	(12, '1', 'Зачистка', 'Клуб "Пещевая цепь"', 'Пора покончить с этими тварями!', 'Необходимо разобраться со всеми участниками "экстремального туризма" в Иваново.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, '0', 0);
/*!40000 ALTER TABLE `quest_template` ENABLE KEYS */;

-- Дамп структуры для таблица bot_game.quest_variants
DROP TABLE IF EXISTS `quest_variants`;
CREATE TABLE IF NOT EXISTS `quest_variants` (
  `total_id` int(11) NOT NULL AUTO_INCREMENT,
  `quest_id` int(11) DEFAULT NULL,
  `scores` int(11) DEFAULT NULL,
  `greetings` text,
  `variant_1` text,
  `variant_2` text,
  `variant_3` text,
  `variant_4` text,
  `succ_score_1` text,
  `succ_score_2` text,
  `succ_score_3` text,
  `succ_score_4` text,
  `req_type_1` text COMMENT 'maybe item id?',
  `req_thing_1` text,
  `special_1` text,
  `req_type_2` text,
  `req_thing_2` text,
  `special_2` text,
  `req_type_3` text,
  `req_thing_3` text,
  `special_3` text,
  `req_type_4` text,
  `req_thing_4` text,
  `special_4` int(11) DEFAULT NULL,
  `rewardType_1` int(11) DEFAULT NULL,
  `rewardItem_1` int(11) DEFAULT NULL,
  `rewardCount_1` int(11) DEFAULT NULL,
  `rewardType_2` int(11) DEFAULT NULL,
  `rewardItem_2` int(11) DEFAULT NULL,
  `rewardCount_2` int(11) DEFAULT NULL,
  `rewardType_3` int(11) DEFAULT NULL,
  `rewardItem_3` int(11) DEFAULT NULL,
  `rewardCount_3` int(11) DEFAULT NULL,
  `rewardType_4` int(11) DEFAULT NULL,
  `rewardItem_4` int(11) DEFAULT NULL,
  `rewardCount_4` int(11) DEFAULT NULL,
  `next_quest` int(11) DEFAULT NULL,
  PRIMARY KEY (`total_id`)
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы bot_game.quest_variants: ~111 rows (приблизительно)
/*!40000 ALTER TABLE `quest_variants` DISABLE KEYS */;
REPLACE INTO `quest_variants` (`total_id`, `quest_id`, `scores`, `greetings`, `variant_1`, `variant_2`, `variant_3`, `variant_4`, `succ_score_1`, `succ_score_2`, `succ_score_3`, `succ_score_4`, `req_type_1`, `req_thing_1`, `special_1`, `req_type_2`, `req_thing_2`, `special_2`, `req_type_3`, `req_thing_3`, `special_3`, `req_type_4`, `req_thing_4`, `special_4`, `rewardType_1`, `rewardItem_1`, `rewardCount_1`, `rewardType_2`, `rewardItem_2`, `rewardCount_2`, `rewardType_3`, `rewardItem_3`, `rewardCount_3`, `rewardType_4`, `rewardItem_4`, `rewardCount_4`, `next_quest`) VALUES
	(1, 1, 0, 'Вы стоите прямо на том месте, где что-то произошло. Что-бы сделать?', 'Осмотреть место проишествия.', 'Использовать ЭМП для обнаружения активности', 'Провести спиритический сеанс', 'Позвонить в полицию, пусть они этим занимаются', '20', '87', '88', '88', '0', NULL, NULL, '2', '7', '0', NULL, NULL, '0', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(2, 1, 87, 'Вы ничего не добились этим не добились. Что будете делать?', 'Осмотреть место проишествия', 'Уйти домой, чтобы обдумать', 'Провести спиритический сеанс', 'Позвонить в полицию, пусть они этим занимаются', '20', '0', '-20', '-20', '0', NULL, NULL, '0', '0', '0', NULL, NULL, '0', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(3, 1, 88, 'Возможно это слишком сложное дело. Наверное стоит попробовать взяться за другое.', '', '', '', NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, '0', '0', '', NULL, NULL, '', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(4, 1, 20, 'Вы находите странную записку, в которой написано, что "Они избранные, вы должны их найти и привести ко мне!"', 'Осмотреть записку со всех сторон.', 'Использовать увеличительное стекло, чтобы внимательнее изучить записку.', 'Положить записку в карман и искать другие улики.', 'Позвонить в полицию и передать найденную записку.', '21', '22', '0', '-20', '0', NULL, NULL, '2', '20', '1', NULL, NULL, '0', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(5, 1, 21, 'Записка написана в старинном стиле. Из нее вы узнаете о таинственном ритуале, который проводится с целью подношения некому богу смерти. ', 'У меня есть информация по этому богу, я знаю что делать', 'Странно. Черт с ним, не понимаю о чем речь', 'Позвонить в полицию, пусть они с этим разбираются', 'Выбросить записку и забыть обо всём!', '90', '0', '-20', '-20', '5', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(6, 1, 22, 'Вы ничего не добились этим не добились. Что будете делать?', 'Осмотреть место проишествия', 'Уйти домой, чтобы обдумать', 'Провести спиритический сеанс', 'Позвонить в полицию, пусть они этим занимаются', '20', '0', '-20', '-20', '0', NULL, NULL, '0', '0', '0', NULL, NULL, '0', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(8, 1, 100, 'Известная информация позволила вам раскрыть это дело. Теперь вы должны получить награду', 'Завершить задание', '', '', '', '110', '', '', '', '0', NULL, NULL, '0', '0', '0', NULL, NULL, '0', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10),
	(9, 2, 0, 'Вы находитесь на месте последнего исчезновения человека.', 'Осмотреть место в поисках зацепок.', 'Позвонить старому знакомому', 'Уйти отсюда подальше', 'Смотреть записи в дневнике', '10', '20', '-100', '25', NULL, NULL, NULL, '2', '22', NULL, NULL, NULL, NULL, '1', '23', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(10, 2, -100, 'Возможно слишком сложное задание, не стоит его выполнять.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(11, 2, 20, 'Товарищ неохотно поделился своими догадками по этому дело. Он особо ничего не знает.', 'Позвонить другому товарищу.', 'Осмотреть место в поисках зацепок', 'Оставить всё как есть, пусть другие разбираются.', 'Слушать радиоволну охотников.', '-40', '10', '-100', '60', '1', '22', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '3', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(12, 2, 10, 'Осмотр местности ничего не дал, но вы нашли странную субстанцию', 'Проверить ее с помощью ЭМТ', 'Это точно призрак!', 'Сообщить в ближайшее отделение полиции, пусть они разбираются', 'Слушать радиоволну охотников.', '30', '30', '-100', '30', '1', '7', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '3', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(13, 2, 30, 'Все говорит о том, что дело действительно связано с призраком.', 'Известно где останки.', 'Призрак? Я пасс.', 'Узнать больше у местных.', 'Позвонить старому знакомому', '60', '-100', '60', '80', '2', '3', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '22', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(14, 2, 60, 'Вы узнали, где находятся останки.', 'Отправиться на поиски', 'Что-то здесь не так, узнать больше.', 'Пусть этим займутся другие', 'Взять лопату, которая без дела', '80', '10', '-100', '80', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 24, 1, NULL),
	(15, 2, 80, 'Вы в том месте, где находятся останки.', 'Раскопать захоранение.', 'Осмотреть место', 'Уйти отсюда подальше!', 'Кричать и звать на помощь!', '98', '-40', '-100', '-100', '1', '24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(16, 2, 98, 'Останкки перед вами, ваши действия.', 'Посыпать солью и сжечь.', 'Сжечь их.', 'Ничего не делать', 'Обратится в полицию', '100', '0', '-100', '-100', '1', '25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(17, 1, 90, 'Перед вами некто, кто называет себя Бессмертным Богом. Действуйте', 'Вступить в бой', 'Убежать', NULL, NULL, '100', '-20', NULL, NULL, '6', '5', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(18, 5, 0, 'Вы начали расследование дела о Черной шенеле.', 'Позвонить своему знакомому охотнику, может он знает что-нибудь.', 'Отправиться на поиски информации в библиотеку.', 'Дело подожет, можно попробовать позже.', 'Кинуться искать привидение, чтобы побыстрее разобраться с делом', '15', '30', '0', '10', '2', '22', NULL, '2', '26', NULL, NULL, NULL, NULL, '6', '6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(19, 5, 10, 'Сработало что-то', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(20, 5, 30, 'Сработало другое', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(21, 5, 15, 'Звонок дал определенные наводки по этому делу. Судя по словам знакомого охотника, это действительно призрак.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(22, 6, 0, 'Ваш жизненный путь привел вас в мир сверхъестественного из-за ...', 'Призрак, реальность моих кошмаров.', 'Ведьма. Мне нужно с ней поквитаться.', 'Я встретил одного охотника...', 'Это не важно...', '10', '20', '40', '30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(23, 6, 10, 'Как бороться с призраком, если он предстал.', 'Ионная пушка. Вот это вариант.', 'Соль. Если таковая имеется.', 'Железо. Поискать его.', 'Флакон со святой водой.', '40', '11', '11', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(24, 6, 20, 'Ведьмы бывают разными...', 'Исключено. От неё нужно избавиться.', 'Не знаю. Возможно...', 'Сначала нужно убедится.', 'Возможно. Но большинство - заноза.', '10', '10', '21', '21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(25, 6, 21, 'Ведьма может манипулировать сознанием?', 'Скорее всего.', 'Привороты, заклинания, проклятия...', 'Не думаю. Против лома нет приема.', 'Нет!', '22', '22', '0', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(26, 6, 22, 'Вы узнали где эта ведьма...', 'Узнать у местных побольше информации.', 'Отправиться вершить возмездие.', 'Шабаш намечается?', 'Мне нужно время подготовится.', '30', '10', '10', '23', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(27, 6, 23, 'Ведьмовские мешочки...', 'Найти и сжечь. Не трогать руками.', 'Какие мешочки?', 'Интересное содержание. Сильная ведьма.', 'Меня это не волнует.', '24', '10', '40', '10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(28, 6, 24, 'Вы удовлетворили свое желание. С ведьмой покончено.', 'Пойти спать. Отдых и еда.', 'Я убил человека... Пускай и ведьму.', 'Посыпать солью и сжечь.', 'Изучить содержимое её дневника.', '10', '25', '25', '40', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(29, 6, 25, 'Вы - Охотник на ведьм?', 'Да. Именно.', 'Нет. Я пожалуй не определился.', NULL, NULL, '4000', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(30, 6, 11, 'От призрака можно избавиться...', 'Сжечь останки, посыпав солью.', 'Сжечь то, что держит его в этом мире.', 'Святая вода.Да побольше лей!', 'Помочь ему закончить незавершенное дело.', '12', '12', '0', '30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(31, 6, 12, 'Вы уверены что столкнулись именно с призраком?', 'Летали предметы и творилось всякое.', 'Загробный холод, скачки напряжения.', 'Кошка внезапно зашипела.', 'Я его видел во плоти!!', '13', '13', '40', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(32, 6, 13, 'Человек попал в беду. У него дома полтергейст. Что делать?', 'Бежать. Я еще не готов к такой встречи.', 'Разузнать об этом получше.', 'Спросить о помощи у опытных охотников.', 'В ловушку и "GHOSTBUSTERS!".', '14', '14', '14', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(33, 6, 14, 'Эктоплазма...', 'След очень гневного призрака.', 'Ингредиент для некоторых ритуалов.', 'Кровь призрака.', 'Мороженное.', '15', '15', '0', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(34, 6, 15, 'Вы - Охотник?', 'Да. Так и есть.', 'Нет. Пожалуй я не определился.', NULL, NULL, '1000', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(35, 2, 100, 'Вы сделали, то что делают все охотники.', 'Завершить задание', NULL, NULL, NULL, '110', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(36, 6, 40, 'Любая задача требует от вас действия. Сейчас необходимо получить информацию.', 'Лучше всего начать в библиотеке.', 'Искать в интернете и газетах.', 'Нечего рассиживаться. Вперед, в бой!', 'Не моё это. Слишком нудно.', '41', '41', '10', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(37, 6, 41, 'К вам обратился охотник, он хочет найти гнездо вампиров.', 'Посоветовать не впутываться.', 'Дать наводку.', 'Отправиться с ним на поиски.', 'Не ввязываться.', '42', '42', '10', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(38, 6, 42, 'У вас в руках старая книга. Судя по обложке - книга древняя и магическая. Судя по рунам - книга запечатана чарами.', 'Попытаться прочесть.', 'Изучить со всех сторон.', 'Сжечь книгу.', 'Никакой магии и рун нет.', '0', '43', '10', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(39, 6, 43, 'По последним сводкам, в селе Туманово происходит что-то странное...', 'Отправиться на разведку.', 'Собрать как-можно больше информации.', 'Сообщить всем охотникам.', 'Меня это не интересует.', '10', '44', '44', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(40, 6, 44, 'Вы столкнулись с необъяснимой аномалией. Вам вроде-бы ничего не угрожает.', 'Изучить аномалию.', 'Держаться от этого подальше.', 'Сделать пометку и вернуться позже.', 'Рассказать охотникам.', '45', '45', '45', '45', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(41, 6, 45, 'Вы - хранитель знаний?', 'Да, это мне подходит.', 'Нет. Я еще не определился.', NULL, NULL, '3000', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(42, 6, 30, 'Прежде чем взяться за дело, необходимо?', 'Выпить пару банок пива.', 'Хорошенько поесть и вздремнуть.', 'Собрать всю информацию.', 'Позаботиться об оружии.', '10', '10', '31', '10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(43, 6, 31, 'Ваше чутьё подсказывает вам, что сейчас перед вами не просто место преступления. Это какая-то чертовщина.', 'Всё внимание на реальных вещах.', 'Вполне возможно.', 'Где-то я уже видел этот символ...', 'Сделать пометку в дневнике.', '32', '32', '32', '10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(44, 6, 32, 'После того, как вас отстранили от полицейской деятельности...', 'Занимался частным сыском.', 'Изучал паранормальное.', 'Путешествия, отдых.', 'Написал автобиографию.', '33', '33', '0', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(45, 6, 33, 'Ваш пыткий ум, желание добиться справедливости и опыт работы в полиции - позволяют вам?', 'Принимать быстрые решения.', 'Пользоваться базой данных.', 'Лицензия на оружие. Вопросы?', 'Меня отличают лидерские качества.', '34', '34', '10', '10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(46, 6, 34, 'Перед вами какое-то существо. Оно пытается выйти на контакт и просит о чём-то.', 'Пристрелить.', 'Разобраться в ситуации.', 'Убежать.', 'Сообщить в полицию.', '10', '35', '0', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(47, 6, 35, 'Вы - детектив?', 'Да. Так и есть.', 'Нет. Я еще не определился.', NULL, NULL, '2000', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(48, 7, 0, 'Это вступительное задание. Просто чтобы показать, как это все выглядит.  Как вы думаете - есть ли смысл в смысле, в котором нет смысла?', 'Есть.', 'Нет.', 'Бред.', 'Что?', '10', '10', '10', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(49, 7, 10, 'Вы слышали что-нибудь о Дине и Сэме Винчестерах?', 'Да.', 'Нет.', 'Возможно.', 'Кто это?', '11', '0', '11', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(50, 7, 11, 'Сэм и Дин - герои или же всё-таки оболтусы, которые всё время попадают в такие ситуации, которые сами спровоцировали?', 'Они герои!', 'Нет, они дураки.', NULL, NULL, '12', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(51, 7, 12, 'Омара и Чак далеко за пределами вселенной. Что сейчас происходит?', 'Война между ангелами и демонами.', 'Буйство Михаила из параллельной вселенной.', 'Возрождение культа Люцифера.', 'Мне плевать.', '13', '13', '13', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(52, 7, 13, 'Спасибо за потраченное время. Вот ваша награда.', 'Забрать награду.', NULL, NULL, NULL, '100', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(53, 9, 0, 'Вы взялись за дело о полтергейсте на улице Отваги', 'Расспросить местных.', 'Почитать газеты.', 'Поискать в интернете.', 'Оставить все как есть.', '10', '10', '10', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(54, 9, 10, 'Вы не нашли никакой конкретной информации.', 'Посетить бар охотников.', 'Опросить свидетеля.', 'Не моё это.', NULL, '20', '11', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(55, 9, 11, 'Свидетель пьян. Никакой конкретики.', 'Посетить бар охотников.', 'Не моё это.', NULL, NULL, '20', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(56, 9, 20, 'В баре никого нет. Однако на стойке лежит записка с пометкой по дому на улице Отваги.', 'Изучить.', 'Заказать выпивку.', 'Уйти домой.', '', '21', '0', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(57, 9, 21, 'В записке говорится, что скорее всего это плод больного воображения и дело не стоит внимания, свидетельства говорят о том, что в доме слышен постоянный шорох. Хотя проверить конечно стоило-бы...', 'Шорох? Просто крысы.', 'Нет, это не полтергейст.', 'Пойти домой.', NULL, '22', '22', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(58, 9, 22, 'Действительно странно. Полтергейст и призраки проявляют себя по другому. ЭМП пока нет, но можно просто сходить на разведку.', 'Посетить дом на улице Отваги.', 'Оставить это дело.', NULL, NULL, '23', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(59, 9, 23, 'Дом старый, но ничего не выдает полтергейст. Ваши знания говорят о том, что это просто крысы.', 'Дело закрыто.', 'Что-то не то...', NULL, NULL, '100', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(60, 10, 0, 'Это место таит в себе вопросов больше, чем ответов...', 'Отправиться на разведку в клуб', 'Искать информацию о пропавших', 'Отправиться в библиотеку', 'Не хочу в это ввязываться', '10', '20', '30', '0', NULL, NULL, NULL, NULL, NULL, NULL, '1', '26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(61, 10, 10, 'Вы приблизились к клубу "Пищевая цепь", однако все двери закрыты. Стоящий мужчина в кожанной куртке попросил вас покинуть частную территорию.', 'Развернутся и уйти.', 'Показать документы.', NULL, NULL, '0', '50', NULL, NULL, NULL, NULL, NULL, '1', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(62, 10, 20, 'Вы узнали, что все пропавшие были туристами. В одной из заметок местной желтой газеты говорится о том, что их приглашали на "экстремальный туризм"...', 'Поискать информацию об этом.', 'Опросить местных', 'Пойти спать.', NULL, '21', '21', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(63, 10, 21, 'Удалось узнать не многое, однако теперь есть зацепка - Елена Петрова - владелец интернет ресурса, предлагающего этот "экстремальный туризм"', 'Выйти с ней на контакт.', 'Уйти.', NULL, NULL, '30', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(64, 10, 30, 'Красивая женщина встречает вас улыбкой, но вы видите лукавый взгляд. "Чем я могу помочь?"', 'Что за "экстремальный туризм"?', 'Что вы знаете о "Пищевой цепи"?', 'Можно пригласить вас на свидание?', 'До свидания!', '31', '32', '33', '21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(65, 10, 31, 'Елена: "О это отличный способ провести время в нашем городе. Или даже единственное, что действительно может вас заинтересовать."', 'А конкретнее?', 'До свидания!', NULL, NULL, '35', '21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(66, 10, 33, 'Женщина меняется в лице и просит вас уйти.', 'До свидания!', NULL, NULL, NULL, '21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(67, 10, 32, 'Елена: "Не думаю, что это уместно. Я замужем и у меня двое детей. Да и интереса вы не вызываете."', 'До свидания!', NULL, NULL, NULL, '21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(68, 10, 35, 'Елена: "Если вы заинтересованы, вы должны пройти в кабинет нашего фельдшера и сдать анализ крови, после этого мы сможем продолжить разговор..."', 'Спасибо, пойду сдам анализ.', '(Осмыслить) Кровь? Странно. Отказать.', NULL, NULL, '36', '40', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(69, 10, 36, 'Елена: "Извините за эту процедуру... Так...так... Извините, вы не можете участвовать в нашей программе туризма..."', 'До свидания!', NULL, NULL, NULL, '36', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(70, 10, 40, 'Размышляя, вы находите всё это странным. Кровь. Странное чувство тревоги... ', 'Я думаю что это вампиры...', 'Нет, что-то не понимаю.', NULL, NULL, '41', '0', NULL, NULL, '2', '4', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(71, 10, 41, 'Имеем что имеем. Достаточно информации. Нужно подготовится. ', 'Просить помощи в баре охотников.', 'Пойти спать', NULL, NULL, '99', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(72, 10, 99, 'Вас встречает Чирик - связной охотников. Он дает вам пару советов и просит поискать кое-какое снаряжение для дела. ', 'Спасибо за помощь, Чирик.', NULL, NULL, NULL, '100', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(73, 10, 50, 'Сиплым голосом и с какой-то издевкой он говорит, что нужен ордер. Однако советует обратится в турагенство "Ночной путь". Поговорить с женщиной по имени Елена Петрова. Узнать про "экстремальный туризм"', 'Попрощаться', NULL, NULL, NULL, '30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(74, 10, 100, 'Нужно подготовится, как посоветовал Чирик.', 'Завершить задание', NULL, NULL, NULL, '110', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(75, 11, 0, 'Значит нечисть... Пора поразмыслить и найти подходящее оружие.', 'В Бар охотников, к Чирику!', 'В магазин мелочей', 'Отдохнуть. Все успеется', 'Меня это не интересует.', '10', '20', '30', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(76, 11, 10, 'Вы встретили Чирика. Однако он сидит с задумчивым видом и не хочет с вами общаться.', 'Предложить махорку.', 'Эй, Чирик - ответь-ка мне!', 'Осмотреться вокруг.', 'Уйти отсюда.', '11', '15', '40', '0', '1', '31', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(77, 11, 11, 'Чирик слегка улыбнулся, поблагодарил вас. Сделал глубокую затяжку: - Слушай, а это то что нужно... Не хочешь побыть "волонтером" на частоте этого города? Тяжело быть связным и мне бы пригодилась любая помощь.', 'Сделать затяжку и согласится.', 'Нет. Не интересно.', NULL, NULL, '12', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(78, 11, 12, 'Чирик хлопнул вас по плечу. Достал из рюкзака портативную радиостанцию и дал её вам.', 'Спасибо Чирик, что мне с этим делать?', 'Всё понятно, спасибо.', NULL, NULL, '13', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(79, 11, 13, 'Он смотрит на вас, откашливается после крепкой затяжки и улыбаясь говорит: Да ничего сложного, просто когда ты будешь один, ты будешь получать сводки о проишествиях. Твоя задача - раздать их охотникам. ', 'Всё понятно, пока!', 'А какой мне с этого прок?', NULL, NULL, '0', '13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(80, 11, 13, 'Прок? Ну-ну...', 'Понятно.', NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(81, 11, 15, 'Чирик не оборачивается и монотонно, на выдохе говорит: Не хочу разговаривать.', 'Понятно.', NULL, NULL, NULL, '10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(82, 11, 20, 'В Магазине мелочей - ревизия. Рядом стоит подозрительный парень, с ним явно что-то не то...', 'Уйти.', 'Подойти к парню', NULL, NULL, '0', '21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(83, 11, 30, 'Вы неплохо отдохнули. ', 'Вспоминается Безумный Макс...', 'Приятный сон.', NULL, NULL, '50', '0', NULL, NULL, '2', '5', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(84, 11, 50, 'Вы уходите за границу города, долго ищете лесок и вот находите эту сторожку...', 'Постучаться в дверь', 'Постучаться в окно', 'Вышибить дверь с ноги.', 'Уйти.', '51', '51', '52', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(85, 11, 51, 'Никого нет. Вы осторожно открываете дверь, осматриваетесь и видите то самое место.', 'Взять оружие.', 'Взять орудие.', 'Мне это не нужно.', NULL, '52', '53', '-250', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(86, 11, 52, 'Появляется неизвестный.', 'Вступить в бой.', 'Бежать.', NULL, NULL, '90', '-90', NULL, NULL, '3', '9', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(87, 11, -250, 'Мне не нужно это, я решу этот вопрос по другому... ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(88, 11, 53, 'ВЫ смотрите на тесак и вас немного трясёт. Вы чувствуете всю его боль... Боль бесполезности в мире людей, но вам кажется, что вы находите общий язык...', 'Осмотреть внимательно', 'Кинуть в сумку', NULL, NULL, '54', '100', NULL, NULL, '1', '20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(89, 11, 54, 'Вы замечаете непонятные символы.', 'Не работает', 'Не работает', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(90, 11, 90, 'Вы уже пытаетесь добить нападающего, но внезапно падаете без сил. Тут-же вы просыпаетесь в своем номере. На журнальном столике лежит тесак.', 'Осмотреть внимательно.', 'Кинуть в сумку.', NULL, NULL, '54', '100', NULL, NULL, '1', '20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(91, 11, 100, 'Приготовления закончены, пора в бой.', 'Завершить приготовления.', NULL, NULL, NULL, '110', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(92, 11, 40, 'Допилить!', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(93, 11, 21, 'Он пристально с прищуром смотрит на вас и улыбаясь говорит: - О, тебя я то и жду. На держи. Он протягивает вам какой-то маленький сверток.', 'Спасибо, что это?', 'Мне это не нужно.', NULL, NULL, '22', '20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 31, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(94, 11, 22, 'Парень: - Да ничего особенного. Просто небольшая порция "успокоительного", если ты понимаешь о чем я. Парень уходит.', 'Уйти.', NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(95, 4, 0, 'Вы приступили к выполнению задания. Ваши действия:', 'Искать информацию', 'Изучить городскую хронику', 'Сходить в бар охотников', 'Пока не интересно', '10', '10', '20', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(96, 4, 10, 'В сводках за последние 10 лет, указано, что такие случаи уже бывали. Все они произошли в одном месте, неподалеку от фермерского хозяйства "Инсекта".', 'Поискать информацию в интернет', 'Сходить на ферму', 'Сходить в бар охотников.', 'Разберусь позже.', '11', '30', '20', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(97, 4, 20, 'В баре никого нет. Бармен смотрит на вас вопросительно.', 'Спросить про саранчу', 'Заказать выпивку', 'Уйти', NULL, '21', '25', '0', NULL, '', '', NULL, '5', '50', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 1, 32, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(98, 4, 21, 'Бармен удивлён: - Слушай, не лезь в это дело... Много охотников уже сломало голову и никто ничего толком не может выяснить.', 'Так в чём-же всё-таки дело?', 'Хорошо.', NULL, NULL, '22', '20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(99, 4, 22, 'Бармен, протирая стойку смотрит на вас: - Ну ладно. В общем, от Чирика я слышал, что это какой-то лесной дух, который кормится каждое третье лето, но проблема в том, что никто не знает где он и как с ним бороться.', 'Лесной дух... Спасибо.', 'Заказать выпивку.', NULL, NULL, '30', '25', NULL, NULL, '5', '50', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 1, 32, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(100, 4, 25, 'Бармен ныряет под стойку и через некоторое время достаёт пыльную бутылку: - Вот. Чирик сказал, что это может кому-нибудь пригодится, кто будет спрашивать про саранчу. Не знаю зачем...', 'Спасибо, пойду на разведку', NULL, NULL, NULL, '30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(101, 4, 30, 'Вы приходите на ферму, но здесь как-то пусто. Вы видите человека в соломенной шляпе, который ковыряется в тракторе.', 'Подойти', 'Осмотреться вокруг', NULL, NULL, '31', '35', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(102, 4, 31, 'Человек машет вам рукой и кричит: Здравствуй друже, что тебя привело ко мне на ферму? Меня зовут Роман. Какое у тебя дело?', 'Спросить про саранчу.', 'Развернуться и уйти', NULL, NULL, '32', '30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(103, 4, 32, 'Он недоверчиво смотрит на вас: - Послушай, друже... Не знаю что там и как, но ко мне это отношения не имеет. Здесь уже было достаточно народу, кто спрашивает об этом. Ничего не знаю, ничего не скажу.', 'Предложить выпить.', 'Хорошо (Уйти)', NULL, NULL, '40', '30', NULL, NULL, '1', '32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(104, 4, 35, 'Вы осмотрелись, но ничего подозрительного не обнаружили.', 'Подойти к фермеру.', 'Уйти', NULL, NULL, '31', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(105, 4, 40, 'Фермер Роман, снимает шляпу: - Ох, это ты хорошо предложил. Моя то на запах не переносит спиртное, а мне иногда нужно, понимаешь? - Фермер делает глоток Грога.', 'Твоя жена?', 'Уйти', NULL, NULL, '41', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(106, 4, 41, 'Роман: - Да, жена. Живём с ней уж... ну лет 20 наверное. У меня хозяйство загибалось, а вот её встретил в лесу, заблудившуюся - без памяти, домой привёл и всё плодородить начало. ', 'А где её найти?', 'Уйти', NULL, NULL, '42', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(107, 4, 42, 'Роман: - В хлеву верно, со скотиной возится. Хочешь пообщаться? Сходи. Я вообще не люблю когда она с незнакомцами разговаривает, но ты для меня сейчас вроде знакомый. Так-что сходи.', 'Хорошо( Пойти в хлев)', 'Уйти', NULL, NULL, '43', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(108, 4, 43, 'Вы находите тот самый хлев, заходите внутрь и видите женщину. Что-то вам не нравится в ней. Женщина: - Ты кто такой? Что тебе нужно? Уходи!', 'Хорошо(Уйти)', 'Я хочу спросить...', NULL, NULL, '0', '44', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(109, 4, 44, 'Женщина: Никаких разговоров. Уходи.', 'Хорошо(Уйти)', 'Подойти ближе', NULL, NULL, '0', '45', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(110, 4, 45, 'Женщина: - Я закричу. Не подходи! - вы приближаетесь к женщине и видите, что это не женщина. Это какое-то человекоподобное насекомое.', 'Облить её остатками Грога', 'Убежать', NULL, NULL, '90', '-100', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(111, 4, 90, 'Существо зашипело и начало биться в конвульсиях. Через некоторое время оно расстаяло, оставив только одежду и лужу непонятной субстанции.', 'Посыпать солью и сжечь.', 'Убежать.', NULL, NULL, '100', '-100', NULL, NULL, '1', '25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(112, 4, 100, 'Вы сделали то, что делают все охотники. ', 'Завершить задание.', NULL, NULL, NULL, '110', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
/*!40000 ALTER TABLE `quest_variants` ENABLE KEYS */;

-- Дамп структуры для таблица bot_game.queue_info
DROP TABLE IF EXISTS `queue_info`;
CREATE TABLE IF NOT EXISTS `queue_info` (
  `id` bigint(20) NOT NULL,
  `player_id` bigint(20) DEFAULT NULL,
  `dungeon_id` int(11) DEFAULT NULL,
  `start_time` text,
  `end_time` text,
  `player_status` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы bot_game.queue_info: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `queue_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `queue_info` ENABLE KEYS */;

-- Дамп структуры для таблица bot_game.queue_rooms
DROP TABLE IF EXISTS `queue_rooms`;
CREATE TABLE IF NOT EXISTS `queue_rooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dungeon_id` int(11) DEFAULT '0',
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы bot_game.queue_rooms: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `queue_rooms` DISABLE KEYS */;
/*!40000 ALTER TABLE `queue_rooms` ENABLE KEYS */;

-- Дамп структуры для таблица bot_game.radio
DROP TABLE IF EXISTS `radio`;
CREATE TABLE IF NOT EXISTS `radio` (
  `id` int(11) NOT NULL,
  `name` varchar(45) COLLATE utf8_bin NOT NULL,
  `location` varchar(45) COLLATE utf8_bin NOT NULL,
  `global` varchar(45) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Дамп данных таблицы bot_game.radio: ~4 rows (приблизительно)
/*!40000 ALTER TABLE `radio` DISABLE KEYS */;
REPLACE INTO `radio` (`id`, `name`, `location`, `global`) VALUES
	(1, 'Рок ФМ', '0', '1'),
	(2, 'Наше Радио', '0', '0'),
	(3, 'Полицейская волна', '0', '0'),
	(4, 'Юмор FM', '0', '1');
/*!40000 ALTER TABLE `radio` ENABLE KEYS */;

-- Дамп структуры для таблица bot_game.recipes
DROP TABLE IF EXISTS `recipes`;
CREATE TABLE IF NOT EXISTS `recipes` (
  `id` int(11) NOT NULL,
  `create_item` int(11) NOT NULL,
  `item_id_1` int(11) NOT NULL,
  `item_id_2` int(11) NOT NULL,
  `item_id_3` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы bot_game.recipes: ~4 rows (приблизительно)
/*!40000 ALTER TABLE `recipes` DISABLE KEYS */;
REPLACE INTO `recipes` (`id`, `create_item`, `item_id_1`, `item_id_2`, `item_id_3`) VALUES
	(1, 13, 10, 11, 0),
	(2, 7, 12, 9, 0),
	(1, 13, 10, 11, 0),
	(2, 7, 12, 9, 0);
/*!40000 ALTER TABLE `recipes` ENABLE KEYS */;

-- Дамп структуры для таблица bot_game.rumors
DROP TABLE IF EXISTS `rumors`;
CREATE TABLE IF NOT EXISTS `rumors` (
  `id` int(11) DEFAULT NULL,
  `link` text,
  `text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы bot_game.rumors: ~5 rows (приблизительно)
/*!40000 ALTER TABLE `rumors` DISABLE KEYS */;
REPLACE INTO `rumors` (`id`, `link`, `text`) VALUES
	(1, 'Бессмертный бог', 'Я слышал о каком-то поклонение некому Богу Смерти, но есть у меня предположения, что на самом деле это все чушь. На самом деле это самыйй бесполезный культ, который имеет место на существование.'),
	(2, 'Существо', 'Странное дело, но есть поверие, что каждое полнолуние, а того и чаще на деревню, что неподалеку совершается набег какого-то существа. Говорят это Чупокабра.'),
	(3, 'Призрак трассы е95', 'Это неупокоенный дух Мечтательной Даши. Её изнасиловали на этой трассе и жестоко убили. Она похоронена на местном кладбище.'),
	(4, 'Таинственные исчезновения', 'Ходят слухи, что в пригороде, на выезде в сторону ферм - пропадают люди. За последние полгода пять или шесть человек. У меня есть предположение, что это как-то связано с ночным клубом "Пищевая цепь". Один мужик в баре, сказал что это сволочные вампиры. Я то конечно не верю в это, но может тебе это пригодится.'),
	(5, 'О Безумном Максе...', 'Не знаю, интересно тебе или нет, но недавно в городе был один парень... Назвался Максом. Выглядел странно, от него жутко пахло бензином. У него был блестящий... не знаю - нож или меч... Что-то такое. Он сказал, что у него здесь какое-то дело. Он направился в сторожку лесника. Зачем правда не сказал, но может тебе интересно?');
/*!40000 ALTER TABLE `rumors` ENABLE KEYS */;

-- Дамп структуры для таблица bot_game.sublocations
DROP TABLE IF EXISTS `sublocations`;
CREATE TABLE IF NOT EXISTS `sublocations` (
  `id` int(11) DEFAULT NULL,
  `location` int(11) DEFAULT NULL,
  `name` text,
  `fatigue_loose` text,
  `isShop` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы bot_game.sublocations: ~5 rows (приблизительно)
/*!40000 ALTER TABLE `sublocations` DISABLE KEYS */;
REPLACE INTO `sublocations` (`id`, `location`, `name`, `fatigue_loose`, `isShop`) VALUES
	(1, 0, 'Бар проходимцев', '10', NULL),
	(2, 0, 'Кладбище', '10', NULL),
	(3, 0, 'Магазин мелочей', '10', 1),
	(4, 0, 'Библиотека', '20', NULL),
	(0, 0, 'Центр города', '5', NULL);
/*!40000 ALTER TABLE `sublocations` ENABLE KEYS */;

-- Дамп структуры для таблица bot_game.vehicles
DROP TABLE IF EXISTS `vehicles`;
CREATE TABLE IF NOT EXISTS `vehicles` (
  `id` int(11) NOT NULL,
  `name` varchar(45) COLLATE utf8_bin NOT NULL,
  `fuel` varchar(45) COLLATE utf8_bin NOT NULL,
  `speed` varchar(45) COLLATE utf8_bin NOT NULL,
  `comfort` varchar(45) COLLATE utf8_bin NOT NULL,
  `type` varchar(45) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Дамп данных таблицы bot_game.vehicles: ~3 rows (приблизительно)
/*!40000 ALTER TABLE `vehicles` DISABLE KEYS */;
REPLACE INTO `vehicles` (`id`, `name`, `fuel`, `speed`, `comfort`, `type`) VALUES
	(1, 'Запорожец', '100', '50', '1', '1'),
	(2, 'Ваз 2110', '110', '70', '1', '1'),
	(3, 'Chevrolete Impala 1967', '150', '100', '5', '1');
/*!40000 ALTER TABLE `vehicles` ENABLE KEYS */;

-- Дамп структуры для таблица bot_game.vehicle_shop
DROP TABLE IF EXISTS `vehicle_shop`;
CREATE TABLE IF NOT EXISTS `vehicle_shop` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `location` int(11) NOT NULL,
  `price` varchar(45) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Дамп данных таблицы bot_game.vehicle_shop: ~2 rows (приблизительно)
/*!40000 ALTER TABLE `vehicle_shop` DISABLE KEYS */;
REPLACE INTO `vehicle_shop` (`id`, `vehicle_id`, `location`, `price`) VALUES
	(1, 1, 0, '1000'),
	(2, 2, 0, '1500');
/*!40000 ALTER TABLE `vehicle_shop` ENABLE KEYS */;

-- Дамп структуры для таблица bot_game.vendor_shop
DROP TABLE IF EXISTS `vendor_shop`;
CREATE TABLE IF NOT EXISTS `vendor_shop` (
  `id` int(11) NOT NULL,
  `location` varchar(45) COLLATE utf8_bin NOT NULL,
  `item` varchar(45) COLLATE utf8_bin NOT NULL,
  `count` varchar(45) COLLATE utf8_bin NOT NULL,
  `price` varchar(45) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Дамп данных таблицы bot_game.vendor_shop: ~11 rows (приблизительно)
/*!40000 ALTER TABLE `vendor_shop` DISABLE KEYS */;
REPLACE INTO `vendor_shop` (`id`, `location`, `item`, `count`, `price`) VALUES
	(1, '0', '3', '1', '100'),
	(2, '0', '5', '1', '200'),
	(3, '0', '7', '1', '300'),
	(4, '0', '12', '1', '30'),
	(5, '0', '15', '1', '10'),
	(6, '0', '16', '1', '20'),
	(7, '0', '17', '1', '50'),
	(8, '0', '19', '1', '300'),
	(9, '0', '6', '1', '10'),
	(10, '0', '13', '1', '50'),
	(11, '0', '26', '1', '100');
/*!40000 ALTER TABLE `vendor_shop` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
