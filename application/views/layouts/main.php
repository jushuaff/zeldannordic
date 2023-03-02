<!DOCTYPE html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>NLRC | <?= $template['title'] ?></title>
    <meta name="description" content="<?php $this->load->view('seo/description');?>" />
    <meta name="keywords" content="<?php $this->load->view('seo/keywords');?>" />
    <meta property="og:title" content="Project Name | <?= $template['title']; ?>" />
    <meta name="og:description" content="<?php $this->load->view('seo/description');?>" />
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <link rel="shortcut icon" href="<?php echo base_url('assets/img/nlrc-logo.png'); ?>">

    <!-- BOOTSTRAP 5.0.2-->
    <link rel="stylesheet" href="<?php echo base_url('plugins/bootstrap-5.0.2/css/bootstrap.min.css')?>">

    <!-- FONTAWESOME 6.2.0 -->
    <link rel="stylesheet" href="<?php echo base_url('plugins/fontawesome-6.2.0/css/all.min.css') ?>" media="screen">

    <!-- FONTS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/fonts/font-face.css?ver=' . filemtime(FCPATH . "assets/fonts/font-face.css")); ?>" media="screen">

    <!-- CUSTOM STYLES -->
    <link rel="stylesheet" href="<?php echo versionAsset('assets/css/styles.css') ?>" media="screen">
    <link rel="stylesheet" href="<?php echo versionAsset('assets/css/main.css') ?>" media="screen">
    <link rel="stylesheet" href="<?php echo versionAsset('assets/css/desktop.css'); ?>" media="screen">
    <link rel="stylesheet" href="<?php echo versionAsset('assets/css/tablet.css') ?>" media="screen">
    <link rel="stylesheet" href="<?php echo versionAsset('assets/css/mobile.css') ?>" media="screen">

    <!-- MODULE STYLES -->
    <?= $template['prepend_metadata']?>

    <script>var base_url = "<?=base_url()?>"</script>
</head>

<body>
    <div id="main-wrapper">
        <?php 
            if (isset($template['partials']['header'])) {
                echo $template['partials']['header'];
            } 
            
            echo $template['body']; 
        ?>
    </div>

    <!-- FOOTER -->
    <?php 
        if (isset($template['partials']['footer'])) {
            echo $template['partials']['footer'];
        } 
    ?>
    <!-- JQUERY -->
    <script src="<?php echo base_url('plugins/jquery-3.6.0/js/jquery-3.6.0.min.js') ?>"></script>
    <!-- TETHER -->
    <script src="<?php echo base_url('plugins/tether.min.js') ?>">
    </script>
    <!-- BOOTSRAP JS -->
    <script src="<?php echo base_url('plugins/bootstrap-5.0.2/js/popper.min.js')?>"></script>
    <script src="<?php echo base_url('plugins/bootstrap-5.0.2/js/bootstrap.min.js')?>"></script>
    <!-- CUSTOM JS -->
    <script src="<?php echo versionAsset('assets/js/main.js'); ?>"></script>
    <?= $template['metadata']?>

</body>

</html>