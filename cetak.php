<?php
include_once "config.php";

$sql = "SELECT r.data as komentar,  GROUP_CONCAT(s.data SEPARATOR ' ') as preprocessing, r.kelas FROM `raw_data` r
JOIN tokenizing t ON t.case_folding_id = r.id
JOIN normalisasi n  ON n.token_id = t.id
JOIN filtering f ON f.normalisasi_id = n.id
JOIN stemming_sastrawi s ON s.filter_id = f.id
GROUP BY r.id";
$exec = mysqli_query($con, $sql);
?>


<table border="1px solid black">
    <thead>
        <th>No.</th>
        <th>Komentar</th>
        <th>Hasil Preprocessing</th>
        <th>Label</th>
    </thead>
    <tbody>
        <?php 
        $no = 1;
        while($data = mysqli_fetch_array($exec, MYSQLI_ASSOC)){
            ?>
            <tr>
                <td><?= $no ?></td>
                <td><?= $data['komentar'] ?></td>
                <td><?= $data['preprocessing'] ?></td>
                <td><?= $data['kelas'] == '1' ? 'Positif' : 'Negatif' ?></td>
            </tr>
            <?php
            $no++;
        }
        ?>
    </tbody>
</table>
