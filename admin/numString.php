<?php

/**
 * Biblioteca de funções para converter números em sua representação por extenso em português (Brasil)
 * Suporta valores monetários em reais, datas e números simples
 * 
 * @author Claude
 * @version 2.0
 */

// Arrays globais com nomes de números
$GLOBALS['unidades'] = array('', 'um', 'dois', 'três', 'quatro', 'cinco', 'seis', 'sete', 'oito', 'nove');
$GLOBALS['especiais'] = array('', 'onze', 'doze', 'treze', 'quatorze', 'quinze', 'dezesseis', 'dezessete', 'dezoito', 'dezenove');
$GLOBALS['dezenas'] = array('', 'dez', 'vinte', 'trinta', 'quarenta', 'cinquenta', 'sessenta', 'setenta', 'oitenta', 'noventa');
$GLOBALS['centenas'] = array('', 'cento', 'duzentos', 'trezentos', 'quatrocentos', 'quinhentos', 'seiscentos', 'setecentos', 'oitocentos', 'novecentos');
$GLOBALS['plurais'] = array('', 'mil', 'milhões', 'bilhões', 'trilhões', 'quatrilhões', 'quintilhões');

// Definições de moedas e seus plurais
$GLOBALS['moedas'] = [
    'real' => ['singular' => 'real', 'plural' => 'reais', 'centavo_s' => 'centavo', 'centavo_p' => 'centavos'],
    'dolar' => ['singular' => 'dólar', 'plural' => 'dólares', 'centavo_s' => 'centavo', 'centavo_p' => 'centavos'],
    'euro' => ['singular' => 'euro', 'plural' => 'euros', 'centavo_s' => 'centavo', 'centavo_p' => 'centavos']
];

// Variável global para armazenar temporariamente a moeda atual
$GLOBALS['moeda_atual'] = 'real';

/**
 * Função para converter um grupo de três dígitos em sua representação por extenso
 * 
 * @param int $num Número a ser convertido (até 3 dígitos)
 * @return string Número por extenso
 */
function converterGrupo($num) {
    // Formatar o número para ter sempre 3 dígitos
    $num = str_pad($num, 3, '0', STR_PAD_LEFT);
    
    $c = $num[0]; // centena
    $d = $num[1]; // dezena
    $u = $num[2]; // unidade
    
    $resultado = '';
    
    // Tratar centenas
    if ($c > 0) {
        // Caso especial: 100 = "cem" (não "cento")
        if ($c == 1 && $d == 0 && $u == 0) {
            $resultado .= 'cem';
        } else {
            $resultado .= $GLOBALS['centenas'][$c];
        }
        
        // Adicionar "e" se houver dezenas ou unidades
        if ($d > 0 || $u > 0) {
            $resultado .= ' e ';
        }
    }
    
    // Tratar dezenas e unidades
    if ($d > 0) {
        // Casos especiais de 11-19
        if ($d == 1 && $u > 0) {
            $resultado .= $GLOBALS['especiais'][$u];
        } else {
            $resultado .= $GLOBALS['dezenas'][$d];
            
            // Adicionar "e" se houver unidades
            if ($u > 0) {
                $resultado .= ' e ';
            }
        }
    }
    
    // Tratar unidades (se não for um caso especial 11-19)
    if ($u > 0 && !($d == 1 && $u > 0)) {
        $resultado .= $GLOBALS['unidades'][$u];
    }
    
    return $resultado;
}

/**
 * Função para converter parte decimal (centavos) em sua representação por extenso
 * 
 * @param int $decimal Valor decimal a ser convertido
 * @return string Valor decimal por extenso
 */
function converterParteDecimal($decimal) {
    $decimal = (int)$decimal;
    
    if ($decimal === 0) {
        return '';
    }
    
    $extensoDecimal = converterGrupo($decimal);
    
    // Tratar singular/plural de centavos
    $moeda = $GLOBALS['moedas'][$GLOBALS['moeda_atual']];
    if ($decimal === 1) {
        return $extensoDecimal . ' ' . $moeda['centavo_s'];
    } else {
        return $extensoDecimal . ' ' . $moeda['centavo_p'];
    }
}

/**
 * Função principal que detecta o tipo de entrada e direciona para a função específica de conversão
 * 
 * @param mixed $valor Valor a ser convertido (número, data ou valor monetário)
 * @param string $tipo Tipo de conversão: 'valor', 'data', 'numero' (padrão: detecção automática)
 * @param array $opcoes Opções adicionais de formatação
 * @return string Valor por extenso em português
 */
