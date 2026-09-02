<?php

namespace App\Jobs;

use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\User;
use App\Notifications\AssessmentEmailProgressNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Notification;

class SendAssessmentEmails implements ShouldQueue
{
      use Queueable;

      public function __construct(
            public int $assessmentId,
            public int $userId, // quem clicou em "Enviar"
      ) {}

      public function handle(): void
      {
            $assessment = Assessment::findOrFail($this->assessmentId);
            $user = User::find($this->userId);

            $assessment->update(['status' => 'sending']);

            $results = AssessmentResult::query()
                  ->where('assessment_id', $assessment->id)
                  ->with('student')
                  ->get();

            $total = $results->count();
            $sent = 0;
            $skipped = 0;
            $failed = 0;

            foreach ($results as $index => $result) {
                  try {
                        // reutiliza o job individual (ou chame o service direto)
                        SendAssessmentResultEmail::dispatchSync($result->id);
                        // se o job individual não enviou por falta de e-mail, trate como skipped
                        $result->refresh();
                        if ($result->email_sent_at) {
                              $sent++;
                        } else {
                              $skipped++;
                        }
                  } catch (\Throwable $e) {
                        $failed++;
                        report($e);
                  }

                  // notifica a cada 5 alunos ou no final (evita spam de notification)
                  $current = $index + 1;
                  if ($user && ($current % 5 === 0 || $current === $total)) {
                        $user->notify(new AssessmentEmailProgressNotification(
                              assessmentName: $assessment->name,
                              current: $current,
                              total: $total,
                              sent: $sent,
                              skipped: $skipped,
                              failed: $failed,
                              finished: $current === $total,
                        ));
                  }
            }

            $assessment->update([
                  'status' => $failed > 0 && $sent === 0 ? 'error' : 'sent',
            ]);
      }
}
