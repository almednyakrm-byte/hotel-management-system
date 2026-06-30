<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Fatortelh;
use App\Repositories\FatortelhRepository;
use App\Services\FatortelhService;
use Mockery;
use Mockery\MockInterface;

class Testفواتير_الفندق extends TestCase
{
    private $fatortelhRepository;
    private $fatortelhService;

    protected function setUp(): void
    {
        $this->fatortelhRepository = Mockery::mock(FatortelhRepository::class);
        $this->fatortelhService = new FatortelhService($this->fatortelhRepository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testGetFatortelhs()
    {
        $fatortelhs = [
            new Fatortelh(),
            new Fatortelh(),
        ];

        $this->fatortelhRepository->shouldReceive('all')->andReturn($fatortelhs);

        $result = $this->fatortelhService->getFatortelhs();

        $this->assertEquals($fatortelhs, $result);
    }

    public function testCreateFatortelh()
    {
        $fatortelh = new Fatortelh();
        $fatortelh->id = 1;
        $fatortelh->name = 'Test Fatortelh';

        $this->fatortelhRepository->shouldReceive('create')->andReturn($fatortelh);

        $result = $this->fatortelhService->createFatortelh($fatortelh);

        $this->assertEquals($fatortelh, $result);
    }

    public function testUpdateFatortelh()
    {
        $fatortelh = new Fatortelh();
        $fatortelh->id = 1;
        $fatortelh->name = 'Test Fatortelh';

        $this->fatortelhRepository->shouldReceive('find')->andReturn($fatortelh);
        $this->fatortelhRepository->shouldReceive('update')->andReturn($fatortelh);

        $result = $this->fatortelhService->updateFatortelh($fatortelh);

        $this->assertEquals($fatortelh, $result);
    }

    public function testDeleteFatortelh()
    {
        $fatortelh = new Fatortelh();
        $fatortelh->id = 1;

        $this->fatortelhRepository->shouldReceive('find')->andReturn($fatortelh);
        $this->fatortelhRepository->shouldReceive('delete')->andReturn(true);

        $result = $this->fatortelhService->deleteFatortelh($fatortelh);

        $this->assertTrue($result);
    }
}



// Fatortelh.php

namespace App\Models;

class Fatortelh
{
    public $id;
    public $name;

    public function __construct()
    {
        $this->id = 0;
        $this->name = '';
    }
}



// FatortelhRepository.php

namespace App\Repositories;

use App\Models\Fatortelh;

class FatortelhRepository
{
    public function all()
    {
        // Return all fatortelhs from database
    }

    public function create(Fatortelh $fatortelh)
    {
        // Create a new fatortelh in database
        return $fatortelh;
    }

    public function find($id)
    {
        // Find a fatortelh by id in database
        return new Fatortelh();
    }

    public function update(Fatortelh $fatortelh)
    {
        // Update a fatortelh in database
        return $fatortelh;
    }

    public function delete(Fatortelh $fatortelh)
    {
        // Delete a fatortelh from database
        return true;
    }
}



// FatortelhService.php

namespace App\Services;

use App\Repositories\FatortelhRepository;
use App\Models\Fatortelh;

class FatortelhService
{
    private $fatortelhRepository;

    public function __construct(FatortelhRepository $fatortelhRepository)
    {
        $this->fatortelhRepository = $fatortelhRepository;
    }

    public function getFatortelhs()
    {
        return $this->fatortelhRepository->all();
    }

    public function createFatortelh(Fatortelh $fatortelh)
    {
        return $this->fatortelhRepository->create($fatortelh);
    }

    public function updateFatortelh(Fatortelh $fatortelh)
    {
        return $this->fatortelhRepository->update($fatortelh);
    }

    public function deleteFatortelh(Fatortelh $fatortelh)
    {
        return $this->fatortelhRepository->delete($fatortelh);
    }
}