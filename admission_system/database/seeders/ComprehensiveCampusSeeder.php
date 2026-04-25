<?php

namespace Database\Seeders;

use App\Models\Campus;
use App\Models\College;
use App\Models\Course;
use Illuminate\Database\Seeder;

class ComprehensiveCampusSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Goa Campus' => [
                'College of Business and Management' => [
                    'Bachelor of Science in Accountancy (BSA)',
                    'Bachelor of Science in Business Administration major in Financial Management (BSBA-FM)',
                    'Bachelor of Science in Economics',
                    'Bachelor of Science in Entrepreneurship (BSEnt)',
                    'Bachelor of Science in Office Administration (BSOA)',
                ],
                'College of Education' => [
                    'Master of Arts in Education (MAED) major in English, Mathematics, Science, Instructional Management',
                    'Bachelor of Secondary Education (BSED) major in English, Filipino, Mathematics, Science, Social Studies, Values Education',
                    'Bachelor of Secondary Education (BSED) Major in English',
                    'Bachelor of Secondary Education (BSED) Major in Filipino',
                    'Bachelor of Secondary Education (BSED) Major in Mathematics',
                    'Bachelor of Secondary Education (BSED) Major in Science',
                    'Bachelor of Secondary Education (BSED) Major in Social Studies',
                    'Bachelor of Secondary Education (BSED) Major in Values Education',
                    'Bachelor of Elementary Education (BEED) major in General Education',
                ],
                'College of Science' => [
                    'Bachelor of Science in Biology (BSBio)',
                    'Bachelor of Science in Geology (BSGeo)',
                ],
                'College of Arts and Humanities' => [
                    'BA in Communication',
                ],
                'College of Engineering and Computational Sciences' => [
                    'Bachelor of Science in Civil Engineering (BSCE)',
                    'Bachelor of Science in Sanitary Engineering (BSSE)',
                    'Bachelor of Science in Computer Science (BSCS)',
                    'Bachelor of Science in Mathematics (BS Math)',
                    'Bachelor of Science in Information Technology (BSIT)',
                    'Bachelor of Automotive Technology (BAT)',
                    'Bachelor of Engineering Technology major in Electrical Engineering Technology (BET-EET)',
                    'Bachelor of Engineering Technology in Mechanical Engineering Technology major in Automotive Technology (BET-MET Auto)',
                    'Bachelor of Engineering Technology in Mechanical Engineering Technology major in Refrigeration and Airconditioning Technology (BET-MET RAC)',
                ],
            ],
            'Salogon Campus' => [
                'College of Agribusiness and Community Development' => [
                    'Bachelor of Science in Agribusiness (BSAB)',
                    'Bachelor of Science in Community Development (BSCD)',
                ],
            ],
            'Tinambac Campus' => [
                'COLLEGE OF ENVIRONMENTAL SCIENCE AND DESIGN' => [
                    'BS ENVIRONMENTAL SCIENCE',
                    'BS ENVIRONMENTAL PLANNING',
                    'BS FORESTRY',
                ],
            ],
            'Caramoan Campus' => [
                'COLLEGE OF SUSTAINABLE COMMUNITIES AND ECOSYSTEM' => [
                    'BS BIOLOGY, major in CONSERVATION AND RESTORATION ECOLOGY',
                    'BS TOURISM MANAGEMENT major in ECOTOURISM',
                ],
            ],
            'Sagñay Campus' => [
                'COLLEGE OF FISHERIES AND MARINE SCIENCE' => [
                    'BS FISHERIES',
                    'BS MARINE BIOLOGY',
                ],
            ],
            'San Jose Campus' => [
                'Hospitality Management' => [
                    'BS Hospitality Management',
                    'BS Tourism',
                ],
            ],
            'Lagonoy Campus' => [
                'Criminology' => [
                    'BS Criminology',
                ],
                'Nutrition and Dietetics' => [
                    'BS Nutrition and Dietetics',
                ],
            ],
        ];

        foreach ($data as $campusName => $colleges) {
            $campus = Campus::create(['name' => $campusName]);
            foreach ($colleges as $collegeName => $courses) {
                $college = $campus->colleges()->create(['name' => $collegeName]);
                foreach ($courses as $courseName) {
                    $college->courses()->create(['name' => $courseName]);
                }
            }
        }
    }
}
