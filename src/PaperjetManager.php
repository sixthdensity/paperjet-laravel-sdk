<?php

namespace Paperjet\Sdk;

class PaperjetManager
{
    public function __construct(
        protected string $endpoint,
        protected string $apiKey,
        protected int $timeout = 15,
        protected array $defaults = []
    ) {}

    public function make(): PaperjetBuilder
    {
        return new PaperjetBuilder(
            $this->endpoint,
            $this->apiKey,
            $this->timeout,
            $this->defaults
        );
    }

    public function fromCkEditor(string $html): PaperjetBuilder
    {
        return $this->make()->fromCkEditor($html);
    }

    public function html(string $html): PaperjetBuilder
    {
        return $this->make()->html($html);
    }
}
