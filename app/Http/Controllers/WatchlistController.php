<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Watchlist;
use App\Models\User;
use Illuminate\Http\Request;

class WatchlistController extends Controller
{
    protected function getDefaultUser()
    {
        return User::first() ?? User::create([
            'name' => 'Demo User',
            'email' => 'demo@scm.com',
            'password' => bcrypt('password')
        ]);
    }

    public function index()
    {
        $user = $this->getDefaultUser();

        $watchlists = Watchlist::with(['country'])
            ->where('user_id', $user->id)
            ->get();

        $countries = Country::orderBy('country_name')->get();

        return view('watchlist.index', [
            'watchlists' => $watchlists,
            'countries' => $countries,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
        ]);

        $user = $this->getDefaultUser();

        $exists = Watchlist::where('user_id', $user->id)
            ->where('country_id', $request->country_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Negara sudah berada di dalam daftar pantau Anda.');
        }

        Watchlist::create([
            'user_id' => $user->id,
            'country_id' => $request->country_id,
        ]);

        return redirect()->route('watchlist.index')
            ->with('success', 'Negara berhasil ditambahkan ke daftar pantau.');
    }

    public function destroy($id)
    {
        $watchlist = Watchlist::findOrFail($id);
        $watchlist->delete();

        return redirect()->route('watchlist.index')
            ->with('success', 'Negara berhasil dihapus dari daftar pantau.');
    }
}
