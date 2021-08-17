<?php

namespace App\Services;

use App\Models\Address;
use App\Services\ViacepService;
use App\Services\PostmonService;

/**
 * Description of AddressService
 *
 * @author eder
 */
class AddressService
{

    private $entity;

    function __construct(Address $address)
    {
        $this->entity = $address;
    }

    /**
     * Recupera o endereço válido do cliente para posterior cadastro
     * 
     * @param int $cep
     * @return array || null
     */
    public function requestFullAddressFromExternApi(int $cep)
    {
        $viaCepService = new ViacepService();
        $addressFromApi = $viaCepService->getFullAddress($cep, 'json');

        if (( $addressFromApi !== null ) && (!isset($addressFromApi['erro']) )) {
            return $this->mountFullAddressModel($addressFromApi);
        } else {
            $postManService = new PostmonService();
            $addressFromApi = $postManService->getFullAddress($cep, 'json');
            if ($addressFromApi !== null) {
                return $this->mountFullAddressModel($addressFromApi);
            } else {
                return null;
            }
        }
    }

    private function mountFullAddressModel(array $addressFromApi)
    {
        $this->entity->street = $addressFromApi['logradouro'];
        $this->entity->neighborhood = $addressFromApi['bairro'];
        $this->entity->city = $addressFromApi['localidade'];
        $this->entity->state = $addressFromApi['uf'];
        $this->entity->postal_code = $addressFromApi['cep'];

        return $this->entity;
    }

}
