<?php

/**
 * @copyright  Copyright (c) 2023 E-vino Comércio de Vinhos S.A. (https://evino.com.br)
 * @author     Kevin Mian Kraiker <kevin.kraiker@evino.com.br>
 *
 * @Link       https://evino.com.br
 */

declare(strict_types=1);

namespace Arxus\NewrelicMessengerBundle\Attribute;

/**
 * Attribute that can be used to set NewRelic transactions metadata for Messenger on a per-class basis.
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class NewrelicMessage
{
    public function __construct(
        public string $transactionName
    ) {
    }
}
