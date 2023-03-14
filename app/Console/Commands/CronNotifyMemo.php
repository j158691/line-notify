<?php

namespace App\Console\Commands;

use App\Services\LineNotifyService;
use App\Services\MemoService;
use App\Services\UserService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CronNotifyMemo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron-notify-memo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * @var MemoService
     */
    protected $memoService;
    /**
     * @var LineNotifyService
     */
    protected $lineNotifyService;
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param  MemoService  $memoService
     * @param  LineNotifyService  $lineNotifyService
     * @param  UserService  $userService
     * @return void
     */
    public function handle(
        MemoService $memoService,
        LineNotifyService $lineNotifyService,
        UserService $userService
    )
    {
        $this->memoService = $memoService;
        $this->lineNotifyService = $lineNotifyService;
        $this->userService = $userService;

        $notify_time_start = Carbon::now()->addHour()->startOfMinute()->toDateTimeString();
        $notify_time_end = Carbon::now()->addHour()->endOfMinute()->toDateTimeString();

        $resultMemos = $this->memoService->getNotifyMemos('2023-03-13 00:00:00', '2023-03-14 19:19:59');
        $user_ids = $resultMemos->pluck('user_id')->toArray();

        $resultUsers = $this->userService->findUsers($user_ids)->keyBy('id');

        $resultMemos->each(function ($memo) use ($resultUsers) {
            $user_id = $memo->user_id;
            $event = $memo->event;

            $user = $resultUsers->get($user_id);
            $line_notify = $user->line_notify;

            $this->lineNotifyService->postNotify($event, $line_notify);
        });
    }
}
