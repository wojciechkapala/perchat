<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chat_model extends CI_Model {

	public function getMessages()
	{
		$this->db->select('*');
		$this->db->from('tblchat_messages');
		$query = $this->db->get();
		return $query->result();
	}

	public function send_message($phone_number, $message)
	{
		$curl = curl_init();

		curl_setopt_array($curl, [
			CURLOPT_URL => 'https://wa.botmate.us/api/create-message',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => [
				'appkey' => '62f76f47-efe7-4d13-9f35-9299f2161b6b',
				'authkey' => 'xpMZ6txNKBTnKUrlUlTdclIMZCjZNGlMD5VLzrSKaMHsYBIhyk',
				'to' => $phone_number,
				'message' => $message
			],
			CURLOPT_HTTPHEADER => [
				'Content-Type: multipart/form-data'
			],
		]);

		$response = curl_exec($curl);
		curl_close($curl);

		// Zapisz wiadomość do bazy danych
		$data = [
			'phone_number' => $phone_number,
			'message' => $message,
			'direction' => 'outgoing',
			'timestamp' => date('Y-m-d H:i:s')
		];
		$this->db->insert('tblchat_messages', $data);

		return $response;
	}
}
