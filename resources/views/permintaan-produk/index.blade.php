@extends('layouts.app')

@include('permintaan-produk.create')

@section('content')
    <div class="section-header">
        <h1>Permintaan Barang</h1>
        <div class="ml-auto">
            <a href="javascript:void(0)" class="btn btn-primary" id="button_tambah_permintaan"><i class="fa fa-plus"></i> Tambah</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table_id" class="display">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        const auth = {
            role: '{{ auth()->user()->role->role }}'
        };
    </script>

    <!-- Datatables jQuery -->
    <script>
    $(document).ready(function() {
        const table = $('#table_id').DataTable({
            paging: true
        });

        function fetchData() {
            $.ajax({
                url: "/permintaan-produk/get-data",
                type: "GET",
                cache: false,
                dataType: 'JSON',
                success: function(response) {
                    console.log("Response Data: ", response.data);
                    let counter = 1;
                    $('#table_id').DataTable().clear();
                    $.each(response.data, function(key, value) {
                        let orderStatus = value.status ?? "Status tidak tersedia";
                        let actionButtons = '';

                        if (auth.role === 'kepala gudang') {
                            if (orderStatus === 'menunggu_konfirmasi') {
                                actionButtons = `
                                    <a href="javascript:void(0)" id="button_approve_permintaan" data-id="${value.id}" class="btn btn-icon btn-success"><i class="fas fa-check"></i> Approve</a>
                                    <a href="javascript:void(0)" id="button_reject_permintaan" data-id="${value.id}" class="btn btn-icon btn-danger"><i class="fas fa-times"></i> Reject</a>
                                `;
                            }
                        } else if (auth.role === 'admin gudang') {
                            if (orderStatus === 'diterima') {
                                actionButtons = `
                                    <a href="javascript:void(0)" id="button_selesaikan_proses" data-id="${value.id}" class="btn btn-icon btn-primary"><i class="fas fa-check"></i> Selesaikan Proses</a>
                                `;
                            }
                        }

                        let row = `
                            <tr class="permintaan-row" id="index_${value.id}">
                                <td>${counter++}</td>
                                <td>${value.tanggal}</td>
                                <td>${value.nama_barang}</td>
                                <td>${value.jumlah_permintaan}</td>
                                <td>
                                    <span class="badge 
                                        ${orderStatus === 'diterima' ? 'badge-success' : 
                                        (orderStatus === 'ditolak' ? 'badge-danger' : 
                                        (orderStatus === 'selesai' ? 'badge-primary' : 'badge-warning'))}">
                                        ${orderStatus}
                                    </span>
                                </td>
                                <td>
                                    <div class="button-group">
                                        ${actionButtons}
                                    </div>
                                </td>
                            </tr>
                        `;
                        $('#table_id').DataTable().row.add($(row)).draw(false);
                    });

                    // Refresh DataTable
                    let table = $('#table_id').DataTable();
                    table.draw();
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching data:", error);
                }
            });
        }

        fetchData();

        $('body').on('click', '#button_tambah_permintaan', function() {
            $('#modal_tambah_permintaan').modal('show');
        });

        $('#store').click(function(e) {
            e.preventDefault();

            let nama_barang = $('#nama_barang').val();
            let jumlah_permintaan = $('#jumlah_permintaan').val();
            let tanggal = $('#tanggal').val();  // Menambahkan pengambilan input tanggal
            let token = $("meta[name='csrf-token']").attr("content");

            let formData = new FormData();
            formData.append('nama_barang', nama_barang);
            formData.append('jumlah_permintaan', jumlah_permintaan);
            formData.append('tanggal', tanggal);  // Menambahkan tanggal ke formData
            formData.append('_token', token);

            $.ajax({
                url: '/permintaan-produk',
                type: "POST",
                cache: false,
                headers: {
                    'Authorization': 'Bearer ' + token 
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: true,
                        timer: 3000
                    });

                    // Reset form dan tutup modal
                    $('#nama_barang').val('');
                    $('#jumlah_permintaan').val('');
                    $('#tanggal').val('');  // Reset input tanggal
                    $('#modal_tambah_permintaan').modal('hide');

                    // Refresh data
                    fetchData();
                },
                error: function(error) {
                    handleFormErrors(error);
                }
            });
        });
    });


    $('body').on('click', '#button_selesaikan_proses', function() {
        let permintaan_id = $(this).data('id');
        let token = $("meta[name='csrf-token']").attr("content");

        Swal.fire({
            title: 'Apakah Kamu Yakin?',
            text: "Pastikan semua barang telah diinput sesuai permintaan sebelum menyelesaikan proses ini.",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'BATAL',
            confirmButtonText: 'YA, SELESAIKAN!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/permintaan-produk/${permintaan_id}/selesaikan`, // Sesuaikan dengan URL yang diinginkan
                    type: "POST",
                    data: { "_token": token },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: response.message,
                            showConfirmButton: true,
                            timer: 3000
                        });
                        var newOrderStatus = "selesai"; // Mengupdate status menjadi 'selesai'
                        updateStatus(permintaan_id, newOrderStatus);

                        $('#button_selesaikan_proses[data-id="' + permintaan_id + '"]').remove();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error completing data:", error);
                    }
                });
            }
        });
    });


    $('body').on('click', '#button_approve_permintaan', function() {
        let permintaan_id = $(this).data('id');
        let token = $("meta[name='csrf-token']").attr("content");

        Swal.fire({
            title: 'Apakah Kamu Yakin?',
            text: "Ingin menyetujui permintaan ini?",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'TIDAK',
            confirmButtonText: 'YA, SETUJU!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/permintaan-produk/${permintaan_id}/approve`,
                    type: "POST",
                    data: { "_token": token },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: response.message,
                            showConfirmButton: true,
                            timer: 3000
                        });
                        // Update status dan hapus tombol
                        updateStatus(permintaan_id, "diterima");
                    },
                    error: function(xhr, status, error) {
                        console.error("Error approving data:", error);
                    }
                });
            }
        });
    });


    $('body').on('click', '#button_reject_permintaan', function() {
        let permintaan_id = $(this).data('id');
        let token = $("meta[name='csrf-token']").attr("content");

        Swal.fire({
            icon: 'warning',
            title: 'Tolak Permintaan?',
            showCancelButton: true,
            cancelButtonText: 'Batal',
            confirmButtonText: 'Ya, Tolak'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/permintaan-produk/${permintaan_id}/reject`,
                    type: "POST",
                    data: { "_token": token },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: response.message,
                            showConfirmButton: true,
                            timer: 3000
                        });
                        var newOrderStatus = "ditolak"; // Atau ambil dari response jika ada
                        updateStatus(permintaan_id, newOrderStatus);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error rejecting data:", error);
                    }
                });
            }
        });
    });

    function handleFormErrors(error) {
        if (error.responseJSON) {
            ['nama_barang', 'jumlah_permintaan', 'satuan_id'].forEach(field => {
                if (error.responseJSON[field]) {
                    $(`#alert-${field}`).removeClass('d-none')
                        .addClass('d-block')
                        .html(error.responseJSON[field][0]);
                }
            });
        }
    }

    function updateStatus(id, newStatus) {
        var row = $('#index_' + id);
        let badgeClass = newStatus === 'diterima' ? 'badge-success' : 
                        newStatus === 'ditolak' ? 'badge-danger' : 
                        newStatus === 'selesai' ? 'badge-primary' :
                        'badge-warning';
        
        row.find('td:eq(4)').html(`<span class="badge ${badgeClass}">${newStatus}</span>`);
        
        // Update tombol berdasarkan role dan status baru
        if (auth.role === 'kepala gudang') {
            if (newStatus === "diterima" || newStatus === "ditolak") {
                row.find('td:eq(5)').empty();
            } else {
                row.find('td:eq(4)').html(`
                    <div class="button-group">
                        <a href="javascript:void(0)" id="button_approve_permintaan" data-id="${id}" class="btn btn-icon btn-success"><i class="fas fa-check"></i> Approve</a>
                        <a href="javascript:void(0)" id="button_reject_permintaan" data-id="${id}" class="btn btn-icon btn-danger"><i class="fas fa-times"></i> Reject</a>
                    </div>
                `);
            }
        } else if (auth.role === 'admin gudang' && newStatus === 'diterima') {
            row.find('td:eq(5)').html(`
                <div class="button-group">
                    <a href="javascript:void(0)" id="button_selesaikan_proses" data-id="${id}" class="btn btn-icon btn-primary">
                        <i class="fas fa-check"></i> Selesaikan Proses
                    </a>
                </div>
            `);
        }
        
        if (newStatus === 'selesai') {
            row.find('td:eq(5)').empty(); // Menghapus tombol di kolom tindakan
        }
    }
