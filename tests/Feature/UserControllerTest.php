<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex(): void
    {
        $this->get('/api/v1/users')
            ->assertOk();

        $this->get('/api/v1/0')
            ->assertNotFound();
    }

    public function testStore(): void
    {
        $payload = ['name' => 'asdrfgh'];
        $this->post('/api/v1/users', $payload)
            ->assertCreated()
            ->assertValid()
            ->assertSessionHasNoErrors();
        $this->assertDataBaseHas('users', $payload);

        $payload = ['name' => 'ab'];
        $this->post('/api/v1/users', $payload)
            ->assertStatus(302)
            ->assertInvalid()
            ->assertSessionHasErrors([
                'name' => 'The name must be at least 3 characters.'
            ]);
        $this->assertDatabaseMissing('users', $payload);
        $this->refreshDatabase();
    }

    public function testShow(): void
    {
        $userData = [
            'name' => 'test'
        ];
        $user     = User::create($userData);
        $id       = $user->id;
        $this->get('/api/v1/users/' . $id)
            ->assertOk();
    }

    public function testDestroy(): void
    {
        $userData = [
            'name' => 'test'
        ];
        $user     = User::create($userData);
        $id       = $user->id;
        $this->delete('/api/v1/users/' . $id)
            ->assertStatus(204)
//                the same
            ->assertNoContent();
        $this->assertDatabaseMissing('users', $userData);

        $id = null;
        $this->delete('/api/v1/users/' . $id)
            ->assertStatus(405);
    }

    public function testUpdate(): void
    {
        $userData        = [
            'name' => 'test'
        ];
        $user            = User::create($userData);
        $id              = $user->id;
        $updatedUserData = ['name' => 'updatedTest'];
        $this->put('/api/v1/users/' . $id, $updatedUserData)
            ->assertOk()
            ->assertValid()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', $updatedUserData);

        $id              = $user->id;
        $updatedUserData = ['name' => 'sd'];
        $this->put('/api/v1/users/' . $id, $updatedUserData)
            ->assertStatus(302)
            ->assertInvalid()
            ->assertSessionHasErrors([
                'name' => 'The name must be at least 3 characters.'
            ]);
        $this->assertDatabaseMissing('users', $updatedUserData);
    }

    public function testUpdateMissingUser(): void
    {
        $id      = 0;
        $payload = ['name' => 'smbd'];
        $this->put('/api/v1/users/' . $id, $payload)
            ->assertNotFound();
    }

    public function testGetMissingUser(): void
    {
        $id = 0;
        $this->get('/api/v1/users/' . $id)
            ->assertNotFound();
    }

    public function testDeleteMissingUser(): void
    {
        $id = 0;
        $this->delete('api/v1/users/' . $id)
            ->assertNotFound();
    }

    public function testStoreWithMissingData(): void
    {
        $this->post('api/v1/users/')
            ->assertStatus(302);
    }

}


