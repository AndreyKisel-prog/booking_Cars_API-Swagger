<?php

namespace Tests\Feature;

use App\Models\Action;
use App\Models\Car;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ActionControllerTest extends TestCase
{

    use RefreshDatabase;


    public function testIndex(): void
    {
        $this->get('/api/v1/actions')
            ->assertOk();

        $this->get('/api/v1/0')
            ->assertNotFound();
    }

    public function testStore(): void
    {
        $car    = Car::create(['name' => 'asdasd']);
        $user   = User::create(['name' => 'asdasd']);
        $carId  = $car->id;
        $userId = $user->id;

        $payload = [
            'carId'  => $carId,
            'userId' => $userId,
        ];

        $this->post('/api/v1/actions', $payload)
            ->assertCreated()
            ->assertValid()
            ->assertSessionHasNoErrors();
        $this->assertDataBaseHas('actions', $payload);

        $this->refreshDatabase();
    }

    public function testShow(): void
    {
        $car    = Car::create(['name' => 'asd3asd']);
        $user   = User::create(['name' => 'asda4sd']);
        $carId  = $car->id;
        $userId = $user->id;
        $payload = [
            'carId'  => $carId,
            'userId' => $userId,
        ];
        $action     = Action::create($payload);
        $id         = $action->id;

        $this->get('/api/v1/actions/' . $id)
            ->assertOk();
    }

    public function testDestroy()
    {
        $car    = Car::create(['name' => 'asdasd']);
        $user   = User::create(['name' => 'asdasd']);
        $carId  = $car->id;
        $userId = $user->id;
        $payload = [
            'carId'  => $carId,
            'userId' => $userId,
        ];
        $action     = Action::create($payload);
        $id         = $action->id;
        $this->delete('/api/v1/actions/' . $id)
            ->assertStatus(204)
//                the same
            ->assertNoContent();
        $this->assertDatabaseMissing('actions', $payload);

        $id = null;
        $this->delete('/api/v1/actions/' . $id)
            ->assertStatus(405);
    }


    public function testGetMissingAction(): void
    {
        $id = 0;
        $this->get('/api/v1/actions/' . $id)
            ->assertNotFound();
    }

    public function testDeleteMissingAction(): void
    {
        $id = 0;
        $this->delete('api/v1/actions/' . $id)
            ->assertNotFound();
    }

    public function testStoreWithMissingData(): void
    {
        $this->post('api/v1/actions/')
            ->assertStatus(302);
    }


}
