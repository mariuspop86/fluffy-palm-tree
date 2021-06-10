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
     * @param $id
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
}
