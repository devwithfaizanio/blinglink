<?php

namespace App\Notifications\v1;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Exception\RuntimeException;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class FirebaseChannel
{
    public Messaging $messaging;

    public function __construct()
    {
        $this->messaging = $this->connect();
    }

    /**
     * Send the given notification.
     * @throws MessagingException
     * @throws FirebaseException
     */
    public function send(Model $notifiable, Notification $notification): void
    {



        $message = $notification->toFirebase($notifiable);

        if (method_exists($notification, 'getData')) {
            $data = $notification->getData();
        }
        /** @var User $notifiable */
        $deviceToken = $notifiable->fcm_token;

        if (!isset($deviceToken)) {
            throw new RuntimeException('fcm_token not set for this user: ' . $notifiable->name);
        }

//        $this->messaging->send(
//            CloudMessage::withTarget('token', $deviceToken)
//                ->withData($data ?? [])
//                ->withNotification($message)
//        );
        try {
            $response = $this->messaging->send(
                CloudMessage::withTarget('token', $deviceToken)
                    ->withData($data ?? [])
                    ->withNotification($message)
            );

            \Log::error("Notification sent successfully to user ID {$notifiable->id}.");
        } catch (\Kreait\Firebase\Exception\MessagingException $e) {
            \Log::error("Failed to send notification to user ID {$notifiable->id}: " . $e->getMessage());
        } catch (\Kreait\Firebase\Exception\FirebaseException $e) {
            \Log::error("Firebase error while sending notification to user ID {$notifiable->id}: " . $e->getMessage());
        } catch (\Exception $e) {
            \Log::error("General error while sending notification to user ID {$notifiable->id}: " . $e->getMessage());
        }




    }

    public function connect(): Messaging
    {

        $firebase = (new Factory)->withServiceAccount(base_path(config('services.firebase.credentials')));
        return $firebase->createMessaging();
    }
}