function converterPorExtenso($valor, $tipo = 'auto', $opcoes = []) {
    // Opções padrão
    $opcoesPadrao = [
        'moeda' => 'real',         // real, dolar, euro, etc.
        'genero' => 'masculino',   // masculino ou feminino
        'formato_data' => 'dmY',   // d/m/Y, Y-m-d, etc.
        'incluir_hora' => false,   // incluir hora na conversão de data
        'capitalizar' => false,    // primeira letra maiúscula
        'separador_decimal' => ',',// separador de decimal
        'separador_milhar' => '.'  // separador de milhar
    ];
    
    // Mesclar opções padrão com as fornecidas
    $opcoes = array_merge($opcoesPadrao, $opcoes);
    
    // Detectar automaticamente o tipo se não foi especificado
    if ($tipo === 'auto') {
        if (is_string($valor) && (
            preg_match('/^\d{1,2}\/\d{1,2}\/\d{2,4}$/', $valor) ||     // dd/mm/yyyy
            preg_match('/^\d{4}-\d{1,2}-\d{1,2}$/', $valor) ||         // yyyy-mm-dd
            preg_match('/^\d{1,2}-\d{1,2}-\d{4}$/', $valor)            // dd-mm-yyyy
        )) {
            $tipo = 'data';
        } else if (is_string($valor) && strpos($valor, $opcoes['separador_decimal']) !== false) {
            $tipo = 'valor';
        } else {
            $tipo = 'numero';
        }
    }
    
    // Direcionar para a função apropriada
    switch ($tipo) {
        case 'valor':
            $resultado = valorMonetarioPorExtenso($valor, $opcoes);
            break;
        case 'data':
            $resultado = dataPorExtenso($valor, $opcoes);
            break;
        case 'numero':
        default:
            $resultado = numeroPorExtenso($valor, $opcoes);
            break;
    }
    
    // Capitalizar se solicitado
    if ($opcoes['capitalizar']) {
        $resultado = ucfirst($resultado);
    }
    
    return $resultado;
}

/**
 * Converte um número simples em sua representação por extenso
 * 
 * @param float|string $numero Número a ser convertido
 * @param array $opcoes Opções de formatação
 * @return string Número por extenso
 */
function numeroPorExtenso($numero, $opcoes = []) {
    // Se o número tiver parte decimal, dividimos em parte inteira e decimal
    if (is_string($numero) && strpos($numero, $opcoes['separador_decimal']) !== false) {
        $partes = explode($opcoes['separador_decimal'], $numero);
        $parteInteira = str_replace($opcoes['separador_milhar'], '', $partes[0]);
        $parteDecimal = isset($partes[1]) ? $partes[1] : '0';
    } else {
        $parteInteira = str_replace($opcoes['separador_milhar'], '', $numero);
        $parteDecimal = '0';
    }
    
    // Verificar se é negativo
    $negativo = false;
    if (strpos($parteInteira, '-') === 0) {
        $negativo = true;
        $parteInteira = substr($parteInteira, 1);
    }
    
    // Se o número for zero
    if ((int)$parteInteira === 0 && (int)$parteDecimal === 0) {
        return 'zero';
    }
    
    // Dividir em grupos de 3 dígitos
    $grupos = array();
    $parteInteiraTemp = $parteInteira;
    
    while ($parteInteiraTemp > 0) {
        $grupos[] = $parteInteiraTemp % 1000;
        $parteInteiraTemp = floor($parteInteiraTemp / 1000);
    }
    
    $extenso = '';
    $totalGrupos = count($grupos);
    
    // Processar cada grupo
    for ($i = $totalGrupos - 1; $i >= 0; $i--) {
        if ($grupos[$i] > 0) {
            $extensoGrupo = converterGrupo($grupos[$i]);
            
            // Adicionar o extenso do grupo atual
            if ($extenso != '') {
                // Decidir entre vírgula ou "e"
                if ($grupos[$i] < 100 || ($i == 0 && $grupos[$i] < 1000)) {
                    $extenso .= ' e ';
                } else {
                    $extenso .= ', ';
                }
            }
            
            $extenso .= $extensoGrupo;
            
            // Adicionar o plural correspondente (mil, milhão/milhões, etc)
            if ($i > 0) {
                // Tratar casos especiais para "milhão"/"milhões" e similares
                if ($i >= 2 && $grupos[$i] > 1) {
                    $extenso .= ' ' . substr($GLOBALS['plurais'][$i], 0, -2) . 'ões';
                } else if ($i >= 2 && $grupos[$i] == 1) {
                    $extenso .= ' ' . substr($GLOBALS['plurais'][$i], 0, -2) . 'ão';
                } else {
                    $extenso .= ' ' . $GLOBALS['plurais'][$i];
                }
            }
        }
    }
    
    // Adicionar parte decimal se existir
    if ((int)$parteDecimal > 0) {
        // Formatar a parte decimal com casas específicas
        $extenso .= ' vírgula ' . converterGrupo($parteDecimal);
    }
    
    // Adicionar "negativo" se for o caso
    if ($negativo) {
        $extenso = 'menos ' . $extenso;
    }
    
    return $extenso ?: 'zero';
}

