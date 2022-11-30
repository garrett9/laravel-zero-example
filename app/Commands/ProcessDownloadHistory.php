<?php

namespace App\Commands;

use App\Dto\Contributor;
use App\Dto\ContributorResource;
use App\Dto\Download;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use LaravelZero\Framework\Commands\Command;

class ProcessDownloadHistory extends Command
{
    private const COMMAND_CONTRIBUTOR = 'Contributor';

    private const COMMAND_RESOURCE = 'Resource';

    private const COMMAND_DOWNLOAD = 'Download';

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'app:process-download-history
                            {file : The path to the file to process}
                            {--table : The view format of the results (One of list,table)}
                            {--year=2020 : The year to filter results to.}
                            {--rating=2 : Return download information for resources having more than this rating.}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = "Given the path to a file, process the download history for the contributors' resources";

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $path = $this->argument('file');
        if (! file_exists($path)) {
            $this->error('Given file does not exist.');

            return 1;
        }

        $handle = fopen($path, 'r');
        if (! $handle) {
            // Shouldn't happen
            $this->error('Failed to open file.'); // @codeCoverageIgnore

            return 1; // @codeCoverageIgnore
        }

        $contributors = [];
        $resources = [];
        $downloads = new Collection();

        while (($line = fgets($handle)) !== false) {
            $args = explode(' ', trim($line));
            $command = $args[0] ?? '';
            $argsCount = count($args) - 1;
            switch($command) {
                case self::COMMAND_CONTRIBUTOR:
                    if (count($args) <= 1) {
                        $this->invalidArgumentsError($command, 1, $argsCount);

                        continue 2;
                    }
                    $contributor = new Contributor($args[1]);
                    $contributors[$contributor->getName()] = $contributor;
                    break;
                case self::COMMAND_RESOURCE:
                    if (count($args) <= 3) {
                        $this->invalidArgumentsError($command, 3, $argsCount);

                        continue 2;
                    }

                    $contributor = $contributors[$args[1]] ?? null;
                    if (! $contributor) {
                        $this->error("Contributor \"{$args[1]}\" for resource \"{$args[2]}\" was not found.");

                        continue 2;
                    }

                    $resource = new ContributorResource($contributor, $args[2], $args[3]);
                    $resources[$resource->getId()] = $resource;

                    break;
                case self::COMMAND_DOWNLOAD:
                    if (count($args) <= 2) {
                        $this->invalidArgumentsError($command, 2, $argsCount);

                        continue 2;
                    }

                    $resource = $resources[$args[1]] ?? null;
                    if (! $resource) {
                        $this->error("Resource \"{$args[1]}\" for the download on date \"{$args[2]}\" was not found.");

                        continue 2;
                    }

                    $date = new Carbon($args[2]);
                    $download = new Download($resource, $date);
                    $downloads->push($download);
                    break;
                default:
                    $this->error("Unknown command \"{$command}\".");
                    break;
            }
        }

        return $this->printOutput($downloads);
    }

    private function printOutput(Collection $downloads)
    {
        $year = (int) $this->option('year');
        $rating = (int) $this->option('rating');
        $daysInYear = (new Carbon("{$year}-01-01"))->isLeapYear() ? 366 : 365;

        $downloads = $downloads->filter(function (Download $download) use ($rating, $year) {
            return $download->getResource()->getRating() > $rating
                && $download->getDate()->year === $year;
        });
        if (count($downloads) <= 0) {
            $this->info('No downloads were processed.');

            return 0;
        }

        $data = $downloads->groupBy(function (Download $download) {
            return $download->getResource()->getContributor()->getName();
        })->map(function (Collection $downloads, string $name) use ($daysInYear) {
            return [
                $name,
                $downloads->count(),
                round($downloads->count() / $daysInYear, 3),
            ];
        })->sortByDesc(function ($result) {
            return $result[1];
        });

        if ($this->option('table')) {
            $this->table(['Contributor', 'Downloads', 'Downloads/Day'], $data);
        } else {
            $data->each(function (array $set) {
                $this->info("{$set[0]}: {$set[1]} downloads, {$set[2]} downloads/day");
            });
        }

        return 0;
    }

    private function invalidArgumentsError(string $command, int $expectedCount, int $givenCount)
    {
        $this->error("Invalid number of arguments for command \"{$command}\". (Exepected {$expectedCount}, given {$givenCount})");
    }
}
