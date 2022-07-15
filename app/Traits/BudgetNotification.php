<?php
namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait BudgetNotification
{
    public function registerNotification(string $name, string $email)
    {
        return Http::post(env('WEBHOOK_REGISTER_NOTIFICATION_URL'), [
            'username' => 'Register Notification',
            'content' => "Um novo usuário se registrou na plataforma.",
            'embeds' => [
                [
                    'title' => $name,
                    'description' => "Se registrou com o email **{$email}**.",
                    'color' => '2067276'
                ]
            ],
        ]);
    }
}
