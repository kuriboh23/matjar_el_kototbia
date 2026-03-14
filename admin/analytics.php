<?php
/**
 * FILE: admin/analytics.php
 * PURPOSE: Admin analytics and statistics page.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_auth_check.php';

$admin_page_title = 'Statistiques & Analytics';

// Fetch Data
$daily_revenue = get_revenue_data_daily(30);
$monthly_revenue = get_revenue_data_monthly();
$yearly_revenue = get_revenue_data_yearly();
$top_customers = get_top_customers(5);
$top_products = get_top_selling_products(5);
$status_stats = get_order_status_stats();

// Prepare Chart Data
$daily_labels = array_keys($daily_revenue);
$daily_values = array_values($daily_revenue);

$monthly_labels = array_keys($monthly_revenue);
$monthly_values = array_values($monthly_revenue);

$status_labels = [];
$status_values = [];
$status_colors = [];

$status_colors_map = [
    'pending'          => '#ff8200', // Princeton Orange
    'confirmed'        => '#3b82f6', // Blue
    'preparing'        => '#8b5cf6', // Violet
    'out_for_delivery' => '#0ea5e9', // Sky Blue
    'delivered'        => '#10b981', // Emerald/Green
    'cancelled'        => '#ef4444'  // Red
];

foreach ($status_stats as $s) {
    $status_labels[] = translate('status_' . $s['order_status']);
    $status_values[] = $s['count'];
    $status_colors[] = $status_colors_map[$s['order_status']] ?? '#94a3b8'; // Default gray if status unknown
}

// Global Stats
$lifetime_revenue = get_lifetime_revenue();
$avg_order_value = get_average_order_value();
$active_customers = get_active_customers_count();

require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="analytics-container">
    
    <!-- Mini Stats Row -->
    <div class="stats-grid" style="margin-bottom: 30px;">
        <div class="stat-box">
            <small>Revenu Global</small>
            <h2 style="color: var(--success);"><?= format_price($lifetime_revenue) ?></h2>
        </div>
        <div class="stat-box">
            <small>Panier Moyen</small>
            <h2 style="color: var(--princeton-orange);"><?= format_price($avg_order_value) ?></h2>
        </div>
        <div class="stat-box">
            <small>Clients Actifs</small>
            <h2><?= $active_customers ?></h2>
        </div>
        <div class="stat-box">
            <small>Année en cours</small>
            <h2 style="color: var(--carbon-black);"><?= date('Y') ?></h2>
        </div>
    </div>
    
    <!-- Revenue Charts -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
        <div class="card">
            <h3 style="margin-bottom: 20px; font-weight: 900;">Chiffre d'Affaires (30 derniers jours)</h3>
            <canvas id="dailyRevenueChart" height="150"></canvas>
        </div>
        <div class="card">
            <h3 style="margin-bottom: 20px; font-weight: 900;">Revenu Mensuel (<?= date('Y') ?>)</h3>
            <canvas id="monthlyRevenueChart" height="150"></canvas>
        </div>
    </div>

    <!-- Middle Row: Top Products & Status -->
    <div style="display: grid; grid-template-columns: 1fr 0.6fr; gap: 30px; margin-bottom: 30px;">
        <div class="card">
            <h3 style="margin-bottom: 20px; font-weight: 900;">Produits les plus vendus</h3>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Quantité Vendue</th>
                            <th>Revenu Généré</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($top_products as $p): ?>
                            <tr>
                                <td><?= htmlspecialchars($p['order_item_name_fr']) ?></td>
                                <td style="font-weight: 800;"><?= $p['total_qty'] ?></td>
                                <td style="font-weight: 800; color: var(--success);"><?= format_price($p['total_revenue']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <h3 style="margin-bottom: 20px; font-weight: 900;">Statut des Commandes</h3>
            <canvas id="statusChart" height="250"></canvas>
        </div>
    </div>

    <!-- Bottom Row: Top Customers & Yearly Summary -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
        <div class="card">
            <h3 style="margin-bottom: 20px; font-weight: 900;">Top Clients (Dépenses)</h3>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Nb. Commandes</th>
                            <th>Total Dépensé</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($top_customers as $c): ?>
                            <tr>
                                <td>
                                    <div style="font-weight: 800;"><?= htmlspecialchars($c['order_customer_name']) ?></div>
                                    <div style="font-size: 12px; color: var(--text-muted);"><?= htmlspecialchars($c['order_customer_phone']) ?></div>
                                </td>
                                <td style="font-weight: 700;"><?= $c['order_count'] ?></td>
                                <td style="font-weight: 800; color: var(--princeton-orange);"><?= format_price($c['total_spent']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card">
            <h3 style="margin-bottom: 20px; font-weight: 900;">Résumé Annuel</h3>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Année</th>
                            <th>Revenu Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($yearly_revenue as $y): ?>
                            <tr>
                                <td style="font-weight: 800;"><?= $y['year'] ?></td>
                                <td style="font-weight: 800; color: var(--success); font-size: 18px;"><?= format_price($y['revenue']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Colors
    const primaryColor = '#ff8200';
    const secondaryColor = '#171711';
    
    // Daily Revenue Chart
    new Chart(document.getElementById('dailyRevenueChart'), {
        type: 'line',
        data: {
            labels: <?= json_encode($daily_labels) ?>,
            datasets: [{
                label: 'Revenu (DH)',
                data: <?= json_encode($daily_values) ?>,
                borderColor: primaryColor,
                backgroundColor: 'rgba(255, 130, 0, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: primaryColor
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f0f0f0' } },
                x: { grid: { display: false } }
            }
        }
    });

    // Monthly Revenue Chart
    new Chart(document.getElementById('monthlyRevenueChart'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($monthly_labels) ?>,
            datasets: [{
                label: 'Revenu Mensuel',
                data: <?= json_encode($monthly_values) ?>,
                backgroundColor: secondaryColor,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f0f0f0' } },
                x: { grid: { display: false } }
            }
        }
    });

    // Status Distribution Chart
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($status_labels) ?>,
            datasets: [{
                data: <?= json_encode($status_values) ?>,
                backgroundColor: <?= json_encode($status_colors) ?>,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
            },
            cutout: '70%'
        }
    });
});
</script>

<?php
require_once __DIR__ . '/includes/admin_footer.php';
?>
