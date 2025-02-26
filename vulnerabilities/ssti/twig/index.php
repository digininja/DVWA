<?php
require_once '..//vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('.');
$twig = new \Twig\Environment($loader, [
    'cache' => '../twig_stuff/cache',
	"enableAutoescape" => false,
]);

$inject = "Inject into me";
if (array_key_exists ("inject", $_GET)) {
	$inject = $_GET['inject'];
}

$template = $twig->load('index.twig');
echo $template->render(['the' => 'variables', 'go' => 'here', "inject" => $inject]);
