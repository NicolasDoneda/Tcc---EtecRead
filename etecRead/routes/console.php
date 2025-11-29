<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// Promove alunos todo dia 1º de fevereiro às 00:00
Schedule::command('students:promote')
    ->yearlyOn(2, 1, '00:00'); // mês 2 (fevereiro), dia 1, 00:00

// Deleta formandos todo dia 20 de dezembro às 00:00
Schedule::command('students:delete-graduated')
    ->yearlyOn(12, 20, '00:00'); // mês 12 (dezembro), dia 20, 00:00