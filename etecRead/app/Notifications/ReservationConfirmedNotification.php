<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationConfirmedNotification extends Notification
{
    use Queueable;

    protected $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('📚 Sua Reserva está Disponível!')
                    ->greeting('Olá, ' . $notifiable->name . '! 👋')
                    ->line('Temos uma ótima notícia para você!')
                    ->line('O livro **' . $this->reservation->book->title . '** que você reservou está disponível para retirada.')
                    ->line('📌 Detalhes da Reserva:')
                    ->line('• Livro: ' . $this->reservation->book->title)
                    ->line('• Gênero: ' . $this->reservation->book->category->name)
                    ->line('• Data da Reserva: ' . \Carbon\Carbon::parse($this->reservation->reservation_date)->format('d/m/Y H:i'))
                    ->action('Ver Minhas Reservas', url('/minhas-reservas'))
                    ->line('Por favor, dirija-se à biblioteca para retirar o livro.')
                    ->line('⚠️ **Atenção:** Reserve sua cópia o quanto antes!')
                    ->salutation('Atenciosamente, Equipe da Biblioteca Escolar 📖');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'reservation_id' => $this->reservation->id,
            'book_title' => $this->reservation->book->title,
        ];
    }
}