<?php defined('ROOT') or header('Location: /');?>
<!DOCTYPE html>
<html>
<head>
    <?=head()?>
    <link href="<?=DIR_TMPL?>styles/style.css" rel="stylesheet" type="text/css" />
    <link href="<?=DIR_TMPL?>styles/loader.css" rel="stylesheet" type="text/css" />
</head>
<body onload="init();" onresize="sizing();">
<div class="wrapper" id="main">
    <div id="header"></div>
    <div id="page"></div>
</div>
</body>
</html>