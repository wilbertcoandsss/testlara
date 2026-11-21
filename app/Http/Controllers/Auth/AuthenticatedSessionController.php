<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // // logic index
        //  $query = Post::query();

        // // searching
        // if ($request->search){
        //     $keyword = '%' . $request->search . '%';
        //     $query->where(function($q) use ($keyword){
        //         $q->where('name', 'LIKE', $keyword)
        //         ->orWhere('caption', 'LIKE', $keyword);
        //     });
        // }

        // // filter status (active / archived)
        // if ($request->status != null && $request->status != ''){
        //     $query->where('is_archived', $request->status);
        // }

        // // sorting
        // $allowedSorts = ['name', 'likes'];
        // $sortBy = in_array($request->sort_by, $allowedSorts) ? $request->sort_by : 'created_at';
        // $sortOrder = strtolower($request->sort_order) == 'asc' ? 'asc' : 'desc';

        // $query->orderBy($sortBy, $sortOrder);

        // $posts = $query->paginate(5)->withQueryString();
        return redirect('/posts');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/posts');
    }
}
