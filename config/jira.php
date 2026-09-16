<?php

$jiraEnv = [];
$jiraEnvPath = base_path('.env.jira');
if (file_exists($jiraEnvPath)) {
    $lines = file($jiraEnvPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line !== '' && ! str_starts_with($line, '#') && str_contains($line, '=')) {
            [$k, $v] = explode('=', $line, 2);
            $jiraEnv[trim($k)] = trim(trim($v), "\"'");
        }
    }
}

return [
    'url' => rtrim(env('JIRA_URL', $jiraEnv['JIRA_URL'] ?? ''), '/'),
    'email' => env('JIRA_EMAIL', $jiraEnv['JIRA_EMAIL'] ?? ''),
    'token' => env('JIRA_API_TOKEN', $jiraEnv['JIRA_API_TOKEN'] ?? ''),
    'project_key' => env('JIRA_PROJECT_KEY', $jiraEnv['JIRA_PROJECT_KEY'] ?? 'UT'),
];
