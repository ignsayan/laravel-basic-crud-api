<?php

namespace App\Extends;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class JsonResourceResponse
{
    private $resource;
    private $code;
    private $msg = null;
    private $status_code = 200;

    public function __construct($resource, int $code, string $msg, int $status_code = null)
    {
        $resource = $resource ? $resource : [];
        if (
            is_subclass_of($resource, JsonResource::class) ||
            is_subclass_of($resource, ResourceCollection::class) ||
            is_array($resource)
        ) {
            $this->resource = $resource;
        } else {
            throw new \RuntimeException('Invalid data format found');
        }

        $this->code = $code;
        $this->status_code = $status_code ?? $code;
        $this->msg = $msg;
    }

    public function response(): JsonResponse
    {
        if (
            is_subclass_of($this->resource, JsonResource::class) ||
            is_subclass_of($this->resource, ResourceCollection::class)
        ) {
            return $this->resource->additional([
                'code' => $this->code, 'msg' => $this->msg
            ])->response();
        }

        return response()->json(array_merge($this->resource, [
            'code' => $this->code,
            'msg' => $this->msg
        ]), $this->status_code);
    }
}
