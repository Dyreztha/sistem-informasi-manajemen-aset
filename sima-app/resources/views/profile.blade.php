<x-app-layout>
    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Pengaturan Profil</h2>
        <p class="text-sm text-gray-600 mt-1">Kelola informasi akun dan keamanan Anda</p>
    </div>

    <div class="max-w-3xl space-y-6">
        <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-200">
            <livewire:profile.update-profile-information-form />
        </div>

        <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-200">
            <livewire:profile.update-password-form />
        </div>

        <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-200">
            <livewire:profile.delete-user-form />
        </div>
    </div>
</x-app-layout>
