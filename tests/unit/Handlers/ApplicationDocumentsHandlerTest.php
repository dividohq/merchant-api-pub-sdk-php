<?php

namespace Divido\MerchantSDK\Test\Unit;

use Divido\MerchantSDK\Handlers\ApplicationDocuments\Handler;
use Divido\MerchantSDK\Models\Application;
use Divido\MerchantSDK\Wrappers\HttpWrapper;
use Psr\Http\Message\ResponseFactoryInterface;

class ApplicationDocumentsHandlerTest extends MerchantSDKTestCase
{
    private $applicationId = '90a25b24-2f53-4c80-aba8-9787c68e4c1d';

    public function test_CreateApplicationDocument_ReturnsNewlyCreatedApplicationDocument()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse(
            $this->createResponseMock(201, [], file_get_contents(
                APP_PATH . '/tests/assets/responses/application_documents_get_one.json'
            ))
        );

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $application = (new Application)->withId($this->applicationId);

        $image = "todo - make this an image file.";

        $document = (new \Divido\MerchantSDK\Models\ApplicationDocument)->withDocument($image);

        $response = $handler->createApplicationDocument($application, $document);

        $result = json_decode($response->getBody()->getContents(), true);

        self::assertSame('7D827A60-7A07-11E8-BDD0-0242AC1E000B', $result['data']['id']);
    }

    public function test_DeleteApplicationDocument_ReturnsOkay()
    {
        $httpClient = new \Http\Mock\Client(self::createMock(ResponseFactoryInterface::class));
        $httpClient->addResponse($this->createResponseMock());

        $requestFactory = $this->createRequestFactory();

        $wrapper = new HttpWrapper('-merchant-api-pub-http-host-', 'divido', $httpClient, $requestFactory);

        $handler = new Handler($wrapper);

        $application = (new Application)->withId($this->applicationId);

        $documentId = 'qwerty-123456-typewriter-foo';

        $response = $handler->deleteApplicationDocument($application, $documentId);

        self::addToAssertionCount(1);
    }
}
