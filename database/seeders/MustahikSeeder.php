<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mustahik;

class MustahikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mustahikData = [
            [
                'name' => 'Hasan Fakir',
                'nik' => '3171020101700001',
                'gender' => 'male',
                'address' => 'Jl. Kampung Miskin No. 1',
                'city' => 'Jakarta Timur',
                'province' => 'DKI Jakarta',
                'phone' => '081298765431',
                'date_of_birth' => '1970-01-01',
                'category' => 'fakir',
                'category_description' => 'Tidak memiliki penghasilan tetap dan harta',
                'family_status' => 'married',
                'family_members' => 4,
                'monthly_income' => 0.00,
                'income_source' => 'Kadang meminta-minta',
                'verification_status' => 'verified',
                'verification_notes' => 'Telah disurvei dan memenuhi kriteria fakir',
                'verified_at' => now(),
                'verified_by' => 1, // Admin
                'is_active' => true,
            ],
            [
                'name' => 'Aminah Miskin',
                'nik' => '3171020202750002',
                'gender' => 'female',
                'address' => 'Jl. Perjuangan No. 23',
                'city' => 'Jakarta Barat',
                'province' => 'DKI Jakarta',
                'phone' => '081298765432',
                'date_of_birth' => '1975-02-02',
                'category' => 'miskin',
                'category_description' => 'Memiliki penghasilan tapi tidak mencukupi kebutuhan',
                'family_status' => 'widow/widower',
                'family_members' => 3,
                'monthly_income' => 1500000.00,
                'income_source' => 'Jualan gorengan',
                'verification_status' => 'verified',
                'verification_notes' => 'Penghasilan tidak cukup untuk kebutuhan keluarga',
                'verified_at' => now(),
                'verified_by' => 1, // Admin
                'is_active' => true,
            ],
            [
                'name' => 'Usman Amil',
                'nik' => '3171020303800003',
                'gender' => 'male',
                'address' => 'Jl. Masjid Al-Ikhlas No. 45',
                'city' => 'Jakarta Selatan',
                'province' => 'DKI Jakarta',
                'phone' => '081298765433',
                'date_of_birth' => '1980-03-03',
                'category' => 'amil',
                'category_description' => 'Petugas pengumpul dan pembagi zakat di masjid',
                'family_status' => 'married',
                'family_members' => 2,
                'monthly_income' => 3000000.00,
                'income_source' => 'Gaji sebagai amil zakat',
                'verification_status' => 'verified',
                'verification_notes' => 'Bertugas sebagai amil zakat di Masjid Al-Ikhlas',
                'verified_at' => now(),
                'verified_by' => 1, // Admin
                'is_active' => true,
            ],
            [
                'name' => 'Sarah Muallaf',
                'nik' => '3171020404850004',
                'gender' => 'female',
                'address' => 'Jl. Dakwah No. 67',
                'city' => 'Jakarta Utara',
                'province' => 'DKI Jakarta',
                'phone' => '081298765434',
                'date_of_birth' => '1985-04-04',
                'category' => 'muallaf',
                'category_description' => 'Baru masuk Islam dan membutuhkan pembinaan',
                'family_status' => 'single',
                'family_members' => 1,
                'monthly_income' => 2500000.00,
                'income_source' => 'Karyawan swasta',
                'verification_status' => 'verified',
                'verification_notes' => 'Baru masuk Islam 6 bulan yang lalu',
                'verified_at' => now(),
                'verified_by' => 1, // Admin
                'is_active' => true,
            ],
            [
                'name' => 'Ahmad Gharim',
                'nik' => '3171020505900005',
                'gender' => 'male',
                'address' => 'Jl. Utang Piutang No. 89',
                'city' => 'Jakarta Pusat',
                'province' => 'DKI Jakarta',
                'phone' => '081298765435',
                'date_of_birth' => '1990-05-05',
                'category' => 'gharim',
                'category_description' => 'Memiliki utang untuk kebutuhan yang baik',
                'family_status' => 'married',
                'family_members' => 5,
                'monthly_income' => 4000000.00,
                'income_source' => 'Wiraswasta',
                'verification_status' => 'verified',
                'verification_notes' => 'Berutang untuk biaya pengobatan keluarga',
                'verified_at' => now(),
                'verified_by' => 1, // Admin
                'is_active' => true,
            ],
            [
                'name' => 'Fatimah Fisabilillah',
                'nik' => '3171020606820006',
                'gender' => 'female',
                'address' => 'Jl. Dakwah Islam No. 12',
                'city' => 'Jakarta Timur',
                'province' => 'DKI Jakarta',
                'phone' => '081298765436',
                'date_of_birth' => '1982-06-06',
                'category' => 'fisabilillah',
                'category_description' => 'Aktivis dakwah dan pendidikan Islam',
                'family_status' => 'married',
                'family_members' => 3,
                'monthly_income' => 2000000.00,
                'income_source' => 'Donasi untuk kegiatan dakwah',
                'verification_status' => 'pending',
                'verification_notes' => 'Sedang dalam proses verifikasi',
                'verified_at' => null,
                'verified_by' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Omar Ibnu Sabil',
                'nik' => '3171020707950007',
                'gender' => 'male',
                'address' => 'Jl. Musafir No. 34',
                'city' => 'Jakarta Barat',
                'province' => 'DKI Jakarta',
                'phone' => '081298765437',
                'date_of_birth' => '1995-07-07',
                'category' => 'ibnu_sabil',
                'category_description' => 'Mahasiswa perantauan yang kehabisan biaya',
                'family_status' => 'single',
                'family_members' => 1,
                'monthly_income' => 500000.00,
                'income_source' => 'Kiriman orangtua (tidak mencukupi)',
                'verification_status' => 'pending',
                'verification_notes' => 'Mahasiswa dari luar kota yang kesulitan biaya',
                'verified_at' => null,
                'verified_by' => null,
                'is_active' => true,
            ],
        ];

        foreach ($mustahikData as $data) {
            Mustahik::create($data);
        }
    }
}
