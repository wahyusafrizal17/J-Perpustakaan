<div class="panel panel-default">
  <div class="panel-heading">
    Cetak Laporan Peminjaman
  </div>
  <div class="panel-body">
    <div class="row">
      <div class="col-md-12">
        <form method="POST" action="laporan/laporan_transaksi.php" target="_blank">
          <div class="form-group">
            <label>Dari Bulan</label>
            <input class="form-control" name="tanggal1" type="date" required />
          </div>

          <div class="form-group">
            <label>Sampai Bulan</label>
            <input class="form-control" name="tanggal2" type="date" required />
          </div>

          <div>
            <input type="submit" name="cetak" value="Cetak" class="btn btn-primary">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
