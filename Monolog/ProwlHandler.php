<?php
namespace CodeLovers\LoggingBundle\Monolog;

use Monolog\Handler\AbstractProcessingHandler;
use Prowl\Connector;
use Prowl\Message;

/**
 * @package logging-bundle
 *
 * @author Daniel Holzmann <d@velopment.at>
 * @date 09.06.14
 * @time 17:31
 */

class ProwlHandler extends AbstractProcessingHandler
{
    /**
     * @var Connector
     */
    private $connector;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $appName;

    /**
     * @param string $apiKey
     * @param string $appName
     * @param \Prowl\Connector $connector
     */
    public function __construct(Connector $connector, $apiKey, $appName)
    {
        $this->apiKey = $apiKey;
        $this->appName = $appName;
        $this->connector = $connector;
        $this->connector->setFilterCallback(function ($text) {
            return $text;
        });
    }

    /**
     * Writes the record down to the log of the implementing handler
     *
     * @param  array $record
     *
     * @return void
     */
    protected function write(array $record)
    {
        $message = new Message();
        $message->addApiKey($this->apiKey);
        $message->setApplication($this->appName);
        $message->setEvent($record['formatted']);
        $message->setDescription($record['message']);

        $this->connector->setIsPostRequest(true);
        $this->connector->push($message);
    }}