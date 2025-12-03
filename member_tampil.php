<table id="member-table"> 
<thead>
    <tr>
        <th>No</th>
        <th>Foto</th>
        <th>Nama Member</th>
        <th>No HP</th>
        <th>Member Tier</th>
        <th>Kadaluarsa</th>
        <th>Aksi</th>
    </tr>
</thead>
<tbody>
<?php
$query = mysqli_query($conn, "SELECT 
    m.*, 
    u.username 
    FROM members m 
    JOIN users u ON m.id_user = u.id_user 
    ORDER BY m.tgl_gabung DESC");
$no = 0;
while($data = mysqli_fetch_array($query)){
    $no++;
    $status_expired = (strtotime($data['tgl_kadaluarsa']) < strtotime(date('Y-m-d'))) ? 'style="color:red; font-weight:bold;"' : '';
?>
    <tr <?= $status_expired ?>>
        <td><?= $no ?></td>
        <td>
            <?php if (!empty($data['foto_profil'])): ?>
                <img src="assets/<?= $data['foto_profil'] ?>" alt="<?= $data['nama_lengkap'] ?>">
            <?php else: ?>
                
            <?php endif; ?>
        </td>
        <td><?= $data['nama_lengkap'] ?> (<?= $data['username'] ?>)</td>
        <td><?= $data['no_hp'] ?></td>
        <td><?= $data['jenis_member'] ?></td>
        <td><?= date('d M Y', strtotime($data['tgl_kadaluarsa'])) ?></td>
        <td>
            <a class="tombol edit" href="member_edit.php?id=<?= $data['id_member'] ?>">Edit</a>
            <a class="tombol hapus" onclick="return confirm('Yakin menghapus <?= $data['nama_lengkap'] ?>?')"
               href="member_hapus.php?id=<?= $data['id_member'] ?>&foto=<?= $data['foto_profil'] ?>">Hapus</a>
        </td>
    </tr>
<?php
}
if ($no == 0) {
    echo "<tr><td colspan='7' style='text-align:center;'>Belum ada member terdaftar.</td></tr>";
}
?>
</tbody>
</table>
