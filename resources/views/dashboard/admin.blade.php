<x-app-layout>

<div
x-data="dashboardApp()"
x-init="initCalendar()"
class="max-w-7xl mx-auto px-6 py-8 space-y-8"
>

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Admin</h1>
        <p class="text-gray-500 text-sm">Overview data sekolah</p>
    </div>

    
    {{-- STATISTIK --}}
    <div class="max-w-6xl mx-auto">
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-3 mb-8">

        {{-- SISWA --}}
        <div class="bg-white shadow-sm border rounded-xl overflow-hidden flex h-20">
            <div class="w-16 bg-sky-500 flex items-center justify-center shrink-0">
                <i data-lucide="users" class="w-7 h-7 text-white"></i>
            </div>

            <div class="flex-1 px-4 flex flex-col justify-center min-w-0">
                <p class="text-[11px] font-semibold text-gray-500 uppercase truncate">
                    Siswa
                </p>

                <h2 class="text-2xl font-bold text-gray-800">
                    {{ $totalSiswa ?? 0 }}
                </h2>
            </div>
        </div>

        {{-- GURU --}}
        <div class="bg-white shadow-sm border rounded-xl overflow-hidden flex h-20">
            <div class="w-16 bg-emerald-500 flex items-center justify-center shrink-0">
                <i data-lucide="user-check" class="w-7 h-7 text-white"></i>
            </div>

            <div class="flex-1 px-4 flex flex-col justify-center min-w-0">
                <p class="text-[11px] font-semibold text-gray-500 uppercase truncate">
                    Guru
                </p>

                <h2 class="text-2xl font-bold text-gray-800">
                    {{ $totalGuru ?? 0 }}
                </h2>
            </div>
        </div>

        {{-- KELAS --}}
        <div class="bg-white shadow-sm border rounded-xl overflow-hidden flex h-20">
            <div class="w-16 bg-amber-500 flex items-center justify-center shrink-0">
                <i data-lucide="school" class="w-7 h-7 text-white"></i>
            </div>

            <div class="flex-1 px-4 flex flex-col justify-center min-w-0">
                <p class="text-[11px] font-semibold text-gray-500 uppercase truncate">
                    Kelas
                </p>

                <h2 class="text-2xl font-bold text-gray-800">
                    {{ $totalKelas ?? 0 }}
                </h2>
            </div>
        </div>

        {{-- MAPEL --}}
        <div class="bg-white shadow-sm border rounded-xl overflow-hidden flex h-20">
            <div class="w-16 bg-rose-500 flex items-center justify-center shrink-0">
                <i data-lucide="book-open" class="w-7 h-7 text-white"></i>
            </div>

            <div class="flex-1 px-4 flex flex-col justify-center min-w-0">
                <p class="text-[11px] font-semibold text-gray-500 uppercase truncate">
                    Mata Pelajaran
                </p>

                <h2 class="text-2xl font-bold text-gray-800">
                    {{ $totalMapel ?? 0 }}
                </h2>
            </div>
        </div>

    </div>
        

        {{-- MONITORING LOGIN --}}
        <div class="bg-white rounded-2xl shadow-sm border p-5 md:p-8">

            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-base md:text-lg font-semibold text-gray-700">
                        Monitoring Login Pengguna
                    </h2>

                    <p class="text-sm text-gray-400">
                        Aktivitas login 7 hari terakhir
                    </p>
                </div>
            </div>

            <div class="h-[250px] md:h-[320px] lg:h-[350px]">
                <canvas id="loginChart"></canvas>
            </div>

        </div>


    {{-- CONTENT --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- ================= PENGUMUMAN ================= --}}
        <div class="col-span-1 bg-white rounded-2xl shadow-sm border flex flex-col">

            <div class="flex justify-between items-center p-4 border-b">
                <h2 class="font-semibold text-gray-700">Pengumuman</h2>

                <button @click="openPengumuman=true"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-2 md:px-3 py-1 rounded-lg text-xs md:text-sm"
                    + Tambah
                </button>
            </div>

            <div class="p-4 space-y-3 overflow-y-auto h-[300px] md:h-[400px] lg:h-[450px]">

                @forelse($pengumuman as $p)
                    <div 
                        @click="openPengumumanDetail({{ $p }})"
                        class="p-3 border rounded-xl hover:shadow transition bg-gray-50 cursor-pointer"
                    >
                        <h3 class="font-semibold text-indigo-600 text-sm">
                            {{ $p->judul }}
                        </h3>

                        <p class="text-xs text-gray-400 mb-1">
                            {{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}
                        </p>

                        <p class="text-sm text-gray-600 line-clamp-2">
                            {{ $p->isi }}
                        </p>
                    </div>
                @empty
                    <p class="text-gray-400 text-sm text-center">Belum ada pengumuman</p>
                @endforelse

            </div>
        </div>

        {{-- ================= AGENDA ================= --}}
        <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border">

            <div class="flex justify-between items-center p-4 border-b">
                <h2 class="font-semibold text-gray-700">Agenda Sekolah</h2>

                <button @click="openAgenda=true"
                    class="bg-green-600 hover:bg-green-700 text-white px-2 md:px-3 py-1 rounded-lg text-xs md:text-sm"
                    + Tambah
                </button>
            </div>

            <div class="p-4">
                <div id="calendar"></div>
            </div>

        </div>

    </div>

    {{-- ================= MODAL TAMBAH PENGUMUMAN ================= --}}
    <div x-show="openPengumuman"
        x-transition
        @click.self="openPengumuman=false"
        @keydown.escape.window="openPengumuman=false"
        class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">

        <div class="bg-white rounded-2xl p-5 md:p-6 w-[95%] max-w-md shadow-lg">

            <h2 class="font-bold text-lg mb-4">Tambah Pengumuman</h2>

            <form method="POST" action="{{ route('pengumuman.store') }}">
                @csrf

                <input type="text" name="judul" placeholder="Judul"
                    class="w-full border rounded-lg p-2 mb-3">

                <textarea name="isi" placeholder="Isi"
                    class="w-full border rounded-lg p-2 mb-3"></textarea>

                <input type="date" name="tanggal"
                    class="w-full border rounded-lg p-2 mb-4">

                <div class="flex justify-end gap-2">
                    <button type="button" @click="openPengumuman=false"
                        class="px-3 py-1 rounded bg-gray-200">
                        Batal
                    </button>

                    <button type="submit"
                        class="px-3 py-1 rounded bg-indigo-600 text-white">
                        Simpan
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- ================= MODAL TAMBAH AGENDA ================= --}}
    <div x-show="openAgenda"
        x-transition
        @click.self="openAgenda=false"
        @keydown.escape.window="openAgenda=false"
        class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">

        <div class="bg-white rounded-2xl p-6 w-[400px] shadow-lg">

            <h2 class="font-bold text-lg mb-4">Tambah Agenda</h2>

            <form method="POST" action="{{ route('agenda.store') }}">
                @csrf

                <input type="text" name="judul" placeholder="Judul"
                    class="w-full border rounded-lg p-2 mb-3">

                <textarea name="deskripsi" placeholder="Deskripsi"
                    class="w-full border rounded-lg p-2 mb-3"></textarea>

                <input type="date" name="tanggal"
                    class="w-full border rounded-lg p-2 mb-3">

                <input type="text" name="lokasi" placeholder="Lokasi"
                    class="w-full border rounded-lg p-2 mb-4">

                <div class="flex justify-end gap-2">
                    <button type="button" @click="openAgenda=false"
                        class="px-3 py-1 rounded bg-gray-200">
                        Batal
                    </button>

                    <button type="submit"
                        class="px-3 py-1 rounded bg-green-600 text-white">
                        Simpan
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- ================= MODAL DETAIL PENGUMUMAN ================= --}}
    <div x-show="detailPengumuman"
        x-transition
        @click.self="detailPengumuman=false"
        class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">

        <div class="bg-white rounded-2xl p-6 w-[400px] shadow-lg">

            <h2 class="font-bold mb-4">Detail Pengumuman</h2>

            <input x-model="selectedPengumuman.judul" class="w-full border p-2 mb-2 rounded">

            <input type="date" x-model="selectedPengumuman.tanggal" class="w-full border p-2 mb-2 rounded">

            <textarea x-model="selectedPengumuman.isi" class="w-full border p-2 mb-3 rounded"></textarea>

            <div class="flex justify-between">

                <button @click="deletePengumuman()" 
                    class="bg-red-600 text-white px-3 py-1 rounded">
                    Hapus
                </button>

                <div class="flex gap-2">
                    <button @click="detailPengumuman=false" 
                        class="bg-gray-200 px-3 py-1 rounded">
                        Batal
                    </button>

                    <button @click="updatePengumuman()" 
                        class="bg-indigo-600 text-white px-3 py-1 rounded">
                        Update
                    </button>
                </div>

            </div>

        </div>
    </div>

    {{-- ================= MODAL DETAIL AGENDA ================= --}}
    <div x-show="detailAgenda"
        x-transition
        @click.self="detailAgenda=false"
        class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">

        <div class="bg-white rounded-2xl p-6 w-[400px] shadow-lg">

            <h2 class="font-bold mb-4">Detail Agenda</h2>

            <input x-model="selectedAgenda.title" class="w-full border p-2 mb-2 rounded">
            <input type="date" x-model="selectedAgenda.date" class="w-full border p-2 mb-2 rounded">
            <input x-model="selectedAgenda.lokasi" class="w-full border p-2 mb-2 rounded">
            <textarea x-model="selectedAgenda.description" class="w-full border p-2 mb-3 rounded"></textarea>

            <div class="flex justify-between">
                <button @click="deleteAgenda()" class="bg-red-600 text-white px-3 py-1 rounded">
                    Hapus
                </button>

                <div class="flex gap-2">
                    <button @click="detailAgenda=false" class="bg-gray-200 px-3 py-1 rounded">
                        Batal
                    </button>

                    <button @click="updateAgenda()" class="bg-green-600 text-white px-3 py-1 rounded">
                        Update
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- AKTIVITAS GURU / STAF -->
        <!-- <div class="bg-white rounded-2xl shadow-sm border mt-6">
            <div class="p-4 border-b flex justify-between items-center">
                <h2 class="font-semibold text-slate-700">Aktivitas Guru / Staf</h2>
                <span class="text-xs text-slate-400">Realtime</span>
            </div>

            <div class="p-4 space-y-3 max-h-[300px] overflow-y-auto"> -->

                <!-- contoh data, nanti bisa dari database -->
                <!-- <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-white hover:shadow transition">
                    <div>
                        <p class="text-sm font-semibold text-slate-700">Guru A</p>
                        <p class="text-xs text-slate-500">Login ke sistem</p>
                    </div>
                    <span class="text-xs text-emerald-500">1 menit lalu</span>
                </div> -->

                <!-- <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-white hover:shadow transition">
                    <div>
                        <p class="text-sm font-semibold text-slate-700">Staf TU</p>
                        <p class="text-xs text-slate-500">Update data siswa</p>
                    </div>
                    <span class="text-xs text-emerald-500">5 menit lalu</span>
                </div>

                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-white hover:shadow transition">
                    <div>
                        <p class="text-sm font-semibold text-slate-700">Guru B</p>
                        <p class="text-xs text-slate-500">Input nilai</p>
                    </div>
                    <span class="text-xs text-slate-400">10 menit lalu</span>
                </div> -->

