@extends('layouts.main', ['title' => 'kategori'])

@section('title-content')
    <i class="fas fa-list mr-2"></i>
    Kategori
@endsection

@section('content')
    @if (session('store') == 'success')
        <x-alert type="success">
            <strong>Berhasil dibuat!</strong> Kategori berhasil dibuat.
        </x-alert>
    @endif

    @if (session('update') == 'success')
        <x-alert type="success">
            <strong>Berhasil diupdate!</strong> Kategori berhasil diupdate.
        </x-alert>
    @endif

    @if (session('destroy') == 'success')
        <x-alert type="success">
            <strong>Berhasil dihapus!</strong> Kategori berhasil dihapus.
        </x-alert>
    @endif

    <div class="card card-orange card-outline">
        <div class="card-header form-inline">
            <a href="{{ route('kategori.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i> Tambah
            </a>
            <form action="{{ url()->current() }}" method="get" class="ml-auto">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" value="{{ request()->input('search') }}"
                        placeholder="Nama Kategori">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Kategori</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($kategoris as $key => $kategori)
                        <tr>
                            <td>{{ $kategoris->firstItem() + $key }}</td>
                            <td>{{ $kategori->nama_kategori }}</td>
                            <td class="text-right">
                                <a href="{{ route('kategori.edit', ['kategori' => $kategori->id]) }}"
                                    class="btn btn-xs text-success p-0 mr-1">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <button type="button" data-toggle="modal" data-target="#modalDelete{{ $kategori->id}}"
                                    data-url="{{ route('kategori.destroy', ['kategori' => $kategori->id]) }}"
                                    class="btn btn-xs text-danger p-0 btn-delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $kategoris->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
@endsection

@push('modals')
@foreach ($kategoris as $kategori)
<div class="modal fade" id="modalDelete{{ $kategori->id }}" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah yakin akan dihapus?</p>
                <form action="{{ route('kategori.destroy', ['kategori' => $kategori->id]) }}" method="post" style="display: none;" id="formBatal{{ $kategori->id }}">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                <button type="button" class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('formBatal{{ $kategori->id }}').submit();">
                    Ya, Hapus!
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endpush
