<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Webhook extends App_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('chat_module/chat_model');
	}

	public function receive_message()
	{
		$json = file_get_contents('php://input');
		$data = json_decode($json, true);

		if (isset($data['phone_number']) && isset($data['message'])) {
			$phone_number = $data['phone_number'];
			$message = $data['message'];
			$lead_id = $this->chat_model->find_lead_by_phone_number($phone_number);
			if ($lead_id) {
				$this->chat_model->save_message([
					'lead_id' => $lead_id,
					'phone_number' => $phone_number,
					'message' => $message,
					'direction' => 'incoming',
					'timestamp' => date('Y-m-d H:i:s')
				]);
				echo json_encode(['status' => 'success']);
			} else {
				echo json_encode(['status' => 'error', 'message' => 'Lead not found']);
			}
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
		}
	}
}
