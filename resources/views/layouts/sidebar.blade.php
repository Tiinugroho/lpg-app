<div id="sidebar">
    <a href="{{ url('/') }}" class="visible-phone">
        <i class="icon icon-home"></i> Dashboard
    </a>
    <ul>
        @if (Auth::check() && in_array(Auth::user()->role, ['super-admin', 'owner', 'kasir']))
            <li class="active">
                <a href="{{ url('/') }}"><i class="icon icon-home"></i> <span>Dashboard</span></a>
            </li>

            {{-- Transaksi --}}
            <li class="submenu">
                <a href="#"><i class="icon icon-pencil"></i> <span>Transaksi</span> <span><i class="icon icon-chevron-down"></i></span></a>
                <ul>
                    <li><a href="{{ url('/pembelian') }}">Pembelian Gas</a></li>
                    <li><a href="{{ url('/penjualan') }}">Penjualan Gas</a></li>
                    <li><a href="{{ url('/pengembalian-tabung') }}">Pengembalian Gas</a></li>
                </ul>
            </li>
        @endif

        @if (Auth::check() && in_array(Auth::user()->role, ['super-admin', 'owner']))
            <li><a href="{{ url('/stok') }}"><i class="icon icon-inbox"></i> <span>Stok Gas</span></a></li>
            <li><a href="{{ url('/mutasi') }}"><i class="icon icon-random"></i> <span>Mutasi Stok</span></a></li>

            <li class="submenu">
                <a href="#"><i class="icon icon-briefcase"></i> <span>Master Data</span> <span><i class="icon icon-chevron-down"></i></span></a>
                <ul>
                    <li><a href="{{ url('/master-data/tipe-gas') }}">Tipe Gas</a></li>
                </ul>
            </li>

            <li><a href="{{ url('/laporan') }}"><i class="icon icon-file"></i> <span>Laporan</span></a></li>

            <li class="submenu">
                <a href="#"><i class="icon icon-user"></i> <span>Manajemen User</span> <span><i class="icon icon-chevron-down"></i></span></a>
                <ul>
                    <li><a href="{{ url('/manajemen-user/karyawan') }}">Karyawan</a></li>
                    <li><a href="{{ url('/manajemen-user/vendor') }}">Vendor</a></li>
                </ul>
            </li>
        @endif
    </ul>
</div>
