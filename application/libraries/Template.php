<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template {

    var $template_data = array();

    function set($name, $value)
    {
        $this->template_data[$name] = $value;
    }

    function load($template = '', $view = '' , $view_data = array(), $return = FALSE)
    {
        $CI =& get_instance();

        // Render konten view ke buffer
        $content_html = $CI->load->view($view, $view_data, TRUE);
        $this->set('contents', $content_html);

        // Render template utama ke buffer
        $output = $CI->load->view($template, $this->template_data, TRUE);

        // === Global Translation Hook ===
        // Pastikan session aktif
        $CI->load->library('session');
        $lang = $CI->session->userdata('site_lang');
        if (!$lang){
            $lang = 'id';
            $CI->session->set_userdata('site_lang','id');
        }

        if ($lang === 'en'){
            // Fingerprint halaman saat ini
            $fullUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off' ? 'https://' : 'http://')
                        .$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            $templateName = $template ?: 'default';
            $hash = sha1(strtolower($fullUrl.'|'.$templateName));

            // Load PageTranslator & cache DB
            $CI->load->library('PageTranslator');
            $cached = $CI->pagetranslator->get_cached($hash,'en');
            if ($cached){
                $output = $cached['html_translated'];
            } else {
                // Translate HTML penuh (format=html, protect script/style/code/pre)
                $translated = $CI->pagetranslator->translate_full_html($output, 'id', 'en');
                // Simpan cache
                $CI->pagetranslator->cache_translation($hash, $fullUrl, 'en', $output, $translated);
                $output = $translated;
            }

            // (Opsional) noindex halaman English
            // $output = str_ireplace('<meta name="robots" content="all,index,follow">','<meta name="robots" content="noindex,follow">',$output);
        }

        if ($return){
            return $output;
        }
        echo $output;
    }
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */