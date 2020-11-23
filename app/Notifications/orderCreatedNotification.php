<?php

namespace App\Notifications;

use App\Channels\HotSms;
use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\NexmoMessage;

class orderCreatedNotification extends Notification
{
    use Queueable;
    protected $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable 
     * @return array
     */
    public function via($notifiable) // تاخذ اكثر من قناة مثل سلاك نيكسمو الخ
    {
        // $notifiable عبارة عن اوبجكت لكي افحص عليها في حال وضع شرط لليوزر اين يستقبل الرسالة
    return ['mail' /*'slack' */, 'database' , 'broadcast' , /*'nexmo'*/ HotSms::class ];
        /* مثال
        if($notifiable->noty_user){
            return ['mail'];
        }else{
            return ['database'];
        }*/
        /* مثال 2
        $via = ['database'];
        if($notifiable->email_notify){
            $via[] = 'mail';
        }
        if($notifiable->sms_notify){
            $via[] = 'nexmo';
        }
        return $via;*/
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('new order')//عنوان الرسلة ان لم تكن ياخذ الافتراضي
                   // ->from('') //  الرسالة من وين اجت ان لم تكن موجودة ياخذ من كونفق ميل
                    ->greeting('hello '.$notifiable->name) // رسالة ترحيب
                    ->line('A new order has been created (order #' . $this->order->id .').')
                    ->action('Notification Action', url(route('orders')))
                    ->line('Thank you for using our application!');
                    /*
                    طريقة ثانية الي فوق مختصرة
        $message = new MailMessage;
        $message->line('A new order has been created order #' . $this->order->id .')')
                ->action('Notification Action', url(route('orders')))
                ->line('Thank you for using our application!');
        return $message;*/
    }
    public function toDatabase($notifiable){
        return [
            'message' => 'A new order has been created (order #' . $this->order->id .').',
            'action' => route('orders'),
            'icone' => '<i class="fas fa-file-invoice"></i>',//  من فونت اوثم 
        ];
    }
    public function toBroadcast($notifiable){
        return [
            'message' => 'A new order has been created (order #' . $this->order->id .').',
            'action' => route('orders'),
            'icone' => '<i class="fas fa-file-invoice"></i>',//  من فونت اوثم
            'order' => $this->order, 
        ];
    }
    public function toNexmo(){/* unicode هاد فقط للغة العربية */
        $message = new NexmoMessage();
        $message->content('A new order Created !!')
                ->from('Test');
        return $message;
    }
    public function toHotsms($notifiable){
        return 'A new order Created !';
    }
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
