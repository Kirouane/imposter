<?=$title['time']; ?> - <?=$title['title']; ?> : <?= $message; ?>
<?php if ($matchResults) : ?>
<?php foreach ($matchResults as $matchResult) : ?>
<?php $mock = $matchResult->getMock(); ?> <?= "\n" ?>
* localhost:<?=$mock->getPort(); ?> <?= $mock->getFile(); ?> : <?= $mock->getLine(); ?>
<?php foreach ($matchResult->getExceptions() as $exception) : ?><?= "\n" ?>
* <?= \PHPUnit\Framework\TestFailure::exceptionToString($exception) . "\n"; ?>
<?php endforeach;?>
<?php endforeach;?>
<?php endif;?>
-----------------------------------------------------------------------------------------
