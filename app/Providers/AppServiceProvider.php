<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Personalizar el correo de verificación
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('¡Bienvenido a UniTask! Verificá tu correo')
                ->greeting('¡Hola, ' . $notifiable->name . '!')
                ->line('Gracias por registrarte en UniTask. Estamos muy emocionados de que empieces a organizar tu vida universitaria con nosotros.')
                ->line('Para poder acceder a tu tablero y empezar a cargar tus materias y tareas, por favor confirmá tu correo electrónico haciendo clic en el siguiente botón:')
                ->action('Verificar Correo Electrónico', $url)
                ->line('Si no fuiste vos quien creó esta cuenta, podés ignorar y eliminar este correo sin problemas.')
                ->salutation('¡Muchos éxitos! El equipo de UniTask.');
        });
    }
}
