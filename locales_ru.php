<?php 
interface character_message
{
    CONST CHARACTER_MALE = 'Мужчина';
    CONST CHARACTER_FEMALE = 'Женщина';
    CONST CHARACTER_IDIOT = 'Не определился';
    CONST CHARACTER_RETURN_TEXT = 'Простите, но ничем не можем помочь.';
    CONST CHARACTER_CREATE_BTN_TEXT = "Cоздать персонажа";
    const KNOW_MORE = "Узнать больше";
    CONST CHARACTER_SELECT_SEX_MESSAGE = "Выберите свой пол. Это не столь категорично, но все мы играем какие-то роли...";
    CONST CHARACTER_START_COMMAND = "начать";
    CONST CHARACTER_CONTINUE_TEXT = "Продолжить? Это действие необходимо для продолжения.";
    const CHARACTER_SET_NAME_TEXT = "Назовите своего персонажа. Для этого введите команду Назвать Имя Фамилия, где Имя и Фамилия - имя и фамилия персонажа.";
    //const CHARACTER_CREATE_COMMAND = "";
    CONST CHARACTER_START_MESSAGE = 'Вы уверены что хотите играть? Нажимая кнопку "Создать персонажа" мы получим некоторую информацию о вашей учетной записи. Идентификатор и прочее, для хранения на своем сервере.'; 
}
interface main_messages
{
    CONST BACK_BUTTON_TEXT = "Назад";
    const CONTINUE_BUTTON_TEXT = "Продолжить";
    const BUTTON_START_TEXT = "Начать задание";
}
interface messages_strings
{
    CONST GO = "Перейти :"; 
    const FATIGUE = " - усталость: ";
    const PLAYERS_AROUND = "Игроки вокруг: ";
    const SMS_COMMAND_TEXT = "Для отправки сообщения игроку введите: смс номер игрока";
    const NOTHING_HERE = "Никого нет. ";
    const MESSAGE_FROM = "Сообщение от ";
    const MESSAGE_SENDED = "Сообщение отправлено.";
    const NAME_ERROR = "Не должно быть пустого имени. Имя и фамилия должны быть больше 5 символов!";
    const YOUR_QUESTS = "Ваши задания: ";
    const START_BATTLE = "Вступить в бой";
    const OPERATOR_PROF_SELECTED = "Вы стали оператором.";
}