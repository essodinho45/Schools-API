<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Models\Remark;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SendRemarks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remarks:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending remarks';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('sending remarks'.PHP_EOL);
        $remarks = Remark::
        where('is-sent-firebase', 0)
        ->where('is-sent', 0)
        ->where('is-read', 0)
        ->get();
        foreach ($remarks as $remark)
        {
            try{
                $fcmToken = $remark->student->user->device_key;
                if ($fcmToken == null)
                {
                    $this->info('remark '.$remark->id.': user has no firebase token'.PHP_EOL);
                    continue;
                }
                $messaging = app('firebase.messaging');
                $message = CloudMessage::withTarget('token', $fcmToken)
                    ->withNotification(Notification::create($remark->title, $remark->text));

                $messaging->send($message);

                $remark->{'is-sent-firebase'} = true;
                $remark->save();
                $this->info('remark '.$remark->id.': sent succesfully'.PHP_EOL);
            }
            catch (\Throwable $e) {
                Log::info($e->getMessage());
                $message = $e->getMessage();
                if (Str::contains($message, 'The token has been unregistered from the project'))
                    $this->info('remark '.$remark->id.': token not valid'.PHP_EOL);
                continue;
            }
        }
        $this->info('all remarks processed');
    }
}
