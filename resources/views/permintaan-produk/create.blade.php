<div class="modal fade" tabindex="-1" role="dialog" id="modal_tambah_permintaan">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Barang</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form enctype="multipart/form-data">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label>Nama Barang</label>
                  <input type="text" class="form-control" name="nama_barang" id="nama_barang">
                  <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-nama_barang"></div>
                </div>
                <div class="form-group">
                  <label>Jenis Barang</label>
                  <select class="form-control" name="jenis_id" id="jenis_id">
                    @foreach ($jenis_barangs as $jenis)
                        @if (old('jenis_id') == $jenis->id)
                          <option value="{{ $jenis->id }}" selected>{{ $jenis->jenis_barang }}</option>
                        @else
                          <option value="{{ $jenis->id }}">{{ $jenis->jenis_barang }}</option>
                        @endif
                    @endforeach
                  </select>
                </div>
    
                <div class="form-group">
                  <label>Satuan Barang</label>
                  <select class="form-control" name="satuan_id" id="satuan_id">
                    @foreach ($satuans as $satuan)
                        @if (old('satuan_id') == $satuan->id)
                          <option value="{{ $satuan->id }}" selected>{{ $satuan->satuan }}</option>
                        @else
                          <option value="{{ $satuan->id }}">{{ $satuan->satuan }}</option>
                        @endif
                    @endforeach
                  </select>
                </div>

                <div class="form-group">
                  <label>Jumlah</label>
                  <input type="number" class="form-control" name="stok_minimum" id="stok_minimum">
                  <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-stok_minimum"></div>
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



