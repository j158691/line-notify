<?php

namespace App\Console\Commands;

use App\Services\LineNotifyService;
use App\Services\RegularEventService;
use App\Services\UserService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class CronNotifyRegularEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron-notify-regular-event';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * @var RegularEventService
     */
    protected $regularEventService;
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
     * @param  RegularEventService  $regularEventService
     * @param  LineNotifyService  $lineNotifyService
     * @param  UserService  $userService
     * @return void
     */
    public function handle(
        RegularEventService $regularEventService,
        LineNotifyService $lineNotifyService,
        UserService $userService
    )
    {
        $this->regularEventService = $regularEventService;
        $this->lineNotifyService   = $lineNotifyService;
        $this->userService         = $userService;

        $time = Carbon::now()->format('H:i:00');
        $time = '12:17:00';
        $resultRegularEvent = $this->regularEventService->getNotifyRegularEvents($time);
        dd($resultRegularEvent->toArray());

        $user_ids = $resultRegularEvent->pluck('user_id')->toArray();

        $resultUsers = $this->userService->findUsers($user_ids)->keyBy('id');

        $resultRegularEvent->each(function ($regularEvent) use ($resultUsers) {
            $user_id = $regularEvent->user_id;
            $event = "\n".$regularEvent->event;

            $user = $resultUsers->get($user_id);
            $line_notify = $user->line_notify;

            $this->lineNotifyService->postNotify($event, $line_notify);

            Log::info("(定期)使用者:{$user_id},發送完畢".Carbon::now()->toDateTimeString());
        });
    }
}
