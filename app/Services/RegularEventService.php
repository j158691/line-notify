<?php

namespace App\Services;

use App\Models\RegularEvent;

class RegularEventService
{
    /**
     * @var RegularEvent
     */
    protected $regularEvent;

    /**
     * RegularEventService constructor.
     *
     * @param  RegularEvent  $regularEvent
     */
    public function __construct(
        RegularEvent $regularEvent
    )
    {
        $this->regularEvent = $regularEvent;
    }

    public function createRegularEvent(
        $userId,
        $event,
        $time,
        $sunday,
        $monday,
        $tuesday,
        $wednesday,
        $thursday,
        $friday,
        $saturday,
        $enabled
    )
    {
        $create = [
            'user_id'   => $userId,
            'event'     => $event,
            'time'      => $time,
            'sunday'    => $sunday,
            'monday'    => $monday,
            'tuesday'   => $tuesday,
            'wednesday' => $wednesday,
            'thursday'  => $thursday,
            'friday'    => $friday,
            'saturday'  => $saturday,
            'enabled'   => $enabled,
        ];

        return $this->regularEvent->newModelQuery()->create($create);
    }

    public function getNotifyRegularEvents($time, $column)
    {
        return $this->regularEvent
            ->newModelQuery()
            ->where('time', $time)
            ->where($column, 1)
            // ->where(function ($subQuery) {
            //     $subQuery->where('sunday', 1)
            //         ->orWhere('monday', 1)
            //         ->orWhere('tuesday', 1)
            //         ->orWhere('wednesday', 1)
            //         ->orWhere('thursday', 1)
            //         ->orWhere('friday', 1)
            //         ->orWhere('saturday', 1);
            // })
            ->where('enabled', 1)
            ->whereNull('deleted_at')
            ->get();
    }

    public function getRegularEventsForRecord($userId)
    {
        return $this->regularEvent
            ->newModelQuery()
            ->where('user_id', $userId)
            ->whereNull('deleted_at')
            ->get();
    }

    public function getRegularEventForUser($regularEventId, $userId)
    {
        return $this->regularEvent
            ->newModelQuery()
            ->where('id', $regularEventId)
            ->where('user_id', $userId)
            ->whereNull('deleted_at')
            ->first();
    }
}
