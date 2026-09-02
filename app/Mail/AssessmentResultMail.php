<?php

namespace App\Mail;

use App\Models\AssessmentResult;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class AssessmentResultMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public AssessmentResult $result) {}

    public function envelope(): Envelope
    {
        $assessment = $this->result->assessment;
        $school = $assessment->school;

        return new Envelope(
            subject: "Resultado — {$assessment->name} | {$school->name}",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.assessment-result',
            with: [
                'studentName' => $this->result->student->name,
                'assessmentName' => $this->result->assessment->name,
                'schoolName' => $this->result->assessment->school->name,
                'percentage' => $this->result->total_questions > 0
                    ? round(($this->result->correct_answers / $this->result->total_questions) * 100, 1)
                    : 0,
            ],
        );
    }

    public function attachments(): array
    {
        if (! $this->result->pdf_path || ! Storage::disk('local')->exists($this->result->pdf_path)) {
            return [];
        }

        return [
            Attachment::fromPath(Storage::disk('local')->path($this->result->pdf_path))
                ->as('relatorio-desempenho.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
