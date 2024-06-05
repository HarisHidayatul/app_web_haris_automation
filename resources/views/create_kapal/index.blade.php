@extends('template_admin_lte.index')
@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Tambahkan Kapal</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form action="{{ route('store.kapal') }}" method="POST">
            @csrf
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                        <a href="{{url('show-kapal')}}">Klik disini untuk melihat kapal</a>
                    </div>
                @endif
                <div class="form-group">
                    <label for="nama_kapal">Nama Kapal</label>
                    <input type="text" class="form-control @error('nama_kapal') is-invalid @enderror" id="nama_kapal"
                        name="nama_kapal" placeholder="Masukkan nama kapal">
                    @error('nama_kapal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer d-flex">
                <button type="submit" class="btn btn-primary ml-auto">Submit</button>
            </div>
        </form>
    </div>
@endsection
