<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Posts</title>
    <!-- Contoh menggunakan Tailwind CSS CDN agar tampilannya normal dan rapi -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans p-6">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">

        <div class="flex justify-start items-center mb-6">
            <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition">
                Login
            </a>
            <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition">
                Register
            </a>
            <a href="{{ route('profile.edit') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition">
                Profile
            </a>
            <form method="POST" action="{{ route('logout') }}">
                <button type="submit">Logout</button>
            </form>
        </div>



        <!-- Header & Tombol Create -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Daftar Posts</h1>
            <a href="{{ route('posts.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition">
                + Create Post
            </a>
        </div>

        <!-- Flash Message jika ada sukses simpan data -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-500 border border-red-600 text-white-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @can('is-admin')
            <h1>Ini adalah text yang hanya bisa dibaca oleh admin</h1>
        @endcan

        <form method="GET" action="{{ route('posts.index') }}" class="bg-gray-50 p-3 rounded-lg border border-gray-200 mb-6">
            <div class="grid grid-cols-1 gap-4">
                {{-- Search Box --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Cari Post / Caption</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari post / caption..." class="w-full border border-gray-300 rounded-md p-2 text-sm bg-white
                    focus:ring-blue-500 focus:border-blue-500">
                </div>

                {{-- Filter Status --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Post Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded-md p-2 text-sm bg-white focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : ''}}>Active</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : ''}}>Archived</option>
                    </select>
                </div>

                {{-- Sorting --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Sorting</label>
                    <div class="flex gap-2">
                        {{-- Sort By --}}
                        <select name="sort_by" class="w-2/3 border border-gray-300 rounded-md p-2 text-sm bg-white focus:ring-blue-500 focus:border-blue-500">
                            <option value="likes" {{ request('sort_by') == 'likes' ? 'selected' : ''}}>Likes</option>
                            <option value="name" {{ request('name') == 'name' ? 'selected' : ''}}>Name</option>
                        </select>

                        {{-- Desc / Asc --}}
                        <select name="sort_order" class="w-1/3 border border-gray-300 rounded-md p-2 text-sm bg-white focus:ring-blue-500 focus:border-blue-500">
                            <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : ''}}>DESC</option>
                            <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : ''}}>ASC</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="w-1/2 bg-gray-800 hover:bg-black text-white py-2 px-3 rounded-md text-sm font-medium transition text-center">
                        Apply
                    </button>
                    <a href="{{ route('posts.index') }}" class="w-1/2 bg-gray-200 hover:bg-gray-300 py-2 px-3 rounded-md text-sm text-center">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        <!-- Daftar Tabel Post -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-200">
                <thead>
                    <tr class="bg-gray-50 text-left text-gray-600 text-sm">
                        <th class="border border-gray-200 p-3">No.</th>
                        <th class="border border-gray-200 p-3">Image</th>
                        <th class="border border-gray-200 p-3">Name</th>
                        <th class="border border-gray-200 p-3">Caption</th>
                        <th class="border border-gray-200 p-3">Likes</th>
                        <th class="border border-gray-200 p-3">Archived</th>
                        <th class="border border-gray-200 p-3">User ID</th>
                        <th class="border border-gray-200 p-3">User Name</th>
                        <th colspan="2" class="border border-gray-200 p-3">Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm">
                    @forelse ($posts as $p)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-200 p-3">{{ $p->id }}</td>
                            <td class="border p-3 text-center">
                                @if($p->image)
                                    <img src="{{ asset('storage/' . $p->image) }}"
                                    alt={{ $p->name }}
                                    class="w-12 h-12 object-cover rounded mx-auto border"
                                @else
                                    <span class="text-xs text-gray-400 italic">No Image</span>
                                @endif
                            </td>
                            <td class="border border-gray-200 p-3">{{ $p->name }}</td>
                            <td class="border border-gray-200 p-3">{{ $p->caption }}</td>
                            <td class="border border-gray-200 p-3">{{ $p->likes }}</td>
                            <td class="border border-gray-200 p-3">
                                @if($p->is_archived)
                                    <span class="bg-yellow-100 text-yellow-800 text-xs p-3 rounded">Archived</span>
                                @else
                                    <span class="bg-green-100 text-green-800 text-xs p-3 rounded">Active</span>
                                @endif
                            </td>
                            <td class="border border-gray-200 p-3">{{  $p->user_id }}</td>
                            <td class="border border-gray-200 p-3">{{  $p->user->name }}</td>
                            <td class="border border-gray-200 p-3">
                                @can('update', $p)
                                    <a href="{{ route('posts.edit', $p->id) }}" class="text-blue-600 hover:underline">
                                        Update
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400 italic">Hanya admin</span>
                                @endcan
                            </td>
                            <td class="border border-gray-200 p-3">
                                @can('delete', $p)
                                <form action="{{ route('posts.destroy', $p->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure want to delete this post?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-700 text-white p-3">
                                        Delete
                                    </button>
                                </form>
                                @else
                                    <span class="text-xs text-gray-400 italic">Hanya admin</span>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="border border-gray-200 p-3 text-center text-gray-500">
                                Belum ada posts.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $posts->links() }}
        </div>
    </div>
</body>
</html>
