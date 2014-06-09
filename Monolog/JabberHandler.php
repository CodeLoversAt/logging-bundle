<?php
/**
 * @package logging-bundle
 *
 * @author Daniel Holzmann <d@velopment.at>
 * @date 09.06.14
 * @time 18:52
 */

namespace CodeLovers\LoggingBundle\Monolog;


use Monolog\Handler\AbstractProcessingHandler;
use xmpphp\XMPP;

class JabberHandler extends AbstractProcessingHandler
{
    /**
     * @var XMPP
     */
    private $xmpp;

    /**
     * @var string
     */
    private $recipient;

    /**
     * @var bool
     */
    private $connected = false;

    /**
     * @param string $host
     * @param string $port
     * @param string $user
     * @param string $password
     * @param null $server
     */
    public function __construct($host, $port, $user, $password, $recipient = null, $server = null, $useEncryption = true)
    {
        $this->xmpp = new XMPP($host, $port, $user, $password, 'xmpphp', $server);
        $this->xmpp->useEncryption($useEncryption);
        $this->recipient = $recipient;
    }

    public function __destruct()
    {
        if (true === $this->connected) {
            $this->xmpp->disconnect();
        }
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
        if (false === $this->connected) {
            $this->xmpp->connect();
            $this->connected = true;
        }

        if (null === $this->recipient) {
            $this->xmpp->send($record['formatted']);
        } else {
            $this->xmpp->message($this->recipient, $record['formatted']);
        }
    }
}