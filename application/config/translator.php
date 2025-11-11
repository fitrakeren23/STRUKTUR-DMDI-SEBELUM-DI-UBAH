<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['translator'] = [
    'provider' => 'libre',
    'endpoint' => 'https://libretranslate.com/translate', // ganti jika pakai self-host / provider lain
    'api_key'  => '',     // isi jika endpoint butuh API key
    'timeout'  => 20,     // detik
];