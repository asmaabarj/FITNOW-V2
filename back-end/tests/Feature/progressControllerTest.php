<?php

namespace Tests\Feature;
use Tests\TestCase;
use App\Models\User;
use App\Models\Progress;
use Illuminate\Http\Response;
use Database\Factories\ProgressFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProgressControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testStoreProgress()
    {
        $user = User::factory()->create();

        $payload = [
            'weight' => '85 kg',
            'measurements' => 'ddddd',
            'performance' => 'fdgvbfgbfgb',
        ];

        $this->actingAs($user)->json('post', 'api/progress', $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'message',
                'progress' => [
                    'id',
                    'user_id',
                    'weight',
                    'measurements',
                    'performance',
                ]
            ]);
    }

    public function testIndexReturnsDataInValidFormat()
    {
        $user = User::factory()->create();

        $progress = Progress::create([
            'user_id' => $user->id,
            'weight' => '85 kg',
            'measurements' => 'ddddd',
            'performance' => 'hjkldcdcd',
        ]);

        $this->actingAs($user)->json('get', 'api/showProgress')
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'progress' => [
                    [
                        'id' => $progress->id,
                        'user_id' => $user->id,
                        'weight' => $progress->weight,
                        'measurements' => $progress->measurements,
                        'performance' => $progress->performance,
                    ]
                ]
            ]);
    }

    public function testUpdateProgress()
    {
        $user = User::factory()->create();

        $progress = Progress::create([
            'user_id' => $user->id,
            'weight' => '85 kg',
            'measurements' => 'ddddd',
            'performance' => 'fdgvbfgbfgb',
        ]);

        $payload = [
            'id' => $progress->id,
            'weight' => '86 KG',
            'measurements' => 'aaaaa',
            'performance' => 'fvbnhbfg'
        ];

        $this->actingAs($user)->json('put', "api/progress/{$progress->id}", $payload)
            ->assertStatus(Response::HTTP_OK);
    }

    public function testCompleteProgress()
    {
        $user = User::factory()->create();

        $progress = Progress::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)->json('put', "api/progress/{$progress->id}/complete")
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'Progress status updated to Completed',
                'progress' => [
                    'id' => $progress->id,
                    'status' => 'Completed',
                ]
            ]);
    }

    public function testDestroyProgress()
    {
        $user = User::factory()->create();

        $progress = Progress::create([
            'user_id' => $user->id,
            'weight' => '85 kg',
            'measurements' => 'ddddd',
            'performance' => 'rferfrefr',
        ]);

        $this->actingAs($user)->json('delete', "api/progress/{$progress->id}")
            ->assertStatus(Response::HTTP_OK);
    }

    public function testGetProgressHistory()
    {
        $user = User::factory()->create();

        Progress::factory()->count(5)->create(['user_id' => $user->id]);

        $this->actingAs($user)->json('get', "api/progress/history")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'message',
                'progressHistory' => [
                    '*' => [
                        'id',
                        'user_id',
                        'weight',
                        'measurements',
                        'performance',
                    ]
                ]
            ]);
    }
}
