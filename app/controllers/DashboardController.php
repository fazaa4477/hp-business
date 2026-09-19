<?php
declare(strict_types=1);

class DashboardController
{
    private Dashboard $dashboard;

    public function __construct(PDO $pdo)
    {
        $this->dashboard = new Dashboard($pdo);
    }

    public function index(): void
    {
        require_auth();
        render('dashboard/index', [
            'pageTitle' => 'HP Business — Dashboard',
            'today' => date('d M Y'),
            'summary' => $this->dashboard->summary(),
            'recentTransactions' => $this->dashboard->recentTransactions(),
        ]);
    }
}
