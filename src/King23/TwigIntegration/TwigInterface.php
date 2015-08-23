<?php
namespace King23\TwigIntegration;

interface TwigInterface
{
    /**
     * renders a template
     * @param string $name
     * @param array $context
     * @return string
     */
    public function render($name, array $context = []);
}
