<?php

namespace App\Console\Commands;

use App\Services\JiraService;
use Illuminate\Console\Command;

class SyncJiraBacklog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jira:sync-backlog 
                            {--board : Consultar únicamente el ID y estado del tablero en Jira}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica la conexión con Jira Cloud y permite consultar el tablero y estado del backlog.';

    public function __construct(public JiraService $jira)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (! $this->jira->isConfigured()) {
            $this->error('❌ Error: Faltan credenciales de Jira en .env.jira o .env (JIRA_URL, JIRA_EMAIL, JIRA_API_TOKEN).');

            return self::FAILURE;
        }

        $this->info('🔍 Verificando conectividad con Jira Cloud...');
        $boardId = $this->jira->getBoardId();

        if ($boardId) {
            $this->info(sprintf('📌 Tablero Jira detectado ID: %d (Proyecto: %s)', $boardId, $this->jira->projectKey));
        } else {
            $this->warn(sprintf('⚠️ No se pudo obtener el tablero principal para el proyecto %s.', $this->jira->projectKey));
        }

        $this->info('✅ Conexión con Jira Cloud validada exitosamente.');

        return self::SUCCESS;
    }
}
