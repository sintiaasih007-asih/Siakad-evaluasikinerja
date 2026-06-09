<div class="bg-white shadow p-4 flex justify-between">

    <h1 class="text-xl font-semibold">Dashboard</h1>

    <div>
        <span class="mr-4">{{ Auth::user()->name }}</span>

        <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button class="bg-red-500 text-white px-3 py-1 rounded">
                Logout
            </button>
        </form>
    </div>

</div>