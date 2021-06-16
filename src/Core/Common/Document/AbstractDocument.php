<?php

declare(strict_types=1);

namespace App\Core\Common\Document;

use Gedmo\Timestampable\Traits\TimestampableDocument;

/**
 * @property string|null $id
 */
abstract class AbstractDocument
{
    use TimestampableDocument;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return (string)$this->id;
    }
}
