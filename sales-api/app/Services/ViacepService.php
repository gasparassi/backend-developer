<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Description of ViacepService
 *
 * @author eder
 */
class ViacepService
{

    private const RETURN_FORMAT_XML = 'xml';

    private $host;

    function __construct()
    {
        $this->host = 'https://viacep.com.br/ws/';
    }

    /**
     * Obtém a endereço completo baseado no cep informado pelo cliente
     * 
     * @param string $cep
     * @param string $format
     * @return array
     */
    public function getFullAddress(string $cep, string $format)
    {
        if ( $format !== self::RETURN_FORMAT_XML ) {
            $addressJson = Http::get($this->host . "$cep/json");
        } else {
            $addressXML = Http::get($this->host . "$cep/" . self::RETURN_FORMAT_XML);
            if ( $addressXML !== null ) {
                $addressJson = $this->xmlToJsonFormat($addressXML);
            }
        }

        if ( $addressJson !== null ) {
            $addressArray = json_decode($addressJson, true);
            unset($addressArray['gia']);
            unset($addressArray['siafi']);

            return $addressArray;
        }
    }

    /**
     * Converte o endereço eno formato xml em formato json
     * 
     * @param \Illuminate\Http\Client\Response $xmlFromApi
     * @return array
     */
    private function xmlToJsonFormat($xmlFromApi)
    {
        $xml = simplexml_load_string($xmlFromApi);

        $xmlToJson = json_encode($xml);

        $xmlToArray = json_decode($xmlToJson, true);

        $xmlToArray['gia'] = '';
        $xmlToArray['complemento'] = '';

        return json_encode($xmlToArray);
    }

}
