<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Testimonial;
use App\Models\Spa;
use App\Models\Gym;
use App\Models\Yoga;
use App\Models\SpaBooking;
use App\Models\GymBooking;
use App\Models\YogaBooking;
use App\Models\User;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first spa, gym, and yoga for testimonials
        $spa = Spa::first();
        $gym = Gym::first();
        $yoga = Yoga::first();

        // Get users (or use existing user IDs)
        $user1 = User::find(1);
        $user2 = User::find(2);

        // Sample testimonials for Spa with completed bookings
        if ($spa && $user1) {
            // Create completed spa booking
            $spaBooking1 = SpaBooking::create([
                'booking_code' => 'SPA' . strtoupper(uniqid()),
                'spa_id' => $spa->id_spa,
                'customer_name' => $user1->name,
                'customer_email' => $user1->email,
                'customer_phone' => '081234567890',
                'service_id' => 1,
                'service_name' => 'Hot Stone Massage',
                'service_price' => 450000,
                'total_amount' => 450000,
                'status' => 'completed',
                'payment_status' => 'paid',
                'booking_date' => now()->subWeeks(2),
                'booking_time' => now()->subWeeks(2)->setTime(14, 0),
                'therapist_preference' => 'Female',
                'notes' => 'Please use lavender aromatherapy',
            ]);

            Testimonial::create([
                'user_id' => $user1->id,
                'booking_id' => $spaBooking1->id,
                'booking_type' => 'spa',
                'service_completed' => true,
                'testimonial_type' => 'spa',
                'testimonial_id' => $spa->id_spa,
                'name' => $user1->name,
                'rating' => 5,
                'comment' => 'Amazing spa experience! The therapists were incredibly professional and the ambiance was so relaxing. I left feeling completely rejuvenated.',
                'service' => 'Hot Stone Massage',
                'is_approved' => true,
                'created_at' => now()->subWeeks(2)->addDays(1),
            ]);

            // Create another spa booking
            $spaBooking2 = SpaBooking::create([
                'booking_code' => 'SPA' . strtoupper(uniqid()),
                'spa_id' => $spa->id_spa,
                'customer_name' => 'Michael Chen',
                'customer_email' => 'michael.chen@example.com',
                'customer_phone' => '081234567891',
                'service_id' => 2,
                'service_name' => 'Aromatherapy Massage',
                'service_price' => 400000,
                'total_amount' => 400000,
                'status' => 'completed',
                'payment_status' => 'paid',
                'booking_date' => now()->subMonth(),
                'booking_time' => now()->subMonth()->setTime(15, 30),
                'therapist_preference' => 'Any',
                'notes' => null,
            ]);

            Testimonial::create([
                'user_id' => null,
                'booking_id' => $spaBooking2->id,
                'booking_type' => 'spa',
                'service_completed' => true,
                'testimonial_type' => 'spa',
                'testimonial_id' => $spa->id_spa,
                'name' => 'Michael Chen',
                'rating' => 5,
                'comment' => 'Best spa in town! The facilities are top-notch and the staff is very attentive. Highly recommend their aromatherapy treatment.',
                'service' => 'Aromatherapy Massage',
                'is_approved' => true,
                'created_at' => now()->subMonth()->addDays(2),
            ]);

            $spaBooking3 = SpaBooking::create([
                'booking_code' => 'SPA' . strtoupper(uniqid()),
                'spa_id' => $spa->id_spa,
                'customer_name' => 'Emily Rodriguez',
                'customer_email' => 'emily.rodriguez@example.com',
                'customer_phone' => '081234567892',
                'service_id' => 3,
                'service_name' => 'Deep Tissue Massage',
                'service_price' => 500000,
                'total_amount' => 500000,
                'status' => 'completed',
                'payment_status' => 'paid',
                'booking_date' => now()->subWeeks(3),
                'booking_time' => now()->subWeeks(3)->setTime(10, 0),
                'therapist_preference' => 'Male',
                'notes' => 'Focus on lower back',
            ]);

            Testimonial::create([
                'user_id' => null,
                'booking_id' => $spaBooking3->id,
                'booking_type' => 'spa',
                'service_completed' => true,
                'testimonial_type' => 'spa',
                'testimonial_id' => $spa->id_spa,
                'name' => 'Emily Rodriguez',
                'rating' => 4,
                'comment' => 'Great service and very clean environment. The massage was excellent and really helped with my back pain.',
                'service' => 'Deep Tissue Massage',
                'is_approved' => true,
                'created_at' => now()->subWeeks(3)->addDays(1),
            ]);
        }

        // Sample testimonials for Gym with completed bookings
        if ($gym && $user2) {
            // Create completed gym booking
            $gymBooking1 = GymBooking::create([
                'booking_code' => 'GYM' . strtoupper(uniqid()),
                'gym_id' => $gym->id_gym,
                'customer_name' => $user2->name,
                'customer_email' => $user2->email,
                'customer_phone' => '081234567893',
                'service_id' => 1,
                'service_name' => 'Personal Training',
                'service_price' => 350000,
                'total_amount' => 350000,
                'status' => 'completed',
                'payment_status' => 'paid',
                'booking_date' => now()->subWeeks(1),
                'notes' => 'Focus on strength training',
            ]);

            Testimonial::create([
                'user_id' => $user2->id,
                'booking_id' => $gymBooking1->id,
                'booking_type' => 'gym',
                'service_completed' => true,
                'testimonial_type' => 'gym',
                'testimonial_id' => $gym->id_gym,
                'name' => $user2->name,
                'rating' => 5,
                'comment' => 'Excellent gym with modern equipment! The trainers are knowledgeable and supportive. Great atmosphere for working out.',
                'service' => 'Personal Training',
                'is_approved' => true,
                'created_at' => now()->subWeeks(1)->addDays(2),
            ]);

            $gymBooking2 = GymBooking::create([
                'booking_code' => 'GYM' . strtoupper(uniqid()),
                'gym_id' => $gym->id_gym,
                'customer_name' => 'Jessica Lee',
                'customer_email' => 'jessica.lee@example.com',
                'customer_phone' => '081234567894',
                'service_id' => 2,
                'service_name' => 'Group Classes',
                'service_price' => 250000,
                'total_amount' => 250000,
                'status' => 'completed',
                'payment_status' => 'paid',
                'booking_date' => now()->subWeeks(2),
                'notes' => null,
            ]);

            Testimonial::create([
                'user_id' => null,
                'booking_id' => $gymBooking2->id,
                'booking_type' => 'gym',
                'service_completed' => true,
                'testimonial_type' => 'gym',
                'testimonial_id' => $gym->id_gym,
                'name' => 'Jessica Lee',
                'rating' => 5,
                'comment' => 'Love this gym! Clean facilities, friendly staff, and great variety of equipment. The group classes are amazing!',
                'service' => 'Group Classes',
                'is_approved' => true,
                'created_at' => now()->subWeeks(2)->addDays(1),
            ]);
        }

        // Sample testimonials for Yoga with completed bookings
        if ($yoga && $user1) {
            $yogaBooking1 = YogaBooking::create([
                'booking_code' => 'YOGA' . strtoupper(uniqid()),
                'yoga_id' => $yoga->id_yoga,
                'customer_name' => 'Amanda Wilson',
                'customer_email' => 'amanda.wilson@example.com',
                'customer_phone' => '081234567895',
                'service_id' => 1,
                'service_name' => 'Hatha Yoga',
                'service_price' => 200000,
                'total_amount' => 200000,
                'status' => 'completed',
                'payment_status' => 'paid',
                'booking_date' => now()->subDays(10),
                'booking_time' => now()->subDays(10)->setTime(8, 0),
                'class_type' => 'Group',
                'notes' => 'Beginner level',
            ]);

            Testimonial::create([
                'user_id' => null,
                'booking_id' => $yogaBooking1->id,
                'booking_type' => 'yoga',
                'service_completed' => true,
                'testimonial_type' => 'yoga',
                'testimonial_id' => $yoga->id_yoga,
                'name' => 'Amanda Wilson',
                'rating' => 5,
                'comment' => 'The yoga classes here are transformative! The instructor is incredibly skilled and creates a peaceful, welcoming environment.',
                'service' => 'Hatha Yoga',
                'is_approved' => true,
                'created_at' => now()->subDays(9),
            ]);

            $yogaBooking2 = YogaBooking::create([
                'booking_code' => 'YOGA' . strtoupper(uniqid()),
                'yoga_id' => $yoga->id_yoga,
                'customer_name' => 'Robert Brown',
                'customer_email' => 'robert.brown@example.com',
                'customer_phone' => '081234567896',
                'service_id' => 2,
                'service_name' => 'Vinyasa Flow',
                'service_price' => 250000,
                'total_amount' => 250000,
                'status' => 'completed',
                'payment_status' => 'paid',
                'booking_date' => now()->subWeeks(3),
                'booking_time' => now()->subWeeks(3)->setTime(18, 0),
                'class_type' => 'Private',
                'notes' => 'Intermediate level',
            ]);

            Testimonial::create([
                'user_id' => null,
                'booking_id' => $yogaBooking2->id,
                'booking_type' => 'yoga',
                'service_completed' => true,
                'testimonial_type' => 'yoga',
                'testimonial_id' => $yoga->id_yoga,
                'name' => 'Robert Brown',
                'rating' => 4,
                'comment' => 'Great yoga studio with a calm atmosphere. The classes are well-structured and suitable for all levels.',
                'service' => 'Vinyasa Flow',
                'is_approved' => true,
                'created_at' => now()->subWeeks(3)->addDays(2),
            ]);
        }

        $this->command->info('Sample testimonials with completed bookings created successfully!');
    }
}
