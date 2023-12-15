<?php declare(strict_types=1);

namespace Arxus\NewrelicMessengerBundle\Tests\Newrelic\Fixture;

use Arxus\NewrelicMessengerBundle\Attribute\NewrelicMessage;

#[NewrelicMessage(transactionName: self::TRANSACTION_NAME)]
class DummyMessage
{
    public const TRANSACTION_NAME = 'dependency-injection-test';
}
