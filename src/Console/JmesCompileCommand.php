<?php

namespace Libtek\Jmes\Console;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use JmesPath\Parser;
use JmesPath\TreeCompiler;
use Jmes;

class JmesCompileCommand extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jmes:compile
        {expression? :  Expression to add. If not provided, expressions from config file will be used.}
        {--c|cli     :  Enter one or more expressions manually. If used, the <comment>expression</comment> argument is ignored.}
        {--force     :  Do not ask for confirmation in production environment.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compile and cache JMESpath expressions';

    /**
     * JmesPathCompileCommand constructor.
     */
    public function __construct()
    {
        $this->parser   = new Parser();
        $this->compiler = new TreeCompiler();
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!$this->confirmToProceed()) {
            return;
        }

        foreach ($this->getExpressions() as $expression) {
            try {
                $result                            = Jmes::compile($expression);
                $compiled[(string)$result['hash']] = $result;
            } catch (\Exception $e) {
                $this->error(sprintf("Failed to compile: %s", $expression));
                $this->output->newLine();
            }
        }

        if (empty($compiled)) {
            $this->info("No compiled expressions.");

            return;
        }

        $this->output->table(['hash', 'expression'], array_map(function ($row) {
            return [$row['hash'], $row['expr']];
        }, $compiled));

        $saved = 0;
        foreach ($compiled as $comp) {
            try {
                Jmes::save($comp);
                $saved++;
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }

        if ($saved) {
            $this->output->success(sprintf("Saved %d %s", $saved,
                (1 === $saved) ? 'file' : 'files'));
        }
    }

    /**
     * Get the expressions to be compiled.
     *
     * @return array
     */
    private function getExpressions()
    {
        $expressions = [];
        if ($this->argument('expression')) {
            $expressions[] = $this->argument('expression');
        } elseif ($this->option('cli')) {
            $expressions = $this->askForExpressions();
        } else {
            $expressions = config('jmes.expressions');
        }

        return array_filter($expressions);
    }

    /**
     * Prompts the user to enter expressions manually.
     *
     * @return array
     */
    private function askForExpressions()
    {
        $expressions = [];
        do {
            $expressions[] = $this->ask("Please enter a JMESPath expression");
        } while ($this->confirm('Add another?', false));

        return $expressions;
    }
}
