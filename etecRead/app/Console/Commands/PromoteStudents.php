<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class PromoteStudents extends Command
{
    protected $signature = 'students:promote';
    protected $description = 'Promove alunos para o próximo ano escolar (1º→2º, 2º→3º)';

    public function handle()
    {
        $this->info('🎓 Iniciando promoção de alunos...');

        // Promove alunos do 1º para o 2º ano
        $primeiroAno = User::where('role', 'aluno')
            ->where('ano_escolar', '1')
            ->get();

        foreach ($primeiroAno as $aluno) {
            $aluno->update(['ano_escolar' => '2']);
            $this->info("{$aluno->name} (RM: {$aluno->rm}) promovido para 2º ano");
        }

        // Promove alunos do 2º para o 3º ano
        $segundoAno = User::where('role', 'aluno')
            ->where('ano_escolar', '2')
            ->get();

        foreach ($segundoAno as $aluno) {
            $aluno->update(['ano_escolar' => '3']);
            $this->info("{$aluno->name} (RM: {$aluno->rm}) promovido para 3º ano");
        }

        // Alunos do 3º ano não são promovidos (serão deletados em dezembro)
        $terceiroAno = User::where('role', 'aluno')
            ->where('ano_escolar', '3')
            ->count();

        $this->info("ℹ{$terceiroAno} aluno(s) no 3º ano (serão removidos após formatura)");

        $total = $primeiroAno->count() + $segundoAno->count();
        
        Log::info("Promoção de alunos executada: {$total} alunos promovidos");
        $this->info("Total de alunos promovidos: {$total}");

        return Command::SUCCESS;
    }
}