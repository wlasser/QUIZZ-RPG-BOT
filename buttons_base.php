<?php
// /["red", "blue", "white", "green"],
const BTN_START =  [["actions" => 'Create'], character_message::CHARACTER_CREATE_BTN_TEXT, "red"]; //Код кнопки

const BTN_BACK = [["actions"=>'back'], main_messages::BACK_BUTTON_TEXT, "red"];
const BTN_CONTINUE = [["actions"=>'continue'], main_messages::CONTINUE_BUTTON_TEXT, "red"];
const BTN_START_FIRST = [["actions"=>'start_first'], "Выбрать свою роль!", "green"];
const BTN_CREATE_CHARACTER_MALE = [["actions"=>'select_sex_male'], character_message::CHARACTER_MALE, "red"];
const BTN_CREATE_CHARACTER_FEMALE = [["actions"=>'select_sex_female'], character_message::CHARACTER_FEMALE, "blue"];
const BTN_CREATE_CHARACTER_OTHER = [["actions"=>'select_sex_lol'], character_message::CHARACTER_IDIOT, "green"];
//const BTN_FIRST_AVAILABLE_QUEST = [["choise"=>'select_sex_lol'], character_message::CHARACTER_IDIOT, "green"];