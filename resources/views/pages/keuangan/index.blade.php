@extends('app')

@section('title', 'Catatan Keuangan')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Catatan Keuangan</h1>
                <p class="text-gray-600 mt-1">Kelola semua transaksi keuangan Anda</p>
            </div>

            <div class="flex space-x-3">
                <button onclick="showAddModal()"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Transaksi
                </button>

                {{-- <button
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-file-excel mr-2"></i>
                    Export Excel
                </button> --}}
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Total Saldo --}}
            <div class="balance-card rounded-xl p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Saldo</p>
                        <p class="text-2xl font-bold">{{ number_format($balance ?? 5000000, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-opacity-20 p-3 rounded-lg">
                        <i class="fa-solid fa-wallet text-2xl"></i>
                    </div>
                </div>
            </div>

            {{-- Total Pemasukan --}}
            <div class="income-card rounded-xl p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Total Pemasukan</p>
                        <p class="text-2xl font-bold">{{ number_format($income ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-opacity-20 p-3 rounded-lg">
                        <i class="fas fa-arrow-up text-2xl"></i>
                    </div>
                </div>
            </div>

            {{-- Total Pengeluaran --}}
            <div class="expense-card rounded-xl p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm font-medium">Total Pengeluaran</p>
                        <p class="text-2xl font-bold">{{ number_format($outcome ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-opacity-20 p-3 rounded-lg">
                        <i class="fas fa-arrow-down text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Section --}}
        <div class="bg-white rounded-lg shadow p-6">
            <form class="grid grid-cols-1 md:grid-cols-5 gap-4" action="{{ route('keuangan.search') }}" method="POST">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                    <input type="date" name="start_date"
                        value="{{ old('start_date', request('start_date', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d'))) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                    <input type="date" name="end_date" value="{{ old('end_date', request('end_date', date('Y-m-d'))) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis</label>
                    <select name="jenis"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="semua" {{ request('jenis') == 'semua' ? 'selected' : '' }}>Semua</option>
                        <option value="pemasukan" {{ request('jenis') == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                        <option value="pengeluaran" {{ request('jenis') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran
                        </option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-search mr-2"></i>
                        Filter
                    </button>
                </div>
            </form>
        </div>

        {{-- Transaction Table --}}
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Riwayat Transaksi</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Deskripsi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($dataUser ?? [] as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item->tanggal ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->deskripsi ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $item->keterangan ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if (($item->jenis ?? 'pemasukan') == 'pemasukan')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-arrow-up mr-1"></i>
                                            Pemasukan
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-arrow-down mr-1"></i>
                                            Pengeluaran
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if (($item->jenis ?? 'pemasukan') == 'pemasukan')
                                        <span class="text-green-600">+Rp
                                            {{ number_format($item->nominal ?? 0, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-red-600">-Rp
                                            {{ number_format($item->nominal ?? 0, 0, ',', '.') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button class="text-blue-600 hover:text-blue-900"
                                            onclick="editTransaction({{ $item->id ?? 0 }}, {
                                                tanggal: '{{ $item->tanggal ?? '' }}',
                                                deskripsi: '{{ $item->deskripsi ?? '' }}',
                                                keterangan: '{{ $item->keterangan ?? '' }}',
                                                jenis: '{{ $item->jenis ?? '' }}',
                                                nominal: '{{ $item->nominal ?? 0 }}'
                                            })">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('keuangan.destroy', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    Belum ada data transaksi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <x-pagination :data="$dataUser" />
        </div>
    </div>

    {{-- Modal for Add/Edit Transaction --}}
    <div id="transactionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-md shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Tambah Transaksi</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="transactionForm" method="POST">
                    @csrf
                    <input type="hidden" id="methodField" name="_method" value="">

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                            <input type="date" id="tanggal" name="tanggal"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                            <input type="text" id="deskripsi" name="deskripsi"
                                placeholder="Masukkan deskripsi transaksi"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <input type="text" id="kategori" name="keterangan"
                                placeholder="Masukkan kategori transaksi"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Transaksi</label>
                            <select id="jenis" name="jenis"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                                <option value="">Pilih Jenis</option>
                                <option value="pemasukan">Pemasukan</option>
                                <option value="pengeluaran">Pengeluaran</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                                <input type="number" id="jumlah" name="nominal" placeholder="0"
                                    class="w-full border border-gray-300 rounded-lg pl-12 pr-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeModal()"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Modal functions
        function showAddModal() {
            document.getElementById('modalTitle').textContent = 'Tambah Transaksi';
            document.getElementById('transactionForm').reset();
            document.getElementById('tanggal').value = new Date().toISOString().split('T')[0];

            // Set form action untuk create
            document.getElementById('transactionForm').action = "{{ route('keuangan.store') }}";
            document.getElementById('methodField').value = '';

            document.getElementById('transactionModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('transactionModal').classList.add('hidden');
        }

        function editTransaction(id, data = null) {
            document.getElementById('modalTitle').textContent = 'Edit Transaksi';

            // Set form action untuk update
            document.getElementById('transactionForm').action = `/keuangan/${id}`;
            document.getElementById('methodField').value = 'PUT';

            if (data) {
                // Isi form dengan data yang dikirim
                document.getElementById('tanggal').value = data.tanggal || '';
                document.getElementById('deskripsi').value = data.deskripsi || '';
                document.getElementById('jenis').value = data.jenis || '';
                document.getElementById('kategori').value = data.keterangan || '';
                document.getElementById('jumlah').value = data.nominal || '';
            }

            document.getElementById('transactionModal').classList.remove('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('transactionModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>
@endpush
