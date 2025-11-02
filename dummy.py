import random
from datetime import datetime, timedelta

# Buat dummy data 30 hari ke belakang
today = datetime(2025, 10, 22)  # sesuai tanggal pada screenshot user
days = 30

presensi_data = []
transaksi_data = []

id_presensi = 1
id_transaksi = 1000
id_buku = 1

for i in range(days):
    tgl = today - timedelta(days=days - i - 1)
    tgl_str = tgl.strftime("%Y-%m-%d")
    tgl_pinjam_str = tgl.strftime("%d-%m-%Y")
    tgl_input_str = tgl.strftime("%Y-%m-%d %H:%M:%S")

    # presensi antara 4 - 17
    jml_presensi = random.randint(4, 17)
    for j in range(jml_presensi):
        nis = 2016210000 + random.randint(1, 999)
        nama = f"Siswa{nis}"
        kelas = f"VII {chr(65 + random.randint(0, 6))}"
        jam = f"{tgl_str} 07:{random.randint(10,59):02d}:00"
        presensi_data.append(f"({id_presensi}, '{nis}', '{nama}', '{kelas}', '{jam}')")
        id_presensi += 1

    # transaksi antara 3 - 10
    jml_transaksi = random.randint(3, 10)
    for k in range(jml_transaksi):
        nis = 2016210000 + random.randint(1, 999)
        nama = f"Siswa{nis}"
        judul = f"Buku {random.randint(1,50)}"
        tgl_kembali = (tgl + timedelta(days=random.randint(3, 14))).strftime("%d-%m-%Y")
        transaksi_data.append(f"({id_transaksi}, {id_buku}, '{judul}', {nis}, '{nama}', '{tgl_pinjam_str}', '{tgl_kembali}', 'Pinjam', '{tgl_input_str}')")
        id_transaksi += 1
        id_buku += 1

# Generate SQL
presensi_sql = "INSERT INTO `tb_presensi` (`id`, `nis`, `nama_anggota`, `kelas`, `tgl_presensi`) VALUES\n" + ",\n".join(presensi_data) + ";"

transaksi_sql = "INSERT INTO `tb_transaksi` (`id`, `id_buku`, `judul`, `nis`, `nama`, `tgl_pinjam`, `tgl_kembali`, `status`, `tgl_input`) VALUES\n" + ",\n".join(transaksi_data) + ";"

presensi_sql[:1000], transaksi_sql[:1000]  # show preview

print(transaksi_sql)
