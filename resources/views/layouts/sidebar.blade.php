<div id="sidebar">
    <a href="{{ url('/') }}" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>
    <ul>
        <li class="active"><a href="{{ url("/") }}"><i class="icon icon-home"></i> <span>Dashboard</span></a> </li>
                <li class="submenu"> <a href="#"><i class="icon icon-pencil"></i> <span>Transaksi</span> <span><i
                        class="icon icon-chevron-down"></i></span></a>
            <ul>
                <li><a href="index2.html">Pembelian Gas</a></li>
                <li><a href="gallery.html">Pengembalian Gas</a></li>
            </ul>
        </li>
        <li> <a href="{{ url('stok/') }}"><i class="icon icon-inbox"></i> <span>Stok Gas</span></a> </li>
        <li> <a href="widgets.html"><i class="icon icon-file"></i> <span>Laporan</span></a> </li>
        
        <li class="submenu"> <a href="#"><i class="icon icon-inbox"></i> <span>Manajemen User</span> <span><i
                        class="icon icon-chevron-down"></i></span> </a>
            <ul>
                <li><a href="form-common.html">Karyawan</a></li>
                <li><a href="form-validation.html">Vendor</a></li>
                <li><a href="form-wizard.html">Form with Wizard</a></li>
            </ul>
        </li>

    </ul>
</div>
