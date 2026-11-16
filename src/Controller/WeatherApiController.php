<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Measurement;
use App\Service\WeatherUtil;

final class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api', methods: ['GET'])]
    public function index(
        #[MapQueryParameter('country')] string $country,
        #[MapQueryParameter('city')] string $city,
        WeatherUtil $util,
        #[MapQueryParameter('format')] string $format = 'json',
        #[MapQueryParameter('twig')] bool $twig = false,
    ): Response
    {
        $measurements = $util->getWeatherForCountryAndCity($country, $city);

        if ($format === 'csv') {
            if ($twig) {
                return $this->render('weather_api/index.csv.twig', [
                    'city' => $city,
                    'country' => $country,
                    'measurements' => $measurements,
                ]);
            }

            $lines = ["city,country,date,celsius,humidity,pressure,cloudiness"];
            foreach ($measurements as $m) {
                $lines[] = sprintf(
                    '%s,%s,%s,%s,%s,%s,%s,%s',
                    $city,
                    $country,
                    $m->getDate()->format('Y-m-d'),
                    $m->getTempCelsius(),
                    $m->getFahrenheit(),
                    $m->getHumidity(),
                    $m->getPressure(),
                    $m->getCloudiness()
                );
            }
            return new Response(implode("\n", $lines));
        }

        if ($twig) {
            return $this->render('weather_api/index.json.twig', [
                'city' => $city,
                'country' => $country,
                'measurements' => $measurements,
            ]);
        }

        return $this->json([
            'city' => $city,
            'country' => $country,
            'measurements' => array_map(fn(Measurement $m) => [
                'date' => $m->getDate()->format('Y-m-d'),
                'celsius' => $m->getTempCelsius(),
                'fahrenheit' => $m->getFahrenheit(),
                'humidity' => $m->getHumidity(),
                'pressure' => $m->getPressure(),
                'cloudiness' => $m->getCloudiness(),
            ], $measurements),
        ]);
    }
}
