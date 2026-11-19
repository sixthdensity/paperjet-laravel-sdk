<?php

namespace Paperjet\Sdk;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PaperjetBuilder
{
    protected string $html = '';
    protected string $format = 'A4';
    protected string $orientation = 'portrait';
    protected array $margins = ['top' => 12, 'right' => 12, 'bottom' => 12, 'left' => 12];
    protected bool $withChartJs = false;
    protected bool $sanitizeWordStyles = true;
    protected bool $normalizeTypography = true;
    protected bool $fixTablePageBreaks = true;
    protected ?string $headerHtml = null;
    protected ?string $footerHtml = null;
    protected ?string $customCss = null;

    public function __construct(
        protected string $endpoint,
        protected string $apiKey,
        protected int $timeout = 15,
        array $defaults = []
    ) {
        $this->format = $defaults['format'] ?? 'A4';
        $this->orientation = $defaults['orientation'] ?? 'portrait';
        $this->margins = $defaults['margins'] ?? $this->margins;
        $this->sanitizeWordStyles = $defaults['sanitize_word_styles'] ?? true;
        $this->normalizeTypography = $defaults['normalize_typography'] ?? true;
        $this->fixTablePageBreaks = $defaults['fix_table_page_breaks'] ?? true;
        $this->withChartJs = $defaults['inject_chartjs_fix'] ?? false;
    }

    public function fromCkEditor(string $html): self
    {
        $this->html = $html;
        $this->sanitizeWordStyles = true;
        $this->normalizeTypography = true;
        $this->fixTablePageBreaks = true;

        return $this;
    }

    public function html(string $html): self
    {
        $this->html = $html;

        return $this;
    }

    public function withChartJs(bool $enabled = true): self
    {
        $this->withChartJs = $enabled;

        return $this;
    }

    public function paper(string $format = 'A4', string $orientation = 'portrait'): self
    {
        $this->format = $format;
        $this->orientation = $orientation;

        return $this;
    }

    public function landscape(): self
    {
        $this->orientation = 'landscape';

        return $this;
    }

    public function margins(int $top, int $right, int $bottom, int $left): self
    {
        $this->margins = compact('top', 'right', 'bottom', 'left');

        return $this;
    }

    public function header(string $html): self
    {
        $this->headerHtml = $html;

        return $this;
    }

    public function footer(string $html): self
    {
        $this->footerHtml = $html;

        return $this;
    }

    public function withCss(string $css): self
    {
        $this->customCss = $css;

        return $this;
    }

    public function toPayload(): array
    {
        return [
            'html' => $this->html,
            'format' => $this->format,
            'orientation' => $this->orientation,
            'margins' => $this->margins,
            'options' => [
                'with_chartjs' => $this->withChartJs,
                'sanitize_word_styles' => $this->sanitizeWordStyles,
                'normalize_typography' => $this->normalizeTypography,
                'fix_table_page_breaks' => $this->fixTablePageBreaks,
                'header_html' => $this->headerHtml,
                'footer_html' => $this->footerHtml,
                'custom_css' => $this->customCss,
            ],
        ];
    }

    public function raw(): string
    {
        $response = $this->sendRequest('/render');
        $response->throw();

        return $response->body();
    }

    public function sanitizeOnly(): array
    {
        $response = $this->sendRequest('/sanitize');
        $response->throw();

        return $response->json();
    }

    public function download(string $filename = 'document.pdf'): StreamedResponse
    {
        $binary = $this->raw();

        return response()->streamDownload(function () use ($binary) {
            echo $binary;
        }, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function inline(string $filename = 'document.pdf'): \Illuminate\Http\Response
    {
        $binary = $this->raw();

        return response($binary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    protected function sendRequest(string $path): Response
    {
        return Http::withToken($this->apiKey)
            ->timeout($this->timeout)
            ->accept('application/pdf, application/json')
            ->post(rtrim($this->endpoint, '/') . $path, $this->toPayload());
    }
}
