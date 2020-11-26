<?php /** @var Exception $error */ ?>
Дата: <?= date('d.m.Y H:i'); ?>
<br/>
<br/>
Адрес: <?= Request::url() ?>
<br/><br/>
Ошибка: <?= $code ?> .::. <?= get_class($error) ?><br/>
<?= $error->getMessage() ?>
<br/><br/>
Строка: <?= $error->getLine() ?>
<br/><br/>
Трэйс:<br/>
<?= $error->getTraceAsString() ?>
