<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserScheduleControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_it_returns_user_schedules()
    {
        $user = User::factory()->create();
        $schedules = Activity::factory(5)->create(['user_id' => $user->id]);

        $response = $this->getJson(route('schedules.index', ['userId' => $user->id]));

        $response->assertStatus(200)
            ->assertJsonCount(5, 'schedules')
            ->assertJsonStructure([
                'name',
                'email',
                'password',
                'schedules' => [['id', 'title', 'description', 'start_date', 'deadline_date', 'end_date', 'status']],
                'pagination' => ['total', 'current_page', 'per_page', 'last_page', 'from', 'to'],
            ]);
    }

    public function test_it_creates_a_user_schedule()
    {
        $user = User::factory()->create();
        $startDate  =   now()->addDays(1);
        $endDate    =   now()->addDays(2);

        if (in_array($startDate->format('N'), ['6', '0'])) {
            $startDate->addDays(2);
            $endDate->addDays(2);
        }

        $scheduleData = [
            'title' => $this->faker->title,
            'description' => $this->faker->paragraph,
            'start_date' => $startDate->format('d/m/Y H\hi'),
            'deadline_date' => $endDate->format('d/m/Y'),
            'end_date' => $endDate->format('d/m/Y H\hi'),
            'status' => 'aberto'
        ];

        $response = $this->postJson(route('schedules.store', ['userId' => $user->id]), $scheduleData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'name',
                'email',
                'password',
                'schedules' => [['id', 'title', 'description', 'start_date', 'deadline_date', 'end_date', 'status']],
            ]);

        $this->assertDatabaseHas('activities', ['title' => $scheduleData['title']]);
    }

    public function test_it_shows_a_user_schedule()
    {
        $user = User::factory()->create();
        $schedule = Activity::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson(route('schedules.show', ['userId' => $user->id, 'schedule' => $schedule->id]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'name',
                'email',
                'password',
                'schedules' => [['id', 'title', 'description', 'start_date', 'deadline_date', 'end_date', 'status']],
            ]);
    }

    public function test_it_updates_a_user_schedule()
    {
        $user = User::factory()->create();
        $schedule = Activity::factory()->create(['user_id' => $user->id]);
        $startDate  =   now()->addDays(3);
        $endDate    =   now()->addDays(4);

        if (in_array($startDate->format('N'), ['6', '0'])) {
            $startDate->addDays(2);
            $endDate->addDays(2);
        }

        $newScheduleData = [
            'title' => $this->faker->title,
            'description' => $this->faker->paragraph,
            'start_date' => $startDate->format('d/m/Y'),
            'deadline_date' => $endDate->format('d/m/Y'),
            'end_date' => $endDate->format('d/m/Y'),
            'status' => 'aberto'
        ];

        $response = $this->putJson(route('schedules.update', ['userId' => $user->id, 'schedule' => $schedule->id]), $newScheduleData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'name',
                'email',
                'password',
                'schedules' => [['id', 'title', 'description', 'start_date', 'deadline_date', 'end_date', 'status']],
            ]);

        $this->assertDatabaseHas('activities', ['id' => $schedule->id, 'title' => $newScheduleData['title']]);
    }

    public function test_it_deletes_a_user_schedule()
    {
        $user = User::factory()->create();
        $schedule = Activity::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson(route('schedules.destroy', ['userId' => $user->id, 'schedule' => $schedule->id]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'name',
                'email',
                'password',
                'schedules' => [['id', 'title', 'description', 'start_date', 'deadline_date', 'end_date', 'status']],
            ]);

        $this->assertDatabaseMissing('activities', ['id' => $schedule->id]);
    }
}
