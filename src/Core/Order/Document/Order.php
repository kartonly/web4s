<?php

declare(strict_types=1);

namespace App\Core\Order\Document;

use App\Core\Common\Document\AbstractDocument;
use App\Core\Order\Repository\OrderRepository;
use App\Core\Order\Enum\OrderStatus;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(repositoryClass=OrderRepository::class, collection="orders")
 */
class Order extends AbstractDocument
{
    /**
     * @MongoDB\Id
     */
    protected ?string $id = null;

    /**
     * @MongoDB\Field(type="string")
     */
    protected ?string $title = null;

    /**
     * @MongoDB\Field(type="string")
     */
    protected ?string $about = null;

    /**
     * @MongoDB\Field(type="string")
     */
    protected string $status;

    public function __construct() {
        $this->status = OrderStatus::OPEN;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAbout(): ?string
    {
        return $this->about;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function setAbout(?string $about): void
    {
        $this->about = $about;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }
}
