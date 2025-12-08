<?php

namespace Tests\Feature;

use App\Enums\ProjectStage;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Volt\Volt;
use Tests\TestCase;

class ProjectTrackerTest extends TestCase
{
    use RefreshDatabase;

    // --- PUBLIC ACCESS TESTS ---

    public function test_landing_page_loads(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Real-Time Project');
    }

    public function test_public_search_page_loads(): void
    {
        $response = $this->get(route('track.search'));
        $response->assertStatus(200);
    }

    public function test_guest_can_track_project_with_valid_code(): void
    {
        // 1. Setup Data
        $client = User::factory()->create();
        $project = Project::create([
            'user_id' => $client->id,
            'title' => 'Test Lift',
            'location' => 'Lagos',
            'tracking_code' => 'TEST1234',
            'current_status' => ProjectStage::PRODUCTION,
        ]);

        // 2. Visit Page
        $response = $this->get(route('track.public', 'TEST1234'));

        // 3. Assertions
        $response->assertStatus(200);
        $response->assertSee('Test Lift');
        $response->assertSee('Production');
    }

    public function test_guest_gets_404_for_invalid_code(): void
    {
        $response = $this->get(route('track.public', 'INVALID999'));
        $response->assertStatus(404);
        $response->assertSee('Project Not Found'); // Assuming custom 404 page text
    }

    // --- AUTHENTICATION & AUTHORIZATION TESTS ---

    public function test_admin_can_access_dashboard(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }

    public function test_regular_user_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));
        
        // Should be forbidden by EnsureUserIsAdmin middleware
        $response->assertStatus(403); 
    }

    public function test_guest_redirected_to_login_when_accessing_admin(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));
    }

    // --- ADMIN FUNCTIONALITY TESTS (VOLT) ---

    public function test_admin_can_create_client(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        Volt::test('admin.create-client')
            ->set('name', 'New Client Co')
            ->set('email', 'new@client.com')
            ->set('phone', '+234800000001')
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('admin.clients.index'));

        $this->assertDatabaseHas('users', [
            'email' => 'new@client.com',
            'name' => 'New Client Co',
        ]);
    }

    public function test_admin_can_create_project(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $client = User::factory()->create(['name' => 'Target Client']);

        Volt::test('admin.create-project')
            ->set('user_id', $client->id)
            ->set('title', 'Luxury Villa Lift')
            ->set('location', 'Banana Island')
            ->call('create')
            ->assertHasNoErrors()
            ->assertRedirect(route('admin.projects.index'));

        $this->assertDatabaseHas('projects', [
            'title' => 'Luxury Villa Lift',
            'user_id' => $client->id,
            'current_status' => ProjectStage::SCHEDULED_VISIT->value, // Default status
        ]);
    }

    public function test_admin_can_update_project_status_and_fire_webhook(): void
    {
        // 1. Mock the HTTP Webhook
        Http::fake([
            '*' => Http::response([], 200),
        ]);

        $admin = User::factory()->create(['is_admin' => true]);
        $client = User::factory()->create();
        
        $project = Project::create([
            'user_id' => $client->id,
            'title' => 'Old Status Project',
            'tracking_code' => 'ABC12345',
            'current_status' => ProjectStage::PRODUCTION,
        ]);

        // 2. Test Component Logic
        Volt::test('admin.manage-project', ['project' => $project])
            ->set('newStatus', ProjectStage::SHIPPED->value)
            ->set('note', 'Container loaded.')
            ->set('notify', true)
            ->call('updateStatus') // Note: In your component code this was a variable $updateStatus, Volt maps this to a callable.
            ->assertHasNoErrors();

        // 3. Assert Database Update
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'current_status' => ProjectStage::SHIPPED->value,
        ]);

        // 4. Assert History Log Created
        $this->assertDatabaseHas('project_updates', [
            'project_id' => $project->id,
            'status_key' => ProjectStage::SHIPPED->value,
            'description' => 'Container loaded.',
        ]);

        // 5. Assert Webhook Sent
        Http::assertSent(function ($request) use ($project) {
            return $request['type'] === 'status_update' &&
                   $request['project_title'] === 'Old Status Project' &&
                   $request['new_status'] === 'Elevator Shipped';
        });
    }

    public function test_admin_can_edit_project_details(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $client = User::factory()->create();
        
        $project = Project::create([
            'user_id' => $client->id,
            'title' => 'Wrong Title',
            'location' => 'Wrong Location',
            'current_status' => ProjectStage::PRODUCTION,
        ]);

        Volt::test('admin.edit-project', ['project' => $project])
            ->set('title', 'Correct Title')
            ->set('location', 'Correct Location')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'title' => 'Correct Title',
            'location' => 'Correct Location',
        ]);
    }
}