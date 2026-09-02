<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AssessmentEmailProgressNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $assessmentName,
        public int $current,
        public int $total,
        public int $sent,
        public int $skipped,
        public int $failed,
        public bool $finished = false,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database']; // aparece no sininho do Filament
    }

    public function toArray(object $notifiable): array
    {
        if ($this->finished) {
            return [
                'title' => "Envio concluído: {$this->assessmentName}",
                'body' => "Enviados: {$this->sent} · Sem e-mail: {$this->skipped} · Falhas: {$this->failed}",
                'status' => $this->failed > 0 ? 'warning' : 'success',
            ];
        }

        return [
            'title' => "Enviando: {$this->assessmentName}",
            'body' => "Progresso {$this->current}/{$this->total} (enviados: {$this->sent})",
            'status' => 'info',
        ];
    }
}
