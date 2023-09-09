<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main_library
{

	public function check_login()
	{
		$_this = &get_instance();
		if ($_this->session->userdata('login') != TRUE) {
			redirect(base_url('login/logout'));
		}
	}

	public function check_level($lvl = '')
	{
		$_this = &get_instance();
		if (!in_array($lvl, $_this->session->userdata('group'))) {
			redirect(base_url('login/logout'));
		}
	}
}

/* End of file main_library.php */
/* Location: ./application/libraries/main_library.php */