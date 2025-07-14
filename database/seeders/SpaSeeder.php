<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Spa;
use App\Models\SpaDetail;

class SpaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $spaData = [
            [
                'nama' => 'Royal Heritage Spa Yogyakarta',
                'alamat' => 'Jl. Prawirotaman II No.15, Mergangsan, Yogyakarta',
                'noHP' => '0274-373511',
                'image' => 'images/spa-royal-heritage.jpg',
                'maps' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.9473847384738!2d110.36588347503846!3d-7.796194392226735!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a578b54a66b31%3A0xf92a735bf5b0b5e8!2sPrawirotaman%2C%20Mergangsan%2C%20Kota%20Yogyakarta!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid',
                'waktuBuka' => [
                    'Senin' => '09:00 - 21:00',
                    'Selasa' => '09:00 - 21:00',
                    'Rabu' => '09:00 - 21:00',
                    'Kamis' => '09:00 - 21:00',
                    'Jumat' => '09:00 - 21:00',
                    'Sabtu' => '09:00 - 22:00',
                    'Minggu' => '09:00 - 22:00',
                ],
                'services' => [
                    'Traditional Javanese Massage',
                    'Royal Heritage Signature Treatment',
                    'Aromatherapy Massage',
                    'Hot Stone Therapy',
                    'Body Scrub & Wrap',
                    'Facial Treatment'
                ],
                'is_open' => true,
                'detail' => [
                    'about_spa' => 'Royal Heritage Spa menghadirkan pengalaman spa authentic dengan sentuhan budaya Jawa yang kental. Terletak di kawasan wisata Prawirotaman, spa ini menawarkan treatment tradisional dengan fasilitas modern.',
                    'facilities' => [
                        'Private Treatment Rooms',
                        'Couple Room',
                        'Steam Room',
                        'Relaxation Lounge',
                        'Traditional Joglo Architecture',
                        'Garden View',
                        'Herbal Tea Corner',
                        'Free Parking'
                    ],
                    'contact_person_name' => 'Mbak Sari Dewi',
                    'contact_person_phone' => '0274-373511',
                    'gallery_images' => [
                        'images/spa-royal-heritage.jpg',
                        'images/spa-treatment-room-1.jpg',
                        'images/spa-relaxation-1.jpg',
                        'images/spa-garden-1.jpg',
                        'images/spa-joglo-1.jpg'
                    ]
                ]
            ],
            [
                'nama' => 'Taman Sari Royal Heritage Spa',
                'alamat' => 'Jl. Taman, Kraton, Yogyakarta',
                'noHP' => '0274-373777',
                'image' => 'images/spa-taman-sari.jpg',
                'maps' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.8847384738473!2d110.36242707503845!3d-7.801682792222105!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a57f3aa4c8b3d%3A0x9c8b5f3a3e9d8e7c!2sTaman%20Sari!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid',
                'waktuBuka' => [
                    'Senin' => '10:00 - 20:00',
                    'Selasa' => '10:00 - 20:00',
                    'Rabu' => '10:00 - 20:00',
                    'Kamis' => '10:00 - 20:00',
                    'Jumat' => '10:00 - 20:00',
                    'Sabtu' => '09:00 - 21:00',
                    'Minggu' => '09:00 - 21:00',
                ],
                'services' => [
                    'Royal Javanese Massage',
                    'Princess Treatment Package',
                    'Detox Body Wrap',
                    'Anti-Aging Facial',
                    'Reflexology',
                    'Meditation Session'
                ],
                'is_open' => true,
                'detail' => [
                    'about_spa' => 'Berlokasi dekat dengan situs bersejarah Taman Sari, spa ini menggabungkan kemewahan treatment modern dengan nilai-nilai spiritual dan budaya Keraton Yogyakarta.',
                    'facilities' => [
                        'Royal Treatment Suites',
                        'Historical Ambiance',
                        'Meditation Garden',
                        'Herbal Preparation Room',
                        'VIP Lounge',
                        'Cultural Art Gallery',
                        'Traditional Music',
                        'Valet Parking'
                    ],
                    'contact_person_name' => 'Mas Bagyo Sutrisno',
                    'contact_person_phone' => '0274-373777',
                    'gallery_images' => [
                        'images/spa-taman-sari.jpg',
                        'images/spa-royal-suite.jpg',
                        'images/spa-meditation.jpg',
                        'images/spa-cultural.jpg',
                        'images/spa-herbal.jpg'
                    ]
                ]
            ],
            [
                'nama' => 'Natura Spa & Wellness',
                'alamat' => 'Jl. Kaliurang Km 8.5, Sleman, Yogyakarta',
                'noHP' => '0274-898123',
                'image' => 'images/spa-natura.jpg',
                'maps' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.5847384738473!2d110.39472607503795!3d-7.764839692261485!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59f7bb5a8c9d%3A0x8f4e5d6a7b8c9e0f!2sKaliurang%20Yogyakarta!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid',
                'waktuBuka' => [
                    'Senin' => '08:00 - 20:00',
                    'Selasa' => '08:00 - 20:00',
                    'Rabu' => '08:00 - 20:00',
                    'Kamis' => '08:00 - 20:00',
                    'Jumat' => '08:00 - 20:00',
                    'Sabtu' => '08:00 - 21:00',
                    'Minggu' => '08:00 - 21:00',
                ],
                'services' => [
                    'Nature Therapy Massage',
                    'Organic Facial Treatment',
                    'Volcanic Stone Massage',
                    'Herbal Steam Bath',
                    'Forest Aromatherapy',
                    'Outdoor Yoga Session'
                ],
                'is_open' => true,
                'detail' => [
                    'about_spa' => 'Natura Spa & Wellness menawarkan konsep spa alami dengan udara sejuk pegunungan Kaliurang. Menggunakan bahan-bahan organik dan treatment yang ramah lingkungan.',
                    'facilities' => [
                        'Outdoor Treatment Pavilion',
                        'Natural Hot Spring',
                        'Organic Garden',
                        'Forest Walking Path',
                        'Eco-Friendly Facilities',
                        'Healthy Organic Restaurant',
                        'Mountain View Lounge',
                        'Free WiFi & Parking'
                    ],
                    'contact_person_name' => 'Ibu Ratna Sari',
                    'contact_person_phone' => '0274-898123',
                    'gallery_images' => [
                        'images/spa-natura.jpg',
                        'images/spa-outdoor.jpg',
                        'images/spa-mountain.jpg',
                        'images/spa-organic.jpg',
                        'images/spa-nature.jpg'
                    ]
                ]
            ],
            [
                'nama' => 'Amandari Spa Yogyakarta',
                'alamat' => 'Jl. Solo Km 9, Kalasan, Sleman, Yogyakarta',
                'noHP' => '0274-497888',
                'image' => 'images/spa-amandari.jpg',
                'maps' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1d3953.1847384738473!2d110.44472607503795!3d-7.784839692241485!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59b7cb5a8c9d%3A0x9f5e6d7a8b9c0e1f!2sKalasan%20Yogyakarta!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid',
                'waktuBuka' => [
                    'Senin' => '09:00 - 21:00',
                    'Selasa' => '09:00 - 21:00',
                    'Rabu' => '09:00 - 21:00',
                    'Kamis' => '09:00 - 21:00',
                    'Jumat' => '09:00 - 21:00',
                    'Sabtu' => '09:00 - 22:00',
                    'Minggu' => '09:00 - 22:00',
                ],
                'services' => [
                    'Balinese Signature Massage',
                    'Luxury Spa Package',
                    'Couple Spa Experience',
                    'Prenatal Massage',
                    'Deep Tissue Therapy',
                    'Holistic Healing'
                ],
                'is_open' => true,
                'detail' => [
                    'about_spa' => 'Amandari Spa menghadirkan luxury spa experience dengan sentuhan Balinese architecture dan Javanese hospitality. Menawarkan treatment premium dengan terapis berpengalaman.',
                    'facilities' => [
                        'Luxury Private Villas',
                        'Balinese Architecture',
                        'Infinity Pool',
                        'Lotus Garden',
                        'Premium Lounge',
                        'Fine Dining Restaurant',
                        'Butler Service',
                        'Helicopter Landing Pad'
                    ],
                    'contact_person_name' => 'Mr. Wayan Sutrisna',
                    'contact_person_phone' => '0274-497888',
                    'gallery_images' => [
                        'images/spa-amandari.jpg',
                        'images/spa-luxury.jpg',
                        'images/spa-villa.jpg',
                        'images/spa-pool.jpg',
                        'images/spa-balinese.jpg'
                    ]
                ]
            ],
            [
                'nama' => 'Phoenix Spa Traditional',
                'alamat' => 'Jl. Jenderal Sudirman No.89, Yogyakarta',
                'noHP' => '0274-561999',
                'image' => 'images/spa-phoenix.jpg',
                'maps' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.7847384738473!2d110.37472607503845!3d-7.805682792216105!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a578f4a4c8b3d%3A0x8c9b6f4a4e8d9e7c!2sJl.%20Jenderal%20Sudirman%2C%20Yogyakarta!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid',
                'waktuBuka' => [
                    'Senin' => '10:00 - 22:00',
                    'Selasa' => '10:00 - 22:00',
                    'Rabu' => '10:00 - 22:00',
                    'Kamis' => '10:00 - 22:00',
                    'Jumat' => '10:00 - 22:00',
                    'Sabtu' => '09:00 - 23:00',
                    'Minggu' => '09:00 - 23:00',
                ],
                'services' => [
                    'Traditional Jamu Treatment',
                    'Royal Lulur Body Scrub',
                    'Javanese Wedding Spa',
                    'Acupuncture Therapy',
                    'Cupping Treatment',
                    'Traditional Herbal Medicine'
                ],
                'is_open' => true,
                'detail' => [
                    'about_spa' => 'Phoenix Spa Traditional adalah spa yang mempertahankan keaslian treatment tradisional Jawa dengan menggunakan ramuan jamu warisan nenek moyang dan teknik penyembuhan kuno.',
                    'facilities' => [
                        'Traditional Treatment Rooms',
                        'Jamu Preparation Kitchen',
                        'Herbal Garden',
                        'Traditional Batik Decor',
                        'Pendopo Meeting Area',
                        'Cultural Performance Space',
                        'Traditional Medicine Shop',
                        'Cultural Tour Guidance'
                    ],
                    'contact_person_name' => 'Nyai Supadmi',
                    'contact_person_phone' => '0274-561999',
                    'gallery_images' => [
                        'images/spa-phoenix.jpg',
                        'images/spa-traditional.jpg',
                        'images/spa-jamu.jpg',
                        'images/spa-batik.jpg',
                        'images/spa-pendopo.jpg'
                    ]
                ]
            ]
        ];

        foreach ($spaData as $data) {
            $detailData = $data['detail'];
            unset($data['detail']);

            $spa = Spa::create($data);

            // Create spa detail
            SpaDetail::create([
                'spa_id' => $spa->id_spa,
                'about_spa' => $detailData['about_spa'],
                'facilities' => $detailData['facilities'],
                'contact_person_name' => $detailData['contact_person_name'],
                'contact_person_phone' => $detailData['contact_person_phone'],
                'gallery_images' => $detailData['gallery_images']
            ]);
        }
    }
}
