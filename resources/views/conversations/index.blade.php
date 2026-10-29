<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pesan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl">
                @if($conversations->isEmpty())
                    <div class="p-8 text-center text-gray-500 text-sm">
                        Belum ada percakapan. Buka detail laporan lalu tekan
                        <span class="font-semibold">Chat Pelapor</span> untuk memulai.
                        <a href="{{ route('reports.index') }}" class="text-indigo-600 hover:underline">Lihat laporan</a>
                    </div>
                @else
                    <ul class="divide-y divide-gray-100">
                        @foreach($conversations as $conversation)
                            @php
                                $lawan = $conversation->otherUser($user);
                                $terakhir = $conversation->latestMessage;
                            @endphp
                            <li>
                                <a href="{{ route('conversations.show', $conversation) }}"
                                   class="flex items-start justify-between gap-4 px-6 py-4 hover:bg-gray-50 transition">
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <p class="font-semibold text-gray-900">{{ $lawan->name ?? 'Pengguna dihapus' }}</p>
                                            @if($conversation->unread_count > 0)
                                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-indigo-600 text-white">
                                                    {{ $conversation->unread_count }} baru
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            Laporan: {{ $conversation->laporan->judul ?? 'Laporan dihapus' }}
                                        </p>
                                        <p class="text-sm mt-1 truncate {{ $conversation->unread_count > 0 ? 'text-gray-900 font-medium' : 'text-gray-600' }}">
                                            @if($terakhir)
                                                @if($terakhir->sender_id === $user->id)<span class="text-gray-400">Anda: </span>@endif
                                                {{ \Illuminate\Support\Str::limit($terakhir->body, 90) }}
                                            @else
                                                <span class="italic text-gray-400">Belum ada pesan</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="text-xs text-gray-400 whitespace-nowrap">
                                        {{ ($terakhir?->created_at ?? $conversation->created_at)->diffForHumans() }}
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="px-6 py-4">
                        {{ $conversations->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
