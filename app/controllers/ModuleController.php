<?php

class ModuleController
{
    private ModuleListModel $lists;

    private array $modules = [
        'standards-compliance' => ['title' => 'Standards & Compliance', 'table' => 'standards_compliance'],
        'documents' => ['title' => 'Documents', 'table' => 'documents'],
        'processes' => ['title' => 'Processes', 'table' => 'processes'],
        'risks-issues' => ['title' => 'Risks & Issues', 'table' => 'risks_issues'],
        'audits' => ['title' => 'Audits', 'table' => 'audits'],
        'actions' => ['title' => 'Actions', 'table' => 'actions'],
        'objectives' => ['title' => 'Objectives', 'table' => 'objectives'],
        'management-review' => ['title' => 'Management Review', 'table' => 'management_reviews'],
        'settings' => ['title' => 'Settings', 'table' => 'settings'],
    ];

    public function __construct()
    {
        $this->lists = new ModuleListModel();
    }

    public function index(string $module): void
    {
        require_module_access($module);
        $organizationId = require_org_context();

        if (!isset($this->modules[$module])) {
            set_flash('error', 'Unknown module.');
            redirect('index.php?page=dashboard');
        }

        $limit = 25;
        $offset = 0;
        $config = $this->modules[$module];

        try {
            $rows = $this->lists->fetchList($config['table'], $organizationId, $limit, $offset);
        } catch (Throwable $e) {
            set_flash('warning', 'This module is configured, but its table is not available yet.');
            $rows = [];
        }

        $title = $config['title'];
        require __DIR__ . '/../views/modules/index.php';
    }
}
