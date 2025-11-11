<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PageTranslator
{
    protected $CI;
    protected $endpoint;
    protected $apiKey;
    protected $timeout;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->database();
        $this->CI->load->config('translator');
        $conf = $this->CI->config->item('translator');
        $this->endpoint = $conf['endpoint'];
        $this->apiKey   = $conf['api_key'];
        $this->timeout  = $conf['timeout'];
    }

    public function translate_full_html($html, $source='id', $target='en')
    {
        // Lindungi blok yang tidak boleh diterjemahkan
        $placeholders = [];
        $htmlProtected = preg_replace_callback(
            '#<(script|style|code|pre)(.*?)>(.*?)</\1>#si',
            function($m) use (&$placeholders){
                $key = '__BLOCK__'.sha1($m[0]).'__';
                $placeholders[$key] = $m[0];
                return $key;
            },
            $html
        );

        $translated = $this->call_api($htmlProtected, $source, $target, 'html');

        if ($translated){
            foreach ($placeholders as $k=>$v){
                $translated = str_replace($k, $v, $translated);
            }
        } else {
            $translated = $html; // fallback jika API gagal
        }
        return $translated;
    }

    protected function call_api($text, $source, $target, $format='html')
    {
        $payload = [
            'q'      => $text,
            'source' => $source,
            'target' => $target,
            'format' => $format
        ];
        if (!empty($this->apiKey)){
            $payload['api_key'] = $this->apiKey;
        }
        $ch = curl_init($this->endpoint);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_TIMEOUT        => $this->timeout,
        ]);
        $res = curl_exec($ch);
        if ($res === false){
            curl_close($ch);
            return null;
        }
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($code >=200 && $code <300){
            $data = json_decode($res,true);
            if (isset($data['translatedText'])){
                return $data['translatedText'];
            }
        }
        return null;
    }

    public function get_cached($url_hash, $lang)
    {
        return $this->CI->db->get_where('page_translations', [
            'url_hash' => $url_hash,
            'lang'     => $lang
        ])->row_array();
    }

    public function cache_translation($url_hash, $url_full, $lang, $original, $translated)
    {
        $data = [
            'url_hash'        => $url_hash,
            'url_full'        => $url_full,
            'lang'            => $lang,
            'html_original'   => $original,
            'html_translated' => $translated
        ];
        $existing = $this->get_cached($url_hash, $lang);
        if ($existing){
            $this->CI->db->where('id', $existing['id'])->update('page_translations', $data);
        } else {
            $this->CI->db->insert('page_translations', $data);
        }
    }

    public function invalidate_by_url_pattern($pattern)
    {
        $this->CI->db->like('url_full', $pattern);
        $rows = $this->CI->db->get('page_translations')->result_array();
        foreach ($rows as $r){
            $this->CI->db->where('id',$r['id'])->delete('page_translations');
        }
    }
}