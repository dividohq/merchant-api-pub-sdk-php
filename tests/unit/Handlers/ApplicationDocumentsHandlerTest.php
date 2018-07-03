<?php
namespace Divido\MerchantSDK\Test\Unit;

use Divido\MerchantSDK\Client;
use Divido\MerchantSDK\Environment;
use Divido\MerchantSDK\HttpClient\GuzzleAdapter;
use Divido\MerchantSDK\Models\Application;
use Divido\MerchantSDK\Models\ApplicationDocument;
use Divido\MerchantSDK\Response\ResponseWrapper;
use GuzzleHttp\Psr7\Response;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class ApplicationDocumentsHandlerTest extends MerchantSDKTestCase
{
    use MockeryPHPUnitIntegration;

    private $applicationId = '90a25b24-2f53-4c80-aba8-9787c68e4c1d';

    function test_CreateApplicationDocument_ReturnsNewlyCreatedApplicationDocument()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/application_documents_get_one.json')),
        ], $history);
        $sdk = new Client('test_key', Environment::SANDBOX, new GuzzleAdapter($client));

        $application = (new Application)->withId($this->applicationId);

        $image = "todo - make this an image file.";

        $document = (new \Divido\MerchantSDK\Models\ApplicationDocument)->withDocument($image);

        $response = $sdk->application_documents()->createApplicationDocument($application, $document);

        self::assertCount(1, $history);
        self::assertSame('POST', $history[0]['request']->getMethod());
        self::assertSame("/applications/{$this->applicationId}/documents", $history[0]['request']->getUri()->getPath());

        $result = json_decode($response->getBody(), true);

        self::assertSame('7D827A60-7A07-11E8-BDD0-0242AC1E000B', $result['data']['id']);
    }

    function test_DeleteApplicationDocument_ReturnsOkay()
    {
        $history = [];

        $client = $this->getGuzzleStackedClient([
            new Response(200, [], file_get_contents(APP_PATH . '/tests/assets/responses/applications_get_one.json')),
        ], $history);
        $sdk = new Client('test_key', Environment::SANDBOX, new GuzzleAdapter($client));

        $application = (new Application)->withId($this->applicationId);

        $documentId = 'qwerty-123456-typewriter-foo';

        $response = $sdk->application_documents()->deleteApplicationDocument($application, $documentId);

        self::assertCount(1, $history);
        self::assertSame('DELETE', $history[0]['request']->getMethod());
        self::assertSame("/applications/{$this->applicationId}/documents/{$documentId}", $history[0]['request']->getUri()->getPath());
    }
}
