<?php
namespace DirectCall;

/**
 * Class Error
 * @package DirectCall
 * @author Renato Neto
 */
class Error
{

    const OK                         = '000';
    const PARAMETROS_INVALIDOS       = '001';
    const FALHA_REQUISITAR_CHAMADA   = '002';
    const CHAMADA_NAO_ESTA_ATIVA     = '003';
    const CHAMADA_NAO_FINALIZADA     = '004';
    const FALHA_CONEXAO              = '005';
    const FALHA_AUTENTICAR           = '006';
    const FALHA_CONECTAR_SMS         = '007';
    const ERRO_ENVIAR_MENSAGEM       = '008';
    const CAMPO_CODE_INEXISTENTE     = '009';
    const IDENTIFICADOR_INEXISTENTE  = '010';
    const PROBLEMA_SERVICO_TARIFACAO = '011';
    const CONTA_TESTE_NAO_CADASTRADA = '100';
    const LOGIN_SEM_CREDITO          = '101';
    const SEM_CHAMADAS_DO_TIPO       = '102';
    const USUARIO_NAO_CADASTRADO     = '200';
    const SALDA_INSUFICIENTE         = '201';

}