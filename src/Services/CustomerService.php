<?php

namespace App\Services;

use App\Entity\Customer;
use App\Repository\CustomerRepository;

class CustomerService
{
    /**
     * @var CustomerRepository
     */
    private $customerRepository;

    /**
     * CustomerService constructor.
     */
    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param int $id
     *
     * @return Customer
     *
     * @throws \Exception
     */
    public function getCustomerById(int $id): Customer
    {
        $customer = $this->customerRepository->find($id);
        if (!$customer) {
            throw new \Exception(json_encode('Customer not found'));
        }
        
        return $customer;
    }

    public function getCustomers(): array
    {
        $customers = $this->customerRepository->findAll();

        $result = [];
        foreach ($customers as $customer) {
            $result[] = [
                'id' => $customer->getId(),
                'name' => $customer->getName(),
            ];
        }

        return $result;
    }
}
