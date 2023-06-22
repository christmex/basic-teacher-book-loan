<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::create([
            'name' => 'Super Admin',
            'email' => 'super@admin.com',
            'password' => bcrypt('mantapjiwa00')
        ]);

        // \App\Models\Member::create([
        //     'member_name' => 'Super Admin',
        // ]);
        // $members = [['member_name' => 'Markus Gunawan Dachi'],
        // ['member_name' => 'Liyanti'],
        // ['member_name' => 'Alexsander Saputra Naibaho S.Pd'],
        // ['member_name' => 'Arman Waruwu, S.PdK'],
        // ['member_name' => 'Budi Prastiyo, S.Pd'],
        // ['member_name' => 'Christina Silaban, S.Pd'],
        // ['member_name' => 'Ckristina'],
        // ['member_name' => 'Eduard Lumbangaol, A.Md'],
        // ['member_name' => 'Erna Mianti Purba, S.Pd'],
        // ['member_name' => 'Erna Yudi Ariani, S.Sn'],
        // ['member_name' => 'Hanna Meilinda Rodearni Sinaga, S.S'],
        // ['member_name' => 'Herlina Hutagaol, S.Pd'],
        // ['member_name' => 'Karolina N Silitonga S.S, S.Pd'],
        // ['member_name' => 'Mardi, S.Pd.K'],
        // ['member_name' => 'Margaretha Mangalik, S.Si'],
        // ['member_name' => 'Masryana Ikawany Girsang, A.Md'],
        // ['member_name' => 'Melyana, S.Pd'],
        // ['member_name' => 'Merry Linda Boru Sirait, S.Sos'],
        // ['member_name' => 'Nani Paskah Silalahi, S.Pd'],
        // ['member_name' => 'Parlan Hutagaol, S.T'],
        // ['member_name' => 'Paul Fernando Silitonga'],
        // ['member_name' => 'Pinta Nerlida Rajagukguk, S.Pd M.M'],
        // ['member_name' => 'Ranto Siagian, S.Pd'],
        // ['member_name' => 'Rinte Gultom, S.Pd'],
        // ['member_name' => 'Roida Manullang, S.Pd'],
        // ['member_name' => 'Roida Niety Sinaga, S.Pd'],
        // ['member_name' => 'Rotua Eva Rita Nainggolan, S.Pd'],
        // ['member_name' => 'Ruth Veronika Siagian, S.Pd'],
        // ['member_name' => 'Safrika Arianta Sipayung, S.Pd'],
        // ['member_name' => 'Salimuddin, S.Pd'],
        // ['member_name' => 'Suryatni'],
        // ['member_name' => 'Yanny Debora Panjaitan, S.Pd'],
        // ['member_name' => 'Yanthi Verawati Sinaga, S.Pd'],
        // ['member_name' => 'Yenny'],
        // ['member_name' => 'Yulia Ermalita Siregar, ST'],
        // ['member_name' => 'Arjuna Putra Tampubolon, S.Pd'],
        // ['member_name' => 'Bambang Triono, SE'],
        // ['member_name' => 'Dahlia M. Hutajulu, S.Pd'],
        // ['member_name' => 'Demita Ayu Yosefin Naingolan, S.Pd'],
        // ['member_name' => 'Dodo Pranatalia Situmorang, S.Pd'],
        // ['member_name' => 'Elisabet Hotma Tua Sitorus, S.Pd'],
        // ['member_name' => 'Hotnida Sianturi, S.Pd'],
        // ['member_name' => 'Ineng Wahyuni, S.Psi'],
        // ['member_name' => 'Jumala Manalu, S.Pd'],
        // ['member_name' => 'Rani A.L Banjarnahor,S.Si'],
        // ['member_name' => 'Setia Ferry, S.Pd'],
        // ['member_name' => 'Sofian Sianturi, S.Kom'],
        // ['member_name' => 'Estinar Silitonga, S.Pd'],
        // ['member_name' => 'Gusti Arma Marpaung, S.Pd'],
        // ['member_name' => 'Jojor Leonora Basaina Siahaan, S.E'],
        // ['member_name' => 'Martina Pasaribu, S.Pd'],
        // ['member_name' => 'Maya Armelia Tumorang, S.Pd'],
        // ['member_name' => 'Pendiman Simbolon, S.Sos'],
        // ['member_name' => 'Sry Fanny Sidabutar, S.Pd'],
        // ['member_name' => 'Verawaty Titik Sari Sihombing, S. Pd'],
        // ['member_name' => 'Heryanto Bone Diktus, S.Si'],
        // ['member_name' => 'Hudirman Halawa, S.Th']];
        // DB::table('members')->insert($members);

        $semesters = [
            ['semester_name' => 'First Semester'],
            ['semester_name' => 'Second Semester']
        ];
        DB::table('semesters')->insert($semesters);

        $school_years = [
            ['school_year_name' => '2023/2024'],
            ['school_year_name' => '2024/2025']
        ];
        DB::table('school_years')->insert($school_years);
    }
}
