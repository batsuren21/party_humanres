<!-- begin::Head -->
<head>
    <meta charset="utf-8"/>
    <title><?=__TITLE__?></title>
    <meta name="description" content="SmartOffice">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!--begin::Fonts -->
<!-- 	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Arial:300,400,500,600,700"> -->
	<!--end::Fonts -->

	<!--begin::Global Theme Styles(used by all pages) -->
	<link href="<?=RF;?>/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
	<link href="<?=RF;?>/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
	<link href="<?=RF;?>/assets/css/style.css" rel="stylesheet" type="text/css" />
	
	<!--end::Global Theme Styles -->

	<!--begin::Layout Skins(used by all pages) -->
	<link href="<?=RF;?>/assets/css/skins/header/base/light.css" rel="stylesheet" type="text/css" />
	<link href="<?=RF;?>/assets/css/skins/header/menu/light.css" rel="stylesheet" type="text/css" />
	<link href="<?=RF;?>/assets/css/skins/brand/light.css" rel="stylesheet" type="text/css" />
	<link href="<?=RF;?>/assets/css/skins/aside/light.css" rel="stylesheet" type="text/css" />

	<!--end::Layout Skins -->
	<!-- <link rel="shortcut icon" href="<?=RF;?>/assets/media/logos/favicon.ico"/> -->
    <link rel="shortcut icon" href="<?=RF;?>/imgs/atg.png"/>
    
    <!--begin::Page Custom Styles(used by this page) -->
    <?php 
    if(isset(\Office\System::$custom_css) && count(\Office\System::$custom_css)>0){
        foreach(\Office\System::$custom_css as $css){
    ?>
    <link href="<?=$css;?>" rel="stylesheet" type="text/css" />
    <?php }}?>
    <!--end::Page Custom Styles -->
</head>
<!-- end::Head -->