</div>

{{-- ================= SCRIPT ================= --}}
<script>
function dashboardApp() {
    return {
        openPengumuman:false,
        openAgenda:false,
        detailAgenda:false,
        detailPengumuman:false,
        selectedAgenda:{},
        selectedPengumuman:{},

        initCalendar() {
            let calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                initialView: 'dayGridMonth',
                height: window.innerWidth < 768 ? 400 : 450,

                events: [
                    @foreach($agenda as $a)
                    {
                        id: "{{ $a->id }}",
                        title: "{{ $a->judul }}",
                        start: "{{ $a->tanggal }}",
                        description: "{{ $a->deskripsi }}",
                        lokasi: "{{ $a->lokasi }}"
                    },
                    @endforeach
                ],

                eventClick: (info) => {
                    this.selectedAgenda = {
                        title: info.event.title,
                        date: info.event.startStr,
                        lokasi: info.event.extendedProps.lokasi,
                        description: info.event.extendedProps.description
                    };

                    window.selectedId = info.event.id;
                    this.detailAgenda = true;
                }
            });

            calendar.render();
        },

        openPengumumanDetail(data) {
            this.selectedPengumuman = {
                id: data.id,
                judul: data.judul,
                tanggal: data.tanggal,
                isi: data.isi
            };

            window.selectedPengumumanId = data.id;
            this.detailPengumuman = true;
        }
    }
}

