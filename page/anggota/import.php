<div class="panel panel-default">
    <div class="panel-heading">
        <strong>Import Data Anggota dari File Excel</strong>
    </div>

    <div class="panel-body">
        <form method="POST" enctype="multipart/form-data" action="page/anggota/proses_import.php">
            <div class="form-group">
                <label>Pilih File Excel (.xls / .xlsx)</label>
                <input type="file" name="fileexcel" class="form-control" accept=".xls,.xlsx" required>
            </div>

            <button type="submit" name="import" class="btn btn-success">
                <i class="fa fa-upload"></i> Upload & Import
            </button>
            <a href="?page=anggota" class="btn btn-default">Kembali</a>
        </form>
    </div>
</div>