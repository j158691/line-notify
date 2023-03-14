<?php

namespace App\Services;

use App\Models\Memo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class MemoService
{
    /**
     * @var Memo
     */
    protected $memo;

    /**
     * MemoService constructor.
     *
     * @param  Memo  $memo
     */
    public function __construct(
        Memo $memo
    )
    {
        $this->memo = $memo;
    }

    /**
     * @param integer $userId
     * @param string $event
     * @param string $notifyTime
     * @return Memo|Builder|Model
     */
    public function createMemo($userId, $event, $notifyTime)
    {
        $create = [
            'user_id' => $userId,
            'event' => $event,
            'notify_time' => $notifyTime,
        ];

        return $this->memo->newModelQuery()->create($create);
    }

    /**
     * @param $notifyTimeStart
     * @param $notifyTimeEnd
     * @return Memo[]|Builder[]|Collection
     */
    public function getNotifyMemos($notifyTimeStart, $notifyTimeEnd)
    {
        return $this->memo
            ->newModelQuery()
            ->where('notify_time', '>=', $notifyTimeStart)
            ->where('notify_time', '<=', $notifyTimeEnd)
            ->get();
    }
}
