@extends('layout.layout')

@section('title', 'Test 2 Page')

@section('content')
    <body>
        <?php echo "Halo" ?>
        <?php $name="<b>Wilbert</b>" ?>
        <?php $score=40 ?>
        {!! $name !!}
        {{-- {{ $name }} --}}

        @if ($score > 80)
            <p>Selamat anda lulus!</p>
        @elseif ($score > 70)
            <p>Oke lah</p>
        @elseif ($score < 50)
            <p>Gak lulus</p>
        @else
            <p>Unknown</p>
        @endif

        {{-- @if(Auth::user()->role == 'admin')
        @else
            <p>Bukan admin</p>
        @endif --}}

        {{-- Terauthentikasi --}}
        @auth
            <p>Authenticated</p>
        @else
            <p>Not Authenticated</p>
        @endauth

        @guest
            <p>Ini tamu</p>
        @endguest

        @php
            // $posts = [
            //     (object)[
            //         'title' => 'Test 1',
            //         'content' => 'Ini test satu',
            //     ],
            //     (object)[
            //         'title' => 'Test 2',
            //         'content' => 'Ini test dua',
            //     ],(object)[
            //         'title' => 'Test 3',
            //         'content' => 'Ini test tiga',
            //     ],
            // ];

            // $posts = [];
        @endphp

        {{-- @for ($i = 0; $i < 5; $i++)
            {{ $i }}
        @endfor --}}

        @forelse ($userData as $s)
            <h2>{{ $s->name }}</h2>
            <h4>{{ $s->email }}</h4>
        @empty
            <p>Belum ada datanya</p>
        @endforelse

        {{-- <h1 className='text-3xl font-bold underline'>Wilbert</h1> --}}
         <h1 class="text-3xl font-bold underline text-amber-300">
            Hello world!
         </h1>
    </body>
@endsection
