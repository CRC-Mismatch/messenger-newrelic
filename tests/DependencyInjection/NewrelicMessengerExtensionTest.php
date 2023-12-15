<?php declare(strict_types=1);

namespace Arxus\NewrelicMessengerBundle\Tests\DependencyInjection;

use Arxus\NewrelicMessengerBundle\DependencyInjection\NewrelicMessengerExtension;
use Arxus\NewrelicMessengerBundle\Newrelic\NewrelicTransactionNameManager;
use Arxus\NewrelicMessengerBundle\Tests\Newrelic\Fixture\DummyMessage;
use PHPUnit\Framework\TestCase;
use PhrozenByte\PHPUnitArrayAsserts\ArrayAssertsTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class NewrelicMessengerExtensionTest extends TestCase
{
    use ArrayAssertsTrait;

    /**
     * @throws \Exception
     */
    private function getContainer(array $config): ContainerBuilder
    {
        $loader = new NewrelicMessengerExtension();
        $container = new ContainerBuilder();

        $container->setParameter('kernel.debug', true);
        $container->setParameter('kernel.cache_dir', sys_get_temp_dir().'/messenger-newrelic');
        $container->setParameter('kernel.bundles', []);
        $container->setParameter('kernel.bundles_metadata', []);

        $loader->load(['arxus_messenger_newrelic' => $config], $container);

        $container->getDefinition(NewrelicTransactionNameManager::class)->setPublic(true);

        return $container;
    }

    public function test_transaction_name_mappings(): void
    {
        $container = $this->getContainer([
            'mappings' => [
                [
                    'target' => DummyMessage::class,
                    'transaction_name' => DummyMessage::TRANSACTION_NAME,
                ],
            ],
        ]);

        $container->compile();

        $transactionNameManagerDef = $container->getDefinition(NewrelicTransactionNameManager::class);

        $this->assertSequentialArray(
            $transactionNameManagerDef->getMethodCalls(),
            1,
            constraint: [
                'addTransactionMapping',
                [DummyMessage::class, DummyMessage::TRANSACTION_NAME],
            ]
        );
    }
}
