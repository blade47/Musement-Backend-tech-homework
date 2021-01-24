<?php require __DIR__ . '/vendor/autoload.php';

use App\Log;
use App\TUIMusement;

/**
 * TUIMusement tech homework
 *
 * Gets the list of the cities from Musement's API for each city gets the
 * forecast for the next 2 days using http://api.weatherapi.com and print to
 * STDOUT "Processed city [city name] | [weather today] - [wheather tomorrow]"
 *
 * Example:
 *  Processed city Milan | Heavy rain - Partly cloudy
 *  Processed city Rome | Sunny - Sunny
 *
 * @author Alessandro Afloarei <alessandro.afloarei@gmail.com>
 *
 */
try {
    Log::debug('Starting the application', [
        'file' => __FILE__,
        'line' => __LINE__
    ]);

    $tui_musement = new TUIMusement();
    $tui_musement->run(Config::NUM_OF_DAYS);

    Log::debug('Application terminated', [
        'file' => __FILE__,
        'line' => __LINE__
    ]);
} catch (Exception $e) {
    Log::critical('Caught exception: ' . $e->getMessage(), [
        'file' => __FILE__,
        'line' => __LINE__
    ]);
}
