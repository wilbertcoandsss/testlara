<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans p-6">
    <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Buat Post Baru</h1>
            <a href="{{ route('posts.index') }}" class="text-gray-600 hover:text-gray-800 text-sm font-medium">
                &larr; Kembali
            </a>
        </div>

        <!-- Form Create -->
        <form action="{{ route('posts.store') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
            @csrf <!-- Token Keamanan wajib di Laravel -->

            <!-- User ID -->
            {{-- <div>
                <label for="user_id" class="block text-sm font-medium text-gray-700">User ID</label>
                <input type="number" name="user_id" id="user_id" value="{{ old('user_id') }}"
                    class="mt-1 block w-full border @error('user_id') border-red-500 @else border-gray-300 @enderror rounded-md p-2 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('user_id')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div> --}}
            <div>
                <label for="user_id" class="block text-sm font-medium text-gray-700">Pilih User</label>
                <select name="user_id" id="user_id"
                    class="mt-1 block w-full border @error('user_id') border-red-500 @else border-gray-300 @enderror rounded-md p-2 shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white">
                    <option value="">-- Pilih User --</option>
                    @forelse($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @empty
                        <option value="{{ $users->id }}" {{ old('user_id') == $users->id ? 'selected' : '' }}>
                            {{ $users->name }} ({{ $users->email }})
                        </option>
                    @endforelse
                </select>
                @error('user_id')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Name / Judul Post -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama / Judul Post</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                    class="mt-1 block w-full border @error('name') border-red-500 @else border-gray-300 @enderror rounded-md p-2 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('name')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Caption -->
            <div>
                <label for="caption" class="block text-sm font-medium text-gray-700">Caption</label>
                <textarea name="caption" id="caption" rows="4"
                    class="mt-1 block w-full border @error('caption') border-red-500 @else border-gray-300 @enderror rounded-md p-2 shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('caption') }}</textarea>
                @error('caption')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Image Upload --}}
            <div>
                <label for="caption" class="block text-sm font-medium text-gray-700">Image Upload</label>
                <input type="file" name="image" id="image"
                    class="mt-1 block w-full border">
                @error('image')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Likes (Default 0) -->
            <div>
                <label for="likes" class="block text-sm font-medium text-gray-700">Likes Awal</label>
                <input type="number" name="likes" id="likes" value="{{ old('likes', 0) }}"
                    class="mt-1 block w-full border @error('likes') border-red-500 @else border-gray-300 @enderror rounded-md p-2 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('likes')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Is Archived (Checkbox) -->
            <div class="flex items-center">
                <input type="checkbox" name="is_archived" id="is_archived" value="1" {{ old('is_archived') ? 'checked' : '' }}
                    class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <label for="is_archived" class="ml-2 block text-sm text-gray-700">Arsipkan Postingan Ini?</label>
            </div>

            <!-- Tombol Submit -->
            <div class="pt-4">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition">
                    Simpan Post
                </button>
            </div>
        </form>

    </div>
</body>
</html>
