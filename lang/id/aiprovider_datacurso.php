<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin strings are defined here.
 *
 * @package     aiprovider_datacurso
 * @category    string
 * @copyright   Josue <josue@datacurso.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['action'] = 'Aksi';
$string['action:generate_image:endpoint'] = 'Endpoint API';
$string['action:generate_image:endpoint_desc'] = 'Endpoint untuk menghasilkan gambar';
$string['action:generate_text:endpoint'] = 'Endpoint API';
$string['action:generate_text:endpoint_desc'] = 'Endpoint untuk menghasilkan teks';
$string['action:generate_text:instruction'] = 'Instruksi sistem';
$string['action:generate_text:instruction_desc'] = 'Instruksi ini dikirim ke model AI bersama dengan permintaan pengguna. Mengedit instruksi ini tidak direkomendasikan kecuali benar-benar diperlukan.';
$string['action:summarise_text:endpoint'] = 'Endpoint API';
$string['action:summarise_text:endpoint_desc'] = 'Endpoint untuk menghasilkan teks';
$string['action:summarise_text:instruction'] = 'Instruksi sistem';
$string['action:summarise_text:instruction_desc'] = 'Instruksi ini dikirim ke model AI bersama dengan permintaan pengguna. Mengedit instruksi ini tidak direkomendasikan kecuali benar-benar diperlukan.';
$string['all'] = 'Semua';
$string['apikey'] = 'Kunci API';
$string['apikey_desc'] = 'Masukkan kunci API dari layanan Datacurso Anda untuk menghubungkan AI.';
$string['apiurl'] = 'URL API dasar';
$string['apiurl_desc'] = 'Masukkan URL dasar layanan untuk terhubung ke API Datacurso.';
$string['assigned'] = 'Ditugaskan';
$string['chart_actions'] = 'Distribusi token berdasarkan layanan';
$string['chart_tokens_by_day'] = 'Konsumsi token per hari';
$string['chart_tokens_by_month'] = 'Jumlah token yang dikonsumsi per bulan';
$string['configured'] = 'Dikonfigurasi';
$string['contextwstoken'] = 'Token layanan web untuk konteks kursus';
$string['contextwstoken_desc'] = 'Token yang digunakan oleh AI untuk mengambil informasi kursus (konteks). Disimpan dengan aman. Buat/kelola token di Administrasi situs > Server > Layanan web > Kelola token.';
$string['created'] = 'Dibuat';
$string['datacurso:manage'] = 'Kelola pengaturan penyedia AI';
$string['datacurso:use'] = 'Gunakan layanan AI Datacurso';
$string['datacurso:viewreports'] = 'Lihat laporan penggunaan AI';
$string['description'] = 'Deskripsi';
$string['descriptionpagelistplugins'] = 'Di sini Anda dapat menemukan daftar plugin yang kompatibel dengan penyedia Datacurso';
$string['disabled'] = 'Dinonaktifkan';
$string['enabled'] = 'Diaktifkan';
$string['enableglobalratelimit'] = 'Aktifkan batas global';
$string['enableglobalratelimit_desc'] = 'Jika diaktifkan, batas permintaan global per jam akan diterapkan untuk semua pengguna.';
$string['enableuserratelimit'] = 'Aktifkan batas per pengguna';
$string['enableuserratelimit_desc'] = 'Jika diaktifkan, setiap pengguna akan memiliki batas permintaan per jam.';
$string['exists'] = 'Ada';
$string['generate_activitie'] = 'Hasilkan aktivitas atau sumber daya dengan AI';
$string['generate_analysis_comments'] = 'Hasilkan analisis penilaian aktivitas/sumber daya dengan AI';
$string['generate_analysis_course'] = 'Hasilkan analisis penilaian kursus dengan AI';
$string['generate_analysis_general'] = 'Hasilkan analisis penilaian umum dengan AI';
$string['generate_analysis_story_student'] = 'Hasilkan analisis riwayat siswa dengan AI';
$string['generate_assign_answer'] = 'Hasilkan tinjauan tugas dengan AI';
$string['generate_certificate_answer'] = 'Hasilkan pesan sertifikat dengan AI';
$string['generate_creation_course'] = 'Buat kursus lengkap dengan AI';
$string['generate_forum_chat'] = 'Hasilkan respons forum dengan AI';
$string['generate_image'] = 'Hasilkan gambar dengan AI';
$string['generate_plan_course'] = 'Hasilkan rencana pembuatan kursus dengan AI';
$string['generate_summary'] = 'Hasilkan ringkasan dengan AI';
$string['generate_text'] = 'Hasilkan teks dengan AI';
$string['globalratelimit'] = 'Batas permintaan global';
$string['globalratelimit_desc'] = 'Jumlah maksimum permintaan yang diizinkan per jam untuk seluruh sistem.';
$string['goto'] = 'Buka laporan';
$string['gotopage'] = 'Buka halaman';
$string['id'] = 'ID';
$string['installed'] = 'Terinstal';
$string['invalidlicensekey'] = 'Kunci lisensi tidak valid';
$string['last_sent'] = 'Terakhir dikirim';
$string['licensekey'] = 'Kunci lisensi';
$string['licensekey_desc'] = 'Masukkan kunci lisensi dari area pelanggan Toko Datacurso.';
$string['link_consumptionhistory'] = 'Riwayat konsumsi token';
$string['link_generalreport'] = 'Laporan umum';
$string['link_generalreport_datacurso'] = 'Laporan umum Datacurso AI';
$string['link_listplugings'] = 'Daftar plugin Datacurso';
$string['link_plugin'] = 'Tautan';
$string['link_report_statistic'] = 'Laporan statistik umum';
$string['link_webservice_config'] = 'Pengaturan layanan web Datacurso';
$string['live_log'] = 'Log langsung';
$string['message_no_there_plugins'] = 'Tidak ada plugin tersedia';
$string['missing'] = 'Hilang';
$string['needs_repair'] = 'Perlu perbaikan';
$string['nodata'] = 'Tidak ada informasi ditemukan';
$string['not_assigned'] = 'Tidak ditugaskan';
$string['not_configured'] = 'Tidak dikonfigurasi';
$string['not_created'] = 'Tidak dibuat';
$string['orgid'] = 'ID organisasi';
$string['orgid_desc'] = 'Masukkan pengidentifikasi organisasi Anda di layanan Datacurso.';
$string['pending'] = 'Tertunda';
$string['plugin'] = 'Plugin';
$string['pluginname'] = 'Penyedia AI Datacurso';
$string['privacy:metadata'] = 'Plugin Penyedia AI Datacurso tidak menyimpan data pribadi secara lokal. Semua data diproses oleh layanan AI eksternal Datacurso.';
$string['privacy:metadata:aiprovider_datacurso'] = 'Data permintaan yang dikirim ke layanan eksternal Datacurso AI.';
$string['privacy:metadata:aiprovider_datacurso:externalpurpose'] = 'Data ini dikirim ke Datacurso AI untuk memenuhi aksi yang diminta.';
$string['privacy:metadata:aiprovider_datacurso:numberimages'] = 'Jumlah gambar yang diminta dari layanan AI.';
$string['privacy:metadata:aiprovider_datacurso:prompt'] = 'Teks prompt yang dikirim ke layanan AI.';
$string['privacy:metadata:aiprovider_datacurso:userid'] = 'ID pengguna Moodle yang membuat permintaan AI.';
$string['read_context_course'] = 'Baca konteks untuk pembuatan kursus AI';
$string['read_context_course_model'] = 'Unggah model akademik untuk pembuatan kursus AI';
$string['registration_error'] = 'Kesalahan terakhir';
$string['registration_last'] = 'Pendaftaran';
$string['registration_lastsent'] = 'Terakhir dikirim';
$string['registration_notverified'] = 'Pendaftaran tidak diverifikasi';
$string['registration_status'] = 'Status terakhir';
$string['registration_verified'] = 'Pendaftaran diverifikasi';
$string['registrationapibearer'] = 'Token bearer pendaftaran';
$string['registrationapibearer_desc'] = 'Token bearer yang digunakan untuk mengautentikasi permintaan pendaftaran.';
$string['registrationapiurl'] = 'URL endpoint pendaftaran';
$string['registrationapiurl_desc'] = 'Endpoint untuk menerima muatan pendaftaran situs. Default: http://localhost:8001/register-site';
$string['registrationsettings'] = 'API pendaftaran';
$string['remainingtokens'] = 'Saldo tersisa';
$string['rest_enabled'] = 'Protokol REST diaktifkan';
$string['service'] = 'Layanan';
$string['showrows'] = 'Tampilkan baris';
$string['tokens_available'] = 'Token tersedia';
$string['tokensused'] = 'Token digunakan';
$string['tokenthreshold'] = 'Ambang batas token';
$string['tokenthreshold_desc'] = 'Jumlah token di mana notifikasi akan ditampilkan untuk membeli lebih banyak.';
$string['total_consumed'] = 'Total dikonsumsi';
$string['userid'] = 'Pengguna';
$string['userratelimit'] = 'Batas permintaan per pengguna';
$string['userratelimit_desc'] = 'Jumlah maksimum permintaan yang diizinkan per jam untuk setiap pengguna individu.';
$string['verified'] = 'Diverifikasi';
$string['webserviceconfig_current'] = 'Konfigurasi saat ini';
$string['webserviceconfig_desc'] = 'Secara otomatis mengonfigurasi layanan web khusus untuk layanan AI Datacurso, memungkinkannya untuk mengekstrak informasi platform dengan aman seperti data pengguna dasar, kursus, dan aktivitas untuk kontekstualisasi AI yang lebih baik. Pengaturan ini membuat pengguna layanan, menetapkan peran yang diperlukan, mengonfigurasi layanan eksternal, menghasilkan token aman, dan mengaktifkan protokol REST dalam satu klik. Catatan: Nilai token tidak ditampilkan karena alasan keamanan.';
$string['webserviceconfig_heading'] = 'Pengaturan layanan web otomatis';
$string['webserviceconfig_site'] = 'Informasi situs';
$string['webserviceconfig_status'] = 'Status';
$string['webserviceconfig_title'] = 'Konfigurasi layanan web Datacurso';
$string['workplace'] = 'Apakah ini Moodle Workplace?';
$string['workplace_desc'] = 'Menentukan apakah header X-Workplace harus dikirim dengan nilai true (Workplace) atau false (Moodle standar).';
$string['ws_activity'] = 'Log aktivitas';
$string['ws_btn_regenerate'] = 'Hasilkan ulang token';
$string['ws_btn_retry'] = 'Coba lagi konfigurasi';
$string['ws_btn_setup'] = 'Konfigurasi layanan web';
$string['ws_enabled'] = 'Layanan web diaktifkan';
$string['ws_error_missing_setup'] = 'Layanan atau pengguna tidak ditemukan. Jalankan pengaturan terlebih dahulu.';
$string['ws_error_missing_token'] = 'Token tidak ditemukan. Hasilkan terlebih dahulu.';
$string['ws_error_regenerate_token'] = 'Kesalahan saat menghasilkan ulang token.';
$string['ws_error_registration'] = 'Kesalahan saat mendaftarkan token layanan web.';
$string['ws_error_setup'] = 'Kesalahan saat mengonfigurasi layanan web.';
$string['ws_role'] = 'Peran layanan';
$string['ws_service'] = 'Layanan eksternal';
$string['ws_step_enableauth'] = 'Mengaktifkan plugin autentikasi layanan web…';
$string['ws_step_enablerest'] = 'Mengaktifkan protokol REST…';
$string['ws_step_enablews'] = 'Mengaktifkan layanan web situs…';
$string['ws_step_registration_sent'] = 'Permintaan pendaftaran dikirim.';
$string['ws_step_role_assign'] = 'Menetapkan peran ke pengguna layanan…';
$string['ws_step_role_caps'] = 'Mengatur kemampuan peran yang diperlukan…';
$string['ws_step_role_create'] = 'Membuat peran "{$a}"…';
$string['ws_step_role_exists'] = 'Peran sudah ada, menggunakan ID {$a}…';
$string['ws_step_service_enable'] = 'Membuat/Mengaktifkan layanan eksternal…';
$string['ws_step_service_functions'] = 'Menambahkan fungsi inti umum ke layanan…';
$string['ws_step_service_user'] = 'Mengotorisasi pengguna untuk layanan…';
$string['ws_step_setup'] = 'Memulai pengaturan…';
$string['ws_step_token_create'] = 'Memastikan token ada…';
$string['ws_step_token_generated'] = 'Token dihasilkan.';
$string['ws_step_token_regenerated'] = 'Token dihasilkan ulang.';
$string['ws_step_token_regenerating'] = 'Menghasilkan ulang token…';
$string['ws_step_token_retry'] = 'Mencoba ulang pengaturan…';
$string['ws_step_user_check'] = 'Memverifikasi apakah pengguna "{$a}" ada…';
$string['ws_step_user_create'] = 'Membuat pengguna layanan "{$a}"…';
$string['ws_tokenexists'] = 'Token ada';
$string['ws_user'] = 'Pengguna layanan';
$string['ws_userassigned'] = 'Peran ditetapkan ke pengguna';
