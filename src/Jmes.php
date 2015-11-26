<?php
namespace Libtek\Jmes;

use JmesPath\Parser;
use JmesPath\TreeCompiler;
use File;

class Jmes
{
    /**
     * @param string $expression JMESPath expression to evaluate
     * @param mixed  $data       Data to search
     *
     * @return mixed|null
     */
    public static function search($expression, $data)
    {
        return app('jmes.runtime')->__invoke($expression, $data);
    }

    /**
     * @param string $expression
     *
     * @return array|void
     */
    public static function compile($expression)
    {
        $hash = md5($expression);
        $ast  = (new Parser())->parse($expression);
        $code = (new TreeCompiler())->visit($ast, "jmespath_{$hash}", $expression);

        return [
            'hash' => $hash,
            'expr' => $expression,
            'ast'  => $ast,
            'code' => $code,
        ];
    }

    /**
     * Write a single compiled expression to disk.
     *
     * @param array $compiled
     *
     * @return bool
     */
    public static function save(array $compiled)
    {
        $dir      = config('jmes.compile_path');
        $filename = "{$dir}/jmespath_{$compiled['hash']}.php";

        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir);
        }

        if (false === File::put($filename, $compiled['code'])) {
            throw new \RuntimeException(sprintf(
                "Unable to write compiled PHP code to: %s(%s)",
                $filename,
                var_export(error_get_last(), true)
            ));
        }

        return true;
    }

    /**
     * @return array
     */
    public static function purgeCompiled()
    {
        $result = [
            'success' => [],
            'failure' => [],
        ];
        $dir    = config('jmes.compile_path');
        $paths  = File::glob("{$dir}/jmespath_*.php");

        if (!$paths) {
            return $result;
        }

        foreach ($paths as $path) {
            if (File::delete($path)) {
                $result['success'][] = $path;
            } else {
                $result['failure'][] = $path;
            }
        }

        return $result;
    }
}
