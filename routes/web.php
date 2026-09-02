<?php

use App\Models\AssessmentResult;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});

Route::get("/pdf", function () {
    return view('pdf-view');
});

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
