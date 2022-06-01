<?php

namespace Tests\Feature;

use App\Models\Car;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CarControllerTest extends TestCase
{

    use RefreshDatabase;

    public function testIndex(): void
    {
        $this->get('/api/v1/cars')
            ->assertOk();

        $this->get('/api/v1/0')
            ->assertNotFound();
    }

    public function testStore(): void
    {
        $payload = ['name' => 'asdrfgh'];
        $this->post('/api/v1/cars', $payload)
            ->assertCreated()
            ->assertValid()
            ->assertSessionHasNoErrors();
        $this->assertDataBaseHas('cars', $payload);

        $payload = ['name' => '123456789'];
        $this->post('/api/v1/cars', $payload)
            ->assertStatus(302)
            ->assertInvalid()
            ->assertSessionHasErrors([
                'name' => 'The name must not be greater than 8 characters.'
            ]);
        $this->assertDatabaseMissing('cars', $payload);
        $this->refreshDatabase();
    }

    public function testShow(): void
    {
        $userData = [
            'name' => 'test'
        ];
        $car     = Car::create($userData);
        $id       = $car->id;
        $this->get('/api/v1/cars/' . $id)
            ->assertOk();
    }

    public function testDestroy(): void
    {
        $userData = [
            'name' => 'test'
        ];
        $car     = Car::create($userData);
        $id       = $car->id;
        $this->delete('/api/v1/cars/' . $id)
            ->assertStatus(204)
//                the same
            ->assertNoContent();
        $this->assertDatabaseMissing('cars', $userData);

        $id = null;
        $this->delete('/api/v1/cars/' . $id)
            ->assertStatus(405);
    }

    public function testUpdate(): void
    {
        $userData        = [
            'name' => 'test'
        ];
        $car            = Car::create($userData);
        $id              = $car->id;
        $updatedCarData = ['name' => 'updated'];
        $this->put('/api/v1/cars/' . $id, $updatedCarData)
            ->assertOk()
            ->assertValid()
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('cars', $updatedCarData);
        $id              = $car->id;
        $updatedCarData = ['name' => '123456789'];
        $this->put('/api/v1/cars/' . $id, $updatedCarData)
            ->assertStatus(302)
            ->assertInvalid()
            ->assertSessionHasErrors([
                'name' => 'The name must not be greater than 8 characters.'
            ]);
        $this->assertDatabaseMissing('cars', $updatedCarData);
    }

    public function testUpdateMissingUser(): void
    {
        $id      = 0;
        $payload = ['name' => 'smbd'];
        $this->put('/api/v1/cars/' . $id, $payload)
            ->assertNotFound();
    }

    public function testGetMissingUser(): void
    {
        $id = 0;
        $this->get('/api/v1/cars/' . $id)
            ->assertNotFound();
    }

    public function testDeleteMissingUser(): void
    {
        $id = 0;
        $this->delete('api/v1/cars/' . $id)
            ->assertNotFound();
    }

    public function testStoreWithMissingData(): void
    {
        $this->post('api/v1/cars/')
            ->assertStatus(302);
    }

}
