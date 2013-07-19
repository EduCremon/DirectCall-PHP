<?php
namespace DirectCall\Module;

use DirectCall\Exception;
use DirectCall\Module\AbstractModule;
use DirectCall\Helper;

/**
 * Class VozModule
 * @package DirectCall\Module
 * @description API para LIGAR e GRAVAR
 * @author Renato Neto
 */
class VozModule extends AbstractModule
{

    /**
     * Enviar pedido de chamada
     *
     * @param string $origem Número de que esta solicitando a ligação, formato exemplo: 554130600300 (DDI DDD NUMERO)
     * @param string $destino Número de destino da chamada, formato exemplo: 554130600300 (DDI DDD NUMERO)
     * @param bool $gravar Opção de gravar a chamada
     * @throws \Exception
     */
    public function call($origem, $destino, $gravar = false)
    {
        try {

            $gravar = ($gravar) ? 's' : 'n';

            if (!Helper::validateNumber($origem) || !Helper::validateNumber($destino)) {
                throw new Exception('Número de origem e/ou destino inválido(s)');
            }

            return $this->makeRequest('/voz/call', array(
                'origem'  => $origem,
                'destino' => $destino,
                'gravar'  => $gravar,
            ));

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Finalizar uma chamada
     *
     * @param string $origem Número de que esta solicitando a ligação, formato exemplo: 554130600300 (DDI DDD NUMERO)
     * @param string $destino Número de destino da chamada, formato exemplo: 554130600300 (DDI DDD NUMERO)
     * @throws \Exception
     */
    public function end($origem, $destino)
    {
        try {

            if (!Helper::validateNumber($origem) || !Helper::validateNumber($destino)) {
                throw new Exception('Número de origem e/ou destino inválido(s)');
            }

            return $this->makeRequest('/voz/end', array(
                'origem'  => $origem,
                'destino' => $destino,
            ), 'GET');

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Consulta de status de chamada
     *
     * @param string $origem Número de que esta solicitando a ligação, formato exemplo: 554130600300 (DDI DDD NUMERO)
     * @param string $destino Número de destino da chamada, formato exemplo: 554130600300 (DDI DDD NUMERO)
     * @throws \Exception
     */
    public function status($origem, $destino)
    {
        try {

            if (!Helper::validateNumber($origem) || !Helper::validateNumber($destino)) {
                throw new Exception('Número de origem e/ou destino inválido(s)');
            }

            return $this->makeRequest('/voz/status', array(
                'origem'  => $origem,
                'destino' => $destino,
            ), 'GET');

        } catch (\Exception $e) {
            throw $e;
        }
    }

}