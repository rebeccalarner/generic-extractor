<?php

declare(strict_types=1);

namespace Keboola\GenericExtractor\Tests\Authentication;

use Keboola\GenericExtractor\Authentication\Basic;
use Keboola\Juicer\Client\RestClient;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class BasicTest extends TestCase
{

    /**
     * @dataProvider credentialsProvider
     */
    public function testAuthenticateClient(array $credentials): void
    {
        $auth = new Basic($credentials);
        $restClient = new RestClient(new NullLogger(), []);
        $auth->authenticateClient($restClient);

        self::assertEquals(['test','pass'], $restClient->getClient()->getDefaultOption('auth'));

        $request = $restClient->getClient()->createRequest('GET', '/');
        self::assertArrayHasKey('Authorization', $request->getHeaders());
        self::assertEquals(['Basic dGVzdDpwYXNz'], $request->getHeaders()['Authorization']);
    }

    public function credentialsProvider(): array
    {
        return [
            [
                ['username' => 'test', 'password' => 'pass'],
            ],
            [
                ['#username' => 'test', '#password' => 'pass'],
            ],
        ];
    }
}