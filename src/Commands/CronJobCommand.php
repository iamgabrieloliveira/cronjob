<?php namespace Daycry\CronJob\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

use Daycry\CronJob\TaskRunner;

/**
 * Base functionality for enable/disable.
 */
abstract class CronJobCommand extends BaseCommand
{
	/**
	 * Command grouping.
	 *
	 * @var string
	 */
	protected $group = 'cronjob';

    /**
     * Config File 
     */
    protected $config = null;

    /**
     * Get Config File 
     */
    protected function getConfig()
    {
        $this->config = config( 'CronJob' );
    }

	/**
	 * Saves the settings.
	 */
	protected function saveSettings( $status )
	{
		$this->getConfig();

		if( !file_exists( $this->config->FilePath . $this->config->FileName ) )
        {
			// dir doesn't exist, make it
			if( !is_dir( $this->config->FilePath ) ){ mkdir( $this->config->FilePath ); }

            $settings = [
                "status" => $status,
                "time" => ( new \DateTime() )->format( 'Y-m-d H:i:s' )
            ];

			// write the file with json content
			file_put_contents(
				$this->config->FilePath . $this->config->FileName,
				json_encode(
                    $settings, 
                    JSON_PRETTY_PRINT
                )
			);

			return $settings;
		}

        return false;
	}

	/**
	 * Gets the settings, if they have never been
	 * saved, save them.
	 */
	protected function getSettings()
	{
		$this->getConfig();
		
		if( file_exists( $this->config->FilePath . $this->config->FileName ) )
        {
			$data = json_decode( file_get_contents( $this->config->FilePath . $this->config->FileName ) );
            return $data;
        }

        return false;	
	}
}