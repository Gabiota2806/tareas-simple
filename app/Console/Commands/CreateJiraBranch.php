<?php

namespace App\Console\Commands;

use App\Services\JiraService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class CreateJiraBranch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jira:create-branch 
                            {key : Clave o ID de la tarea en Jira (ej. UT-222, TASK-131, 222)} 
                            {--dry-run : Simular la consulta y el nombre de rama sin ejecutar cambios en Git}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consulta una tarea en Jira Cloud, obtiene su clave y título oficial, y genera la rama estandarizada en Git.';

    public function __construct(public JiraService $jira)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $issueQuery = trim((string) $this->argument('key'));
        $dryRun = (bool) $this->option('dry-run');
        $projectKey = $this->jira->projectKey;

        if (empty($issueQuery)) {
            $this->error('❌ Debes proporcionar una clave o ID de tarea (ej. UT-222 o TASK-131).');

            return self::FAILURE;
        }

        if (! $this->jira->isConfigured()) {
            $this->error('❌ Error: Faltan credenciales de Jira en .env.jira o .env (JIRA_URL, JIRA_EMAIL, JIRA_API_TOKEN).');

            return self::FAILURE;
        }

        // Si nos pasan solo un número como "222", asumimos el project_key
        if (ctype_digit($issueQuery)) {
            $issueQuery = sprintf('%s-%s', $projectKey, $issueQuery);
        }

        $this->info(sprintf("🔍 Consultando Jira Cloud para '%s'...", $issueQuery));

        $issueKey = null;
        $issueSummary = null;

        if (str_starts_with(strtoupper($issueQuery), strtoupper($projectKey).'-')) {
            // Búsqueda directa por Key
            $data = $this->jira->getIssue($issueQuery);
            if (! $data) {
                $this->error(sprintf('❌ No se encontró la incidencia %s en Jira.', $issueQuery));

                return self::FAILURE;
            }
            $issueKey = $data['key'] ?? null;
            $issueSummary = $data['fields']['summary'] ?? null;
        } else {
            // Búsqueda por JQL (ej. TASK-131 en summary)
            $data = $this->jira->searchBySummary($issueQuery, $projectKey);
            if (! $data) {
                $this->error(sprintf("❌ No se encontraron tareas que coincidan con '%s' en el proyecto %s.", $issueQuery, $projectKey));

                return self::FAILURE;
            }
            $issueKey = $data['key'] ?? null;
            $issueSummary = $data['fields']['summary'] ?? null;
        }

        if (empty($issueKey) || empty($issueSummary)) {
            $this->error('❌ Error: No se pudieron extraer la clave y el resumen de la incidencia.');

            return self::FAILURE;
        }

        $this->info(sprintf('📌 Incidencia detectada: [%s] %s', $issueKey, $issueSummary));

        // Formatear el nombre de la rama
        $branchName = $this->jira->generateBranchName($issueKey, $issueSummary);
        $this->info(sprintf('🌿 Nombre de rama generado: %s', $branchName));

        if ($dryRun) {
            $this->comment('ℹ️  Modo dry-run: No se creará la rama en Git.');

            return self::SUCCESS;
        }

        // Validar estado de Git
        $statusProcess = Process::path(base_path())->run('git status --porcelain');
        $status = trim($statusProcess->output());
        if (! empty($status)) {
            $this->warn('⚠️  El árbol de trabajo tiene cambios pendientes. Por favor limpia tu working directory antes de crear la rama.');
            $this->line($status);

            return self::FAILURE;
        }

        $this->info("🔄 Sincronizando rama 'develop'...");
        $checkoutDev = Process::path(base_path())->run('git checkout develop');
        if (! $checkoutDev->successful()) {
            $this->error("❌ Error al cambiar a 'develop': ".$checkoutDev->errorOutput());

            return self::FAILURE;
        }

        $pullDev = Process::path(base_path())->run('git pull origin develop');
        if (! $pullDev->successful()) {
            $this->warn("⚠️  Aviso: 'git pull origin develop' reportó una advertencia o error: ".$pullDev->errorOutput());
        }

        $this->info(sprintf("🌱 Creando y cambiando a nueva rama '%s'...", $branchName));
        $checkoutBranch = Process::path(base_path())->run(sprintf("git checkout -b '%s'", $branchName));
        if (! $checkoutBranch->successful()) {
            $this->error('❌ Error al crear la rama: '.$checkoutBranch->errorOutput());

            return self::FAILURE;
        }

        $this->info(sprintf("🚀 Empujando rama '%s' a GitHub...", $branchName));
        $pushBranch = Process::path(base_path())->run(sprintf("git push -u origin '%s'", $branchName));
        if (! $pushBranch->successful()) {
            $this->warn('⚠️  Aviso: No se pudo empujar la rama a GitHub automáticamente: '.$pushBranch->errorOutput());
            $this->line(sprintf("💡 Puedes empujarla manualmente en tu terminal con: git push -u origin '%s'", $branchName));
        }

        $this->newLine();
        $this->line('================================================================');
        $this->info('✅ RAMA CREADA Y VINCULADA A JIRA EXITOSAMENTE:');
        $this->info(sprintf('🌿 Rama: %s', $branchName));
        $this->info(sprintf('🎯 Incidencia Jira: %s', $issueKey));
        $this->line('================================================================');

        return self::SUCCESS;
    }
}
