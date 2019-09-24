<?php
include 'framework/includes.php';

$game = new Game();

$game->checkActivity();
$game->updateFatigue();
