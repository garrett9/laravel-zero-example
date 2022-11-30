# Installation
1. Install PHP8
1. [Install Composer](https://getcomposer.org/download/)
1. Clone the repository.

    ```
    git clone https://github.com/garrett/laravel-zero-example
    cd laravel-zero-example
    ```
1. Install the project's dependences.

    ```
    composer install
    ```
1. Learn about the command to process download histories.

    ```
    php application help app:process-download-history
    ```
1. Run the command.

    ```
    php application app:process-download-history storage/input.txt
    ```
1. Run the command but display a table view.

    ```
    php application app:process-download-history storage/input.txt --table
    ```
1. Run the tests

    ```
    php application test
    ```
1. Run tests with code coverage (requires [XDebug](https://xdebug.org/docs/install))

    ```
    composer test:coverage
    ```

# Getting Started

To acomplish this task, I chose a framework and language I'm well versed in by using [Laravel Zero](https://laravel-zero.com/). Laravel Zero is an unofficial fork of the [Laravel Framework](https://laravel.com/) that only includes its libraries for building and executing console commands. Using the framework, I wrote the command `ProcessDownloadHistory.php` to perform the task that was outlined in the provided README. 

I first wanted to create maps of both Contributor and Resource classes since I'll need easy access to those instances once I start parsing downloads. Reading through the file, I first parsed Contributors and added them to the map, followed by Resources. With both maps built, I was able to easily parse through downloads and push the instances onto a Laravel `Collection` instance. A `Collection` is a Laravel utility that allows you to perform a variety of array manipulation commands, which came in very handy when having to filter the result set, sort it, and finally build it to be displayed. With the data set built, the methods available in the Console libraries of the framework allow for printing clean output, as well as output in a table format. I gave this as an option for the command.

# File Structure
- `Commands/ProcessDownloadHistory.php`
    - Processes `input.txt` based on the requirements provided in the README.
- `Dto/Contributor.php`
    - An object represenation of a Contributor.
- `Dto/ContributorResource.php`
    - An object representation of a Resource (`resource` is a keyword in PHP which is why I named the class slightly different).
- `Dto/Download.php`
    - An ojbect representation of a Download.
- `storage/input.txt`
    - The provide input file.
- `storage/tests/*`
    - Various input files used to test the command.
- `tests/Feature/ProcessDownloadHistoryTest.php`
    - The test suite for testing full coverage on the `ProcessDownloadHistory.php` command.
- `tests/Unit/*`
    - Unit tests for testing the data transfer objects created in `Dto/*`

# Testing
I always try to aim for full test coverage over the code I write, so on top of testing the happy path that was outlined in the provided README, I also tested the following:
- An input file that doesn't exist.
- An empty file.
- A file that has a contributor with no downloads.
- A file with only 1 contributor that has downloads.
- A file with 2 contributors that have downloads.
- A file with 1 contributor that has downloads outside of the year 2020.
- A file with 1 contributor having resources below a 2 rating.
- A file that has an unknown resource on a download.
- A file that has an unknown contributor on a resource.
- A file that has an unknown command.
- Files that have invalid argument parameters for the 3 accepted commands.
