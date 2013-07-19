<?php
namespace DirectCall\Module;

use DirectCall\Exception;
use DirectCall\Module\AbstractModule;
use DirectCall\Helper;

/**
 * Class SmsModule
 * @package DirectCall\Module
 * @description Módulo de SMS e Torpedo de voz
 * @author Renato Neto
 */
class SmsModule extends AbstractModule
{

    /**
     * @param string $origem Número de quem esta enviando o SMS, formato exemplo: 554130600300 (DDI DDD NUMERO)
     * @param string $destino Número de destino do SMS, formato exemplo: 554130600300 (DDI DDD NUMERO)
     * @param string $texto Texto a ser enviado na mensagem, deve ter no máximo 140 caracteres, em caso de ultrapassar o limite o sistema realizara o envio paginado
     * @param string $tipo Opção para enviar como texto ou voz podendo ser ("voz" para torpedo de voz | "texto" para sms)
     * @param string|null $cron Em caso de envio agendado esta variável deve ser enviada no formato <dia-mes-ano-hora-minuto-segundo> "d-m-Y-H-i-s"
     * @param bool $shortNumber Opção para envio com ou sem short number
     * @param bool $idOrigem Opção para envio do número de origem no início do SMS
     * @return array
     * @throws \Exception
     */
    public function send($origem, $destino, $texto, $tipo = 'texto', $cron = null,
                         $shortNumber = true, $idOrigem = false)
    {
        try {

            if (is_file($destino)) {
                $destinoIndex = 'destino_csv';
                $files        = array('destino_csv' => $destino);
            } else {
                $destinoIndex = 'destino';
                $files        = null;
            }

            if (!Helper::validateNumber($origem) || (!Helper::validateNumber($destino) && !$files)) {
                throw new Exception('Número de origem e/ou destino inválido(s)');
            }

            $tipo        = (strtolower($tipo) == 'voz') ? 'voz' : 'texto';
            $shortNumber = ($shortNumber) ? 's' : 'n';
            $idOrigem    = ($idOrigem) ? 's' : 'n';

            if ($cron) {
                $cron = Helper::checkFormat($cron);

                if (!$cron) {
                    throw new Exception('Data de agendamento inválida');
                }
            }

            return $this->makeRequest('/sms/send', array(
                'origem'       => $origem,
                $destinoIndex  => $destino,
                'texto'        => $texto,
                'tipo'         => $tipo,
                'cron'         => $cron,
                'short_number' => $shortNumber,
                'id_origem'    => $idOrigem,
            ), 'POST');

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Consulta de status de SMS
     *
     * @param string $code "callerid" Obtido no retorno do envio do SMS
     * @return array
     * @throws \Exception
     */
    public function status($code)
    {
        try {
            return $this->makeRequest('/sms/status', array(
                'code' => $code
            ), 'GET');

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Remover agendamento de SMS
     *
     * @param string $code "callerid" Obtido no retorno do envio do SMS
     * @return array
     * @throws \Exception
     */
    public function remove($code)
    {
        try {
            return $this->makeRequest('/sms/remove', array(
                'code' => $code
            ), 'GET');

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Listar SMS recebidos
     *
     * @param string $destino Número de destino do SMS, formato exemplo: 554130600300 (DDI DDD NUMERO)
     * @param string $dataInicio Data de inicio da pesquisa, formato <dia-mes-ano-hora-minuto-segundo> "d-m-Y-H-i-s"
     * @param string $dataFim Data de fim da pesquisa, formato <dia-mes-ano-hora-minuto-segundo> "d-m-Y-H-i-s"
     * @return array
     * @throws \Exception
     */
    public function received($destino, $dataInicio, $dataFim)
    {
        try {

            if (!Helper::validateNumber($destino)) {
                throw new Exception('Número de destino inválido');
            }

            $dataInicio = Helper::checkFormat($dataInicio);
            $dataFim    = Helper::checkFormat($dataFim);

            if (!$dataInicio || !$dataFim) {
                throw new Exception('Data de início e/ou fim inválida(s)');
            }

            return $this->makeRequest('/sms/received', array(
                'destino'     => $destino,
                'data_inicio' => $dataInicio,
                'data_fim'    => $dataFim,
            ));

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @todo precisa fazer esse método funcionar
     */
    public function sendMultiple($origem, $destino, $texto, $tipo = 'texto', $cron = null,
                                 $shortNumber = true, $idOrigem = false)
    {
        if (!file_exists($destino)) {
            throw new Exception('Arquivo de destinos não encontrado');
        }

        return $this->send($origem, $destino, $texto, $tipo, $cron, $shortNumber, $idOrigem);
    }

}