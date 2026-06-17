<?php

ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);

session_start();

if (!isset($_SESSION['login'])) {
    exit('Akses ditolak');
}

$base_url  = '/manajemen_rs/';
$base_path = $_SERVER['DOCUMENT_ROOT'] . $base_url;

require_once($base_path . 'config/db.php');
require_once __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$config = [
    'PNS' => [
        'table' => 'pegawai_pns',
        'judul' => 'DATA PEGAWAI PNS',
        'headers' => [
            // IDENTITAS
            'ID' => 'id',
            'Nama' => 'nama',
            'NIP' => 'nip',
            'NIK' => 'nik',
            'NPWP' => 'npwp',

            // BIODATA
            'Jenis Kelamin' => 'jenis_kelamin',
            'Agama' => 'agama',
            'Tempat Lahir' => 'tempat_lahir',
            'Tgl Lahir' => 'tgl_lahir',
            'Status Perkawinan' => 'status_perkawinan',
            'Nama Suami/Istri' => 'nama_suami_istri',
            'Nama Anak' => 'nama_anak',
            'Alamat' => 'alamat_rumah',
            'No Telpon' => 'no_telpon',
            'Email' => 'email',

            // PENDIDIKAN
            'Pendidikan' => 'pendidikan_terakhir',
            'Program Studi' => 'program_studi_pendidikan',
            'Universitas' => 'universitas',
            'Tahun Pendidikan' => 'tahun_pendidikan',

            // JABATAN
            'Jabatan' => 'jabatan',
            'Eselon' => 'eselon',
            'Unit' => 'unit',
            'Mekanisme Jabatan' => 'keterangan_mekanisme_jabatan',

            // KARIER
            'Gol Terakhir' => 'gol_terakhir',
            'TMT Gol Terakhir' => 'tmt_gol_terakhir',
            'KP' => 'kp',
            'KGB 2024' => 'rencana_kgb_2024',
            'KGB 2025' => 'rencana_kgb_2025',
            'KGB 2026' => 'rencana_kgb_2026',

            // KEPEGAWAIAN
            'TMT Masuk' => 'tmt_masuk',
            'TMT CPNS' => 'tmt_cpns',
            'TMT Jabatan' => 'tmt_jabatan',
            'Status Pegawai' => 'status_pegawai',
            'Status Kepegawaian' => 'status_kepegawaian',

            // STR / SIP
            'STR' => 'str_no',
            'Tgl STR' => 'tgl_str',
            'SIP' => 'sip',
            'Masa Berlaku SIP' => 'masa_berlaku',
        ]
    ],
    'P3K FULL' => [
        'table' => 'pegawai_p3k_penuh_waktu',
        'judul' => 'DATA P3K PENUH WAKTU',
        'headers' => [
            // IDENTITAS
            'ID' => 'id',
            'Nama' => 'nama',
            'NIP' => 'nip',
            'NIK' => 'nik',
            'NPWP' => 'npwp',

            // BIODATA
            'Jenis Kelamin' => 'jenis_kelamin',
            'Agama' => 'agama',
            'Tempat Lahir' => 'tempat_lahir',
            'Tgl Lahir' => 'tgl_lahir',
            'Status Perkawinan' => 'status_perkawinan',
            'Nama Suami/Istri' => 'nama_suami_istri',
            'Nama Anak' => 'nama_anak',
            'Alamat' => 'alamat_rumah',
            'No Telpon' => 'no_telpon',
            'Email' => 'email',

            // PENDIDIKAN
            'Pendidikan' => 'pendidikan_terakhir',
            'Program Studi' => 'program_studi_pendidikan',
            'Universitas' => 'universitas',
            'Tahun Pendidikan' => 'tahun_pendidikan',

            // JABATAN
            'Jabatan' => 'jabatan',
            'Eselon' => 'eselon',
            'Unit' => 'unit',
            'Mekanisme Jabatan' => 'keterangan_mekanisme_jabatan',

            // KARIER
            'Gol Terakhir' => 'gol_terakhir',
            'TMT Gol Terakhir' => 'tmt_gol_terakhir',
            'KP' => 'kp',
            'KGB 2024' => 'rencana_kgb_2024',
            'KGB 2025' => 'rencana_kgb_2025',
            'KGB 2026' => 'rencana_kgb_2026',

            // KEPEGAWAIAN
            'TMT Masuk' => 'tmt_masuk',
            'TMT CPNS' => 'tmt_cpns',
            'TMT Jabatan' => 'tmt_jabatan',
            'Status Pegawai' => 'status_pegawai',
            'Status Kepegawaian' => 'status_kepegawaian',

            // STR / SIP
            'STR' => 'str_no',
            'Tgl STR' => 'tgl_str',
            'SIP' => 'sip',
            'Masa Berlaku SIP' => 'masa_berlaku',
        ]
    ],
    'P3K PART' => [
        'table' => 'pegawai_p3k_paruh_waktu',
        'judul' => 'DATA P3K PARUH WAKTU',
        'headers' => [
            'ID' => 'id',
            'Nama' => 'nama',
            'NIK' => 'nik',
            'NIP' => 'nip',

            // biodata
            'Tempat Lahir' => 'tempat_lahir',
            'Tanggal Lahir' => 'tanggal_lahir',
            'Jenis Kelamin' => 'jenis_kelamin',
            'Agama' => 'agama',
            'Status Perkawinan' => 'status_perkawinan',

            // kontak
            'Alamat' => 'alamat',
            'No HP' => 'no_hp',
            'Email' => 'email',

            // pendidikan
            'Pendidikan' => 'pendidikan',
            'Program Studi' => 'program_studi',

            // pekerjaan
            'Jabatan' => 'jabatan',
            'Unit' => 'unit',
            'Status Kepegawaian' => 'status_kepegawaian',

            // status waktu
            'TMT Masuk' => 'tmt_masuk',
            'TMT Kepegawaian' => 'tmt_kepegawaian',
            'Masa Berlaku' => 'masa_berlaku',
        ]
    ],
    'KONTRAK' => [
        'table' => 'pegawai_kontrak',
        'judul' => 'DATA PEGAWAI KONTRAK',
        'headers' => [
            'ID' => 'id',
            'Nama' => 'nama',
            'NIK' => 'nik',
            'NIP' => 'nip',
            'Tempat Lahir' => 'tempat_lahir',
            'Tanggal Lahir' => 'tanggal_lahir',
            'Jenis Kelamin' => 'jenis_kelamin',
            'Agama' => 'agama',
            'Alamat' => 'alamat',
            'Nomor HP' => 'nomor_hp',
            'Email' => 'email',
            'Pendidikan' => 'pendidikan',
            'Program Studi' => 'program_studi',
            'Jabatan' => 'jabatan',
            'Unit' => 'unit',
            'Status Kepegawaian' => 'status_kepegawaian',
            'TMT Kepegawaian' => 'tmt_kepegawaian',
            'TMT Masuk' => 'tmt_masuk',
            'Status Pegawai' => 'status_pegawai',
            'Masa Berlaku SIP' => 'masa_berlaku'
        ]
    ],
    'TETAP' => [
        'table' => 'pegawai_tetap',
        'judul' => 'DATA PEGAWAI TETAP',
        'headers' => [
            'ID' => 'id',
            'Nama' => 'nama',
            'NIK' => 'nik',
            'NIP' => 'nip',
            'Tempat Lahir' => 'tempat_lahir',
            'Tanggal Lahir' => 'tanggal_lahir',
            'Jenis Kelamin' => 'jenis_kelamin',
            'Agama' => 'agama',
            'Alamat' => 'alamat',
            'Nomor HP' => 'nomor_hp',
            'Email' => 'email',
            'Pendidikan' => 'pendidikan',
            'Program Studi' => 'program_studi',
            'Jabatan' => 'jabatan',
            'Unit' => 'unit',
            'Status Kepegawaian' => 'status_kepegawaian',
            'TMT Masuk' => 'tmt_masuk',
            'Status Pegawai' => 'status_pegawai',
            'Masa Berlaku SIP' => 'masa_berlaku'
        ]
    ],
    'MITRA' => [
        'table' => 'pegawai_mitra',
        'judul' => 'DATA PEGAWAI MITRA',
        'headers' => [
            'ID' => 'id',
            'Nama' => 'nama',
            'TMT Masuk' => 'tmt',
            'Jabatan' => 'jabatan',
            'Jenis Kelamin' => 'jenis_kelamin',
            'Unit' => 'unit',
            'Alamat' => 'alamat',
            'No HP' => 'no_hp',
            'Email' => 'email',
            'Masa Berlaku SIP' => 'masa_berlaku',
            'Status Kepegawaian' => 'status_kepegawaian',
        ]
    ],
];

