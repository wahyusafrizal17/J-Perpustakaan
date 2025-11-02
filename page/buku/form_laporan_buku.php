<div class="panel panel-default">
  <div class="panel-heading">
    <strong>Cetak Laporan Berdasarkan Tahun Terbit</strong>
  </div>
  <div class="panel-body">
    <div class="row">
      <div class="col-md-12">
        <form method="POST" action="laporan/laporan.php" target="_blank">
          <div class="form-group">
            <label>Dari Tahun</label>
            <input class="form-control" name="tahun1" type="number" min="1900" max="2100" required />
          </div>

          <div class="form-group">
            <label>Sampai Tahun</label>
            <input class="form-control" name="tahun2" type="number" min="1900" max="2100" required />
          </div>

          <div>
            <input type="submit" name="cetak" value="Cetak" class="btn btn-primary">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>