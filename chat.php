<?php

/**
 * Ensures that the module init file can't be accessed directly, only within the application.
 */
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Chat WhatsApp Custom
Description:
Version: 0.9
Requires at least: 2.3.*
*/

/**
 * Hook dla PerfexCRM do wykonania podczas instalacji modułu
 */
register_activation_hook('chat_module', 'chat_module_activation_hook');

function chat_module_activation_hook() {
	$CI =& get_instance();

	// Tworzenie tabeli chat_messages w bazie danych
	$CI->db->query("CREATE TABLE IF NOT EXISTS `chat_messages` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`lead_id` INT(11) NOT NULL,
		`phone_number` VARCHAR(20) NOT NULL,
		`message` TEXT NOT NULL,
		`direction` ENUM('incoming', 'outgoing') NOT NULL,
		`timestamp` DATETIME NOT NULL,
		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
}

hooks()->add_action('admin_init', 'chat_module_init_menu_items');
function chat_module_init_menu_items() {
	$CI = &get_instance();
	if (has_permission('chat', '', 'view')) {
		$CI->app_menu->add_sidebar_menu_item('chat-module', [
			'name'     => 'Czat z klientami', // Nazwa wyświetlana w menu
			'href'     => admin_url('admin/chat/index'), // Link do głównego kontrolera modułu
			'position' => 25, // Pozycja w menu, zmień zgodnie z potrzebami
			'icon'     => 'fa fa-comments', // Ikonka, np. z Font Awesome
		]);
	}
}

hooks()->add_action('app_admin_head', 'load_chat_resources');
function load_chat_resources() {
	echo '<link href="' . base_url('assets/css/chat_style.css') . '" rel="stylesheet" type="text/css">';
	echo '<script src="' . base_url('assets/js/chat_functions.js') . '"></script>';
}


/**
 * Hook dla PerfexCRM do wykonania podczas dezinstalacji modułu
 */
register_deactivation_hook('chat_module', 'chat_module_deactivation_hook');

function chat_module_deactivation_hook() {
	$CI =& get_instance();

	// Usuwanie tabeli chat_messages z bazy danych
	$CI->db->query("DROP TABLE IF EXISTS `chat_messages`");
}
