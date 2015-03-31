<?php

function is_win(){
	return (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');
}

echo PHP_EOL;
echo 'Checking Deployer...'.PHP_EOL;

try{
	//check deployer requirement
	if (! file_exists(__DIR__.'/../vendor/deployer.phar'))
		file_put_contents(__DIR__.'/../vendor/deployer.phar', file_get_contents('http://deployer.org/deployer.phar'));
	echo 'Execute php vendor/deployer.phar when you want it'.PHP_EOL;
}catch(Exception $e){
	die('Error downloading Deployer'.PHP_EOL);
}

flush();

echo PHP_EOL;
echo 'Installing Bower dependencies...'.PHP_EOL;

try{
	echo shell_exec('bower'.((is_win())?'.cmd':'').' install');
}catch(Exception $e){
	die('Error installing composer dependencies');
}

flush();
echo PHP_EOL;
echo 'Installing Sparks packages...'.PHP_EOL;

try{
	echo shell_exec('php tools\spark install -v1.0.1 ajax');
	echo shell_exec('php tools\spark install -v0.7.0 console');
	echo shell_exec('php tools\spark install -v1.5.1 assets');
}catch(Exception $e){
	die('Error installing sparks dependencies');
}

flush();
echo PHP_EOL;
echo 'Creamture is ready to work!!'.PHP_EOL;