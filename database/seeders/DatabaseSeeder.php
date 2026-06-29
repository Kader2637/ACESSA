<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\StudentClassroomRelation;
use App\Models\Semester;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Teacher
        $teacher = User::create([
            'image' => 'user.png',
            'name' => 'Abdul Kader',
            'email' => 'kader@gmail.com',
            'password' => '12345678',
            'role' => 'teacher',
            'no_telephone' => '081234567890',
            'gender' => 'male',
            'status' => 'accept'
        ]);

        // 2. Create Students
        $student1 = User::create([
            'image' => 'user.png',
            'name' => 'Icha',
            'email' => 'icha@gmail.com',
            'password' => '12345678',
            'role' => 'student',
            'no_telephone' => '081234567891',
            'gender' => 'female',
            'status' => 'accept'
        ]);

        $student2 = User::create([
            'image' => 'user.png',
            'name' => 'Dimas',
            'email' => 'dimas@gmail.com',
            'password' => '12345678',
            'role' => 'student',
            'no_telephone' => '081234567892',
            'gender' => 'male',
            'status' => 'accept'
        ]);

        $student3 = User::create([
            'image' => 'user.png',
            'name' => 'Rio',
            'email' => 'rio@gmail.com',
            'password' => '12345678',
            'role' => 'student',
            'no_telephone' => '081234567893',
            'gender' => 'male',
            'status' => 'accept'
        ]);

        // 3. Create Admin
        User::create([
            'image' => 'user.png',
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => '12345678',
            'role' => 'admin',
            'no_telephone' => '081234567899',
            'gender' => 'male',
            'status' => 'accept'
        ]);

        // Fetch active semester (seeded in migration)
        $semester = Semester::where('is_active', true)->first() ?? Semester::first();

        // 4. Create Classrooms
        $classroom1 = Classroom::create([
            'name' => 'Pemrograman Aplikasi Web',
            'codeClass' => 'PAW2026',
            'limit' => 50,
            'total_user' => 3,
            'description' => 'Mata kuliah ini membahas mengenai dasar pemrograman web menggunakan HTML, CSS, JavaScript, PHP, dan Framework Laravel.',
            'thumbnail' => 'default.png',
            'status' => 'accept',
            'statusClass' => 'public',
            'user_id' => $teacher->id,
            'semester_id' => $semester ? $semester->id : null,
            'sks' => 3
        ]);

        $classroom2 = Classroom::create([
            'name' => 'Interaksi Manusia dan Komputer',
            'codeClass' => 'IMK2026',
            'limit' => 40,
            'total_user' => 2,
            'description' => 'Mempelajari dasar perancangan antarmuka pengguna (UI/UX) dan prinsip-prinsip kegunaan (usability).',
            'thumbnail' => 'default.png',
            'status' => 'accept',
            'statusClass' => 'public',
            'user_id' => $teacher->id,
            'semester_id' => $semester ? $semester->id : null,
            'sks' => 3
        ]);

        // 5. Enroll Students in Classroom 1
        StudentClassroomRelation::create([
            'user_id' => $student1->id,
            'classroom_id' => $classroom1->id,
            'status' => 'accept'
        ]);

        StudentClassroomRelation::create([
            'user_id' => $student2->id,
            'classroom_id' => $classroom1->id,
            'status' => 'accept'
        ]);

        StudentClassroomRelation::create([
            'user_id' => $student3->id,
            'classroom_id' => $classroom1->id,
            'status' => 'accept'
        ]);

        // Enroll Students in Classroom 2
        StudentClassroomRelation::create([
            'user_id' => $student1->id,
            'classroom_id' => $classroom2->id,
            'status' => 'accept'
        ]);

        StudentClassroomRelation::create([
            'user_id' => $student2->id,
            'classroom_id' => $classroom2->id,
            'status' => 'accept'
        ]);

        // 6. Create Courses (Materials) for Classroom 1
        Course::create([
            'classroom_id' => $classroom1->id,
            'name' => 'Pertemuan 1: Dasar HTML & CSS',
            'description' => 'Materi pengenalan tag-tag HTML dasar dan teknik pewarnaan layout menggunakan CSS.',
            'type' => 'text_course',
            'text_course' => "HTML (HyperText Markup Language) adalah bahasa standar untuk membuat halaman web. Sedangkan CSS (Cascading Style Sheets) digunakan untuk mendesain gaya tampilan dari dokumen HTML tersebut.\n\nDalam materi ini, kita akan mempelajari tag-tag pembentuk struktur seperti heading, paragraph, image, link, serta pemakaian selector warna dan font pada CSS.",
            'link' => null,
            'document' => null
        ]);

        Course::create([
            'classroom_id' => $classroom1->id,
            'name' => 'Pertemuan 2: Pemrograman JavaScript',
            'description' => 'Materi dasar interaktivitas halaman web menggunakan JavaScript.',
            'type' => 'text_course',
            'text_course' => "JavaScript adalah bahasa pemrograman dinamis yang berjalan di sisi klien (browser) untuk memberikan efek interaktif. \n\nDi sesi ini kita membahas konsep dasar variabel, percabangan (if-else), perulangan (loops), fungsi, serta manipulasi DOM sederhana.",
            'link' => null,
            'document' => null
        ]);

        Course::create([
            'classroom_id' => $classroom1->id,
            'name' => 'Pertemuan 3: Framework Laravel',
            'description' => 'Pengenalan struktur folder Laravel dan konsep dasar MVC.',
            'type' => 'text_course',
            'text_course' => "Laravel adalah salah satu framework PHP terpopuler yang mengadopsi konsep MVC (Model-View-Controller). \n\nPada modul ini kita akan belajar dasar penulisan routing, pembuatan controller, passing data ke blade view, serta konfigurasi database (.env).",
            'link' => null,
            'document' => null
        ]);

        Course::create([
            'classroom_id' => $classroom1->id,
            'name' => 'Pertemuan 4: Virtual Class ACESSA',
            'description' => 'Panduan mengikuti sesi virtual class menggunakan Zoom SDK.',
            'type' => 'text_course',
            'text_course' => "ACESSA mengintegrasikan Zoom Meeting SDK ke dalam platform. Sesi ini diperuntukkan untuk perkuliahan tatap muka secara online. \n\nPastikan mikrofon dan kamera Anda berfungsi dengan baik saat memasuki kelas.",
            'link' => null,
            'document' => null
        ]);

        // Courses (Materials) for Classroom 2
        Course::create([
            'classroom_id' => $classroom2->id,
            'name' => 'Pertemuan 1: Pengenalan UI/UX',
            'description' => 'Dasar perancangan antarmuka digital yang fungsional dan ramah pengguna.',
            'type' => 'text_course',
            'text_course' => "UI (User Interface) berfokus pada visual keindahan produk, sedangkan UX (User Experience) berfokus pada kemudahan alur interaksi pengguna saat memakai produk digital.",
            'link' => null,
            'document' => null
        ]);
    }
}
