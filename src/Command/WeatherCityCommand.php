<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\WeatherUtil;

#[AsCommand(
    name: 'weather:city',
    description: 'Command displays weather forecast for country code and city name.',
)]
class WeatherCityCommand{
    private WeatherUtil $weatherUtil;
    public function __construct(WeatherUtil $weatherUtil)
    {
        $this->weatherUtil = $weatherUtil;
    }
    public function __invoke(
        SymfonyStyle $io,
        #[Argument('Country code')] string $country,
        #[Argument('City name')] string $city,
    ): int {
        $measurements = $this->weatherUtil->getWeatherForCountryAndCity($country, $city);

        $io->writeln(sprintf('Location: %s, %s', $city, $country));
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
