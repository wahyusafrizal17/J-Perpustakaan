<div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             Data Buku
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <?php if ($_SESSION['admin']) { ?>
                                <div>
                            <a href="?page=buku&aksi=tambah" class="btn btn-success" style="margin-top:  8px;"><i class="fa fa-plus"></i> Tambah Data</a>

                            <a href="?page=buku&aksi=import" class="btn btn-primary" style="margin-top:  8px;"><i class="fa fa-file-excel-o"></i> Import Excel</a>

                            </div><br>
                                <?php } ?>
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Judul</th>
                                            <th>Pengarang</th>
                                            <th>Penerbit</th>
                                            <th>Tahun</th>
                                            <th>ISBN</th>
                                            <th>Jumlah</th>
                                            <th>Lokasi</th>
                                            <?php if ($_SESSION['admin']) { ?>
                                            <th width="19%">Aksi</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php

                                        $no = 1;

                                        $sql = $koneksi->query("select * from tb_buku");

                                        while ($data= $sql->fetch_assoc()) {

                                    ?>

                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo $data['judul'];?></td>
                                        <td><?php echo $data['pengarang'];?></td>
                                        <td><?php echo $data['penerbit'];?></td>
                                        <td><?php echo $data['tahun_terbit'];?></td>
                                        <td><?php echo $data['isbn'];?></td>
                                        <td><?php echo $data['jumlah_buku'];?></td>
                                        <td><?php echo $data['lokasi'];?></td>
                                        <?php if ($_SESSION['admin']) { ?>
                                        <td>
                                            <a href="?page=buku&aksi=ubah&id_buku=<?php echo $data['id_buku']; ?>" class="btn btn-warning" ><i class="fa fa-edit"></i> Ubah</a>
                                            <a onclick="return confirm('Anda yakin ingin menghapus?')" href="?page=buku&aksi=hapus&id_buku=<?php echo $data['id_buku']; ?>" class="btn btn-danger" ><i class="fa fa-trash"></i> Hapus</a>

                                        </td>
                                        <?php } ?>
                                    </tr>


                                    <?php  } ?>
                                    </tbody>

                                    </table>

                                  </div>
                        </div>
                     </div>
                   </div>
     </div>                           

     