<?php

namespace Tests\Feature;

use App\Models\ScrapboardRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScrapboardRecordManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_index_can_search_code(): void
    {
        ScrapboardRecord::create([
            'code' => 'SM-A14-A2-0012',
            'classification' => 'A2',
        ]);

        ScrapboardRecord::create([
            'code' => 'IP13-A4-8831',
            'classification' => 'A4',
        ]);

        $response = $this->get('/?q=IP13');

        $response->assertOk();
        $response->assertSee('IP13-A4-8831');
        $response->assertDontSee('SM-A14-A2-0012');
    }

    public function test_public_index_paginates_15_records_per_page(): void
    {
        for ($i = 1; $i <= 16; $i++) {
            ScrapboardRecord::create([
                'code' => sprintf('CODE-%04d', $i),
                'classification' => 'A1',
            ]);
        }

        $pageOne = $this->get('/');
        $pageOne->assertOk();
        $pageOne->assertSee('CODE-0001');
        $pageOne->assertDontSee('CODE-0016');

        $pageTwo = $this->get('/?page=2');
        $pageTwo->assertOk();
        $pageTwo->assertSee('CODE-0016');
        $pageTwo->assertDontSee('CODE-0001');
    }

    public function test_user_can_login_and_logout_from_public_index(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
        ]);

        $loginResponse = $this->from('/')
            ->post('/login', [
                'email' => $user->email,
                'password' => 'password',
            ]);

        $loginResponse->assertRedirect(route('admin.scrapboard-records.index'));
        $this->assertAuthenticatedAs($user);

        $logoutResponse = $this->post(route('logout'));
        $logoutResponse->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get(route('admin.scrapboard-records.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_create_update_and_delete_record(): void
    {
        $this->actingAs(User::factory()->create());

        $storeResponse = $this->post(route('admin.scrapboard-records.store'), [
            'code' => 'IP11-A3-1001',
            'classification' => 'A3',
        ]);

        $storeResponse->assertRedirect(route('admin.scrapboard-records.index'));
        $this->assertDatabaseHas('scrapboard_records', [
            'code' => 'IP11-A3-1001',
            'classification' => 'A3',
        ]);

        $record = ScrapboardRecord::where('code', 'IP11-A3-1001')->firstOrFail();

        $updateResponse = $this->put(route('admin.scrapboard-records.update', $record), [
            'code' => 'IP11-A5-2002',
            'classification' => 'A5',
        ]);

        $updateResponse->assertRedirect(route('admin.scrapboard-records.index'));
        $this->assertDatabaseHas('scrapboard_records', [
            'id' => $record->id,
            'code' => 'IP11-A5-2002',
            'classification' => 'A5',
        ]);

        $deleteResponse = $this->delete(route('admin.scrapboard-records.destroy', $record));
        $deleteResponse->assertRedirect(route('admin.scrapboard-records.index'));
        $this->assertDatabaseMissing('scrapboard_records', [
            'id' => $record->id,
        ]);
    }

    public function test_code_is_unique(): void
    {
        $this->actingAs(User::factory()->create());

        ScrapboardRecord::create([
            'code' => 'IP12-A1-5555',
            'classification' => 'A1',
        ]);

        $response = $this->from(route('admin.scrapboard-records.index'))
            ->post(route('admin.scrapboard-records.store'), [
                'code' => 'IP12-A1-5555',
                'classification' => 'A2',
            ]);

        $response->assertRedirect(route('admin.scrapboard-records.index'));
        $response->assertSessionHasErrors('code');
    }
}
