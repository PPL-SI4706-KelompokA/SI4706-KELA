<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Pesan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_pesan_page(): void
    {
        $response = $this->get(route('pesan.index'));
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_pesan_page(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('pesan.index'));

        $response->assertOk();
        $response->assertSee('Pesan');
    }

    public function test_user_can_send_chat_message(): void
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();

        $response = $this
            ->actingAs($sender)
            ->postJson(route('pesan.send'), [
                'id_penerima' => $receiver->id_user,
                'pesan' => 'Halo Budi, apakah makanan masih ada?',
            ]);

        $response->assertOk();
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('message.pesan', 'Halo Budi, apakah makanan masih ada?');

        $this->assertDatabaseHas('pesans', [
            'id_pengirim' => $sender->id_user,
            'id_penerima' => $receiver->id_user,
            'pesan' => 'Halo Budi, apakah makanan masih ada?',
            'status_baca' => 0,
        ]);
    }

    public function test_user_can_retrieve_chat_history_and_unread_messages_are_marked_as_read(): void
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();

        // Create an unread message from receiver to sender
        $message = Pesan::create([
            'id_pengirim' => $receiver->id_user,
            'id_penerima' => $sender->id_user,
            'pesan' => 'Halo, makanan masih tersedia.',
            'status_baca' => 0,
        ]);

        $this->assertDatabaseHas('pesans', [
            'id_pesan' => $message->id_pesan,
            'status_baca' => 0,
        ]);

        // Access the conversation as the sender
        $response = $this
            ->actingAs($sender)
            ->getJson(route('pesan.messages', $receiver->id_user));

        $response->assertOk();
        $response->assertJsonPath('success', true);
        $response->assertJsonCount(1, 'messages');
        $response->assertJsonPath('messages.0.pesan', 'Halo, makanan masih tersedia.');

        // Verify that the message status_baca was updated to 1 (read)
        $message->refresh();
        $this->assertEquals(1, $message->status_baca);
    }

    public function test_user_can_delete_conversation(): void
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();

        // Create some messages between them
        Pesan::create([
            'id_pengirim' => $sender->id_user,
            'id_penerima' => $receiver->id_user,
            'pesan' => 'Message 1',
        ]);
        Pesan::create([
            'id_pengirim' => $receiver->id_user,
            'id_penerima' => $sender->id_user,
            'pesan' => 'Message 2',
        ]);
        
        $this->assertDatabaseCount('pesans', 2);

        // Delete the conversation
        $response = $this
            ->actingAs($sender)
            ->deleteJson(route('pesan.delete', $receiver->id_user));

        $response->assertOk();
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('message', 'Percakapan berhasil dihapus.');

        $this->assertDatabaseCount('pesans', 0);
    }
}