/**
 * Converte um valor monetário em sua representação por extenso
 * 
 * @param float|string $valor Valor monetário a ser convertido
 * @param array $opcoes Opções de formatação
 * @return string Valor monetário por extenso
 */
function valorMonetarioPorExtenso($valor, $opcoes = []) {
    // Verificar se a moeda existe nas definições
    $moeda = $opcoes['moeda'];
    if (!isset($GLOBALS['moedas'][$moeda])) {
        $moeda = 'real'; // Moeda padrão
    }
    
    // Guardar a moeda atual em uma variável global temporária
    $GLOBALS['moeda_atual'] = $moeda;
    
    // Converter string para formato numérico adequado
    if (is_string($valor)) {
        // Substituir separadores conforme configuração
        $valor = str_replace($opcoes['separador_milhar'], '', $valor);
        $valor = str_replace($opcoes['separador_decimal'], '.', $valor);
    }
    
    // Limitar a duas casas decimais e separar parte inteira e decimal
    $valor = number_format((float)$valor, 2, '.', '');
    list($parteInteira, $parteDecimal) = explode('.', $valor);
    
    // Verificar se é um valor negativo
    $negativo = false;
    if ($valor < 0) {
        $negativo = true;
        $parteInteira = substr($parteInteira, 1); // Remove o sinal negativo
    }
    
    // Se o valor for zero
    if ((int)$parteInteira === 0 && (int)$parteDecimal === 0) {
        return 'zero ' . $GLOBALS['moedas'][$moeda]['plural'];
    }
    
    // Se a parte inteira for zero
    if ((int)$parteInteira === 0) {
        // Adicionar centavos se houver
        if ((int)$parteDecimal > 0) {
            return converterParteDecimal($parteDecimal);
        } else {
            return 'zero ' . $GLOBALS['moedas'][$moeda]['plural'];
        }
    }
    
    // Dividir em grupos de 3 dígitos
    $grupos = array();
    $parteInteiraTemp = $parteInteira;
    
    while ($parteInteiraTemp > 0) {
        $grupos[] = $parteInteiraTemp % 1000;
        $parteInteiraTemp = floor($parteInteiraTemp / 1000);
    }
    
    $extenso = '';
    $totalGrupos = count($grupos);
    
    // Processar cada grupo
    for ($i = $totalGrupos - 1; $i >= 0; $i--) {
        if ($grupos[$i] > 0) {
            $extensoGrupo = converterGrupo($grupos[$i]);
            
            // Adicionar o extenso do grupo atual
            if ($extenso != '') {
                // Decidir entre vírgula ou "e"
                if ($grupos[$i] < 100 || ($i == 0 && $grupos[$i] < 1000)) {
                    $extenso .= ' e ';
                } else {
                    $extenso .= ', ';
                }
            }
            
            $extenso .= $extensoGrupo;
            
            // Adicionar o plural correspondente (mil, milhão/milhões, etc)
            if ($i > 0) {
                // Tratar casos especiais para "milhão"/"milhões" e similares
                if ($i >= 2 && $grupos[$i] > 1) {
                    $extenso .= ' ' . substr($GLOBALS['plurais'][$i], 0, -2) . 'ões';
                } else if ($i >= 2 && $grupos[$i] == 1) {
                    $extenso .= ' ' . substr($GLOBALS['plurais'][$i], 0, -2) . 'ão';
                } else {
                    $extenso .= ' ' . $GLOBALS['plurais'][$i];
                }
            }
        }
    }
    
    // Adicionar nome da moeda
    if ((int)$parteInteira === 1) {
        $extenso .= ' ' . $GLOBALS['moedas'][$moeda]['singular'];
    } else {
        $extenso .= ' ' . $GLOBALS['moedas'][$moeda]['plural'];
    }
    
    // Adicionar centavos se houver
    if ((int)$parteDecimal > 0) {
        $extenso .= ' e ' . converterParteDecimal($parteDecimal);
    }
    
    // Adicionar "negativo" se for o caso
    if ($negativo) {
        $extenso = 'menos ' . $extenso;
    }
    
    return $extenso;
}

