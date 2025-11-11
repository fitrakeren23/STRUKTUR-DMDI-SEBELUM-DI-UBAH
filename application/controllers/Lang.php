<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lang extends CI_Controller {

    public function switch($lang='id')
    {
        $lang = strtolower($lang);
        if (!in_array($lang,['id','en'])) $lang='id';
        $this->load->library('session');
        $this->session->set_userdata('site_lang',$lang);

        $ref = $this->input->get('ref');
        if ($ref && filter_var($ref, FILTER_VALIDATE_URL)){
            redirect($ref);
        } else {
            redirect(base_url());
        }
    }
}