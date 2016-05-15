<?php

namespace OvhDatabaseBackup;

use \Ovh\Api;

/**
 * @package Backup
 * @author  Léopold Jacquot {@link https://www.leopoldjacquot.com}
 */
class Backup
{

    /**
     * @var \Ovh\Api
     */
    private $ovhClient;

    /**
     * Backup constructor.
     */
    public function __construct()
    {
        $this->ovhClient = new Api(
            APPLICATION_KEY,
            APPLICATION_SECRET,
            API_ENDPOINT,
            CONSUMER_KEY
        );
    }

    /**
     * @return bool
     */
    public function backupDatabase() {
        $webHosting = $this->fetchWebHosting();

        try {
            foreach ($webHosting as $host) {
                $databases = $this->fetchHostingDatabase($host);

                foreach ($databases as $database) {
                    $this->dumpDatabase($host, $database);
                }
            }

            return true;
        } catch(\Exception $e) {
            return false;
        }
    }

    /**
     * @return array
     */
    private function fetchWebHosting() {
        return $this->ovhClient->get('/hosting/web/');
    }

    /**
     * @param string $webHosting
     *
     * @return array
     */
    private function fetchHostingDatabase($webHosting) {
        return $this->ovhClient->get('/hosting/web/'.$webHosting.'/database');
    }

    /**
     * @param string $webHosting
     * @param string $database
     */
    private function dumpDatabase($webHosting, $database) {
         $this->ovhClient->post(
            '/hosting/web/' . $webHosting . '/database/' . $database . '/dump',
            [
                'date' => 'now',
                'sendEmail' => false,
            ]
        );
    }
}