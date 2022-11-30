<?php

namespace Tests\Feature;

use Tests\TestCase;

class ProcessDownloadHistoryTest extends TestCase
{
    /** @test */
    public function it_prints_correct_downloads_in_table_after_sort()
    {
        $path = storage_path('tests/two_contributors_example.txt');

        $this->artisan("app:process-download-history {$path} --table")
            ->expectsTable([
                'Contributor',
                'Downloads',
                'Downloads/Day',
            ], [
                [
                    'Susan',
                    '5',
                    '0.014',
                ],
                [
                    'Dan',
                    '3',
                    '0.008',
                ],
            ])
            ->assertExitCode(0);
    }

    public function data(): array
    {
        return [
            'for unknown file' => [
                'unknown.txt',
                ['Given file does not exist.'],
                1,
            ],
            'for empty file' => [
                'empty_example.txt',
                ['No downloads were processed.'],
            ],
            'for a contributor with no downloads' => [
                'contributor_no_downloads.txt',
                ['No downloads were processed.'],
            ],
            'for 1 contributor' => [
                'one_contributor_example.txt',
                [
                    'Dan: 3 downloads, 0.008 downloads/day',
                ],
            ],
            'for 2 contributors' => [
                'two_contributors_example.txt',
                [
                    'Susan: 5 downloads, 0.014 downloads/day',
                    'Dan: 3 downloads, 0.008 downloads/day',
                ],
            ],
            'for 1 contributor with downloads outside of filter year' => [
                'downloads_outside_of_year.txt',
                ['No downloads were processed.'],
            ],
            'for 1 contributor with resources below rating' => [
                'resources_below_rating.txt',
                ['No downloads were processed.'],
            ],
            'for unknown resource on download' => [
                'unknown_resource.txt',
                [
                    'Resource "8e1d1c97-c347-43b1-af4a-220a88f02c9b" for the download on date "2020-11-20" was not found.',
                    'Dan: 3 downloads, 0.008 downloads/day',
                ],
            ],
            'for unknown contributor' => [
                'unknown_contributor.txt',
                [
                    'Contributor "Susan" for resource "6277c885-4905-4c5f-acf8-370b338f2867" was not found.',
                    'Dan: 3 downloads, 0.008 downloads/day',
                ],
            ],
            'for unknown command' => [
                'unknown_command.txt',
                [
                    'Unknown command "unknown".',
                    'No downloads were processed.',
                ],
            ],
            'for invalid contributor args' => [
                'invalid_contributor_args.txt',
                [
                    'Invalid number of arguments for command "Contributor". (Exepected 1, given 0)',
                    'No downloads were processed.',
                ],
            ],
            'for invalid resource args' => [
                'invalid_resource_args.txt',
                [
                    'Invalid number of arguments for command "Resource". (Exepected 3, given 1)',
                    'No downloads were processed.',
                ],
            ],
            'for invalid download args' => [
                'invalid_download_args.txt',
                [
                    'Invalid number of arguments for command "Download". (Exepected 2, given 1)',
                    'No downloads were processed.',
                ],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider data
     */
    public function it_prints_output(string $file, array $expected, int $status = 0)
    {
        $path = storage_path("tests/{$file}");

        $testConsole = $this->artisan("app:process-download-history {$path}");

        foreach ($expected as $line) {
            $testConsole->expectsOutput($line);
        }
        $testConsole->assertExitCode($status);
    }
}
