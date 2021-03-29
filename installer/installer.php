#!/usr/bin/php -q
<?php 

    /*
    |--------------------------------------------------------------------------
    | Webzone Installer
    |--------------------------------------------------------------------------
    |
    | The Installer will install latest version of termux webzone cli.
    |
    */

CONST PATH ="/data/data/com.termux/files/usr/bin";

class WebzoneInstaller
{
	//properties
	
	public function __construct()
	{
		echo exec("clear");
		$this->logo();
		$this->packages = ['php', 'curl', 'composer'];
	}
	
	public function checkInstallations()
	{
		echo PHP_EOL;
		echo PHP_EOL;
		print_r("\033[1;33m Checking installations");
		echo PHP_EOL;
		echo PHP_EOL;
		$this->updatePackages();
		echo PHP_EOL;
		print_r("\033[1;33m Checking Requirements..");
		echo PHP_EOL;
		foreach($this->packages as $package)
		{
			$this->checkCommand($package);
		}
		echo PHP_EOL;
		$this->install();
	}
	
	private function checkCommand($cmd)
	{
		if(file_exists(PATH."/{$cmd}"))
		{
			echo "\033[32m   [*] {$cmd} is installed\n";
		} else {
			echo "\033[0;32m   [*] {$cmd} is not installed. Installing {$cmd}.....\n";
			exec("apt-get install {$cmd} -q");
			echo "\033[0;32m   [*] {$cmd} is installed\n";
		}
	}
	
	private function updatePackages()
	{
		echo "\033[0;36m Updating packages, Please wait...\n\n";
			exec("apt-get update && apt-get upgrade -y");
			echo "\033[0;36m Updation Completed \n";
	}
	
	private function install()
	{
		echo "\033[0;36m Installing webzone...\n\n \033[0;32m";
		exec('PATH=\$PATH:/data/data/com.termux/files/home/.composer/vendor/bin');
		exec('composer global require albinvar/termux-webzone');
		echo "\n\033[1;33m Webshell Installation Complete.. Try to execute \"webzone\" on terminal. \n";
	}
	
	public function logo()
	{
		echo PHP_EOL;
		echo PHP_EOL;
		echo '     _      __    __                       ____         __       ____       ' . PHP_EOL;
		echo '    | | /| / /__ / /  ___ ___  ___  ___   /  _/__  ___ / /____ _/ / /__ ____' . PHP_EOL;
		echo '    | |/ |/ / -_) _ \/_ // _ \/ _ \/ -_) _/ // _ \(_-</ __/ _ `/ / / -_) __/' . PHP_EOL;
		echo '    |__/|__/\__/_.__//__/\___/_//_/\__/ /___/_//_/___/\__/\_,_/_/_/\__/_/   ' . PHP_EOL;
		echo PHP_EOL;
		echo PHP_EOL;
		echo "\033[32m                             Version 1.0 \033[0m - cli version \n" . PHP_EOL;
		echo PHP_EOL;
                                                                  
	}
}


//object
$installer = new WebzoneInstaller();
$installer->checkInstallations();

?>