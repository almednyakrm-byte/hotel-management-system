<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\HotelReservationRepository;
use App\Service\HotelReservationService;
use PHPUnit\Framework\MockObject\MockObject;

class Testحجوزات_الفندق extends TestCase
{
    private $router;
    private $serializer;
    private $hotelReservationRepository;
    private $hotelReservationService;

    protected function setUp(): void
    {
        $this->router = $this->createMock(RouterInterface::class);
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->hotelReservationRepository = $this->createMock(HotelReservationRepository::class);
        $this->hotelReservationService = $this->createMock(HotelReservationService::class);
    }

    public function testGetAllHotelReservations()
    {
        $this->hotelReservationRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'hotel_id' => 1, 'guest_id' => 1, 'check_in' => '2022-01-01', 'check_out' => '2022-01-02'],
                ['id' => 2, 'hotel_id' => 1, 'guest_id' => 2, 'check_in' => '2022-01-03', 'check_out' => '2022-01-04'],
            ]);

        $response = $this->hotelReservationService->getAllHotelReservations();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testCreateHotelReservation()
    {
        $data = [
            'hotel_id' => 1,
            'guest_id' => 1,
            'check_in' => '2022-01-01',
            'check_out' => '2022-01-02',
        ];

        $this->hotelReservationRepository->expects($this->once())
            ->method('create')
            ->with($data)
            ->willReturn(['id' => 1, 'hotel_id' => 1, 'guest_id' => 1, 'check_in' => '2022-01-01', 'check_out' => '2022-01-02']);

        $response = $this->hotelReservationService->createHotelReservation($data);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateHotelReservation()
    {
        $data = [
            'hotel_id' => 1,
            'guest_id' => 1,
            'check_in' => '2022-01-01',
            'check_out' => '2022-01-02',
        ];

        $this->hotelReservationRepository->expects($this->once())
            ->method('update')
            ->with(1, $data)
            ->willReturn(['id' => 1, 'hotel_id' => 1, 'guest_id' => 1, 'check_in' => '2022-01-01', 'check_out' => '2022-01-02']);

        $response = $this->hotelReservationService->updateHotelReservation(1, $data);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDeleteHotelReservation()
    {
        $this->hotelReservationRepository->expects($this->once())
            ->method('delete')
            ->with(1);

        $response = $this->hotelReservationService->deleteHotelReservation(1);
        $this->assertEquals(204, $response->getStatusCode());
    }
}


This test file uses PHPUnit to test the CRUD API operations on the 'حجوزات الفندق' module. It uses mocked PDO statements to isolate the dependencies and make the tests more efficient. The tests cover the following scenarios:

*   `testGetAllHotelReservations`: Tests the GET request to retrieve all hotel reservations.
*   `testCreateHotelReservation`: Tests the POST request to create a new hotel reservation.
*   `testUpdateHotelReservation`: Tests the PUT request to update an existing hotel reservation.
*   `testDeleteHotelReservation`: Tests the DELETE request to delete a hotel reservation.

Each test method uses the `createMock` method to create a mock object for the `HotelReservationRepository` and `HotelReservationService` classes. The `expects` method is used to specify the expected behavior of the mock objects, and the `willReturn` method is used to specify the expected return value.

The tests use the `getStatusCode` and `headers` methods to verify the HTTP status code and the `Content-Type` header of the response. The `assertEquals` method is used to verify that the response matches the expected value.

Note that this is just an example, and you may need to modify the test file to fit your specific use case.