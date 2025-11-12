<?php

namespace Omadonex\LaravelTools\Support\Services;

use Carbon\Carbon;
use Carbon\Month;
use Carbon\WeekDay;
use DateTimeInterface;
use DateTimeZone;

class LocalTimeService extends OmxService
{
    protected string $timezoneString;
    protected DateTimeZone $timezone;

    public function __construct(string $timezone = 'UTC')
    {
        $this->timezoneString = $timezone;
        $this->timezone = new DateTimeZone($timezone);
    }

    public function timezone(): DateTimeZone
    {
        return $this->timezone;
    }

    public function timezoneString(): string
    {
        return $this->timezoneString;
    }

    public function now(): Carbon
    {
        return Carbon::now($this->timezone);
    }

    public function nowToUTC(): Carbon
    {
        return Carbon::createFromTimestamp($this->now()->getTimestamp(), 'UTC');
    }

    public function parse(DateTimeInterface|WeekDay|Month|string|int|float|null $time): Carbon
    {
        return Carbon::parse($time, $this->timezone);
    }

    public function parseToUTC(DateTimeInterface|WeekDay|Month|string|int|float|null $time): Carbon
    {
        return Carbon::createFromTimestamp($this->parse($time)->getTimestamp(), 'UTC');
    }
}