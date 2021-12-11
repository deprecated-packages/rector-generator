<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\ValueObject;

final class Placeholder
{
    /**
     * @var string
     */
    final public const PACKAGE = '__Package__';

    /**
     * @var string
     */
    final public const CATEGORY = '__Category__';

    /**
     * @var string
     */
    final public const DESCRIPTION = '__Description__';

    /**
     * @var string
     */
    final public const NAME = '__Name__';

    /**
     * @var string
     */
    final public const CODE_BEFORE = '__CodeBefore__';

    /**
     * @var string
     */
    final public const CODE_BEFORE_EXAMPLE = '__CodeBeforeExample__';

    /**
     * @var string
     */
    final public const CODE_AFTER = '__CodeAfter__';

    /**
     * @var string
     */
    final public const CODE_AFTER_EXAMPLE = '__CodeAfterExample__';

    /**
     * @var string
     */
    final public const RESOURCES = '__Resources__';
}
