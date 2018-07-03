<?php

namespace Divido\MerchantSDK\Handlers\ApplicationDocuments;

use Divido\MerchantSDK\Models\Application;
use Divido\MerchantSDK\Models\ApplicationDocument;
use Divido\MerchantSDK\Response\ResponseWrapper;

use Divido\MerchantSDK\Handlers\AbstractHttpHandler;
use Divido\MerchantSDK\Handlers\ApiRequestOptions;

/**
 * ApplicationDocumentsHandler Client
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @copyright (c) 2018, Divido
 * @package Divido\MerchantSDK
 */
class Handler extends AbstractHttpHandler
{
    /**
     * Create a cancellation
     *
     * @return ResponseWrapper
     */
    public function createApplicationDocument(Application $application, ApplicationDocument $applicationDocument)
    {
        $path = vsprintf('%s/%s/%s', [
            'applications',
            $application->getId(),
            'documents',
        ]);

        return $this->httpClientWrapper->request('post', $path, [], ['Content-Type' => 'multipart/form-data'], $applicationDocument->getJsonPayload());
    }

    /**
     * Create a cancellation
     *
     * @return ResponseWrapper
     */
    public function deleteApplicationDocument(Application $application, $applicationDocumentId)
    {
        $path = vsprintf('%s/%s/%s/%s', [
            'applications',
            $application->getId(),
            'documents',
            $applicationDocumentId,
        ]);

        return $this->httpClientWrapper->request('delete', $path);
    }
}
