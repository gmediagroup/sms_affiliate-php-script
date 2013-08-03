<? require_once "config.php";
if(!isset($title)) $title="SMS-AFFILIATE";
if(!isset($subtitle)) $subtitle="биллинг";?><html><head>                             
<meta http-equiv="content-type" content="text/html; charset=windows-1251"/>
<meta name="description" content="description">
<meta name="keywords" content="keywords"> 
<meta name="author" content="author"> 
<link rel="stylesheet" type="text/css" href="tmpl/default.css">
<title>SMS-Affiliate 1.3 &mdash; Member Panel</title>
</head>
<body>
<div class="header">
<h1><?=$title;?></h1>
<h2><?=$subtitle?></h2>
</div>
<div class="main">
<div class="menu">
<?
    if(!session_is_registered("logon") and !isset($_SESSION['name'])) {
        echo "<p><a href=/>Главная</a><a href=reg.php>Регистрация</a></p>";
    }
    else {
        echo "<p><a href=/>Главная</a>
        <a href=?mod=stat>Статистика</a>
        <a href=?mod=promo>Промо</a>
        <a href=?mod=desk>Суппорт</a>
        <a href=?mod=profile>Профиль</a>
        <a href=?mod=logout>Выход</a></p>";
    }
?>
</div>
<div class="content">
<div class="item"><br>
