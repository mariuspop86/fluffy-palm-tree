<?php

namespace App\Transformer;

use App\Model\HeatmapModel;
use App\Model\HitModel;
use Symfony\Component\HttpFoundation\Request;

class HeatmapTransformer
{
    public function convertDataToHeatmapModel(array $data): HeatmapModel
    {
        $model = new HeatmapModel();
        
        $model->setCustomerId($data['customer_id'] ?? null);
        $model->setLinkId($data['link_id'] ?? null);
        $model->setLink($data['link'] ?? null);
        
        return $model;
    }

    public function convertDataToHitModel(Request $request): HitModel
    {
        $model = new HitModel();

        $model->setFrom($request->query->get('from'));
        $model->setTo($request->query->get('to'));

        return $model;
    }
}
