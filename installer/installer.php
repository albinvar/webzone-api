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
CONST CLI_LINK = "https://gitlab.com/albinvar/pma-cli/-/raw/master/webzone?inline=false";
CONST COMMAND = "webzone";

class WebzoneInstaller
{
	//properties
	
	public function __construct()
	{
		$this->dir = "/data/data/com.termux/files/usr/bin";
		echo exec("clear");
		$this->logo();
		$this->packages = ['php', 'curl', 'composer'];
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
		echo "\033[32m                             Version 1.0 \033[0m - stable release \n" . PHP_EOL;
		echo PHP_EOL;
                                                                  
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
		echo PHP_EOL;
		foreach($this->packages as $package)
		{
			$this->checkCommand($package);
		}
		echo PHP_EOL;
		$type = getopt('c', ['composer']);
		if($type)
		{
			$this->install();
		} else {
			$this->curlInstall();
		 }
	}
	
	public function checkOs()
	{
		$response = shell_exec('echo $PREFIX | grep -o "com.termux"');
		if(!empty($response) && $response == "com.termux\n")
		{
			$this->checkInstallations();
		} else {
			echo PHP_EOL;
		    echo "\033[1;31m We are sorry, but Webzone is supported for Termux only.\n";
			echo PHP_EOL;
			die();
		}
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
		exec('webzone composer:global -s -qq');
	}
	
	private function curlInstall()
	{
		echo "\033[1;33m Downloading Webzone\n";
		echo PHP_EOL;
		$this->downloadPMACurl();
	}
	
	private function downloadPMACurl()
    {
    	echo "\033[32m";
    	$lines = shell_exec("curl -w '\n%{http_code}\n' " . CLI_LINK . " -o {$this->dir}/" . COMMAND);
	    $lines = explode("\n", trim($lines));
		$status = $lines[count($lines)-1];
		$this->checkDownloadStatus($status);
    }
    
    
    private function checkDownloadStatus(Int $status)
    {
    	switch ($status) {
  case 000:
    echo "Cannot connect to Server";
    break;
  case 200:
    echo "\n Downloaded Successfully...!!!\n";
    shell_exec("chmod +x {$this->dir}/" . COMMAND);
    echo PHP_EOL;
    echo "\033[1;33m Webzone Installation Complete.. Try to execute \"".COMMAND."\" on terminal. \n";
    break;
  case 404:
    echo "File not found on server..";
    break;
  default:
    echo "An Unknown Error occurred...";
}
    }
}


//object
$installer = new WebzoneInstaller();
$installer->checkOs();

?>