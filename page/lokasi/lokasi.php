<div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             Data Lokasi Buku
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <?php if ($_SESSION['admin']) { ?>
                                <div>
                            <a href="?page=lokasi&aksi=tambah" class="btn btn-success" style="margin-top:  8px;"><i class="fa fa-plus"></i> Tambah Data</a>
                            </div><br>
                                <?php } ?>
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Lokasi Buku</th>
                                            <?php if ($_SESSION['admin']) { ?>
                                            <th width="19%">Aksi</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php

                                        $no = 1;

                                        $sql = $koneksi->query("select * from tb_lokasi");

                                        while ($data= $sql->fetch_assoc()) {

                                    ?>

                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo $data['lokasi'];?></td>
                                        <?php if ($_SESSION['admin']) { ?>
                                        <td>
                                            <a href="?page=lokasi&aksi=ubah&id_lokasi=<?php echo $data['id_lokasi']; ?>" class="btn btn-warning" ><i class="fa fa-edit"></i> Ubah</a>
                                            <a onclick="return confirm('Anda yakin ingin menghapus?')" href="?page=lokasi&aksi=hapus&id_lokasi=<?php echo $data['id_lokasi']; ?>" class="btn btn-danger" ><i class="fa fa-trash"></i> Hapus</a>

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

     