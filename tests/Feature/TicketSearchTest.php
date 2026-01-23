<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Tests\TestCase;

class TicketSearchTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(VerifyCsrfToken::class);
        $this->withoutMiddleware(ValidateCsrfToken::class);
        $this->artisan('migrate:fresh', ['--force' => true]);
    }

    public function test_admin_can_search_tickets_by_user_name(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $alice = User::create([
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'password' => 'password',
            'role' => 'user',
        ]);

        $bob = User::create([
            'name' => 'Bob',
            'email' => 'bob@example.com',
            'password' => 'password',
            'role' => 'user',
        ]);

        $category = Category::create([
            'name' => 'IT',
            'slug' => 'it',
            'description' => null,
            'icon' => '💻',
            'is_active' => true,
        ]);

        $aliceTicket = Ticket::create([
            'ticket_number' => 'TKT-ALICE-0001',
            'title' => 'Laptop',
            'description' => 'Rusak',
            'user_id' => $alice->id,
            'category_id' => $category->id,
            'priority' => 'medium',
            'status' => 'open',
        ]);

        $bobTicket = Ticket::create([
            'ticket_number' => 'TKT-BOB-0002',
            'title' => 'Printer',
            'description' => 'Error',
            'user_id' => $bob->id,
            'category_id' => $category->id,
            'priority' => 'low',
            'status' => 'open',
        ]);

        $response = $this->actingAs($admin)->get(route('tickets.index', ['q' => 'Alice']));
        $response->assertOk();
        $response->assertSeeText($aliceTicket->ticket_number);
        $response->assertDontSeeText($bobTicket->ticket_number);
    }

    public function test_admin_can_search_tickets_by_ticket_number(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin2@example.com',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $user = User::create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => 'password',
            'role' => 'user',
        ]);

        $category = Category::create([
            'name' => 'IT',
            'slug' => 'it2',
            'description' => null,
            'icon' => '💻',
            'is_active' => true,
        ]);

        Ticket::create([
            'ticket_number' => 'TKT-XYZ-1234',
            'title' => 'Mouse',
            'description' => 'Tidak berfungsi',
            'user_id' => $user->id,
            'category_id' => $category->id,
            'priority' => 'medium',
            'status' => 'open',
        ]);

        $response = $this->actingAs($admin)->get(route('tickets.index', ['q' => '1234']));
        $response->assertOk();
        $response->assertSeeText('TKT-XYZ-1234');
    }
}

