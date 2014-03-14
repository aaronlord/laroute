<?php

namespace Lord\Laroute\Compilers;

class TemplateCompiler implements CompilerInterface
{
    /**
     * Compile a template with given data.
     *
     * @param $template
     * @param $data
     *
     * @return string
     */
    public function compile($template, $data)
    {
        foreach ($data as $key => $value) {
            $key      = strtoupper($key);
            $template = preg_replace("#\\$$key\\$#i", $value, $template);
        }

        return $template;
    }
}
