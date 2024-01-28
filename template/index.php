<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="utf-8">
    <title><?= $title ?></title>
    <meta name="description" content="<?= $description ?>">
    <meta name="author" content="<?= $author ?>">
    <meta name="keywords" content="<?=$keywords?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="stylesheet" href="css/style.css">

    <javascript src="js/script.js"></javascript>
</head>

<body>
    <a href="#main" class="sr-only"><?= $i18n['skip_to_main'][$lang] ?></a>

    <?php include $header; ?>

    <main id="main"><?php include $main; ?></main>

    <a href="#" class="fab-button">↑</a>

    <?php include $footer; ?>
</body>

</html>