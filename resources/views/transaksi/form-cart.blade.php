<div class="card card-orange card-outline">
    <div class="card-body">
        <h3 class="m-0 text-right">Rp <span id="totalJumlah">0</span> ,-</h3>
    </div>
</div>

<form action="{{ route('transaksi.store') }}" method="POST" class="card card-orange card-outline">
    @csrf
    <div class="card-body">
        <p class="text-right">
            Tanggal : {{ $tanggal }}
        </p>
        <div class="row">
            <div class="col">
                <label for="namaPelanggan">Nama Pelanggan</label>
                <input type="text" id="namaPelanggan"
                    class="form-control @error('pelanggan_id') is-invalid @enderror" disabled>
                @error('pelanggan_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
                <input type="hidden" name="pelanggan_id" id="pelangganId">
            </div>
            <div class="col">
                <label for="namaKasir">Nama Kasir</label>
                <input type="text" class="form-control" value="{{ $nama_kasir }}" disabled>
            </div>
        </div>

        <table class="table table-striped table-hover table-bordered mt-3">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Sub Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="resultCart">
                <tr>
                    <td colspan="5" class="text-center"> Tidak ada data.</td>
                </tr>
            </tbody>
        </table>

        <div class="row mt-3">
            <div class="col-2 offset-6">
                <p>Total</p>
                <p>Pajak 20 %</p>
                <p>Total Bayar</p>
            </div>
            <div class="col-4 text-right">
                <p id="subtotal">0</p>
                <p id="taxAmount">0</p>
                <p id="total">0</p>
            </div>
        </div>

        <div class="col-6 offset-6">
            <hr class="mt-0">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Cash</span>
                </div>
                <input type="text" name="cash"
                    class="form-control @error('cash') is-invalid @enderror" placeholder="Jumlah Cash"
                    value="{{ old('cash') }}">
            </div>
            <input type="hidden" name="total_bayar" id="totalBayar" />
            @error('cash')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="col-12 form-inline mt-3">
            <a href="{{ route('transaksi.index') }}" class="btn btn-secondary mr-2">Ke Transaksi</a>
            <a href="{{ route('cart.clear') }}" class="btn btn-danger">Kosongkan</a>
            <button type="submit" class="btn btn-success ml-auto">
                <i class="fas fa-money-bill-wave mr-2"></i> Bayar Transaksi
            </button>
        </div>
    </div>
</form>
@push('scripts')
<script>
    $(document).ready(function() {
        fetchCart();
    });

    function fetchCart() {
        $.getJSON("/cart", function(response) {
            $('#resultCart').empty();

            const { items, subtotal, tax_amount, total, extra_info } = response;

            $('#subtotal').html(rupiah(subtotal));
            $('#taxAmount').html(rupiah(tax_amount));
            $('#total, #totalJumlah').html(rupiah(total));
            $('#totalBayar').val(total);

            for (const property in items) {
                addRow(items[property]);
            }

            if (Array.isArray(items) && items.length === 0) {
                $('#resultCart').html('<tr><td colspan="5" class="text-center">Tidak ada data.</td></tr>');
            }

            if (extra_info && extra_info.pelanggan) {
                const { id, nama } = extra_info.pelanggan;
                $('#namaPelanggan').val(nama);
                $('#pelangganId').val(id);
            }
        });
    }

    function addRow(item) {
        const { hash, title, quantity, price, total_price } = item;

        let btn = `<button type="button" class="btn btn-xs btn-success mr-2" onclick="ePut('${hash}', 1)">
            <i class="fas fa-plus"></i>
            </button>`;

        btn += `<button type="button" class="btn btn-xs btn-primary mr-2" onclick="ePut('${hash}', -1)">
                <i class="fas fa-minus"></i>
                </button>`;

        btn += `<button type="button" class="btn btn-xs btn-danger" onclick="eDel('${hash}')">
                <i class="fas fa-trash"></i>
                </button>`;

        const row = `<tr>
            <td>${title}</td>
            <td>${quantity}</td>
            <td>${rupiah(price)}</td>
            <td>${rupiah(total_price)}</td>
            <td>${btn}</td>
        </tr>`;

        $('#resultCart').append(row);
    }

    function rupiah(number) {
        return new Intl.NumberFormat("id-ID").format(number);
    }

    function ePut(hash, qty) {
        $.ajax({
            type: "PUT",
            url: "/cart/" + hash,
            data: {
                qty: qty
            },
            dataType: "json",
            success: function(response) {
                fetchCart();
            }
        });
    }

    function eDel(hash) {
        $.ajax({
            type: "DELETE",
            url: "/cart/" + hash,
            dataType: "json",
            success: function(response) {
                fetchCart();
            }
        });
    }
</script>
@endpush
