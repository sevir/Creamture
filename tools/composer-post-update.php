<?php

function is_win(){
	return (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');
}

echo PHP_EOL;
echo 'Updating Bower dependencies...'.PHP_EOL;

try{
	echo shell_exec('bower'.((is_win())?'.cmd':'').' update');
}catch(Exception $e){
	die('Error updating composer dependencies');
}

flush();
echo PHP_EOL;
echo 'Creamture is updated, let go!!'.PHP_EOL;