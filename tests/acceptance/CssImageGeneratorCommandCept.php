<?php
$i = 1;
$artisan = 'artisan';
while($i++ < 10 && ! is_file($artisan))
{
	 $artisan = "../$artisan";
}

$I = new AcceptanceTester($scenario);
$I->wantTo('Generate images out of a CSS file');

$I->runShellCommand("php $artisan asset:generate-css-images");


