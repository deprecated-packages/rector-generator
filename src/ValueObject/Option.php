<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\ValueObject;

/**
 * @api
 */
final class Option
{
    /**
     * @var string
     */
    final public const PACKAGE = 'package';

    /**
     * @var string
     */
    final public const NAME = 'name';

    /**
     * @var string
     */
    final public const NODE_TYPES = 'node_types';

    /**
     * @var string
     */
    final public const DESCRIPTION = 'description';

    /**
     * @var string
     */
    final public const CODE_BEFORE = 'code_before';

    /**
     * @var string
     */
    final public const CODE_AFTER = 'code_after';

    /**
     * @var string
     */
    final public const CONFIGURATION = 'configuration';

    /**
     * @var string
     */
    final public const RESOURCES = 'resources';

    /**
     * @var string
     */
    final public const SET_FILE_PATH = 'set_file_path';
}
