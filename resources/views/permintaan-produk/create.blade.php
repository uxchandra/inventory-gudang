<div class="modal fade" tabindex="-1" role="dialog" id="modal_tambah_permintaan">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Permintaan Produk</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form enctype="multipart/form-data">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label>Tanggal</label>
                  <input type="text" class="form-control" name="tanggal" id="tanggal">
                  <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-tanggal_keluar"></div>
                </div>

                <div class="form-group">
                  <label>Pilih Barang</label>
                  <select class="js-example-basic-single" name="nama_barang" id="nama_barang" style="width: 100%">
                      <option selected>Pilih Barang</option>
                      @foreach ($barangs as $barang)
                        <option value="{{ $barang->nama_barang }}">{{ $barang->nama_barang }}</option>
                      @endforeach
                  </select>
                  <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-nama_barang"></div>
                </div>

                <div class="form-group">
                  <label>Jumlah</label>
                  <input type="number" class="form-control" name="jumlah_permintaan" id="jumlah_permintaan">
                  <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-stok_minimum"></div>
                </div>  
    
                <div class="form-group">
                  <label>Satuan Barang</label>
                  <input type="text" class="form-control" name="satuan_id" id="satuan_id" disabled>
                </div>
              </div>
            </div>

        </div>
        <div class="modal-footer bg-whitesmoke br">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
          <button type="button" class="btn btn-primary" id="store">Tambah</button>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>



