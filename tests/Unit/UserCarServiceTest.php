<?php

namespace Tests\Unit;

use App\Car;
use App\Factories\CarFactory;
use App\Services\UserCarService;
use Illuminate\Support\LazyCollection;
use Tests\TestCase;

class UserCarServiceTest extends TestCase
{

    public function testModelToDTOSuccessful()
    {
        $model = Car::factory()->make();
        $model->id = 1;

        $expectedDTO = new \App\DTO\Car(
            $model->id,
            $model->make,
            $model->model,
            $model->year
        );

        $factoryFake = $this->mock(CarFactory::class);
        $factoryFake->expects('createFromModel')
            ->once()
            ->withArgs([$model])
            ->andReturn($expectedDTO);

        $service = new UserCarService($factoryFake);
        $result = $service->modelToDTO($model);
        $this->assertInstanceOf(\App\DTO\Car::class, $result);
        $this->assertSame($expectedDTO, $result);
    }

    public function testModelToDTOReturnNull()
    {
        $factoryFake = $this->mock(CarFactory::class);
        $factoryFake->expects('createFromModel')
            ->once()
            ->withArgs([null])
            ->andReturn(null);

        $service = new UserCarService($factoryFake);
        $this->assertNull($service->modelToDTO(null));
    }

    public function testLazyModelsToDtoSuccessful()
    {
        $cars = Car::factory(3)
            ->sequence(fn ($sequence) => ['id' => $sequence->index + 1])
            ->make();

        $lazyCollection = new LazyCollection($cars);

        $factory = new CarFactory();
        $service = new UserCarService($factory);
        $result = $service->mapLazyModelsToDTO($lazyCollection);
        $this->assertEquals(3, $result->count());
        $result->each(
            function ($dto) {
                $this->assertInstanceOf(\App\DTO\Car::class, $dto);
            }
        );
    }

}
