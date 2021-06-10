<?php

namespace App\Services;

use App\Entity\LinkType;
use App\Repository\LinkTypeRepository;

class LinkService
{
    /**
     * @var LinkTypeRepository
     */
    private $linkTypeRepository;

    /**
     * CustomerService constructor.
     */
    public function __construct(LinkTypeRepository $linkTypeRepository)
    {
        $this->linkTypeRepository = $linkTypeRepository;
    }

    /**
     * @param int $id
     *
     * @return LinkType
     *
     * @throws \Exception
     */
    public function getLinkTypeById(int $id): LinkType
    {
        $linkType = $this->linkTypeRepository->find($id);
        if (!$linkType) {
            throw new \Exception(json_encode('Type not found'));
        }

        return $linkType;
    }
}
