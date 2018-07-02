<?php

namespace Divido\MerchantSDK\Handlers;

use Divido\MerchantSDK\Models\Application;
use Divido\MerchantSDK\Models\ApplicationDocument;
use Divido\MerchantSDK\Response\ResponseWrapper;

/**
 * ApplicationDocumentsHandler Client
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @copyright (c) 2018, Divido
 * @package Divido\MerchantSDK
 */
class ApplicationDocumentsHandler extends AbstractHttpHandler
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

        return $this->httpClientWrapper->request('post', $path, [], ['Content-Type' => 'multipart/form-data'], $applicationDocument->getPayload());
    }

    /**
     * Create a cancellation
     *
     * @return ResponseWrapper
     */
    public function deleteApplicationDocument(Application $application, $applicationDocumentId)
    {
        $path = vsprintf('%s/%s/%s/$s', [
            'applications',
            $application->getId(),
            'documents',
            $applicationDocumentId,
        ]);

        return $this->httpClientWrapper->request('delete', $path);
    }
}
