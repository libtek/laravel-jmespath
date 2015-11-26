<?php

namespace Libtek\Jmes\Console;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Libtek\Jmes\Jmes;

class JmesClearCommand extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jmes:clear
        {--force : Do not ask for confirmation.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all previously compiled JMESPath expressions';

    /**
     * JmesClearCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!$this->confirmToProceed("Deleting all compiled expressions!")) {
            return;
        }

        $result = Jmes::purgeCompiled();

        if ($result['success']) {
            $this->output->success(sprintf("Successfully removed %d %s", count($result['success']),
                (1 === count($result['success']) ? 'file' : 'files')));

            $this->output->newLine();
        }
        if ($result['failure']) {
            $this->output->warning(sprintf("Failed to remove %d %s:\n%s", count($result['failure']),
                (1 === count($result['failure']) ? 'file' : 'files'),
                implode("\n", $result['failure'])));

            $this->output->newLine();
        }
    }
}
