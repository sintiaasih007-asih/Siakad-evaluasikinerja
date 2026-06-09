<x-guest-layout>

<form method="POST" class="p-6">
@csrf

<h2 class="text-lg font-bold mb-4">Buat Password</h2>

<input type="password" name="password"
placeholder="Password"
class="border p-2 w-full mb-3">

<input type="password" name="password_confirmation"
placeholder="Konfirmasi"
class="border p-2 w-full mb-3">

<button class="bg-indigo-600 text-white px-4 py-2 rounded w-full">
Simpan Password
</button>

</form>

</x-guest-layout>