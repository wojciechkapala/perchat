<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Chat extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('app_modules');

		$this->load->model('Chat_model');
		$this->load->library('session');
		$this->load->model('staff_model');
		$this->load->model('projects_model');
		$this->load->library('app_object_cache');
		$this->load->library('assets/App_css');  // Załaduj bibliotekę zarządzającą CSS
		$this->load->library('assets/App_scripts');

		// Inicjalizacja właściwości data jako tablicy
		$this->data = array();

		// Ustawienie danych użytkownika dla widoków
		$this->data['current_user'] = $this->staff_model->get(get_staff_user_id());

		// Sprawdzanie, czy current_user został poprawnie załadowany
		if (!isset($this->data['current_user'])) {
			log_message('error', 'Current user data is not set.');
			show_error('Nie można załadować danych użytkownika.');
		}

		// Ustaw locale, jeśli używasz wielojęzyczności
		$this->locale = $this->session->userdata('locale') ?? 'pl_PL';
		$this->data['locale'] = $this->locale;
	}

	public function index()
	{
		if (!staff_can('view', 'chat')) {
			access_denied('chat');
		}

		$this->data['messages'] = $this->Chat_model->getMessages();
		$this->data['title'] = _l('chat');
		$this->data['startedTimers'] = array(); // Możesz zmienić na odpowiednie dane

		$this->load->view('admin/chat/index', $this->data); // Upewnij się, że ścieżka jest prawidłowa


	}

	public function sendMessage()
	{
		if (!staff_can('create', 'chat')) {
			access_denied('chat');
		}

		$phone_number = $this->input->post('phone_number');
		$message = $this->input->post('message');
		$response = $this->Chat_model->send_message($phone_number, $message);

		if ($response) {
			$this->session->set_flashdata('message', 'Message sent successfully');
			redirect(admin_url('chat'));
		} else {
			$this->session->set_flashdata('error', 'Failed to send message');
			redirect(admin_url('chat'));
		}
	}
}
