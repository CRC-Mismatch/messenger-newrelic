<?php declare(strict_types=1);

namespace Arxus\NewrelicMessengerBundle\DependencyInjection;

use Arxus\NewrelicMessengerBundle\Newrelic\NewrelicTransactionNameManager;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class NewrelicMessengerExtension extends ConfigurableExtension
{
    /**
     * @throws \Exception
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $locator = new FileLocator(__DIR__.'/../Resources/config/');
        $loader = new YamlFileLoader($container, $locator);
        $loader->load('services.yaml');

        $container->getDefinition(NewrelicTransactionNameManager::class)
            ->setMethodCalls($this->getConfigTransactionNameMappings($mergedConfig));
    }

    private function getConfigTransactionNameMappings(array $config): array
    {
        if (empty($config['mappings'] ?? [])) {
            return [];
        }

        $calls = [];
        foreach ($config['mappings'] as $target => $attrs) {
            if (empty($attrs['transaction_name'] ?? null) || !class_exists($target)) {
                continue;
            }
            $calls[] = ['addTransactionMapping', [$target, $attrs['transaction_name']]];
        }

        return $calls;
    }
}
