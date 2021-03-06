<?php

namespace Piwik\Plugins\ExtraTools\Lib;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Import
{

    protected $config;

    /**
     * @var OutputInterface
     */
    private $output;

    public function __construct($config, OutputInterface $output)
    {
        $this->config = $config;
        $this->output = $output;
    }

    public function execute()
    {
        // Fetch config.
        $backup_path = $this->config['db_backup_path'];
        $db_host = $this->config['db_host'];
        $db_user = $this->config['db_user'];
        $db_pass = $this->config['db_pass'];
        $db_name = $this->config['db_name'];

        $backup = new Process\Process(
            "mysql -u $db_user -h $db_host -p$db_pass $db_name < $backup_path"
        );
        $backup->enableOutput();

        $backup->run();
        echo $backup->getOutput();
        if (!$backup->isSuccessful()) {
            throw new ProcessFailedException($backup);
        } else {
            $this->output->writeln("<info>Import done</info>");
        }
    }
}
