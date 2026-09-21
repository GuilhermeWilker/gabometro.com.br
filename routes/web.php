<?php

use App\Http\Controllers\AsaasWebhookController;
use App\Models\AssessmentResult;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::view('/', 'landing')->name('home');
Route::view('/privacidade', 'legal.privacy')->name('privacy');
Route::view('/termos', 'legal.terms')->name('terms');

Route::get("/pdf", function () {
    return view('pdf-view');
});

Route::post('/webhook/asaas', [AsaasWebhookController::class, 'handle'])
    ->name('webhook.asaas');

Route::get('/admin/results/{result}/pdf', function (AssessmentResult $result) {
    abort_unless(
        filled($result->pdf_path) && Storage::disk('local')->exists($result->pdf_path),
        404
    );

    return response()->file(
        Storage::disk('local')->path($result->pdf_path),
        ['Content-Type' => 'application/pdf']
    );
})->middleware(['web', 'auth'])->name('students.results.pdf.view');
