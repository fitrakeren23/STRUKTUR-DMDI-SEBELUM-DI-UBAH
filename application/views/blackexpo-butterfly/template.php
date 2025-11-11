<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $title; ?></title>

    <meta name="description" content="<?php echo $description; ?>">
    <meta name="keywords" content="<?php echo $keywords; ?>">
    <meta name="author" content="mycoding.net">
    <meta name="robots" content="all,index,follow">
    <meta http-equiv="Content-Language" content="id-ID">
    <meta NAME="Distribution" CONTENT="Global">
    <meta NAME="Rating" CONTENT="General">
    <link rel="canonical" href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>"/>

    <?php if ($this->uri->segment(1)=='berita' AND $this->uri->segment(2)=='detail'){
        $rows = $this->model_utama->view_where('berita',array('judul_seo' => $this->uri->segment(3)))->row_array();
        echo '<meta property="og:title" content="'.$title.'" />
              <meta property="og:type" content="article" />
              <meta property="og:url" content="'.base_url().''.$this->uri->segment(3).'" />
              <meta property="og:image" content="'.base_url().'asset/foto_berita/'.$rows['gambar'].'" />
              <meta property="og:description" content="'.$description.'"/>';
    } ?>

    <link rel="shortcut icon" href="<?php echo base_url(); ?>asset/images/<?php echo favicon(); ?>" />

    <link href="https://fonts.googleapis.com/css?family=Barlow+Semi+Condensed:600,700%7CNunito:300,400" rel="stylesheet">
    <link href="<?= base_url(); ?>template/<?= template(); ?>/css/animate.min.css" rel="stylesheet" media="screen">
    <link href="<?= base_url(); ?>template/<?= template(); ?>/css/fonts.css" rel="stylesheet" media="screen">
    <link href="<?= base_url(); ?>template/<?= template(); ?>/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href='<?= base_url(); ?>template/<?= template(); ?>/jssocials/jssocials.css' rel='stylesheet' type='text/css' />
    <link href='<?= base_url(); ?>template/<?= template(); ?>/jssocials/theme-flat.css' rel='stylesheet' type='text/css' />
    <link href="<?= base_url(); ?>template/<?= template(); ?>/css/style.css" rel="stylesheet" media="screen">

    <style>
        /* Styling kecil untuk tombol switch bahasa */
        .lang-switch-wrapper {
            position: fixed;
            top: 12px;
            right: 12px;
            z-index: 1100;
            display: flex;
            gap: 6px;
            font-family: 'Nunito', Arial, sans-serif;
        }
        .lang-switch-wrapper a {
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 13px;
            line-height: 1.2;
            font-weight: 600;
            border: 1px solid #444;
            background:#222;
            color:#fff;
            transition: all .2s;
        }
        .lang-switch-wrapper a.active {
            background:#ff4d17;
            border-color:#ff4d17;
        }
        .lang-switch-wrapper a:hover {
            background:#444;
            color:#fff;
        }
        @media (max-width: 640px){
            .lang-switch-wrapper { top: 8px; right: 8px; }
            .lang-switch-wrapper a { padding:5px 9px;font-size:12px; }
        }
    </style>
</head>

<body>
       <?php
  $currLang = function_exists('active_lang') ? active_lang() : 'id';
  $ref      = urlencode((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off'?'https://':'http://').$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
?>
<div class="lang-switch-wrapper" style="position:fixed;top:12px;right:12px;z-index:1100;display:flex;gap:6px;">
  <a href="<?php echo base_url('lang/id?ref='.$ref); ?>"
     style="text-decoration:none;padding:6px 12px;border-radius:4px;font-size:13px;background:#222;color:#fff;border:1px solid #444;<?php echo $currLang==='id'?'background:#ff4d17;border-color:#ff4d17;':''; ?>">
     ID
  </a>
  <a href="<?php echo base_url('lang/en?ref='.$ref); ?>"
     style="text-decoration:none;padding:6px 12px;border-radius:4px;font-size:13px;background:#222;color:#fff;border:1px solid #444;<?php echo $currLang==='en'?'background:#ff4d17;border-color:#ff4d17;':''; ?>">
     EN
  </a>
</div>
    <!-- /language switch -->

    <div id="fbt-content-overlay" onclick="closeNav()"></div>
    <form autocomplete="off" id="search" role="search">
        <div class="input">
            <input class="search" name="search" placeholder="Search..." type="text" />
            <button class="submit fa fa-search" type="submit" value=""></button>
        </div>
        <button id="close" type="reset" value="">×</button>
    </form>

    <div id="page-wrapper" class="magazine-view feed-view">
        <?php
            $this->load->view(template().'/header');
            echo $contents;
            $this->load->view(template().'/footer');
        ?>
    </div><!-- #page-wrapper end -->

    <script src="<?= base_url(); ?>template/<?= template(); ?>/js/jquery.min.js"></script>
    <script src="<?= base_url(); ?>template/<?= template(); ?>/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url(); ?>template/<?= template(); ?>/js/plugins.js"></script>
    <script src="<?= base_url(); ?>template/<?= template(); ?>/js/main.js"></script>
    <script src="<?= base_url(); ?>template/<?= template(); ?>/jssocials/jssocials.min.js"></script>
    <script>
        $("#share").jsSocials({
            shareIn: "popup",
            showLabel: true,
            showCount: true,
            shares: ["twitter", "intagram", "linkedin", "whatsapp"]
        });
    </script>
</body>
</html>