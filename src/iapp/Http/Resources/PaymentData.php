<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 7/21/20, 6:35 PM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\iPayment\iApp\Http\Resources;

use iLaravel\Core\iApp\Http\Resources\ResourceData;

class PaymentData extends ResourceData
{
    public function toArray($request)
    {
        $data = parent::toArray($request);
        if ($file = $this->icon ?: $this->resource->getFile('icon')) $data['icon'] = @$file['original']->url;
        return $data;
    }
}