// ================= AGENDA =================
function updateAgenda() {
    fetch('/agenda/' + window.selectedId, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            judul: document.querySelector('[x-model="selectedAgenda.title"]').value,
            tanggal: document.querySelector('[x-model="selectedAgenda.date"]').value,
            lokasi: document.querySelector('[x-model="selectedAgenda.lokasi"]').value,
            deskripsi: document.querySelector('[x-model="selectedAgenda.description"]').value
        })
    }).then(() => location.reload());
}

function deleteAgenda() {
    if (!confirm('Yakin hapus agenda?')) return;

    fetch('/agenda/' + window.selectedId, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    }).then(() => location.reload());
}

// ================= PENGUMUMAN =================
window.updatePengumuman = function () {
    let root = document.querySelector('[x-data]').__x.$data;

    console.log('UPDATE ID:', window.selectedPengumumanId); // debug

    fetch('/pengumuman/' + window.selectedPengumumanId, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            judul: root.selectedPengumuman.judul,
            tanggal: root.selectedPengumuman.tanggal,
            isi: root.selectedPengumuman.isi
        })
    })
    .then(res => res.json())
    .then(res => {
        console.log(res);
        location.reload();
    });
};


window.deletePengumuman = function () {
    console.log('DELETE ID:', window.selectedPengumumanId); // debug

    if (!confirm('Yakin hapus?')) return;

    fetch('/pengumuman/' + window.selectedPengumumanId, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => res.json())
    .then(() => location.reload());
};
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const loginData = @json($loginPerHari);

new Chart(
    document.getElementById('loginChart'),
    {
        type: 'line',

        data: {
            labels: loginData.map(x => x.tanggal),

            datasets: [
                {
                    label: 'Guru',
                    data: loginData.map(x => x.guru)
                },
                {
                    label: 'Guru & Wali Kelas',
                    data: loginData.map(x => x.walikelas)
                },
                {
                    label: 'Kepala Sekolah',
                    data: loginData.map(x => x.kepsek)
                },
                {
                    label: 'Orang Tua',
                    data: loginData.map(x => x.orangtua)
                }
            ]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    position: window.innerWidth < 768 ? 'bottom' : 'top'
                }
            },

            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    }
);

</script>
</x-app-layout>