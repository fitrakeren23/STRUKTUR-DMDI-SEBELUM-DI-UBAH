<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('current_full_url')){
    function current_full_url(){
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
        return $scheme.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }
}

if (!function_exists('active_lang')){
    function active_lang(){
        $CI =& get_instance();
        $lang = $CI->session->userdata('site_lang');
        if (!$lang){
            $lang='id';
            $CI->session->set_userdata('site_lang','id');
        }
        return $lang;
    }
}