</script>

<script>
    $(document).ready(function() {
        setTimeout(function() {
            $('.js-example-basic-single').select2();

            $('#nama_barang').on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var nama_barang = selectedOption.text();

                $.ajax({
                    url: 'api/permintaan-produk', 
                    type: 'GET',
                    data: { nama_barang: nama_barang },
                    success: function(response) {
                        console.log('Response:', response); 
                        if (response && response.satuan_id) {
                            $('#satuan_id').val(response.satuan_id).change(); 
                            getSatuanName(response.satuan_id, function(satuan) {
                                $('#satuan_id').val(satuan); 
                            });
                        } else {
                            $('#satuan_id').val(''); 
                        }
                    },
                    error: function(xhr) {
                        console.error('Terjadi kesalahan saat mengambil data:', xhr);
                    }
                });
            });
            
            function getSatuanName(satuanId, callback) {
                $.getJSON('{{ url('api/satuan') }}', function(satuans) {
                    var satuan = satuans.find(function(s) {
                        return s.id === satuanId;
                    });
                    callback(satuan ? satuan.satuan : ''); 
                });
            }
        }, 500);
    });
</script>

<script>
        var today = new Date();


        var year = today.getFullYear();
        var month = (today.getMonth() + 1).toString().padStart(2, '0'); 
        var day = today.getDate().toString().padStart(2, '0');

        var formattedDate = year + '-' + month + '-' + day;

        document.getElementById('tanggal').value = formattedDate;
</script>


@endsection
