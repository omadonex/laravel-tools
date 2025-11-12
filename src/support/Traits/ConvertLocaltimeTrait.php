<?php

namespace Omadonex\LaravelTools\Support\Traits;

use Carbon\Carbon;
use Omadonex\LaravelTools\Support\Services\LocalTimeService;
use Illuminate\Http\Resources\Json\JsonResource;

trait ConvertLocaltimeTrait
{
    public function convertFromLocaltimeToUTCValues(array $data, array $fields, LocalTimeService $localTimeService): array
    {
        foreach ($data as $key => $value) {
            if (in_array($key, $fields)) {
                $data[$key] = $localTimeService->parseToUTC($value);
            }
        }

        return $data;
    }

    public function convertToLocaltimeFromUTCValues(array $data, array $fields, LocalTimeService $localTimeService): array
    {
        foreach ($data as $key => $value) {
            if (in_array($key, $fields)) {
                $data[$key] = Carbon::createFromTimestamp($value->getTimestamp(), $localTimeService->timezone());
            }
        }

        return $data;
    }

    public function convertToLocaltimeFromUTCValuesResource(JsonResource $resource, array $fields, LocalTimeService $localTimeService, string $format = 'd.m.Y H:i:s'): JsonResource
    {
        foreach ($fields as $field) {
            $resource->$field = $resource->$field->setTimezone($localTimeService->timezone())->format($format);
        }

        return $resource;
    }
}
