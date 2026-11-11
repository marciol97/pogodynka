<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Repository\LocationRepository;
use App\Service\WeatherUtil;

#[AsCommand(
    name: 'weather:location',
    description: 'Command displays weather forecast for location ID',
)]

class WeatherLocationCommand{

    private LocationRepository $locationRepository;
    private WeatherUtil $weatherUtil;

    public function __construct(
        LocationRepository $locationRepository,
        WeatherUtil $weatherUtil
    ) {
        $this->locationRepository = $locationRepository;
        $this->weatherUtil = $weatherUtil;
    }
    public function __invoke(
        SymfonyStyle $io,
        #[Argument('Location ID')] int $id,
    ): int {

        $location = $this->locationRepository->find($id);
        $measurements = $this->weatherUtil->getWeatherForLocation($location);

        $io->writeln(sprintf('Location: %s', $location->getCity()));
        $io->table(
            ['Date', 'Temperature', 'Humidity', 'Pressure', 'Cloudiness'],
            array_map(fn($m) => [
                $m->getDate()->format('Y-m-d'),
                $m->getTempCelsius() . '°C',
                $m->getHumidity() . '%',
                $m->getPressure() . ' hPa',
                $m->getCloudiness() . '%',
            ], $measurements)
        );

        return Command::SUCCESS;
    }
}
