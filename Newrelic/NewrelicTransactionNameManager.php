<?php declare(strict_types=1);

namespace Arxus\NewrelicMessengerBundle\Newrelic;

use Arxus\NewrelicMessengerBundle\Attribute\NewrelicMessage;
use Symfony\Component\Messenger\Envelope;

class NewrelicTransactionNameManager
{
    /**
     * @var array<class-string, string>
     */
    private array $transactionNameRegistry = [];

    /**
     * @param class-string $class
     * @param string       $transactionName
     *
     * @return $this
     */
    public function addTransactionMapping(string $class, string $transactionName): self
    {
        $this->transactionNameRegistry[$class] = $transactionName;

        return $this;
    }

    public function getTransactionName(Envelope $envelope): string
    {
        $stamp = $envelope->last(NewrelicTransactionStamp::class);
        if (null !== $stamp) {
            return $stamp->getTransactionName();
        }

        $message = $envelope->getMessage();
        if (!array_key_exists($message::class, $this->transactionNameRegistry)) {
            $reflClass = new \ReflectionClass($message);
            $attrs = $reflClass->getAttributes(NewrelicMessage::class);
            foreach ($attrs as $attr) {
                $attr = $attr->newInstance();

                /** @var NewrelicMessage $attr */
                return $this->transactionNameRegistry[$message::class] = $attr->transactionName;
            }
        }

        if ($message instanceof NameableNewrelicTransactionInterface) {
            return $this->transactionNameRegistry[$message::class] = $message->getNewrelicTransactionName();
        }

        return $this->transactionNameRegistry[$message::class] ??= $message::class;
    }
}
