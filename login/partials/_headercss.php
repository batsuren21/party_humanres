<!-- begin::Head -->
<head>
    <meta charset="utf-8"/>
    <title><?=__TITLE__?></title>
    <meta name="description" content="Latest updates and statistic charts">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!--begin::Fonts -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script> -->
    <script>            
        // WebFont.load({
        //     google: {
        //         "families":["Arial:300,400,500,600,700"]},
        //     active: function() {sessionStorage.fonts = true; }
        // });        </script>
    <!--end::Fonts -->

    

    <!--begin::Global Theme Styles(used by all pages) -->
    <link href="<?=RF;?>/login/assets/vendors/global/vendors.bundle.css" rel="stylesheet" type="text/css" />
    <link href="<?=RF;?>/login/assets/css/demo1/style.bundle.css" rel="stylesheet" type="text/css" />
    <link href="<?=RF;?>/login/assets/css/custom.css" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles -->
    <!--begin::Page Custom Styles(used by this page) -->
    <?php 
        if(isset(Office\System::$custom_css) && count(Office\System::$custom_css)>0){
            foreach(Office\System::$custom_css as $css){
    ?>
    <link href="<?=$css;?>" rel="stylesheet" type="text/css" />
    <?php }}?>
    <!--end::Page Custom Styles -->
    <!--begin::Layout Skins(used by all pages) -->
    <link href="<?=RF;?>/login/assets/css/demo1/skins/header/base/light.css" rel="stylesheet" type="text/css" />
    <link href="<?=RF;?>/login/assets/css/demo1/skins/header/menu/light.css" rel="stylesheet" type="text/css" />
    <link href="<?=RF;?>/login/assets/css/demo1/skins/brand/light.css" rel="stylesheet" type="text/css" />
    <link href="<?=RF;?>/login/assets/css/demo1/skins/aside/light.css" rel="stylesheet" type="text/css" />
    <!--end::Layout Skins -->
    <link rel="shortcut icon" href="<?=__LOGO__;?>"/>
</head>
<!-- end::Head -->