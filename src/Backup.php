<?php

namespace OvhDatabaseBackup;

use Ovh\Api;
use GuzzleHttp\Client;

/**
 * @package Backup
 * @author  Léopold Jacquot {@link https://www.leopoldjacquot.com}
 */
class Backup
{

    const OVH_API_HOSTING_URL               = '/hosting/{host_type}';
    const OVH_API_HOSTING_DATABASE_URL      = '/hosting/{host_type}/{host}/database';
    const OVH_API_HOSTING_DATABASE_DUMP_URL = '/hosting/{host_type}/{host}/database/{database}/dump';
    const OVH_HOSTING_TYPES                 = ['web', 'privateDatabase'];

    /**
     * @var Api
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
            CONSUMER_KEY,
            new Client(
                [
                    'timeout'         => 60,
                    'connect_timeout' => 10,
                ]
            )
        );
    }
    
    public function backupDatabase()
    {
        foreach (self::OVH_HOSTING_TYPES as $hostingType) {

            /**
             * @var $hosting array<string> OVH hosting
             */
            $hosting = $this->fetchHosting($hostingType);

            foreach ($hosting as $host) {
                
                /**
                 * @var $databases array<string> OVH hosting
                 */
                $databases = $this->fetchHostingDatabase($hostingType, $host);

                foreach ($databases as $database) {
                    $this->dumpDatabase($hostingType, $host, $database);
                }
            }
        }
    }

    /**
     * @param string $hostingType
     *
     * @return array
     */
    private function fetchHosting($hostingType)
    {
        return $this->ovhClient->get(str_replace('{host_type}', $hostingType, self::OVH_API_HOSTING_URL));
    }

    /**
     * @param string $hostingType
     * @param string $webHosting
     *
     * @return array
     */
    private function fetchHostingDatabase($hostingType, $webHosting)
    {
        return $this->ovhClient->get(str_replace(['{host_type}', '{host}'], [$hostingType, $webHosting], self::OVH_API_HOSTING_DATABASE_URL));
    }

    /**
     * @param string $hostingType
     * @param string $webHosting
     * @param string $database
     */
    private function dumpDatabase($hostingType, $webHosting, $database)
    {
        $options = ['sendEmail' => false];

        if ($hostingType === 'web') {
            $options['date'] = 'now';
        }

        $this->ovhClient->post(
            str_replace(['{host_type}', '{host}', '{database}'], [$hostingType, $webHosting, $database], self::OVH_API_HOSTING_DATABASE_DUMP_URL),
            $options
        );
    }
}