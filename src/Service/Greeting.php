<?php


namespace App\Service;


use Psr\Log\LoggerInterface;

class Greeting
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $message;

    public function __construct(LoggerInterface $logger, string $message)
    {
        $this->message = $message;
        $this->logger = $logger;

    }

    public function greet(string $name): string
    {
        $this->logger->info("Greeted $name");
        return "{$this->message} $name";
    }
}