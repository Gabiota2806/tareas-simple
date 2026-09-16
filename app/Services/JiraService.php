<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class JiraService
{
    public string $url;

    public string $email;

    public string $token;

    public string $projectKey;

    public function __construct(
        ?string $url = null,
        ?string $email = null,
        ?string $token = null,
        ?string $projectKey = null
    ) {
        $rawUrl = $url ?? config('jira.url', '');
        if (str_contains($rawUrl, '.atlassian.net')) {
            $rawUrl = explode('.atlassian.net', $rawUrl)[0].'.atlassian.net';
        }

        $this->url = rtrim($rawUrl, '/');
        $this->email = $email ?? config('jira.email', '');
        $this->token = $token ?? config('jira.token', '');
        $this->projectKey = $projectKey ?? config('jira.project_key', 'UT');
    }

    /**
     * Verifica si el servicio cuenta con las credenciales requeridas.
     */
    public function isConfigured(): bool
    {
        return ! empty($this->url) && ! empty($this->email) && ! empty($this->token);
    }

    /**
     * Obtiene el cliente HTTP preconfigurado con autenticación Basic.
     */
    protected function client(): PendingRequest
    {
        return Http::baseUrl($this->url)
            ->withBasicAuth($this->email, $this->token)
            ->acceptJson()
            ->asJson()
            ->timeout(15);
    }

    /**
     * Consulta una incidencia en Jira por su clave oficial (ej. UT-222).
     */
    public function getIssue(string $key): ?array
    {
        if (! $this->isConfigured()) {
            Log::warning('JiraService: Intentando consultar incidencia sin credenciales configuradas.');

            return null;
        }

        $response = $this->client()->get('/rest/api/2/issue/'.strtoupper(trim($key)));

        if ($response->successful()) {
            return $response->json();
        }

        Log::warning('JiraService: Error al consultar incidencia', [
            'key' => $key,
            'status' => $response->status(),
            'body' => $response->json(),
        ]);

        return null;
    }

    /**
     * Busca una incidencia mediante JQL por coincidencia en el summary (ej. TASK-131).
     */
    public function searchBySummary(string $query, ?string $projectKey = null): ?array
    {
        if (! $this->isConfigured()) {
            Log::warning('JiraService: Intentando buscar incidencia sin credenciales configuradas.');

            return null;
        }

        $project = $projectKey ?? $this->projectKey;
        $jql = sprintf('project = "%s" AND summary ~ "\"%s\"" ORDER BY created DESC', $project, addslashes(trim($query)));

        $response = $this->client()->get('/rest/api/3/search/jql', [
            'jql' => $jql,
            'maxResults' => 1,
            'fields' => 'key,summary',
        ]);

        if ($response->successful()) {
            $issues = $response->json('issues', []);

            return $issues[0] ?? null;
        }

        Log::warning('JiraService: Error al buscar incidencia por JQL', [
            'query' => $query,
            'status' => $response->status(),
            'body' => $response->json(),
        ]);

        return null;
    }

    /**
     * Crea una incidencia (Épica, Historia, Tarea o Subtarea) en Jira Cloud.
     */
    public function createIssue(
        string $summary,
        string $description,
        string $targetType = 'Story',
        ?string $parentKey = null,
        ?int $storyPoints = null
    ): ?array {
        if (! $this->isConfigured()) {
            Log::warning('JiraService: Intentando crear incidencia sin credenciales configuradas.');

            return null;
        }

        $fields = [
            'project' => ['key' => $this->projectKey],
            'summary' => $summary,
            'description' => $description,
            'issuetype' => ['name' => $targetType],
        ];

        if (! empty($parentKey)) {
            $fields['parent'] = ['key' => $parentKey];
        }

        if ($storyPoints !== null) {
            $fields['customfield_10016'] = $storyPoints;
        }

        $response = $this->client()->post('/rest/api/2/issue', [
            'fields' => $fields,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('JiraService: Error al crear incidencia', [
            'summary' => $summary,
            'status' => $response->status(),
            'body' => $response->json(),
        ]);

        return null;
    }

    /**
     * Obtiene el ID del tablero principal de Jira para el proyecto configurado.
     */
    public function getBoardId(?string $projectKey = null): ?int
    {
        if (! $this->isConfigured()) {
            return null;
        }

        $project = $projectKey ?? $this->projectKey;
        $response = $this->client()->get('/rest/agile/1.0/board', [
            'projectKeyOrId' => $project,
        ]);

        if ($response->successful()) {
            $values = $response->json('values', []);
            if (! empty($values)) {
                return (int) $values[0]['id'];
            }
        }

        return null;
    }

    /**
     * Sanitiza el texto del título para generar nombres de ramas válidos y limpios.
     */
    public function sanitizeBranchName(string $text): string
    {
        $replacements = [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
            'ñ' => 'n', 'Ñ' => 'N',
        ];
        $text = strtr($text, $replacements);
        $text = preg_replace('/[:\/\\\\_]/', '-', $text) ?? $text;
        $text = preg_replace('/[^a-zA-Z0-9\-]/', '-', $text) ?? $text;
        $text = preg_replace('/-+/', '-', $text) ?? $text;

        return trim($text, '-');
    }

    /**
     * Genera el nombre estandarizado de la rama a partir de la clave y el resumen.
     */
    public function generateBranchName(string $issueKey, string $summary): string
    {
        $sanitizedSummary = $this->sanitizeBranchName($summary);

        if (str_starts_with(strtoupper($sanitizedSummary), strtoupper($issueKey))) {
            $branchName = $sanitizedSummary;
        } else {
            $branchName = sprintf('%s-%s', $issueKey, $sanitizedSummary);
        }

        if (strlen($branchName) > 120) {
            $branchName = rtrim(substr($branchName, 0, 120), '-');
        }

        return $branchName;
    }
}
