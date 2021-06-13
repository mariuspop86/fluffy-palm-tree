<?php

namespace App\Services;

use App\Builder\RequestToModelBuilder;
use App\Model\ModelInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestService
{
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var \App\Builder\RequestToModelBuilder
     */
    private $requestToModelBuilder;

    /**
     * HeatmapService constructor.
     */
    public function __construct(
        RequestToModelBuilder $requestToModelBuilder,
        ValidatorInterface $validator
    ) {
        $this->requestToModelBuilder = $requestToModelBuilder;
        $this->validator = $validator;
    }

    /**
     * @throws Exception
     */
    public function createModelFromRequest(string $modelClassName, Request $request): ModelInterface
    {
        $model = $this->requestToModelBuilder->build($modelClassName, $request);
        $this->validateModel($model);
        
        return $model;
    }

    /**
     * @throws Exception
     */
    protected function validateModel(ModelInterface $model) {
        $errors = $this->validator->validate($model);
        if (count($errors) == 0) {
            return;
        }
        $message = [];
        foreach ($errors as $error) {
            $message[$error->getPropertyPath()] = $error->getMessage();
        }
        throw new Exception(json_encode($message));
    }
}
