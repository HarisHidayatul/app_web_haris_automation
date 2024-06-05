@extends('template_admin_lte.index')
@section('content')
    @include('show_kapal_desc.css')
    <div class="row" style="margin-top: 15px;">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $kapal->nama_kapal }}</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="table_search" class="form-control float-right"
                                placeholder="Cari pekerjaan">

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Pekerjaan</th>
                                <th>Berangkat</th>
                                <th>Pulang</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>ODME</td>
                                <td>02/10/2024</td>
                                <td>04/10/2024</td>
                                <td>Finish</td>
                            </tr>
                            <tr>
                                <td>ODME</td>
                                <td>02/10/2024</td>
                                <td>04/10/2024</td>
                                <td>Finish</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between" style="margin-top: 10px;">
                    <div></div>
                    <div style="margin-right: 10px; margin-bottom: 10px;">
                        <a href="#addAndEditData" class="btn btn-success d-flex align-content-center" data-toggle="modal"><i
                                class="material-icons">&#xE147;</i> <span>Add New Data</span></a>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
    <div id="addAndEditData" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h4 id="modal_title" class="modal-title"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div id="modal_body">
                        </div>
                        {{-- <div class="form-group">
                            <label for="programmingLanguagesInput">Programming Language</label>
                            <input type="text" id="programmingLanguagesInput" list="programmingLanguages"
                                class="form-control" placeholder="Enter Here" onchange="checkText();" />
                            <datalist id="programmingLanguages">
                                <option value="Objective C">1</option>
                                <option value="C++">2</option>
                                <option value="C#">3</option>
                                <option value="Cobol">4</option>
                                <option value="Go">5</option>
                                <option value="Java">6</option>
                                <option value="JavaScript">7</option>
                                <option value="Python">8</option>
                                <option value="PHP">9</option>
                                <option value="Pascal">10</option>
                                <option value="Perl">11</option>
                                <option value="R">12</option>
                                <option value="Swift">13</option>
                            </datalist>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" class="form-control">
                        </div> --}}
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <input class="btn btn-success" value="Save" onclick="save_click();">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function save_click() {
            $('#addAndEditData').modal('hide');
        }

        function form_group_option(label, id_input, id_list, list_value, name) {
            // Validate input
            if (!label || !id_input || !id_list || !Array.isArray(list_value)) {
                console.error("Invalid arguments provided to form_group_option.");
                return "";
            }

            // Build HTML string using template literals
            let html = `<div class="form-group">
                <label for="${id_input}">${label}</label>
                <input type="text" id="${id_input}" list="${id_list}" class="form-control">
                <datalist name="${name}" id="${id_list}">`;

            // Loop through list_value and create options
            for (const item of list_value) {
                html += `<option value="${item[0]}">${item[1]}</option>`;
            }

            html += `</datalist>
                </div>`;

            return html;
        }

        async function list_pekerjaan(text_input) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: '/search-pekerjaan',
                    type: 'GET',
                    data: {
                        search: text_input
                    },
                    success: function(data) {
                        console.log(data); // Log the data to inspect the response

                        // Convert data to list_value format
                        let list_value = data.map(item => [item.nama_pekerjaan, item.id]);
                        resolve(list_value);
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        reject(xhr);
                    }
                });
            });
        }

        async function add_form_data_pekerjaan() {
            document.getElementById('modal_title').innerHTML = "Tambahkan Data Pekerjaan";
            let list_value = await list_pekerjaan('');
            var form_html = form_group_option(
                "Pekerjaan",
                "pekerjaan_input",
                "pekerjaan_list",
                list_value,
                "pekerjaan"
            );
            document.getElementById('modal_body').innerHTML = form_html;
        }

        // Initialize with some content
        add_form_data_pekerjaan();

        // Example of calling list_pekerjaan with user input
        $('#search_pekerjaan').on('input', async function() {
            var query = $(this).val().trim();
            if (query.length > 0) {
                let list_value = await list_pekerjaan(query);
                var form_html = form_group_option(
                    "Pekerjaan",
                    "pekerjaan_input",
                    "pekerjaan_list",
                    list_value,
                    "pekerjaan"
                );
                document.getElementById('modal_body').innerHTML = form_html;
            } else {
                $('#modal_body').empty();
            }
        });

        function form_group(tipe, label, id, name) {
            var temp = '';
            temp += '<div class="form-group">';
            temp += '<label for="';
            temp += id;
            temp += '">';
            temp += label;
            temp += '</label>';
            temp += '<input type="';
            temp += tipe;
            temp += '" id="';
            temp += id;
            temp += '" name="';
            temp += name;
            temp += '"';
            temp += ' class="form-control">';
            return temp;
        }

        function checkText() {
            const input = document.getElementById('programmingLanguagesInput').value;
            const dataList = document.getElementById('programmingLanguages').options;
            let found = false;

            for (let i = 0; i < dataList.length; i++) {
                if (dataList[i].value === input) {
                    console.log(dataList[i].text);
                    found = true;
                    break;
                }
            }

            if (!found) {
                console.log("Tidak ditemukan");
            }
        }

        $(document).ready(function() {
            $('#addAndEditData').modal('show');
            add_form_data_pekerjaan();
            $('#search_pekerjaan').on('input', function() {
                var query = $(this).val().trim();

                if (query.length > 0) {
                    $.ajax({
                        url: '/search-pekerjaan',
                        type: 'GET',
                        data: {
                            search: query
                        },
                        success: function(data) {
                            console.log(data); // Log the data to inspect the response
                            var searchResults = $('#search_results_pekerjaan');
                            searchResults.empty();

                            if (data.length > 0) {
                                data.forEach(function(item) {
                                    var resultItem = `
                                        <a href="/pekerjaan/${item.id}" class="list-group-item list-group-item-action">
                                            <div class="search-title">${item.nama_pekerjaan}</div>
                                        </a>
                                    `;
                                    searchResults.append(resultItem);
                                });
                            } else {
                                var noResultsItem = `
                                    <a href="/tambah-pekerjaan" class="list-group-item list-group-item-action text-danger">
                                        Tidak ditemukan, klik untuk menambahkan
                                    </a>
                                `;
                                searchResults.append(noResultsItem);
                            }
                        },
                        error: function(xhr) {
                            console.error('Error:', xhr);
                        }
                    });
                } else {
                    $('#search_results_pekerjaan').empty();
                }
            });
        });
    </script>
@endsection
