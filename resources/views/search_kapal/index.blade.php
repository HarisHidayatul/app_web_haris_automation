@extends('template_admin_lte.index')
@section('content')
    <div class="container-fluid">
        <h2 class="text-center display-4">Cari Kapal</h2>
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <form id="search-form" action="{{ route('search.kapal.form') }}" method="GET">
                    <div class="input-group">
                        <input type="search" id="search" name="query" class="form-control form-control-lg" placeholder="Cari kapal disini" value="{{ request()->input('query') }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-lg btn-default">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <a href="{{url('create-kapal')}}" class="text-center text-danger">Tidak menemukan? Tambahkan secara manual</a>
            </div>
        </div>
    </div>
    <div class="card-body pb-0">
        <div class="row" id="results">
            @foreach ($kapal as $loop_kapal)
                <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column" style="cursor: pointer">
                    <div class="card bg-light d-flex flex-fill">
                        <div class="card-header text-muted border-bottom-0">
                        </div>
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-7">
                                    <h2 class="lead"><b>{{ $loop_kapal->nama_kapal }}</b></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#search').on('keyup', function() {
                var query = $(this).val();
                $.ajax({
                    url: "{{ route('search.kapal') }}",
                    type: "GET",
                    data: {'query': query},
                    success: function(data) {
                        $('#results').html('');
                        if (data.length > 0) {
                            $.each(data, function(index, kapal) {
                                $('#results').append(
                                    '<div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column" style="cursor: pointer">' +
                                    '<div class="card bg-light d-flex flex-fill">' +
                                    '<div class="card-header text-muted border-bottom-0"></div>' +
                                    '<div class="card-body pt-0">' +
                                    '<div class="row">' +
                                    '<div class="col-7">' +
                                    '<h2 class="lead"><b>' + kapal.nama_kapal + '</b></h2>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>'
                                );
                            });
                        } else {
                            $('#results').append('<p class="text-center">Tidak ada kapal yang ditemukan.</p>');
                        }
                    }
                });
            });

            $('#search-form').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                var query = $('#search').val();
                $.ajax({
                    url: "{{ route('search.kapal') }}",
                    type: "GET",
                    data: {'query': query},
                    success: function(data) {
                        $('#results').html('');
                        if (data.length > 0) {
                            $.each(data, function(index, kapal) {
                                $('#results').append(
                                    '<div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column" style="cursor: pointer">' +
                                    '<div class="card bg-light d-flex flex-fill">' +
                                    '<div class="card-header text-muted border-bottom-0"></div>' +
                                    '<div class="card-body pt-0">' +
                                    '<div class="row">' +
                                    '<div class="col-7">' +
                                    '<h2 class="lead"><b>' + kapal.nama_kapal + '</b></h2>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>'
                                );
                            });
                        } else {
                            $('#results').append('<p class="text-center">Tidak ada kapal yang ditemukan.</p>');
                        }
                    }
                });
            });
        });
    </script>
@endsection

{{-- <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column" style="cursor: pointer">
    <div class="card bg-light d-flex flex-fill">
        <div class="card-header text-muted border-bottom-0">
            Kapal Tanker
        </div>
        <div class="card-body pt-0">
            <div class="row">
                <div class="col-7">
                    <h2 class="lead"><b>Sinar Busan</b></h2>
                    <p class="text-muted text-sm"><b>About: </b> Web Designer / UX / Graphic Artist / Coffee
                        Lover </p>
                    <ul class="ml-4 mb-0 fa-ul text-muted">
                        <li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span>
                            Address: Demo Street 123, Demo City 04312, NJ</li>
                        <li class="small"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> Phone
                            #: + 800 - 12 12 23 52</li>
                    </ul>
                </div>
                <div class="col-5 text-center">
                    <img src="../../dist/img/user1-128x128.jpg" alt="user-avatar" class="img-circle img-fluid">
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="text-right">
                <a href="#" class="btn btn-sm bg-teal">
                    <i class="fas fa-comments"></i>
                </a>
                <a href="#" class="btn btn-sm btn-primary">
                    <i class="fas fa-user"></i> View Profile
                </a>
            </div>
        </div>
    </div>
</div> --}}