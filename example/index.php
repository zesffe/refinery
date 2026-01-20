<?php

	use Refinery\Refinery;
	use Refinery\FormPost;

	error_reporting(E_ALL | E_STRICT);


	$dir = __DIR__;

	require $dir . '/../Refinery.php';
	require $dir . '/../FormPost.php';
	require $dir . '/../FormContext.php';
	require $dir . '/../FormPage.php';
	require $dir . '/../FormResult.php';
	require $dir . '/../renderHelper.php';

	require $dir . '/sendMeMyMail.php';


	$secret = md5('Tuesaday');
	
	$post = $_POST;

	$formPost = new FormPost($secret);
	
	$context = $formPost->decode($post['_postpack'] ?? null);

	$refinery = new Refinery($post, $formPost, $context);

	$refinery
	    ->add(Pipelines\InitializeForm::class)
	    ->add(Pipelines\ComposeBody::class)
	    ->add(Pipelines\Send::class);

	extract((array) $refinery->handle($post));

	if ($redirect) {header("Location: {$redirect}"); exit; }


	$title = 'A form to post';
	$subtitle = '(an email to send)';

?><!DOCTYPE html>

<html lang="en">

	<head>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>

		<link rel="stylesheet" href="css/normal.css"/>
		<link rel="stylesheet" href="css/style.css"/>

		<title><?= $title ?></title>

		<link rel="icon" type="image/png" sizes="16x16" href="img/favicon-16.png"/>
	</head>

	<body>


		<h1> <a href="."><?= $title ?></a> </h1>

		<h2> <?= $subtitle ?> </h2>


		<form method="post" action=".">

			<?= ($notes = renderNotes($notes)) ? '<div class="max-width">' . $notes . '</div>' : null?>

			<?= renderFields($refinery->fields(), $refinery->postValues(), $refinery->formPostValues()) ?>

			<div class="buttons-row max-width"> <input type="submit" value="submit form"/> </div>

		</form>


	</body>

</html>
