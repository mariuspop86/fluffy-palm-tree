<?php

namespace App\Builder;

use App\Model\CustomerJourneyModel;
use App\Model\HeatmapModel;
use App\Model\HitModel;
use App\Model\ModelInterface;
use App\Traits\RequestTrait;
use Exception;
use Symfony\Component\HttpFoundation\Request;

class RequestToModelBuilder
{
    use RequestTrait;

    /**
     * @throws Exception
     */
    public function build(string $className, Request $request): ModelInterface 
    {
        switch ($className) {
            case $className === CustomerJourneyModel::class:
                return $this->buildCustomerJourneyModelFromRequest($request);
            case $className === HeatmapModel::class:
                return $this->buildHeatmapModelFromRequest($request);
            case $className === HitModel::class:
                return $this->buildHitModelFromRequest($request);
            default:
                throw new Exception('Model not implemented');
        }
    }

    private function buildCustomerJourneyModelFromRequest(Request $request): CustomerJourneyModel
    {
        $model = new CustomerJourneyModel();

        $model->setCustomerId((int) $request->get('id'));

        return $model;
    }
    
    public function buildHeatmapModelFromRequest(Request $request): HeatmapModel
    {
        $data = $this->getValidatedRequestContent($request->getContent());
        $model = new HeatmapModel();

        $model->setCustomerId($data['customer_id'] ?? null);
        $model->setLinkId($data['link_id'] ?? null);
        $model->setLink($data['link'] ?? null);

        return $model;
    }

    public function buildHitModelFromRequest(Request $request): HitModel
    {
        $model = new HitModel();

        $model->setFrom($request->query->get('from'));
        $model->setTo($request->query->get('to'));

        return $model;
    }
}
