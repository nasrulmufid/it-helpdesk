<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Tests\TestCase;

class TicketAttachmentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(VerifyCsrfToken::class);
        $this->withoutMiddleware(ValidateCsrfToken::class);
        $this->artisan('migrate:fresh', ['--force' => true]);
    }

    public function test_user_must_upload_attachment_when_creating_ticket(): void
    {
        $user = User::create([
            'name' => 'User',
            'email' => 'user@example.com',
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

        $response = $this->actingAs($user)
            ->from(route('tickets.create'))
            ->post(route('tickets.store'), [
                'title' => 'Laptop rusak',
                'description' => 'Tidak menyala',
                'category_id' => $category->id,
                'priority' => 'medium',
            ]);

        $response->assertRedirect(route('tickets.create'));
        $response->assertSessionHasErrors(['attachments']);
    }

    public function test_owner_can_view_and_download_attachment(): void
    {
        Storage::fake('local');

        $user = User::create([
            'name' => 'User',
            'email' => 'user2@example.com',
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

        $file = UploadedFile::fake()->create('foto.jpg', 10, 'image/jpeg');

        $response = $this->actingAs($user)->post(route('tickets.store'), [
            'title' => 'Laptop rusak',
            'description' => 'Tidak menyala',
            'category_id' => $category->id,
            'priority' => 'medium',
            'attachments' => [$file],
        ]);

        $ticket = Ticket::query()->firstOrFail();
        $attachment = $ticket->attachments()->firstOrFail();

        $response->assertRedirect(route('tickets.show', $ticket));

        $viewResponse = $this->actingAs($user)->get(route('tickets.attachments.view', [$ticket, $attachment]));
        $viewResponse->assertOk();
        $this->assertSame('inline; filename="' . $attachment->file_name . '"', $viewResponse->headers->get('content-disposition'));

        $downloadResponse = $this->actingAs($user)->get(route('tickets.attachments.download', [$ticket, $attachment]));
        $downloadResponse->assertOk();
        $this->assertTrue(str_contains((string) $downloadResponse->headers->get('content-disposition'), 'attachment'));
    }

    public function test_other_user_cannot_download_attachment(): void
    {
        Storage::fake('local');

        $owner = User::create([
            'name' => 'Owner',
            'email' => 'owner@example.com',
            'password' => 'password',
            'role' => 'user',
        ]);

        $otherUser = User::create([
            'name' => 'Other',
            'email' => 'other@example.com',
            'password' => 'password',
            'role' => 'user',
        ]);

        $category = Category::create([
            'name' => 'IT',
            'slug' => 'it3',
            'description' => null,
            'icon' => '💻',
            'is_active' => true,
        ]);

        $file = UploadedFile::fake()->create('dokumen.pdf', 10, 'application/pdf');

        $this->actingAs($owner)->post(route('tickets.store'), [
            'title' => 'Printer error',
            'description' => 'Kertas macet',
            'category_id' => $category->id,
            'priority' => 'high',
            'attachments' => [$file],
        ]);

        $ticket = Ticket::query()->firstOrFail();
        $attachment = $ticket->attachments()->firstOrFail();

        $response = $this->actingAs($otherUser)->get(route('tickets.attachments.download', [$ticket, $attachment]));
        $response->assertForbidden();
    }
}
