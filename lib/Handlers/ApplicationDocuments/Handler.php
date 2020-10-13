<?php

namespace Divido\MerchantSDK\Handlers\ApplicationDocuments;

use Divido\MerchantSDK\Handlers\AbstractHttpHandler;
use Divido\MerchantSDK\Models\Application;
use Divido\MerchantSDK\Models\ApplicationDocument;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Handler
 *
 * @author Neil McGibbon <neil.mcgibbon@divido.com>
 * @author Mike Lovely <mike.lovely@divido.com>
 * @copyright (c) 2018, Divido
 */
class Handler extends AbstractHttpHandler
{
    /**
     * Create a cancellation
     *
     * @param Application $application
     * @param ApplicationDocument $applicationDocument
     * @return ResponseInterface
     */
    public function createApplicationDocument(Application $application, ApplicationDocument $applicationDocument)
    {
        $path = vsprintf('%s/%s/%s', [
            'applications',
            $application->getId(),
            'documents',
        ]);

        return $this->wrapper->request('post', $path, [], ['Content-Type' => 'multipart/form-data'], $applicationDocument->getJsonPayload());
    }

    /**
     * Create a cancellation
     *
     * @param Application $application
     * @param string $applicationDocumentId
     * @return ResponseInterface
     */
    public function deleteApplicationDocument(Application $application, $applicationDocumentId)
    {
        $path = vsprintf('%s/%s/%s/%s', [
            'applications',
            $application->getId(),
            'documents',
            $applicationDocumentId,
        ]);

        return $this->wrapper->request('delete', $path);
    }
}
