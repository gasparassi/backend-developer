<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Description of PostmonService
 *
 * @author eder
 */
class PostmonService
{

    private const RETURN_FORMAT_XML = 'xml';

    private $host;

    function __construct()
    {
        $this->host = 'https://api.postmon.com.br/v1/cep/';
    }

    /**
     * Obtém a endereço completo baseado no cep informado pelo cliente
     * 
     * @param string $cep
     * @param string $format
     * @return array
     */
    public function getFullAddress($cep, $format)
    {
        if ( $format !== self::RETURN_FORMAT_XML ) {
            $addressJson = Http::get($this->host . $cep);
        } else {
            $addressXML = Http::get($this->host . "$cep?format=" . self::RETURN_FORMAT_XML);
            if ( $addressXML !== null ) {
                $addressJson = $this->xmlToJsonFormat($addressXML);
            }
        }
        
        if ( $addressJson !== null && $addressJson->status() === 200 || !$addressJson ) {
            $addressArray = json_decode($addressJson, true);
            unset($addressArray['gia']);
            unset($addressArray['siafi']);

            return $this->reorganiseArray($addressArray);;
        } else {
            return null;
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

        return json_encode($xmlToArray);
    }

    /**
     * Reornanisa o array vindo da Api do Portmon, adequando ao ViaCep
     * 
     * @param array $xmlToArray
     * @return array
     */
    private function reorganiseArray(array $xmlToArray)
    {
        $xmlToArray['localidade'] = $xmlToArray['cidade'];
        $xmlToArray['ibge'] = $xmlToArray['cidade_info']['codigo_ibge'];
        $xmlToArray['uf'] = $xmlToArray['estado'];

        unset($xmlToArray['cidade']);
        unset($xmlToArray['cidade_info']);
        unset($xmlToArray['estado_info']);
        unset($xmlToArray['estado']);

        return $xmlToArray;
    }

}
