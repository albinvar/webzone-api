#!/usr/bin/php -q
<?php 

    /*
    |--------------------------------------------------------------------------
    | Webzone Installer
    |--------------------------------------------------------------------------
    |
    | The Installer will install the latest version of termux webzone cli.
    |
    */

CONST INSTALLER_VERSION = "2.0";
CONST PATH ="/data/data/com.termux/files/usr/bin";
CONST CLI_LINK = "https://raw.githubusercontent.com/albinvar/termux-webzone/main/builds/webzone";
CONST COMMAND = "webzone";

CONST INSTALLATION_NORMAL_MODE = 0;
CONST INSTALLATION_COMPOSER_MODE = 1;

class WebzoneInstaller
{
	protected string $dir;

	protected array $packages;

    private int $installationMode;

    /**
     *
     */
    public function __construct()
	{
		$this->dir = "/data/data/com.termux/files/usr/bin";
		echo exec("clear");
		$this->logo();
		$this->packages = ['php', 'curl', 'composer'];
	}

    /**
     * Basic landing interface with ascii logo.
     *
     * @return void
     */
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
		echo "\033[32m                             Version ". INSTALLER_VERSION ." \033[0m - stable release \n" . PHP_EOL;
		echo PHP_EOL;
                                                                  
	}

    /**
     * Checks all prerequisites for installation of webzone.
     *
     * @return void
     */
    public function checkInstallations()
	{
		echo PHP_EOL . PHP_EOL;
		print_r("\033[1;33m Checking installations");
        echo PHP_EOL . PHP_EOL;
		$this->updatePackages();
		echo PHP_EOL;
		print_r("\033[1;33m Checking Requirements..");
        echo PHP_EOL . PHP_EOL;

		foreach($this->packages as $package)
		{
			$this->checkCommand($package);
		}
		echo PHP_EOL;

		if(! getopt('c', ['composer']))
		{
            $this->installationMode = INSTALLATION_NORMAL_MODE;
		} else {
            $this->installationMode = INSTALLATION_COMPOSER_MODE;
		 }
	}

    /**
     * Checks if the operating system is termux and abort if not suppoerted with exit code 1
     *
     * @return bool
     */
    public function checkOs(): bool
    {
        //checks if the os is termux.
		$response = shell_exec('echo $PREFIX | grep -o "com.termux"');

        //abort if not termux.
		return !empty($response) && ($response == "com.termux\n");
	}

    /**
     * Basically checks if a command/package is installed or not.
     *
     * @param $cmd
     * @return void
     */
    private function checkCommand($cmd)
	{
		if(file_exists(PATH."/$cmd"))
		{
			echo "\033[32m   [*] $cmd is installed\n";
		} else {
			echo "\033[0;32m   [*] $cmd is not installed. Installing $cmd.....\n";
			exec(sprintf("apt-get install %s -q", $cmd));
			echo "\033[0;32m   [*] $cmd is installed\n";
		}
	}

    /**
     * Updates all packages in clients machine using apt.
     *
     * @return void
     */
    private function updatePackages()
	{
		echo "\033[0;36m Updating packages, Please wait...\n\n";
			exec("apt-get update && apt-get upgrade -y");
			echo "\033[0;36m Updation completed \n";
	}

    /**
     * @return void
     */
    public function install()
    {
        if($this->installationMode == INSTALLATION_NORMAL_MODE)
        {
            $this->normalInstall();
        } elseif($this->installationMode == INSTALLATION_COMPOSER_MODE)
        {
            $this->composerInstall();
        }
    }

    /**
     * Installation of project using composer.
     *
     * @return void
     */
    private function composerInstall()
	{
		echo "\033[0;36m Installing webzone...\n\n \033[0;32m";
		exec('PATH=\$PATH:/data/data/com.termux/files/home/.composer/vendor/bin');
		exec('composer global require albinvar/termux-webzone');
		echo "\n\033[1;33m Webzone Installation Complete.. Try to execute \"webzone\" on terminal. \n";
		exec('webzone composer:global -s -qq');
	}

    /**
     * Launches normal installation process.
     * latest version of phar/build file is downloaded from GitHub repository.
     *
     * @return void
     */
    private function normalInstall()
	{
		echo "\033[1;33m Downloading Webzone\n";
		echo PHP_EOL;
		$this->downloadPMACurl();
	}

    /**
     * Downloading using curl package installed in clients machine.
     *
     * @return void
     */
    private function downloadPMACurl()
    {
    	echo "\033[32m";
    	$lines = shell_exec("curl -w '\n%{http_code}\n' " . CLI_LINK . " -o $this->dir/" . COMMAND . " --location --remote-header-name --remote-name");
	    $lines = explode("\n", trim($lines));
		$status = $lines[count($lines)-1];
		$this->checkDownloadStatus($status);
    }


    /**
     * Checks download status and shows responses based on exceptions if any error occurs.
     *
     * @param Int $status
     * @return void
     */
    private function checkDownloadStatus(Int $status)
    {
    	switch ($status) {
  case 000:
    echo "Cannot connect to Server";
    break;
  case 200:
    echo "\n Downloaded Successfully...!!!\n";
    shell_exec("chmod +x $this->dir/" . COMMAND);
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

/*
 * Creates an instance of WebzoneInstaller.
 *
 */
$installer = new WebzoneInstaller();


// checks if the client's os is termux.
if($installer->checkOs())
{
    $installer->checkInstallations();
} else {
    echo PHP_EOL;
    echo "\033[1;31m We are sorry, but Webzone is supported for Termux only.\n";
    echo PHP_EOL;
    exit(1);
}

// launches the installation process with the predefined installation method.
$installer->install();

/*
 * Created by @albinvar
 *
 */
?>