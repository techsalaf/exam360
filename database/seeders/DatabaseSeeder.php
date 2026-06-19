<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Roles & Permissions (Must run first for Spatie)
        $this->call(RolesAndPermissionsSeeder::class);

        // 2. Users (Includes Admin & Random Students)
        $this->call(UserSeeder::class);

        // 3. Core Data
        $this->call(CategorySeeder::class);
        $this->call(ExamSeeder::class);
        $this->call(ResultSeeder::class);
        $this->call(PlanSeeder::class);
        $this->call(PaymentSeeder::class);
        
        // 4. Tickets (Depends on Users)
        $this->call(TicketSeeder::class);
        $this->call(SubscriptionPaymentSeeder::class);
        $this->call(LoginHistorySeeder::class);
        $this->call(StudentExamSessionSeeder::class);
        $this->call(NotificationSeeder::class);
        $this->call(CouponSeeder::class);
        $this->call(PageSeeder::class);
        $this->call(AdminNotificationSeeder::class);
        $this->call(CMSSeeder::class);
        $this->call(TestimonialSeeder::class);     
    }
}