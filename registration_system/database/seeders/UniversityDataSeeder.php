<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use Illuminate\Support\Facades\DB;

class UniversityDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing courses
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Course::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            'Salogon Campus' => [
                'College of Agribusiness and Community Development' => [
                    ['Bachelor of Science in Agribusiness', 'BSAB'],
                    ['Bachelor of Science in Community Development', 'BSCD'],
                ],
            ],
            'Tinambac Campus' => [
                'COLLEGE OF ENVIRONMENTAL SCIENCE AND DESIGN' => [
                    ['BS ENVIRONMENTAL SCIENCE', 'BSES'],
                    ['BS ENVIRONMENTAL PLANNING', 'BSEP'],
                    ['BS FORESTRY', 'BSF'],
                ],
            ],
            'Caramoan Campus' => [
                'COLLEGE OF SUSTAINABLE COMMUNITIES AND ECOSYSTEM' => [
                    ['BS BIOLOGY, major in CONSERVATION AND RESTORATION ECOLOGY', 'BSBio-CRE'],
                    ['BS TOURISM MANAGEMENT major in ECOTOURISM', 'BSTM-EcoTheme'],
                ],
            ],
            'Sagñay Campus' => [
                'COLLEGE OF FISHERIES AND MARINE SCIENCE' => [
                    ['BS FISHERIES', 'BSFi'],
                    ['BS MARINE BIOLOGY', 'BSMB'],
                ],
            ],
            'Goa Campus' => [
                'College of Business and Management' => [
                    ['Bachelor of Science in Accountancy', 'BSA'],
                    ['Bachelor of Science in Business Administration major in Financial Management', 'BSBA-FM'],
                    ['Bachelor of Science in Economics', 'BSEcon'],
                    ['Bachelor of Science in Entrepreneurship', 'BSEnt'],
                    ['Bachelor of Science in Office Administration', 'BSOA'],
                ],
                'College of Education' => [
                    ['Master of Arts in Education major in English, Mathematics, Science, Instructional Management', 'MAED'],
                    ['Bachelor of Secondary Education major in English, Filipino, Mathematics, Science, Social Studies, Values Education', 'BSED-Gen'],
                    ['Bachelor of Secondary Education Major in English', 'BSED-Eng'],
                    ['Bachelor of Secondary Education Major in Filipino', 'BSED-Fil'],
                    ['Bachelor of Secondary Education Major in Mathematics', 'BSED-Math'],
                    ['Bachelor of Secondary Education Major in Science', 'BSED-Sci'],
                    ['Bachelor of Secondary Education Major in Social Studies', 'BSED-SS'],
                    ['Bachelor of Secondary Education Major in Values Education', 'BSED-Val'],
                    ['Bachelor of Elementary Education major in General Education', 'BEED'],
                ],
                'College of Science' => [
                    ['Bachelor of Science in Biology', 'BSBio'],
                    ['Bachelor of Science in Geology', 'BSGeo'],
                ],
                'College of Arts and Humanities' => [
                    ['BA in Communication', 'BAComm'],
                ],
                'College of Engineering and Computational Sciences' => [
                    ['Bachelor of Science in Civil Engineering', 'BSCE'],
                    ['Bachelor of Science in Sanitary Engineering', 'BSSE'],
                    ['Bachelor of Science in Computer Science', 'BSCS'],
                    ['Bachelor of Science in Mathematics', 'BS Math'],
                    ['Bachelor of Science in Information Technology', 'BSIT'],
                    ['Bachelor of Automotive Technology', 'BAT'],
                    ['Bachelor of Engineering Technology major in Electrical Engineering Technology', 'BET-EET'],
                    ['Bachelor of Engineering Technology in Mechanical Engineering Technology major in Automotive Technology', 'BET-MET Auto'],
                    ['Bachelor of Engineering Technology in Mechanical Engineering Technology major in Refrigeration and Airconditioning Technology', 'BET-MET RAC'],
                ],
            ],
        ];

        foreach ($data as $campus => $colleges) {
            foreach ($colleges as $college => $courses) {
                foreach ($courses as $course) {
                    Course::create([
                        'campus' => $campus,
                        'department' => $college,
                        'course_name' => $course[0],
                        'course_code' => $course[1],
                        'description' => $course[0] . ' program at ' . $campus,
                    ]);
                }
            }
        }
    }
}
