<?php declare(strict_types=1);

namespace Shopware\AppBundle\Test\Annotation;

use PHPUnit\Framework\TestCase;
use Shopware\AppBundle\Annotation\Webhook;

class WebhookTest extends TestCase
{
    #[Webhook(name: 'name', event: 'event')]
    public function testWebhookAnnotation(): void
    {
        $reflectionClass = new \ReflectionClass($this);
        $reflectionMethod = $reflectionClass->getMethod('testWebhookAnnotation');

        $reflectionAttribute = $reflectionMethod->getAttributes(Webhook::class);

        static::assertEquals($reflectionAttribute[0]->getArguments(), [
            'name' => 'name',
            'event' => 'event',
        ]);
    }
}