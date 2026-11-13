<x-guest-layout>
    <h2 class="text-center text-xl font-bold text-gray-800 mb-6">Daftar Akun TemuUNRI</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Nama --}}
        <div>
            <x-input-label for="name" value="Nama Lengkap" />
            <x-text-input id="name" class="block mt-1 w-full" type="text"
                          name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        {{-- NIM --}}
        <div class="mt-4">
            <x-input-label for="nim" value="NIM (Nomor Induk Mahasiswa)" />
            <x-text-input id="nim" class="block mt-1 w-full" type="text"
                          name="nim" :value="old('nim')" required autocomplete="off"
                          pattern="[a-zA-Z0-9]+" title="NIM hanya boleh berisi angka atau huruf" />
            <x-input-error :messages="$errors->get('nim')" class="mt-2" />
        </div>

        {{-- Email --}}
        <div class="mt-4">
            <x-input-label for="email" value="Alamat Email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email"
                          name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Fakultas & Program Studi (2 kolom di sm ke atas) --}}
        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
            {{-- Fakultas --}}
            <div>
                <x-input-label for="fakultas" value="Fakultas" />
                <x-text-input id="fakultas" class="block mt-1 w-full" type="text"
                              name="fakultas" :value="old('fakultas')" required
                              placeholder="Contoh: Fakultas Teknik" />
                <x-input-error :messages="$errors->get('fakultas')" class="mt-2" />
            </div>

            {{-- Program Studi --}}
            <div>
                <x-input-label for="program_studi" value="Program Studi" />
                <x-text-input id="program_studi" class="block mt-1 w-full" type="text"
                              name="program_studi" :value="old('program_studi')" required
                              placeholder="Contoh: Teknik Informatika" />
                <x-input-error :messages="$errors->get('program_studi')" class="mt-2" />
            </div>
        </div>

        {{-- Nomor HP --}}
        <div class="mt-4">
            <x-input-label for="no_hp" value="Nomor HP (Opsional)" />
            <x-text-input id="no_hp" class="block mt-1 w-full" type="tel"
                          name="no_hp" :value="old('no_hp')" autocomplete="tel"
                          placeholder="Contoh: 08123456789" />
            <x-input-error :messages="$errors->get('no_hp')" class="mt-2" />
        </div>

        {{-- Password --}}
        <div class="mt-4">
            <x-input-label for="password" value="Kata Sandi" />
            <x-text-input id="password" class="block mt-1 w-full"
                          type="password" name="password"
                          required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Konfirmasi Password --}}
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Konfirmasi Kata Sandi" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                          type="password" name="password_confirmation"
                          required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
               href="{{ route('login') }}">
                Sudah punya akun?
            </a>

            <x-primary-button class="ms-4">
                Daftar
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
