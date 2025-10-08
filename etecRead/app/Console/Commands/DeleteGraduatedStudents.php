<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DeleteGraduatedStudents extends Command
{
    protected $signature = 'students:delete-graduated';
    protected $description = 'Deleta (soft delete) alunos do 3º ano após 20 de dezembro';

    public function handle()
    {
        $hoje = Carbon::now();
        
        // Verifica se é após 20 de dezembro
        if ($hoje->month == 12 && $hoje->day >= 20) {
            
            $alunos = User::where('role', 'aluno')
                ->where('ano_escolar', '3')
                ->get();

            $count = 0;

            foreach ($alunos as $aluno) {
                $this->info("Deletando: {$aluno->name} (RM: {$aluno->rm})");
                $aluno->delete(); // Soft delete
                $count++;
            }

            Log::info("Alunos formandos deletados: {$count}");
            $this->info("✅ Total de alunos deletados: {$count}");
            
            return Command::SUCCESS;
        }
        
        $this->info("⏳ Ainda não é período de exclusão (após 20/12)");
        return Command::SUCCESS;
    }
}