<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Port;
use App\Models\Article;
use App\Models\Country;
use App\Models\Shipment;
use App\Models\Supplier;
use App\Models\EconomicIndicator;
use App\Models\Notification;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // ===== DASHBOARD =====
    public function dashboard()
    {
        $stats = [
            'users'     => User::count(),
            'countries' => Country::count(),
            'ports'     => Port::count(),
            'shipments' => Shipment::count(),
            'suppliers' => Supplier::count(),
            'articles'  => Article::count(),
        ];

        $recentShipments = Shipment::with(['originCountry', 'destinationCountry'])
            ->latest()
            ->take(5)
            ->get();

        $recentUsers = User::latest()->take(5)->get();
        $recentArticles = Article::latest()->take(5)->get();

        // Shipment status breakdown
        $shipmentByStatus = Shipment::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // Shipment risk breakdown
        $shipmentByRisk = Shipment::selectRaw('risk_level, COUNT(*) as total')
            ->groupBy('risk_level')
            ->pluck('total', 'risk_level');

        return view('admin.dashboard', compact(
            'stats', 'recentShipments', 'recentUsers',
            'recentArticles', 'shipmentByStatus', 'shipmentByRisk'
        ));
    }

    // ===== USERS TAB =====
    public function users(Request $request)
    {
        $search = $request->get('search');
        $users = User::when($search, fn($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"))
            ->latest()
            ->paginate(15)
            ->appends($request->only('search'));

        return view('admin.users', compact('users', 'search'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('admin.users')
            ->with('success', 'User "' . $request->name . '" berhasil ditambahkan.');
    }

    public function destroyUser(User $user)
    {
        if (User::count() <= 1) {
            return redirect()->route('admin.users')
                ->with('error', 'Tidak dapat menghapus. Minimal harus ada satu user admin.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users')
            ->with('success', 'User "' . $name . '" berhasil dihapus.');
    }

    // ===== PORTS TAB =====
    public function ports(Request $request)
    {
        $search = $request->get('search');
        $countryFilter = $request->get('country_id');

        $ports = Port::with('country')
            ->when($search, fn($q) => $q->where('port_name', 'like', "%{$search}%"))
            ->when($countryFilter, fn($q) => $q->where('country_id', $countryFilter))
            ->latest()
            ->paginate(20)
            ->appends($request->only('search', 'country_id'));

        $countries = Country::orderBy('country_name')->get(['id', 'country_name', 'country_code']);

        return view('admin.ports', compact('ports', 'search', 'countryFilter', 'countries'));
    }

    // ===== ARTICLES TAB =====
    public function articles(Request $request)
    {
        $search = $request->get('search');
        $articles = Article::with('author')
            ->when($search, fn($q) => $q->where('title', 'like', "%{$search}%"))
            ->latest()
            ->paginate(15)
            ->appends($request->only('search'));

        return view('admin.articles', compact('articles', 'search'));
    }

    public function storeArticle(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $adminId = session('admin_user_id');

        Article::create([
            'title'        => $request->title,
            'content'      => $request->content,
            'author_id'    => $adminId,
            'published_at' => now(),
        ]);

        return redirect()->route('admin.articles')
            ->with('success', 'Artikel "' . $request->title . '" berhasil diterbitkan.');
    }

    public function destroyArticle(Article $article)
    {
        $title = $article->title;
        $article->delete();

        return redirect()->route('admin.articles')
            ->with('success', 'Artikel "' . \Str::limit($title, 40) . '" berhasil dihapus.');
    }

    // ===== COUNTRIES TAB =====
    public function countries(Request $request)
    {
        $search = $request->get('search');
        $countries = Country::when($search, fn($q) => $q->where('country_name', 'like', "%{$search}%")
                ->orWhere('country_code', 'like', "%{$search}%"))
            ->orderBy('country_name')
            ->paginate(20)
            ->appends($request->only('search'));

        return view('admin.countries', compact('countries', 'search'));
    }

    // ===== OLD INDEX (for backward compat, redirect to login or dashboard) =====
    public function index(Request $request)
    {
        return redirect()->route('admin.dashboard');
    }
}
