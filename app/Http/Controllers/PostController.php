<?php

namespace App\Http\Controllers;

use App\Jobs\SendNotificationPostCreated;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ngeshow homepage
        // $posts = Post::with('user')->get();

        // query
        // search
        // wildcard
        // se%
        // %se%
        // %se

        // $query = Post::query();
        $query = Post::with('user');

        if (Auth::check() && !Auth::user()->isAdmin()){
            $query->where('user_id', Auth::id());
        }

        // searching
        if ($request->search){
            $keyword = '%' . $request->search . '%';
            $query->where(function($q) use ($keyword){
                $q->where('name', 'LIKE', $keyword)
                ->orWhere('caption', 'LIKE', $keyword);
            });
        }

        // filter status (active / archived)
        if ($request->status != null && $request->status != ''){
            $query->where('is_archived', $request->status);
        }

        // sorting
        $allowedSorts = ['name', 'likes'];
        $sortBy = in_array($request->sort_by, $allowedSorts) ? $request->sort_by : 'created_at';
        $sortOrder = strtolower($request->sort_order) == 'asc' ? 'asc' : 'desc';

        $query->orderBy($sortBy, $sortOrder);

        $posts = $query->paginate(5)->withQueryString();

        return view('posts.index', ['posts' => $posts]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = null;
        if(Auth::user()->role == 'admin'){
            $users = User::all();
        }
        else{
            $users = User::where('id', Auth::id())->get();
        }
        return view('posts.create', ['users' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     //
    // }

    public function store(Request $request)
    {
        # Validation
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|max:255|min:2',
            'caption' => 'required|min:2',
            'likes' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:10240'
        ]);

        // is_archived
        $validatedData['is_archived'] = $request->has('is_archived') ? 1 : 0;

        // image validation
        if($request->hasFile('image')){
            $file = $request->file('image');

            // format custom name
            // timestamp_nama file.extension
            // 20260110_picture1.jpg
            $extension = $file->getClientOriginalExtension();
            $rawName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $fileName = time() . '_' . Str::slug($rawName) . '.' . $extension;
            // liburan pergi ke JAWA 2026.png
            // liburan-pergi-ke-jawa-2026.png
            // simpan filenya
            $imagePath = Storage::disk('public')->putFileAs('posts', $file, $fileName);

            $validatedData['image'] = $imagePath;
        }

        $post = Post::create($validatedData);

        SendNotificationPostCreated::dispatch($post)->delay(now()->addSeconds(10));
        return redirect()->route('posts.index')->with('success', 'Post berhasil ditambahkan!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // harus ambil post, cari di DB menggunakan id postnya
        $post = Post::findOrFail($id);

        Gate::authorize('update', $post);

        $users = User::all();

        // balikin ke view update
        return view('posts.update', ['post' => $post, 'users' => $users]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $post = Post::findOrFail($id);

        # Validation
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|max:255|min:2',
            'caption' => 'required|min:2',
            'likes' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:10240'
        ]);

        // is_archived
        $validatedData['is_archived'] = $request->has('is_archived') ? 1 : 0;

        // image validation
        if($request->hasFile('image')){

            // delete old images
            if($post->image && Storage::disk('public')->exists($post->image)){
                Storage::disk('public')->delete($post->image);
            }

            $file = $request->file('image');

            // format custom name
            // timestamp_nama file.extension
            // 20260110_picture1.jpg
            $extension = $file->getClientOriginalExtension();
            $rawName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $fileName = time() . '_' . Str::slug($rawName) . '.' . $extension;
            // liburan pergi ke JAWA 2026.png
            // liburan-pergi-ke-jawa-2026.png
            // simpan filenya
            $imagePath = Storage::disk('public')->putFileAs('posts', $file, $fileName);

            $validatedData['image'] = $imagePath;
        }
        else{
            // if no picture want to be uploaded, then use the old image
            $validatedData['image'] = $post->image;
        }

        // update
        $post->update($validatedData);

        return redirect()->route('posts.index')->with('success', 'Post berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $post = Post::findOrFail($id);
        Gate::authorize('delete', $post);
        // hapus file
        if($post->image && Storage::disk('public')->exists($post->image)){
            Storage::disk('public')->delete($post->image);
        }
        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post berhasil didelete!');
    }
}
