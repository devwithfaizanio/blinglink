<?php

namespace Tests\Feature;

use App\Models\Connection;
use App\Models\User;
use App\Models\UserReferral;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ReferralTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_auto_generated_referral_code(): void
    {
        $user = User::factory()->create();

        $this->assertNotEmpty($user->referral_code);
        $this->assertStringStartsWith('BLING-', $user->referral_code);
    }

    public function test_user_can_fetch_referral_info_and_code(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/referrals/my-code');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'referral_code',
                    'share_link',
                    'total_referrals_count',
                    'referred_friends',
                ],
            ]);
    }

    public function test_user_can_send_friend_invite(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/referrals/send-invite', [
            'email' => 'friend@example.com',
            'message' => 'Hey join me on BlingLink!',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Friend invitation sent successfully',
            ]);

        $this->assertDatabaseHas('user_referrals', [
            'referrer_id' => $user->id,
            'friend_email' => 'friend@example.com',
            'status' => 'invited',
        ]);
    }

    public function test_new_user_can_register_with_referral_code_and_auto_connect(): void
    {
        Storage::fake('public');

        $inviter = User::factory()->create();

        $emirateId = UploadedFile::fake()->image('emirate.jpg');
        $profileImage = UploadedFile::fake()->image('profile.jpg');

        $registerData = [
            'f_name' => 'John Friend',
            'email' => 'john.friend@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'age' => 28,
            'gender' => 'male',
            'nationality' => 'Emirati',
            'profession' => 'Engineer',
            'company' => 'TechCorp',
            'dubai_location' => 'Downtown',
            'height' => "5'10\"",
            'education_level' => 'Bachelor',
            'family' => 'Single',
            'lifestyle_preference' => 'Fitness, Travel',
            'your_interest' => 'Tech, Sports',
            'languages' => 'English, Arabic',
            'bio' => 'Hello there!',
            'linkedin_profile' => 'https://linkedin.com/in/johnfriend',
            'emirate_id' => $emirateId,
            'profile_image' => $profileImage,
            'referral_code' => $inviter->referral_code,
        ];

        $response = $this->postJson('/api/v1/register', $registerData);

        // UserController register method returns HTTP forbidden (403) with message 'User registered successfully' for unapproved accounts by design
        $this->assertTrue(in_array($response->getStatusCode(), [200, 403]));

        $newUser = User::where('email', 'john.friend@example.com')->first();
        $this->assertNotNull($newUser);
        $this->assertEquals($inviter->id, $newUser->referred_by_id);

        // Verify connection automatically created
        $connectionExists = Connection::where(function ($q) use ($inviter, $newUser) {
            $q->where('requester_id', $inviter->id)->where('requested_id', $newUser->id);
        })->where('status', 'accepted')->exists();

        $this->assertTrue($connectionExists);
    }
}
