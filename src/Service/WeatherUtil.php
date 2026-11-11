<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Location;
use App\Entity\Measurement;
use App\Repository\LocationRepository;
use App\Repository\MeasurementRepository;

class WeatherUtil
{
    private MeasurementRepository $measurementRepository;
    private LocationRepository $locationRepository;

    public function __construct(
        MeasurementRepository $measurementRepository,
        LocationRepository $locationRepository
    ) {
        $this->measurementRepository = $measurementRepository;
        $this->locationRepository = $locationRepository;
    }

    /**
     * @return Measurement[]
     */
    public function getWeatherForLocation(Location $location): array
    {
        return $this->measurementRepository->findByLocation($location);
    }

    /**
     * @return Measurement[]
     */
    public function getWeatherForCountryAndCity(string $countryCode, string $city): array
    {
        $location = $this->locationRepository->findOneBy([
            'country' => $countryCode,
            'city' => $city,
        ]);

        if (!$location) {
            return [];
        }

        return $this->getWeatherForLocation($location);
    }
}
