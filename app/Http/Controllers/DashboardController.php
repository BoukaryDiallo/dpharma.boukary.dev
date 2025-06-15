<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\Sale;
use App\Models\PharmaceuticalProduct;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $userCount = User::count();
        $clientCount = Client::count();
        $saleCount = Sale::count();
        $salesTotal = Sale::sum('montant_total');
        $productCount = PharmaceuticalProduct::count();
        $recentSales = Sale::with(['client', 'user'])->orderByDesc('date_vente')->take(5)->get();

        // Top 5 clients par montant d'achat
        $topClients = Client::select('clients.id', 'clients.nom')
            ->join('sales', 'sales.client_id', '=', 'clients.id')
            ->selectRaw('SUM(sales.montant_total) as total_achats')
            ->groupBy('clients.id', 'clients.nom')
            ->orderByDesc('total_achats')
            ->take(5)
            ->get();

        // Top 5 produits les plus vendus (quantité)
        $topProducts = \DB::table('sale_product')
            ->join('pharmaceutical_products', 'sale_product.product_id', '=', 'pharmaceutical_products.id')
            ->select('pharmaceutical_products.id', 'pharmaceutical_products.name')
            ->selectRaw('SUM(sale_product.quantity) as total_vendus')
            ->groupBy('pharmaceutical_products.id', 'pharmaceutical_products.name')
            ->orderByDesc('total_vendus')
            ->take(5)
            ->get();

        // Evolution des ventes sur les 12 derniers mois (compatible SQLite/MySQL)
        $connection = \DB::connection()->getDriverName();
        if ($connection === 'sqlite') {
            $salesByDay = Sale::selectRaw("strftime('%Y-%m-%d', date_vente) as day, COUNT(*) as nb, SUM(montant_total) as total")
                ->where('date_vente', '>=', now()->subDays(6)->startOfDay())
                ->groupBy('day')
                ->orderBy('day')
                ->get();
        } else {
            $salesByDay = Sale::selectRaw("DATE(date_vente) as day, COUNT(*) as nb, SUM(montant_total) as total")
                ->where('date_vente', '>=', now()->subDays(6)->startOfDay())
                ->groupBy('day')
                ->orderBy('day')
                ->get();
        }

        // Statistiques alertes
        $lowStockThreshold = 10;
        $lowStockProducts = \App\Models\PharmaceuticalProduct::where('stock_quantity', '<=', $lowStockThreshold)
            ->orderBy('stock_quantity')
            ->get(['id', 'name', 'stock_quantity']);
        $lowStockCount = $lowStockProducts->count();
        $expiringSoonProducts = \App\Models\PharmaceuticalProduct::whereDate('expiration_date', '<=', now()->addDays(30)->toDateString())
            ->orderBy('expiration_date')
            ->get(['id', 'name', 'expiration_date']);
        $expiringSoonCount = $expiringSoonProducts->count();
        $monthlyGoal = 100000; // Objectif fictif (à personnaliser)
        $currentMonthSales = \App\Models\Sale::whereYear('date_vente', now()->year)
            ->whereMonth('date_vente', now()->month)
            ->sum('montant_total');
        $goalReached = $currentMonthSales >= $monthlyGoal;
        $goalPercent = $monthlyGoal > 0 ? round(($currentMonthSales / $monthlyGoal) * 100) : 0;

        return Inertia::render('Dashboard', [
            'metrics' => [
                'users' => $userCount,
                'clients' => $clientCount,
                'sales' => $saleCount,
                'sales_total' => $salesTotal,
                'products' => $productCount,
            ],
            'recentSales' => $recentSales,
            'topClients' => $topClients,
            'topProducts' => $topProducts,
            'salesByDay' => $salesByDay,
            'stats_alerts' => [
                'lowStockCount' => $lowStockCount,
                'expiringSoonCount' => $expiringSoonCount,
                'goalReached' => $goalReached,
                'goalPercent' => $goalPercent,
                'monthlyGoal' => $monthlyGoal,
                'currentMonthSales' => $currentMonthSales,
            ],
            'lowStockProducts' => $lowStockProducts,
            'expiringSoonProducts' => $expiringSoonProducts,
        ]);
    }
}
