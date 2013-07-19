<?php
namespace DirectCall\Module;

use DirectCall\Exception;
use DirectCall\Module\AbstractModule;
use DirectCall\Helper;

/**
 * Class PortabilidadeModule
 * @package DirectCall\Module
 * @author Renato Neto
 */
class PortabilidadeModule extends AbstractModule
{

    /**
     * Com este método podemos consultar um numero móvel de portabilidade.
     *
     * @param string $numero Numero que vai ser consultado
     * @return array
     * @throws \Exception
     */
    public function consultar($numero)
    {
        try {

            if (!Helper::validateNumber($numero)) {
                throw new Exception('Número inválido');
            }

            $client = $this->getDirectCall()->getHttpClient();

            $request = $client->post('/portabilidade/consultar')
                ->addPostFields(array(
                    'numero' => $numero,
                ));

            $response = $request->send();
            $response = $response->json();

            return $response;

        } catch (\Exception $e) {
            throw $e;
        }
    }

}