/**
 * Converte uma data em sua representação por extenso
 * 
 * @param string $data Data a ser convertida (formatos aceitos: dd/mm/yyyy, yyyy-mm-dd, etc.)
 * @param array $opcoes Opções de formatação
 * @return string Data por extenso
 */
function dataPorExtenso($data, $opcoes = []) {
    // Arrays com nomes de meses
    $meses = [
        1 => 'janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho',
        'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'
    ];
    
    // Arrays com nomes de dias da semana
    $diasSemana = [
        0 => 'domingo', 'segunda-feira', 'terça-feira', 'quarta-feira', 
        'quinta-feira', 'sexta-feira', 'sábado'
    ];
    
    // Identificar formato da data
    $timestamp = false;
    
    // Formato dd/mm/yyyy
    if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{2,4})$/', $data, $matches)) {
        $dia = (int)$matches[1];
        $mes = (int)$matches[2];
        $ano = (int)$matches[3];
        $timestamp = mktime(0, 0, 0, $mes, $dia, $ano);
    }
    // Formato yyyy-mm-dd
    else if (preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $data, $matches)) {
        $ano = (int)$matches[1];
        $mes = (int)$matches[2];
        $dia = (int)$matches[3];
        $timestamp = mktime(0, 0, 0, $mes, $dia, $ano);
    }
    // Formato dd-mm-yyyy
    else if (preg_match('/^(\d{1,2})-(\d{1,2})-(\d{4})$/', $data, $matches)) {
        $dia = (int)$matches[1];
        $mes = (int)$matches[2];
        $ano = (int)$matches[3];
        $timestamp = mktime(0, 0, 0, $mes, $dia, $ano);
    }
    // Formato timestamp Unix
    else if (is_numeric($data)) {
        $timestamp = (int)$data;
        $dia = (int)date('d', $timestamp);
        $mes = (int)date('m', $timestamp);
        $ano = (int)date('Y', $timestamp);
    }
    // Formato ISO 8601
    else if (preg_match('/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})/', $data, $matches)) {
        $ano = (int)$matches[1];
        $mes = (int)$matches[2];
        $dia = (int)$matches[3];
        $hora = (int)$matches[4];
        $minuto = (int)$matches[5];
        $segundo = (int)$matches[6];
        $timestamp = mktime($hora, $minuto, $segundo, $mes, $dia, $ano);
    }
    
    // Se não foi possível identificar o formato
    if (!$timestamp) {
        return 'data inválida';
    }
    
    // Obter dia da semana (0-6)
    $diaSemana = date('w', $timestamp);
    
    // Converter números em extenso
    $diaExtenso = numeroPorExtenso($dia, $opcoes);
    $anoExtenso = numeroPorExtenso($ano, $opcoes);
    
    // Formato padrão: "dia de mês de ano"
    $resultado = $diaExtenso . ' de ' . $meses[$mes] . ' de ' . $anoExtenso;
    
    // Incluir dia da semana se solicitado
    if (isset($opcoes['incluir_dia_semana']) && $opcoes['incluir_dia_semana']) {
        $resultado = $diasSemana[$diaSemana] . ', ' . $resultado;
    }
    
    // Incluir hora se solicitado
    if ($opcoes['incluir_hora']) {
        $hora = date('H', $timestamp);
        $minuto = date('i', $timestamp);
        
        // Converter hora e minuto para extenso
        $horaExtenso = numeroPorExtenso($hora, $opcoes);
        $minutoExtenso = numeroPorExtenso($minuto, $opcoes);
        
        // Singular/plural para hora
        $textoHora = $hora == 1 ? 'hora' : 'horas';
        
        // Adicionar hora e minuto
        if ($minuto > 0) {
            $resultado .= ', ' . $horaExtenso . ' ' . $textoHora . ' e ' . $minutoExtenso . ' minutos';
        } else {
            $resultado .= ', ' . $horaExtenso . ' ' . $textoHora;
        }
    }
    
    return $resultado;
}

?>