/* =========================
   INIT EXCEL
========================= */
$spreadsheet = new Spreadsheet();
$sheetIndex = 0;

/* =========================
   LOOP SHEETS
========================= */
foreach ($config as $key => $cfg) {

    $table = $cfg['table'];
    $judul = $cfg['judul'];
    $headers = $cfg['headers'];

    // FIX: urutan data biar konsisten (penting untuk MITRA)
    $sql = "SELECT * FROM `$table`
        WHERE LOWER(status_pegawai)='aktif'
        ORDER BY id ASC";
    $query = mysqli_query($koneksi, $sql);

    if (!$query) continue;

    $sheet = ($sheetIndex == 0)
        ? $spreadsheet->getActiveSheet()
        : $spreadsheet->createSheet();

    $sheet->setTitle(substr($key, 0, 31));

    /* =========================
       TITLE
    ========================= */
    $sheet->setCellValue('A1', $judul);
    $sheet->mergeCells('A1:' . Coordinate::stringFromColumnIndex(count($headers)) . '1');

    $sheet->getStyle('A1')->applyFromArray([
        'font' => [
            'bold' => true,
            'size' => 14
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER
        ]
    ]);

    $sheet->getRowDimension(1)->setRowHeight(25);

    /* =========================
       HEADER
    ========================= */
    $col = 1;
    foreach ($headers as $label => $dbKey) {
        $cell = Coordinate::stringFromColumnIndex($col) . '3';
        $sheet->setCellValue($cell, $label);
        $col++;
    }

    $lastCol = Coordinate::stringFromColumnIndex(count($headers));

    $sheet->getStyle("A3:{$lastCol}3")->applyFromArray([
        'font' => [
            'bold' => true,
            'size' => 12,
            'color' => ['rgb' => 'FFFFFF']
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
            'wrapText' => false
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => '4F46E5']
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => 'FFFFFF']
            ]
        ]
    ]);

    $sheet->getRowDimension(3)->setRowHeight(28);

    /* =========================
       DATA (FIXED - hanya 1 loop)
    ========================= */
    $rowExcel = 4;
    $no = 1;

    while ($row = mysqli_fetch_assoc($query)) {

        $col = 1;

        foreach ($headers as $dbKey) {

            $value = ($dbKey === 'id')
                ? $no
                : ($row[$dbKey] ?? '');

            $cell = Coordinate::stringFromColumnIndex($col) . $rowExcel;

            $sheet->setCellValueExplicit(
                $cell,
                (string)$value,
                DataType::TYPE_STRING
            );

            $col++;
        }

        $no++;
        $rowExcel++;
    }

    $lastRow = $rowExcel - 1;
    /* ================= BORDER FIX (GLOBAL + CLEAN) ================= */
    $sheet->getStyle("A3:{$lastCol}{$lastRow}")
        ->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_LEFT
            ]
        ]);

    $sheet->freezePane('A4');
    if ($lastRow >= 4) {
        $sheet->setAutoFilter("A3:{$lastCol}{$lastRow}");
    }

    /* ================= WRAP TEXT ================= */
    if ($lastRow >= 4) {
        $sheet->getStyle("A4:{$lastCol}{$lastRow}")
            ->getAlignment()
            ->setWrapText(false)
            ->setVertical(Alignment::VERTICAL_CENTER);
    }

    /* ================= AUTO SIZE ================= */
    foreach (range(1, count($headers)) as $i) {
        $colLetter = Coordinate::stringFromColumnIndex($i);
        $sheet->getColumnDimension($colLetter)->setAutoSize(true);
    }

    $sheetIndex++;
}

/* =========================
   OUTPUT
========================= */
if (ob_get_length()) ob_end_clean();

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="semua_pegawai.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
