<?php

namespace Paperjet\Sdk\Facades;

use Illuminate\Support\Facades\Facade;
use Paperjet\Sdk\PaperjetBuilder;
use Paperjet\Sdk\PaperjetManager;

/**
 * @method static PaperjetBuilder fromCkEditor(string $html)
 * @method static PaperjetBuilder html(string $html)
 * @method static PaperjetBuilder make()
 *
 * @see PaperjetManager
 */
class Paperjet extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PaperjetManager::class;
    }
}
