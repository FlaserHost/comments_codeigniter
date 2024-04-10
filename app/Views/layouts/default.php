<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $this->renderSection('title') ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/adaptive.css') ?>">
    <script src="<?= base_url('js/jquery-3.7.1.min.js') ?>"></script>
</head>
<body>
    <div class="main-container">
        <?= view('layouts/header') ?>
        <main class="content">
            <?= $this->renderSection('content') ?>
        </main>
    </div>
    <script src="<?= base_url('js/script.js') ?>"></script>
</body>
</html>
